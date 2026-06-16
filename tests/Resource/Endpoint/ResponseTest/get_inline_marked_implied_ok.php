<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ResponseTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_inline_marked_implied_ok implements Resource {

	#[Content\Text]
	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
