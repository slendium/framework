<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ParameterTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Arguments\FromQuery;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class explicit_query_parameter implements Resource {

	#[Content\Text]
	public function get(#[FromQuery] string $required): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody($required);
	}

}
