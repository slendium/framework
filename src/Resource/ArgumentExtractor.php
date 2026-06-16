<?php

namespace Slendium\Framework\Resource;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface ArgumentExtractor {

	/**
	 * @since 1.0
	 * @param non-empty-string $name The argument name as defined by the resource
	 */
	public function extract(RequestInfo $request, string $name): ?Argument;

}
