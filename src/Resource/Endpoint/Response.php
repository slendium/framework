<?php

namespace Slendium\Framework\Resource\Endpoint;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionFunctionAbstract;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\ResponseModifier;

/**
 * Describes a potential response from the invocation of a resource method.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class Response {

	/**
	 * Non-empty sequence of response modifiers that contains at least one {@see Method\Status},
	 * falling back to {@see Resource\Method\Status\Ok} if none was declared.
	 * @since 1.0
	 * @var iterable<ResponseModifier>
	 */
	public iterable $modifiers {
		get => $this->get_modifiers();
	}

	/**
	 * @internal
	 * @param class-string<Resource\Method> $class
	 * @return iterable<self>
	 */
	public static function getAllFromClass(string $class): iterable {
		foreach (new ReflectionClass($class)->getMethods() as $method) {
			$hasModifier = false;
			foreach ($method->getAttributes(ResponseModifier::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
				$hasModifier = true;
				break;
			}

			if ($hasModifier) {
				yield new self($method);
			}
		}
	}

	public function __construct(private readonly ReflectionFunctionAbstract $reflector) { }

	/** @return iterable<ResponseModifier> */
	private function get_modifiers(): iterable {
		$hasStatus = false;
		$iterations = 0;
		foreach ($this->reflector->getAttributes(ResponseModifier::class, ReflectionAttribute::IS_INSTANCEOF) as $attr) {
			$modifier = $attr->newInstance();
			$hasStatus = $hasStatus || $modifier instanceof Resource\Method\Status;
			$iterations += 1;
			yield $modifier;
		}

		if (!$hasStatus || $iterations < 1) {
			yield new Resource\Method\Status\Ok;
		}
	}

}
