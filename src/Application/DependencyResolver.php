<?php

namespace Slendium\Framework\Application;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface DependencyResolver {

	/**
	 * An intersection of types satisfied by this resolver.
	 * @since 1.0
	 * @var non-empty-list<class-string>
	 */
	public array $satisfies { get; }

	/** @since 1.0 */
	public function resolve(Assembler $assembler, Dependency $dependency): object;

}
