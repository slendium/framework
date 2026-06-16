<?php

namespace Slendium\FrameworkTests\Resource\HandlerTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class optional_argument implements Resource {

	const DEFAULT_REFLECTED_VALUE = 'default';

	#[Content\Text]
	public function get(string $reflected = self::DEFAULT_REFLECTED_VALUE): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody($reflected);
	}

}
