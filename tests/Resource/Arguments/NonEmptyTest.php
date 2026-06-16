<?php

namespace Slendium\FrameworkTests\Resource\Arguments;

use Countable;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use Slendium\Framework\Resource\Arguments\NonEmpty;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class NonEmptyTest extends TestCase {

	public static function errorCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ '' ];
		yield [ [ ] ];
		yield [ new class { public function __toString(): string { return ''; } } ];
		yield [ new class implements Countable { public function count(): int { return 0; } } ];
	}

	#[DataProvider('errorCases')]
	public function test_validate_shouldYieldErrors_whenEmptyValueGiven(mixed $value): void {
		$result = \iterator_to_array((new NonEmpty)->validate($value), preserve_keys: false);

		$this->assertNotEmpty($result);
	}

	public static function validCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ 0 ];
		yield [ '0' ];
		yield [ false ];
		yield [ ' ' ];
		yield [ [ 1 ] ];
		yield [ (object)[] ];
		yield [ new class { public function __toString(): string { return 'ok'; } } ];
		yield [ new class implements Countable { public function count(): int { return 1; } } ];
	}

	#[DataProvider('validCases')]
	public function test_validate_shouldNotYieldErrors_whenNonEmptyOrInapplicableValueGiven(mixed $value): void {
		$result = \iterator_to_array((new NonEmpty)->validate($value), preserve_keys: false);

		$this->assertEmpty($result);
	}

}
