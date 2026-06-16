<?php

namespace Slendium\Framework\Resource;

use ArrayAccess;
use Countable;
use ReflectionClass;
use Traversable;

use Slendium\Framework\Resource;

/**
 * An HTTP endpoint that derives its behavior from a declared resource.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class Endpoint {

	/**
	 * @since 1.0
	 * @param class-string<Resource> $class
	 */
	public static function fromClass(string $class): self {
		return new self(Endpoint\MethodMap::forClass($class));
	}

	/** @internal */
	public static function fromInstance(Resource $instance): self {
		return new self(new Endpoint\MethodMap(new ReflectionClass($instance)));
	}

	private function __construct(

		/**
		 * @since 1.0
		 * @var ArrayAccess<lowercase-string&non-empty-string,?Endpoint\Method>&Countable&Traversable<Endpoint\Method>
		 */
		public ArrayAccess&Countable&Traversable $methods,

	) { }

}
