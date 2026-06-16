<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ResponseTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_with_method implements Resource {

	public function get(): GetWithMethodMethod {
		return new GetWithMethodMethod;
	}

}
