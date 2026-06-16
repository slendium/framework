<?php

namespace Slendium\Framework\Resource\Endpoint;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use LogicException;
use Override;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Traversable;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\ResourceException;

/**
 * @internal
 * @template T of Resource
 * @implements ArrayAccess<lowercase-string&non-empty-string,?Method>
 * @implements IteratorAggregate<Method>
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class MethodMap implements ArrayAccess, Countable, IteratorAggregate {

	/** @var array<string,Method> */
	private array $map;

	/**
	 * @internal
	 * @template TClass of Resource
	 * @param class-string<TClass> $class
	 * @return self<TClass>
	 */
	public static function forClass(string $class): self {
		return new self(new ReflectionClass($class));
	}

	/**
	 * @internal
	 * @param ReflectionClass<T> $reflector
	 */
	public function __construct(

		/** @var ReflectionClass<T> $reflector */
		private readonly ReflectionClass $reflector,

	) { }

	#[Override]
	public function offsetExists(mixed $offset): bool {
		return match($offset) {
			'options' => true,
			'head' => $this->reflector->hasMethod('head') && $this->reflector->getMethod('head')->isPublic()
				|| $this->offsetExists('get'),
			default => $this->reflector->hasMethod($offset) && $this->reflector->getMethod($offset)->isPublic()
		};
	}

	/** @return ($offset is 'get'|'head'|'options' ? Method : Method|null) */
	#[Override]
	public function offsetGet(mixed $offset): mixed {
		$method = $this->getDeclaredMethod($offset)
			?? $this->getImpliedMethod($offset)
			?? null;

		return $method === null && $offset === 'get'
			? throw ResourceException::forMissingGetMethod()
			: $method;
	}

	#[Override]
	public function offsetSet(mixed $offset, mixed $value): void {
		throw new LogicException('Unexpected attempt to modify method map');
	}

	#[Override]
	public function offsetUnset(mixed $offset): void {
		throw new LogicException('Unexpected attempt to modify method map');
	}

	#[Override]
	public function count(): int {
		if (!isset($this->map)) {
			$this->iterate();
		}

		return \count($this->map);
	}

	#[Override]
	public function getIterator(): Traversable {
		if (!isset($this->map)) {
			$this->iterate();
		}

		return new ArrayIterator($this->map);
	}

	private function iterate(): void {
		$this->map = [ ];

		foreach ($this->reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
			$name = \strtolower($method->getName());
			if (!\array_key_exists($name, $this->map)) {
				$this->map[$name] = new ReflectedMethod($method);
			}
		}

		if (!isset($this->map['get'])) {
			throw ResourceException::forMissingGetMethod();
		}

		$this->map['head'] = isset($this->map['head'])
			? new HeadMethod($this->map['head'])
			: $this->getImpliedMethod('head');

		if (!isset($this->map['options'])) {
			$this->map['options'] = $this->getImpliedMethod('options');
		}
	}

	/** @param lowercase-string&non-empty-string $name */
	private function getDeclaredMethod(string $name): ?Method {
		try {
			$method = $this->reflector->getMethod($name);
			if (!$method->isPublic()) {
				return null;
			}

			$endpointMethod = new ReflectedMethod($method);
			return $name === 'head'
				? new HeadMethod($endpointMethod)
				: $endpointMethod;
		} catch (ReflectionException $_) { }
		return null;
	}

	/**
	 * @param lowercase-string&non-empty-string $name
	 * @return ($name is 'options'|'head' ? Method : null)
	 */
	private function getImpliedMethod(string $name): ?Method {
		return match($name) {
			'options' => new ImpliedOptionsMethod,
			'head' => new HeadMethod($this['get']),
			default => null
		};
	}

}
