<?php

namespace Slendium\Framework\Resource\Method\Content;

use Attribute;

use Slendium\Framework\Resource\Method\Content;

/**
 * Applies the header `content-type: text/html` to the response.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Html extends Content {

	/** @since 1.0 */
	const MEDIA_TYPE = 'text/html';

	/** @since 1.0 */
	public function __construct() {
		parent::__construct(self::MEDIA_TYPE);
	}

}
