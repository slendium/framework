<?php

namespace Slendium\Framework\Resource\Method\Content;

use Attribute;

use Slendium\Framework\Resource\Method\Content;

/**
 * Applies the header `content-type: text/plain` to the response.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Text extends Content {

	/** @since 1.0 */
	const MEDIA_TYPE = 'text/plain';

	/** @since 1.0 */
	public function __construct() {
		parent::__construct(self::MEDIA_TYPE);
	}

}
