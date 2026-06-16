<?php

namespace Slendium\FrameworkTests\Resource;

use ArrayAccess;
use ArrayObject;
use Countable;
use IteratorAggregate;
use Override;
use Traversable;

use Slendium\Http\Content\Structured;

/**
 * @internal
 * @implements IteratorAggregate<string>
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class MockedEncodedBody implements IteratorAggregate, Structured {

	#[Override]
	public ArrayAccess&Countable&Traversable $root {
		get => new ArrayObject($this->body);
	}

	public function __construct(

		/** @var array<string,mixed> */
		public readonly array $body,

	) { }

	#[Override]
	public function getIterator(): Traversable {
		yield \http_build_query($this->body);
	}

}
