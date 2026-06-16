<?php

namespace Slendium\Framework\Resource;

use ArrayAccess;

use Slendium\Http\Message\BodyArgs;
use Slendium\Http\Message\Cookies;
use Slendium\Http\Message\QueryArgs;
use Slendium\Http\Request;

/**
 * A wrapper for the HTTP request to improve information extraction.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final readonly class RequestInfo {

	/**
	 * @since 1.0
	 * @var ArrayAccess<string,mixed>
	 */
	public ArrayAccess $query;

	/**
	 * @since 1.0
	 * @var ArrayAccess<string,mixed>
	 */
	public ArrayAccess $body;

	/**
	 * @since 1.0
	 * @var ArrayAccess<string,mixed>
	 */
	public ArrayAccess $cookies;

	/** @internal */
	public function __construct(

		/** @since 1.0 */
		public Request $request,

	) {
		$this->query = QueryArgs::getAll($request);
		$this->body = BodyArgs::getAll($request);
		$this->cookies = Cookies::getAll($request);
	}

}
