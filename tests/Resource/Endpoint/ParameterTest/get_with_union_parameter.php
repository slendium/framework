<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ParameterTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_with_union_parameter implements Resource {

	#[Content\Text]
	public function get(string|int|float $required): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
