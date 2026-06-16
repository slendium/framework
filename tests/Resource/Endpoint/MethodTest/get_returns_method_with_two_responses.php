<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\MethodTest;

use Slendium\Framework\Resource;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class get_returns_method_with_two_responses implements Resource {

	public function get(): TwoResponsesMethod {
		return new TwoResponsesMethod();
	}

}
