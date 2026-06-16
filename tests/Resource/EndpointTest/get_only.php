<?php

namespace Slendium\FrameworkTests\Resource\EndpointTest;

use Override;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_only implements Resource {

	#[Content\Text]
	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody('GET invoked.');
	}

}
