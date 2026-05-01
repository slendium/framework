<?php

namespace Slendium\Framework\Application;

use ReflectionAttribute;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface Dependency {

	/**
	 * The client that requires the dependency to be resolved.
	 * @since 1.0
	 * @var class-string
	 */
	public string $client { get; }

	/**
	 * Type constraints that all must apply to the created service.
	 * @since 1.0
	 * @var non-empty-list<class-string>
	 */
	public array $constraints { get; }

	/**
	 * @since 1.0
	 * @var list<ReflectionAttribute<object>>
	 */
	public array $attributes { get; }

}
