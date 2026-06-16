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
class explicit_head implements Resource {

	#[Content\Text]
	public function head(): Response {
		return ResponseFactory::reflect(__METHOD__)
			->setHeader('x-method', 'head')
			->setBody('Should not appear in results');
	}

	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__)->setHeader('x-method', 'get');
	}

}
