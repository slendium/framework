<?php

namespace Slendium\Framework\Application;

/**
 * Assembles objects using dependency injection.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface Assembler {

	/**
	 * Assembles a dependency into an object instance.
	 * @since 1.0
	 */
	public function assemble(Dependency $dependency): object;

}
