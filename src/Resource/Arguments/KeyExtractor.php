<?php

namespace Slendium\Framework\Resource\Arguments;

use ArrayAccess;
use Override;

use Slendium\Framework\Resource\Argument;
use Slendium\Framework\Resource\ArgumentExtractor;
use Slendium\Framework\Resource\RequestInfo;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
abstract class KeyExtractor implements ArgumentExtractor {

	public function __construct(

		/** @var list<non-empty-string> */
		private readonly array $altNames = [ ],

	) { }

	#[Override]
	public function extract(RequestInfo $info, string $name): ?Argument {
		$root = $this->getRoot($info);
		foreach ([ $name, ...$this->altNames ] as $tryName) {
			if (isset($root[$tryName])) {
				return new Argument($root[$tryName]);
			}
		}
		return null;
	}

	/** @return ArrayAccess<string,mixed>|array<string,mixed> */
	protected abstract function getRoot(RequestInfo $info): ArrayAccess|array;

}
