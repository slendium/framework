<?php

namespace Slendium\Framework\Resource\Method;

use Attribute;

/**
 * Applies an HTTP status code to a response.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Status extends Header {

	/**
	 * @since 1.0
	 * @param int<100,599> $status
	 */
	public function __construct(int $status) {
		parent::__construct(':status', (string)$status);
	}

}
