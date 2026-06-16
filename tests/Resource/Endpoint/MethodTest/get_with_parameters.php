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
class get_with_parameters implements Resource {

	#[Content\Text]
	public function get(string $required, string $optional = ''): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody($required);
	}

}
