<?php

namespace Slendium\Framework\Resource\Method;

use Attribute;
use Override;

use Slendium\Framework\Builders\ResponseBuilder;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Header implements ResponseModifier {

	/** @since 1.0 */
	public function __construct(

		/**
		 * @since 1.0
		 * @var lowercase-string&non-empty-string
		 */
		public readonly string $name,

		/** @since 1.0 */
		public readonly string $value,

	) { }

	#[Override]
	public function apply(ResponseBuilder $response): void {
		$response->setHeader($this->name, $this->value);
	}

}
