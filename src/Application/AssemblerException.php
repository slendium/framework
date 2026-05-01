<?php

namespace Slendium\Framework\Application;

use Exception;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class AssemblerException extends Exception {

	/** @since 1.0 */
	public static function forMissingResolver(Dependency $dependency): Exception {
		return new self(self::getMessagePrefix($dependency).', no resolver available');
	}

	/** @since 1.0 */
	public static function forMissingImplementation(Dependency $dependency): Exception {
		return new self(self::getMessagePrefix($dependency).', implementation not found');
	}

	/** @since 1.0 */
	public static function forUninstantiable(Dependency $dependency): Exception {
		return new self(self::getMessagePrefix($dependency).', not instantiable');
	}

	/** @since 1.0 */
	public static function forMissingParameterType(string $client, string $param): Exception {
		return new self("Unable to assemble parameter `$param` for $client, no type declared");
	}

	/** @since 1.0 */
	public static function forRequiredParameter(string $client, string $param, ?Exception $previous = null): Exception {
		return new self("Unable to assemble required parameter `$param` for $client", previous: $previous);
	}

	/** @since 1.0 */
	public static function forUninjectableParameter(string $client, string $param, string $type): Exception {
		if ($client === $type) {
			$type = 'self';
		}
		return new self("Unable to assemble parameter `$param` for $client, type `$type` can't be injected");
	}

	/** @since 1.0 */
	public static function forExhaustedUnionParameter(string $client, string $param, ?Exception $previous = null): Exception {
		return new self(
			message: "Unable to assemble parameter `$param` for $client, could not assemble any of the types in the union",
			previous: $previous
		);
	}

	private static function getMessagePrefix(Dependency $dependency): string {
		$intersection = \implode('&', $dependency->constraints);
		return "Unable to assemble $intersection for {$dependency->client}";
	}

}
