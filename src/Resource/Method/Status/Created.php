<?php

namespace Slendium\Framework\Resource\Method\Status;

use Attribute;

use Slendium\Framework\Resource\Method\Status;

/**
 * Applies the "Created" HTTP status code to a response.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Created extends Status {

	/** @since 1.0 */
	const CODE = 201;

	/** @since 1.0 */
	public function __construct() {
		parent::__construct(self::CODE);
	}

}
