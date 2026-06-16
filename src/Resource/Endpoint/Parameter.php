<?php

namespace Slendium\Framework\Resource\Endpoint;

use ReflectionAttribute;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

use Slendium\Http\Field;

use Slendium\Framework\Resource\Argument;
use Slendium\Framework\Resource\Arguments\FromQuery;
use Slendium\Framework\Resource\ArgumentException;
use Slendium\Framework\Resource\ArgumentExtractor;
use Slendium\Framework\Resource\ArgumentValidator;
use Slendium\Framework\Resource\RequestInfo;
use Slendium\Framework\Resource\ResourceException;
use Slendium\Framework\Resource\ValidationException;

/**
 * Describes a single parameter of a resource method.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class Parameter {

	/**
	 * @since 1.0
	 * @var non-empty-string
	 */
	public string $name {
		get => $this->reflector->name; // @phpstan-ignore return.type (assume parameter names cant be empty)
	}

	/**
	 * Ordered sequence of valid types.
	 * @since 1.0
	 * @var iterable<non-empty-string>
	 */
	public iterable $types {
		get => $this->get_types();
	}

	/**
	 * Ordered sequence of extractors to try to obtain an argument value.
	 *
	 * Will yield at least one item, falling back to {@see FromQuery} if none were declared.
	 *
	 * @since 1.0
	 * @var iterable<ArgumentExtractor>
	 */
	public iterable $extractors {
		get => $this->get_extractors();
	}

	/**
	 * @since 1.0
	 * @var iterable<ArgumentValidator>
	 */
	public iterable $validators {
		get => $this->get_validators();
	}

	/** @since 1.0 */
	public bool $isOptional {
		get => $this->reflector->isOptional();
	}

	/**
	 * The default value, if this parameter is optional.
	 *
	 * Throws an exception if the parameter is not optional.
	 *
	 * @since 1.0
	 * @see self::$isOptional
	 */
	public mixed $defaultValue {
		get => $this->reflector->getDefaultValue();
	}

	/** @internal */
	public function __construct(private readonly ReflectionParameter $reflector) { }

	/** @internal */
	public function extractValidatedArgument(RequestInfo $request): Argument|ArgumentException {
		$argument = $this->extract($request);
		if ($argument instanceof ArgumentException) {
			return $argument;
		}

		$validationErrors = \iterator_to_array($this->validate($argument->value), preserve_keys: false);
		return \count($validationErrors) > 0
			? ArgumentException::forValidationErrors($this->name, $validationErrors)
			: $argument;
	}

	private function extract(RequestInfo $request): Argument|ArgumentException {
		foreach ($this->extractors as $extractor) {
			$argument = $extractor->extract($request, $this->name);
			if ($argument !== null) {
				return $this->coerceFirstValidType($argument);
			}
		}

		return $this->isOptional
			? new Argument($this->defaultValue)
			: ArgumentException::forRequiredArgument($this->name);
	}

	private function coerceFirstValidType(Argument $argument): Argument|ArgumentException {
		$typesAsString = [ ];
		foreach ($this->types as $type) {
			$typesAsString[] = $type;
			$result = $this->coerceSpecificType($argument, $type);
			if ($result instanceof Argument) {
				return $result;
			}
		}

		return ArgumentException::forInvalidType($typesAsString);
	}

	private function coerceSpecificType(Argument $argument, string $type): Argument|ArgumentException {
		$argument->value = match($type) {
			'bool' => ArgumentCasts::toBool($argument->value),
			'int' => ArgumentCasts::toInt($argument->value),
			'float' => ArgumentCasts::toFloat($argument->value),
			'string' => ArgumentCasts::toString($argument->value),
			Field::class => ArgumentCasts::toField($argument->value),
			default => throw ResourceException::forUnsupportedType($this->name, $type)
		};

		return $argument->value === null
			? ArgumentException::forInvalidType($type)
			: $argument;
	}

	/** @return iterable<ValidationException> */
	private function validate(mixed $value): iterable {
		foreach ($this->validators as $validator) {
			yield from $validator->validate($value);
		}
	}

	/** @return iterable<non-empty-string> */
	private function get_types(): iterable {
		$type = $this->reflector->getType();
		return match(true) {
			$type === null => throw ResourceException::forMissingType($this->name),
			$type instanceof ReflectionNamedType => self::generateNamedType($type),
			$type instanceof ReflectionUnionType => $this->generateUnionTypes($type),
			default => throw ResourceException::forUnsupportedType($this->name, $type)
		};
	}

	/** @return iterable<non-empty-string> */
	private static function generateNamedType(ReflectionNamedType $named): iterable {
		yield $named->getName(); // @phpstan-ignore generator.valueType (assume type strings cant be empty)
	}

	/** @return iterable<non-empty-string> */
	private function generateUnionTypes(ReflectionUnionType $union): iterable {
		foreach ($union->getTypes() as $type) {
			if ($type instanceof ReflectionIntersectionType) {
				throw ResourceException::forUnsupportedType($this->name, $type);
			}

			yield $type->getName(); // @phpstan-ignore generator.valueType (assume type strings cant be empty)
		}
	}

	/** @return iterable<ArgumentExtractor> */
	private function get_extractors(): iterable {
		$iterations = 0;
		foreach ($this->reflector->getAttributes(ArgumentExtractor::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
			yield $attr->newInstance();
			$iterations += 1;
		}

		if ($iterations < 1) {
			yield new FromQuery;
		}
	}

	/** @return iterable<ArgumentValidator> */
	private function get_validators(): iterable {
		foreach ($this->reflector->getAttributes(ArgumentValidator::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
			yield $attr->newInstance();
		}
	}

}
