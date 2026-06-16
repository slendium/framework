<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ResponseTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;
use Slendium\Framework\Resource\Method\Status;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_inline_marked_not_found implements Resource {

	#[Content\Text, Status\NotFound]
	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
