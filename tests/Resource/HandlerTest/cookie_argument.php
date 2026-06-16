<?php

namespace Slendium\FrameworkTests\Resource\HandlerTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Arguments\FromCookie;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class cookie_argument implements Resource {

	#[Content\Text]
	public function get(#[FromCookie] string $input): Response {
		return ResponseFactory::reflect(__METHOD__)->setBOdy($input);
	}

}
