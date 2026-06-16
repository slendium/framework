<?php

namespace Slendium\Framework\Resource\Endpoint;

use Override;

use Slendium\Http\Request;
use Slendium\Http\Response;

use Slendium\Framework\Resource;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class HeadMethod implements Method {

	#[Override]
	public string $name {
		get => 'HEAD';
	}

	#[Override]
	public iterable $parameters {
		get => $this->baseMethod->parameters;
	}

	#[Override]
	public iterable $responses {
		get => $this->baseMethod->responses;
	}

	public function __construct(private readonly Method $baseMethod) { }

	#[Override]
	public function invokeInternal(Resource $resource, Request $request): Response {
		$response = $this->baseMethod->invokeInternal($resource, $request);
		return new class($response) implements Response {

			#[Override]
			public iterable $headers {
				get => $this->response->headers;
			}

			#[Override]
			public iterable $body {
				get => [ ];
			}

			#[Override]
			public iterable $trailers {
				get => $this->response->trailers;
			}

			public function __construct(private readonly Response $response) { }

		};
	}

}
