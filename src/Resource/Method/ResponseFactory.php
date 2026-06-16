<?php

namespace Slendium\Framework\Resource\Method;

use ReflectionFunctionAbstract;
use ReflectionMethod;

use Slendium\Framework\Resource\Endpoint;
use Slendium\Framework\Builders\ResponseBuilder;
use Slendium\Framework\Resource\MutableResponse;

/**
 * Factory for {@see ResponseBuilder}s.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class ResponseFactory {

	/**
	 * @since 1.0
	 * @param non-empty-string $method A string of the form `ClassName::methodName`, as produced by
	 *  the `__METHOD__` magic constant
	 */
	public static function reflect(string $method): ResponseBuilder {
		return self::createFromReflectionFunction(ReflectionMethod::createFromMethodName($method));
	}

	/** @since 1.0 */
	public static function blank(): ResponseBuilder {
		return new MutableResponse;
	}

	/** @internal */
	public static function createFromReflectionFunction(ReflectionFunctionAbstract $function): ResponseBuilder {
		$response = new MutableResponse;
		foreach (new Endpoint\Response($function)->modifiers as $modifier) {
			$modifier->apply($response);
		}
		return $response;
	}

	private function __construct() { }

}
