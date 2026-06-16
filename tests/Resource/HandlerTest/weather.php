<?php

namespace Slendium\FrameworkTests\Resource\HandlerTest;

use Slendium\Http\Response;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class weather implements Resource {

	private const DATA = [
		[
			'city' => 'Amsterdam',
			'description' => 'Cloudy',
			'temperature' => 22,
			'humidity' => .78
		]
	];

	#[Content\Json]
	public function get(): Response {
		return ResponseFactory::reflect(__METHOD__)
			|> (fn($x) => $x->setHeader('x-method', 'get'))
			|> (fn($x) => Content\Json::applyBody($x, [ 'matches' => self::DATA ]));
	}

}
