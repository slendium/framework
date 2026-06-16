<?php

namespace Slendium\FrameworkTests\Resource\EndpointTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class public_get_private_post implements Resource {

	#[Content\Text]
	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody('GET invoked.');
	}

	#[Content\Text] // @phpstan-ignore method.unused (deliberate for testing purposes)
	private function post(): Response{
		return ResponseFactory::reflect(__METHOD__)->setBody('POST invoked.');
	}

}
