<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\ResponseTest;

use Override;

use Slendium\Http\Response;

use Slendium\Framework\Resource\Method;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;
use Slendium\Framework\Resource\Method\Status;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class GetWithMethodMethod implements Method {

	#[Override, Content\Text, Status\NotFound]
	public function invoke(): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
