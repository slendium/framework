<?php

namespace Slendium\Framework\Resource;

use Exception;

/**
 * Indicates a problem with the way the resource has been declared.
 *
 * This error is thrown and indicates a developer error.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class ResourceException extends Exception {

	/** @internal */
	public static function forMissingType(string $param): self {
		return new self("Parameter `$param` did not define a type");
	}

	/** @internal */
	public static function forUnsupportedType(string $param, string $type): self {
		return new self("Unsupported type `$type` for parameter `$param`");
	}

	/** @internal */
	public static function forMissingMethodReturnType(string $method): self {
		return new self("Expected implementation of HTTP $method to specify a return type");
	}

	/** @internal */
	public static function forUnsupportedMethodReturnType(string $method, string $type): self {
		return new self("Unsupported return type `$type` for HTTP $method, expected Method|Response|null");
	}

	/** @internal */
	public static function forMissingGetMethod(): self {
		return new self("Expected HTTP GET to be declared as a public method on the resource");
	}

}
