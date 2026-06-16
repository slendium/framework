<?php

namespace Slendium\FrameworkTests\Resource\Endpoint;

use Countable;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Slendium\Http\Field;
use Slendium\Http\ReadOnlyField;

use Slendium\Framework\Resource\Endpoint\ArgumentCasts;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class ArgumentCastsTest extends TestCase {

	public static function boolCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ true, true ];
		yield [ 1, true ];
		yield [ '1', true ];
		yield [ 'true', true ];
		yield [ false, false ];
		yield [ 0, false ];
		yield [ '0', false ];
		yield [ 'false', false ];
		yield [ '', null ];
		yield [ null, null ];
		yield [ [ ], null ];
		yield [ [ '' ], null ];
		yield [ (object)[ ], null ];
	}

	#[DataProvider('boolCases')]
	public function test_toBool_shouldReturnExpectedResult(mixed $value, ?bool $expectedResult): void {
		$result = ArgumentCasts::toBool($value);

		$this->assertSame($expectedResult, $result);
	}

	public static function intCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ -1, -1 ];
		yield [ 0, 0 ];
		yield [ 1, 1 ];
		yield [ '-1', -1 ];
		yield [ '0', 0 ];
		yield [ '1', 1 ];
		yield [ 'abc', null ];
		yield [ '1.0', null ];
		yield [ 1.0, null ];
		yield [ null, null ];
		yield [ [ ], null ];
		yield [ (object)[ ], null ];
	}

	#[DataProvider('intCases')]
	public function test_toInt_shouldReturnExpectedResult(mixed $value, ?int $expectedResult): void {
		$result = ArgumentCasts::toInt($value);

		$this->assertSame($expectedResult, $result);
	}

	public static function floatCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ '-1.0', -1.0 ];
		yield [ 1.0, 1.0 ];
		yield [ 1, 1.0 ];
		yield [ null, null ];
		yield [ [ ], null ];
		yield [ (object)[ ], null ];
	}

	#[DataProvider('floatCases')]
	public function test_toFloat_shouldReturnExpectedResult(mixed $value, ?float $expectedResult): void {
		$result = ArgumentCasts::toFloat($value);

		$this->assertSame($expectedResult, $result);
	}

	public static function stringCases(): iterable { // @phpstan-ignore missingType.iterableValue
		yield [ '', '' ];
		yield [ 'nonempty', 'nonempty' ];
		yield [ new class { public function __toString(): string { return 'inner'; } }, 'inner' ];
		yield [ null, null ];
		yield [ [ ], null ];
		yield [ (object)[ ], null ];
	}

	#[DataProvider('stringCases')]
	public function test_toString_shouldReturnExpectedResult(mixed $value, ?string $expectedResult): void {
		$result = ArgumentCasts::toString($value);

		$this->assertSame($expectedResult, $result);
	}

	public static function fieldCases(): iterable { // @phpstan-ignore missingType.iterableValue
		$field = new ReadOnlyField('x-test', '1');
		yield [ $field, $field ];
		yield [ null, null ];
		yield [ [ ], null ];
		yield [ (object)[ ], null ];
	}

	#[DataProvider('fieldCases')]
	public function test_toField_shouldReturnExpectedResult(mixed $value, ?Field $expectedResult): void {
		$result = ArgumentCasts::toField($value);

		$this->assertSame($expectedResult, $result);
	}

}
