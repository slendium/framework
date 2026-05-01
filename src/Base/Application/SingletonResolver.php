<?php

namespace Slendium\Framework\Base\Application;

use Override;

use Slendium\Framework\Application\Assembler;
use Slendium\Framework\Application\Dependency;
use Slendium\Framework\Application\DependencyResolver;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final readonly class SingletonResolver implements DependencyResolver {

	#[Override]
	public array $satisfies;

	/**
	 * @since 1.0
	 * @param ?non-empty-list<class-string> $satisfies
	 */
	public function __construct(private object $instance, ?array $satisfies = null) {
		$this->satisfies = $satisfies ?? [ $instance::class ];
	}

	#[Override]
	public function resolve(Assembler $assembler, Dependency $dependency): object {
		return $this->instance;
	}

}
