<?php

namespace Slendium\Framework\Resource\Endpoint;

use Stringable;

use Slendium\Http\Field;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class ArgumentCasts {

	public static function toBool(mixed $value): ?bool {
		return match($value) {
			true, 1, '1', 'true' => true,
			false, 0, '0', 'false' => false,
			default => null
		};
	}

	public static function toInt(mixed $value): ?int {
		if (\is_int($value)) {
			return $value;
		}

		return \is_string($value) && \is_numeric($value) && \strpos($value, '.') === false
			? (int)$value
			: null;
	}

	public static function toFloat(mixed $value): ?float {
		return match(true) {
			\is_float($value) => $value,
			\is_int($value) || \is_string($value) && \is_numeric($value) => (float)$value,
			default => null
		};
	}

	public static function toString(mixed $value): ?string {
		return \is_scalar($value) || $value instanceof Stringable
			? (string)$value
			: null;
	}

	public static function toField(mixed $value): ?Field {
		return $value instanceof Field
			? $value
			: null;
	}

	private function __construct() { }

}
