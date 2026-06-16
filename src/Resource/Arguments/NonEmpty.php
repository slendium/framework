<?php

namespace Slendium\Framework\Resource\Arguments;

use Attribute;
use Countable;
use Override;
use Stringable;

use Slendium\Framework\Resource\ArgumentValidator;
use Slendium\Framework\Resource\ValidationException;

/**
 * Validates only when the argument value is non-empty.
 *
 * The concept of "emptiness" is only defined for `Stringable|string` and `Countable|array`.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class NonEmpty implements ArgumentValidator {

	#[Override]
	public function validate(mixed $value): iterable {
		if (\is_array($value) && empty($value) || $value instanceof Countable && \count($value) <= 0) {
			yield new ValidationException('Expected argument value to contain at least one item');
		}

		if (\is_string($value) && $value === '' || $value instanceof Stringable && ((string)$value) === '') {
			yield new ValidationException('Expected argument value to be a non-empty string');
		}
	}

}
