<?php

namespace Slendium\FrameworkTests\Base\Application\InternalAssemblerTest;

use Slendium\Framework\Application\Assembler\ImplementedBy;

/**
 * Implementations that don't exist should be silently ignored, allowing libraries to implement and
 * thus override the provided defaults without making the library required.
 *
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[ImplementedBy(does_not_exist::class), ImplementedBy(InterfaceWithImplementedByImpl::class)] // @phpstan-ignore class.notFound (deliberate)
interface InterfaceWithImplementedBy { }
