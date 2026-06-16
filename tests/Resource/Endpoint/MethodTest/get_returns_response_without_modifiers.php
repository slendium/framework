<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\MethodTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_returns_response_without_modifiers implements Resource {

	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
