<?php

namespace Slendium\FrameworkTests\Resource\HandlerTest;

use Countable;
use Stringable;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class intersection_argument implements Resource {

	public function get(Countable&Stringable $intersection): Response {
		return ResponseFactory::blank();
	}

}
