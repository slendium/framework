<?php

namespace Slendium\Framework\Resource\Arguments;

use ArrayAccess;
use Attribute;
use Override;

use Slendium\Framework\Resource\RequestInfo;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class FromBody extends KeyExtractor {

	#[Override]
	public function getRoot(RequestInfo $info): ArrayAccess {
		return $info->body;
	}

}
