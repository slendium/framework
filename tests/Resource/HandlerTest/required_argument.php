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
class required_argument implements Resource {

	#[Content\Text]
	public function get(string $reflected): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody($reflected);
	}

}
