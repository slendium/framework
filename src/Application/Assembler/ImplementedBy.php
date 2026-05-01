<?php

namespace Slendium\Framework\Application\Assembler;

use Attribute;

/**
 * Allows marking an interface with an implementation for the assembler to instantiate in case one is
 * not available in the assembler itself.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final readonly class ImplementedBy {

	/** @since 1.0 */
	public function __construct(

		/**
		 * @since 1.0
		 * @var class-string
		 */
		public string $type,

	) { }
}
