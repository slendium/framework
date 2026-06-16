<?php

namespace Slendium\Framework\Resource\Method\Status;

use Attribute;

use Slendium\Framework\Resource\Method\Status;

/**
 * Applies the "Accepted" HTTP status code to a response.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Accepted extends Status {

	/** @since 1.0 */
	const CODE = 202;

	/** @since 1.0 */
	public function __construct() {
		parent::__construct(self::CODE);
	}

}
