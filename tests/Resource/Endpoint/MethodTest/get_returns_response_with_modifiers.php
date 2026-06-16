<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\MethodTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_returns_response_with_modifiers implements Resource {

	#[Content\Text]
	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody('GET invoked');
	}

}
