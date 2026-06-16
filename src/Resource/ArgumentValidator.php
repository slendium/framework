<?php

namespace Slendium\Framework\Resource;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface ArgumentValidator {

	/**
	 * @since 1.0
	 * @return iterable<ValidationException>
	 */
	public function validate(mixed $value): iterable;

}
