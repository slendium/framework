<?php

namespace Slendium\Framework\Base\Application;

use Exception;
use LogicException;
use Override;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
use RuntimeException;

use Slendium\Framework\Application\Assembler;
use Slendium\Framework\Application\AssemblerException;
use Slendium\Framework\Application\Dependency;
use Slendium\Framework\Application\DependencyResolver;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class InternalAssembler implements Assembler {

	/** @var array<non-empty-string,DependencyResolver> */
	private readonly array $resolvers;

	/** @param iterable<DependencyResolver> $resolvers */
	public function __construct(iterable $resolvers) {
		$this->resolvers = self::mapResolvers($resolvers);
	}

	#[Override]
	public function assemble(Dependency $dependency): object {
		if (\count($dependency->constraints) > 2) {
			throw new Exception('Dependency injection for intersection types with more than two types is currently not supported');
		}

		$key = self::createMultiResolverKey($dependency->constraints);
		if (isset($this->resolvers[$key])) {
			return $this->resolvers[$key]->resolve($this, $dependency);
		} else if (\count($dependency->constraints) !== 1) {
			throw AssemblerException::forMissingResolver($dependency);
		}

		$implementation = self::convertTypeToImplementation($dependency->constraints[0]);
		return \class_exists($implementation, autoload: true)
			? $this->assembleClass($dependency, $implementation)
			: throw AssemblerException::forMissingImplementation($dependency);
	}

	/** @param class-string $class */
	private function assembleClass(Dependency $dependency, string $class): object {
		$reflector = new ReflectionClass($class);
		if (!$reflector->isInstantiable()) {
			throw AssemblerException::forUninstantiable($dependency);
		}

		$constructor = $reflector->getConstructor();
		if ($constructor === null) {
			return $reflector->newInstance();
		}

		return $reflector->newLazyGhost(function (object $obj) use ($class, $constructor) {
			$obj->__construct(...\array_map( // @phpstan-ignore method.notFound (constructor exists, see early return above)
				fn($param) => $this->assembleParameter($class, $param),
				$constructor->getParameters()
			));
		});
	}

	/** @param class-string $client */
	private function assembleParameter(string $client, ReflectionParameter $parameter): mixed {
		try {
			return $this->assembleParameterType($client, $parameter, $parameter->getType());
		} catch (AssemblerException $exception) {
			return $parameter->isOptional()
				? $parameter->getDefaultValue()
				: throw AssemblerException::forRequiredParameter($client, $parameter->name, $exception);
		}
	}

	/** @param class-string $client */
	private function assembleParameterType(string $client, ReflectionParameter $param, ?ReflectionType $type): mixed {
		if ($type === null) {
			throw AssemblerException::forMissingParameterType($client, $param->name);
		} else if ($type instanceof ReflectionNamedType) {
			return $this->assembleIntersection($client, $param, [ $type ]);
		} else if ($type instanceof ReflectionUnionType) {
			return $this->assembleUnion($client, $param, $type->getTypes());
		} else if ($type instanceof ReflectionIntersectionType) {
			return $this->assembleIntersection($client, $param, $type->getTypes()); // @phpstan-ignore argument.type (assume getTypes is never empty)
		}
		throw new Exception("Unsupported type declaration: $type");
	}

	/**
	 * @param class-string $client
	 * @param array<ReflectionIntersectionType|ReflectionNamedType> $types
	 */
	private function assembleUnion(string $client, ReflectionParameter $param, array $types): mixed {
		$firstException = null;
		foreach ($types as $type) {
			try {
				return $this->assembleParameterType($client, $param, $type);
			} catch (AssemblerException $exception) {
				$firstException ??= $exception;
			}
		}
		throw AssemblerException::forExhaustedUnionParameter($client, $param->name, $firstException);
	}

	/**
	 * @param class-string $client
	 * @param non-empty-array<ReflectionType> $types
	 */
	private function assembleIntersection(string $client, ReflectionParameter $param, array $types): mixed {
		$names = [ ];
		foreach ($types as $type) {
			if (!($type instanceof ReflectionNamedType)) {
				throw new Exception("Expected intersection type to only contain named types, not $type");
			}

			$name = $type->getName();
			if ($name === $client || $type->isBuiltin() || self::isRelativeType($name)) {
				throw AssemblerException::forUninjectableParameter($client, $param->name, $name);
			}

			/** @var class-string $name */
			$names[] = $name;
		}

		return $this->assemble(new ReadOnlyDependency($client, $names, $param->getAttributes()));
	}

	/**
	 * @param class-string $type
	 * @return class-string
	 */
	private static function convertTypeToImplementation(string $type): string {
		if (\interface_exists($type, autoload: true)) {
			foreach (new ReflectionClass($type)->getAttributes(Assembler\ImplementedBy::class) as $attr) {
				$implementation = $attr->newInstance()->type;
				if (\class_exists($implementation, autoload: true)) {
					return $implementation;
				}
			}
		}
		return $type;
	}

	private static function isRelativeType(string $type): bool {
		return match($type) {
			'self', 'static', 'parent' => true,
			default => false
		};
	}

	/**
	 * @param iterable<DependencyResolver> $resolvers
	 * @return array<non-empty-string,DependencyResolver>
	 */
	private static function mapResolvers(iterable $resolvers): array {
		$mapped = [ ];
		foreach ($resolvers as $resolver) {
			foreach (self::getResolverKeys($resolver) as $key) {
				if (isset($mapped[$key])) {
					throw new LogicException("Unexpected duplicate dependency resolver for `$key`");
				}
				$mapped[$key] = $resolver;
			}
		}
		return $mapped;
	}

	/** @return iterable<non-empty-string> */
	private static function getResolverKeys(DependencyResolver $resolver): iterable {
		if (\count($resolver->satisfies) > 2) {
			// A&B&C would satisfy A, B, C, A&B, A&C, B&C and A&B&C
			// A different internal structure would be needed to avoid filling the memory with all possible permutations
			throw new Exception('Resolvers that satisfy more than 2 intersected types are currently not supported for dependency injection');
		}

		if (\count($resolver->satisfies) > 1) {
			yield self::createMultiResolverKey($resolver->satisfies);
		}
		yield from $resolver->satisfies;
	}

	/**
	 * @param non-empty-list<class-string> $types
	 * @return non-empty-string
	 */
	private static function createMultiResolverKey(array $types): string {
		\sort($types, \SORT_STRING);
		return \implode('&', $types);
	}

}
