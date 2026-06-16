<?php

namespace Slendium\Framework\Resource\Method\Status;

use Attribute;

use Slendium\Framework\Resource\Method\Status;

/**
 * Applies the "OK" HTTP status code to a response.
 *
 * This status code is implied when no other status code is specified. It does not have to be added
 * explicitly to every response-returning method.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Ok extends Status {

	/** @since 1.0 */
	const CODE = 200;

	/** @since 1.0 */
	public function __construct() {
		parent::__construct(self::CODE);
	}

}
