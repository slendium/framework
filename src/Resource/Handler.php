<?php

namespace Slendium\Framework\Resource;

use Slendium\Http\Message;
use Slendium\Http\Response;
use Slendium\Http\Request;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final readonly class Handler {

	public static function handle(Request $request, Resource $resource): Response {
		$method = Message\Headers::getFirst($request, ':method');
		if ($method === null) {
			return ResponseFactory::blank()
				->setHeader(':status', '400')
				->setHeader('content-type', 'text/plain')
				->setBody('Bad Request');
		}

		$method = Endpoint::fromInstance($resource)->methods[\strtolower($method->value)];
		return $method !== null
			? $method->invokeInternal($resource, $request)
			: ResponseFactory::blank()
				->setHeader(':status', '405')
				->setHeader('content-type', 'text/plain')
				->setBody('Method Not Allowed');
	}

	private function __construct() { }

}
