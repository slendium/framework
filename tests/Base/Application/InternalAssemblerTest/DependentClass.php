<?php

namespace Slendium\FrameworkTests\Base\Application\InternalAssemblerTest;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class DependentClass {

	public function __construct(

		public ConstructorlessClass $param1,

		public bool $flag = false,

	) { }

}
