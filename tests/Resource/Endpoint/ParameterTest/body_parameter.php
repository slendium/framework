<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ParameterTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Arguments\FromBody;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class body_parameter implements Resource {

	#[Content\Text]
	public function post(#[FromBody] string $required): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody($required);
	}

}
