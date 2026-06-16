<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ParameterTest;

use ArrayAccess;
use Countable;

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
class get_with_intersection_parameter implements Resource {

	/** @param ArrayAccess<mixed,mixed>&Countable $required */
	#[Content\Text]
	public function get(ArrayAccess&Countable $required): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
