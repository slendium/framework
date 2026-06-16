<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ParameterTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Arguments\NonEmpty;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_with_optional_parameter implements Resource {

	const DEFAULT_VALUE = 'b49823b2-2344-4526-9059-9100451e7e64';

	public function get(string|int|null $optional = self::DEFAULT_VALUE): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
