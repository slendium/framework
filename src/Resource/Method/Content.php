<?php

namespace Slendium\Framework\Resource\Method;

/**
 * Applies a `content-type` header to an HTTP response.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class Content extends Header {

	/**
	 * @since 1.0
	 * @param non-empty-string $type
	 */
	public function __construct(string $type) {
		parent::__construct('content-type', $type);
	}

}
