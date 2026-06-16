<?php

namespace Slendium\FrameworkTests\Resource\Endpoint\MethodTest;

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
class TwoResponsesMethod implements Method {

	#[Override]
	public function invoke(): Response {
		return \random_int(0, 1) > 0
			? $this->respondOk()
			: $this->respondNoContent();
	}

	#[Content\Text]
	private function respondOk(): Response {
		return ResponseFactory::reflect(__METHOD__)->setBody('OK');
	}

	#[Content\Text, Status\NoContent]
	private function respondNoContent(): Response {
		return ResponseFactory::reflect(__METHOD__);
	}

}
