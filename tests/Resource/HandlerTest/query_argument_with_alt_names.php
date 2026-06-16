<?php

namespace Slendium\FrameworkTests\Resource\HandlerTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Arguments\FromQuery;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class query_argument_with_alt_names implements Resource {

	#[Content\Text]
	public function get(#[FromQuery(altNames: [ 'alt', 'legacy' ])] string $input): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody($input);
	}

}
