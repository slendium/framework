<?php

namespace Slendium\Framework\Resource\Endpoint;

use Override;
use ReflectionMethod;

use Slendium\Http\Request;
use Slendium\Http\Response as HttpResponse;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Endpoint\Response as EndpointResponse;
use Slendium\Framework\Resource\Method\ResponseFactory;
use Slendium\Framework\Resource\Method\Status;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class ImpliedOptionsMethod implements Method {

	#[Override]
	public string $name {
		get => 'OPTIONS';
	}

	#[Override]
	public iterable $parameters {
		get => [ ];
	}

	#[Override]
	public iterable $responses {
		get => [ new EndpointResponse(new ReflectionMethod(__CLASS__.'::invokeInternal')) ];
	}

	#[Override, Status\NoContent]
	public function invokeInternal(Resource $resource, Request $request): HttpResponse {
		$methods = [ ];
		foreach (Resource\Endpoint::fromClass(\get_class($resource))->methods as $method) {
			$methods[] = $method->name;
		}

		return ResponseFactory::reflect(__METHOD__)
			->setHeader('allow', \implode(', ', $methods));
	}

}
