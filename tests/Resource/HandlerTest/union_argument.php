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
class union_argument implements Resource {

	#[Content\Text]
	public function get(int|string|float $union): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody(\get_debug_type($union));
	}

}
