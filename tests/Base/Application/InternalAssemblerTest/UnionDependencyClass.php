<?php

namespace Slendium\FrameworkTests\Base\Application\InternalAssemblerTest;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class UnionDependencyClass {

	public function __construct(public PrivateConstructorClass|ConstructorlessClass $parameter) { }

}
