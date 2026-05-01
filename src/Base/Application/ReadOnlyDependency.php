<?php

namespace Slendium\Framework\Base\Application;

use Override;
use ReflectionAttribute;

use Slendium\Framework\Application\Dependency;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final readonly class ReadOnlyDependency implements Dependency {

	/** @since 1.0 */
	public function __construct(

		/** @var class-string */
		#[Override]
		public string $client,

		/** @var non-empty-list<class-string> */
		#[Override]
		public array $constraints,

		/** @var list<ReflectionAttribute<object>> */
		#[Override]
		public array $attributes = [ ],

	) { }

}
