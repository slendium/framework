<?php

namespace Slendium\Framework\Resource\Endpoint;

use Slendium\Http\Request;
use Slendium\Http\Response as HttpResponse;

use Slendium\Framework\Resource;

/**
 * Describes the requirements and potential responses of a given resource method.
 *
 * Not to be implemented. Only its declaration and properties are part of the public API.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface Method {

	/**
	 * @since 1.0
	 * @var non-empty-string
	 */
	public string $name { get; }

	/**
	 * @since 1.0
	 * @var iterable<Parameter>
	 */
	public iterable $parameters { get; }

	/**
	 * @since 1.0
	 * @var iterable<Response>
	 */
	public iterable $responses { get; }

	/** @internal */
	public function invokeInternal(Resource $resource, Request $request): HttpResponse;

}
