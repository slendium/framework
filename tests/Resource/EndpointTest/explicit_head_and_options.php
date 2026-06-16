<?php

namespace Slendium\FrameworkTests\Resource\EndpointTest;

use Override;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method;
use Slendium\Framework\Resource\Method\Content;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class explicit_head_and_options implements Resource {

	#[Content\Text]
	public function options(string $optionsParameter = ''): Response {
		return Method\ResponseFactory::reflect(__METHOD__)->setBody('OPTIONS invoked.');
	}

	#[Content\Text]
	public function head(string $headParameter = ''): Response {
		return Method\ResponseFactory::reflect(__METHOD__)->setBody('HEAD invoked.');
	}

	#[Content\Text]
	public function get(): Response {
		return Method\ResponseFactory::reflect(__METHOD__)->setBody('GET invoked.');
	}

}
