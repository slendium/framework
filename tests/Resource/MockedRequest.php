<?php

namespace Slendium\FrameworkTests\Resource;

use Override;
use Stringable;

use Slendium\Http\Base\FieldSet;
use Slendium\Http\Field;
use Slendium\Http\ReadOnlyField;
use Slendium\Http\Request;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class MockedRequest implements Request {

	#[Override]
	public iterable $trailers {
		get => [ ];
	}

	/**
	 * @param iterable<Field> $headers
	 * @param iterable<Stringable|string> $body
	 */
	public static function head(iterable $headers = [ ], iterable $body = [ ]): Request {
		return new self(new FieldSet([ new ReadOnlyField(':method', 'HEAD'), ...$headers ]), $body);
	}

	/**
	 * @param iterable<Field> $headers
	 * @param iterable<Stringable|string> $body
	 */
	public static function options(iterable $headers = [ ], iterable $body = [ ]): Request {
		return new self(new FieldSet([ new ReadOnlyField(':method', 'OPTIONS'), ...$headers ]), $body);
	}

	/**
	 * @param iterable<Field> $headers
	 * @param iterable<Stringable|string> $body
	 */
	public static function get(iterable $headers = [ ], iterable $body = [ ]): Request {
		return new self(new FieldSet([ new ReadOnlyField(':method', 'GET'), ...$headers ]), $body);
	}

	/**
	 * @param iterable<Field> $headers
	 * @param iterable<Stringable|string> $body
	 */
	public static function post(iterable $headers = [ ], iterable $body = [ ]): Request {
		return new self(new FieldSet([ new ReadOnlyField(':method', 'POST'), ...$headers ]), $body);
	}

	public function __construct(

		/** @var iterable<Field> */
		#[Override]
		public iterable $headers = [ ],

		/** @var iterable<Stringable|string> */
		#[Override]
		public iterable $body = [ ],

	) { }

}
