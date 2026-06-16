<?php

namespace Slendium\FrameworkTests\Resource\HandlerTest;

use Slendium\Http\Field;
use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Arguments\FromHeader;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class header_arguments implements Resource {

	public function get(
		#[FromHeader] Field $X_IMPLIED_NAME,
		#[FromHeader('x-explicit-name')] Field $explicitName,
	): Response {
		return ResponseFactory::reflect(__METHOD__)
			->setHeader('x-implied-name', $X_IMPLIED_NAME->value)
			->setHeader('x-explicit-name', $explicitName->value);
	}

}
