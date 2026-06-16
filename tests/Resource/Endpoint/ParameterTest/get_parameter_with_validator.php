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
class get_parameter_with_validator implements Resource {

	public function get(#[NonEmpty] string $required): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
