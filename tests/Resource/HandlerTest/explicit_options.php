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
class explicit_options implements Resource {

	#[Content\Text]
	public function options(): Response {
		return ResponseFactory::reflect(__METHOD__)
			->setHeader('x-method', 'options')
			->setBody('Not returning an `Allow` header on purpose');
	}

}
