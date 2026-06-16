<?php

namespace Slendium\FrameworkTests\Resource\HandlerTest;

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
class body_argument implements Resource {

	#[Content\Text]
	public function post(#[FromBody] string $input): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody($input);
	}

}
