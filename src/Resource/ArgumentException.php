<?php

namespace Slendium\Framework\Resource;

use Exception;

/**
 * An error returned from methods that extract and validate request arguments, such as query arguments,
 * cookies or POST'ed form data.
 *
 * This error is normally returned instead of thrown.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class ArgumentException extends Exception {

	/** @since 1.0 */
	public static function forRequiredArgument(string $name): self {
		return new self("Missing required argument `$name`");
	}

	/**
	 * @since 1.0
	 * @param array<string>|string $expected
	 */
	public static function forInvalidType(array|string $expected): self {
		if (\is_array($expected)) {
			$expected = \implode('|', $expected);
		}

		return new self("Expected argument type to be `$expected`");
	}

	/**
	 * @since 1.0
	 * @param list<ValidationException> $errors
	 */
	public static function forValidationErrors(string $paramName, array $errors): self {
		$errorConcat = $errors
			|> (fn($x) => \array_map(fn($e) => '* '.$e->getMessage(), $x))
			|> (fn($x) => \implode("\n", $x));

		return new self("Unexpected validation errors for argument `$paramName`:\n$errorConcat");
	}

}
