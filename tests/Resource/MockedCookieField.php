<?php

namespace Slendium\FrameworkTests\Resource;

use ArrayAccess;
use ArrayObject;
use Countable;
use Override;
use Traversable;

use Slendium\Http\Content\Structured;
use Slendium\Http\Field;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class MockedCookieField implements Field, Structured {

	#[Override]
	public string $name {
		get => 'cookie';
	}

	#[Override]
	public string $value {
		get => $this->get_value();
	}

	#[Override]
	public ArrayAccess&Countable&Traversable $root {
		get => new ArrayObject($this->cookies);
	}

	public function __construct(

		/** @var array<string,string> */
		private readonly array $cookies = [ ],

	) { }

	public function __toString(): string {
		return "{$this->name}: {$this->value}";
	}

	private function get_value(): string {
		$out = [ ];
		foreach ($this->cookies as $name => $value) {
			$out[] = \urlencode($name).'='.\urlencode($value);
		}
		return \implode('; ', $out);
	}

}
