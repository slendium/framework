<?php

namespace Slendium\Framework\Resource\Method;

use Slendium\Framework\Builders\ResponseBuilder;

/**
 * HTTP response modifier for a declared resource method.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface ResponseModifier {

	/** @since 1.0 */
	public function apply(ResponseBuilder $response): void;

}
