<?php

namespace Slendium\FrameworkTests\Resource\Endpoint;

use PHPUnit\Framework\TestCase;

use Slendium\Framework\Resource\Arguments\FromBody;
use Slendium\Framework\Resource\Arguments\FromQuery;
use Slendium\Framework\Resource\Arguments\NonEmpty;
use Slendium\Framework\Resource\Endpoint;
use Slendium\Framework\Resource\ResourceException;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class ParameterTest extends TestCase {

	public function test_name_shouldReturnExpectedResult(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\explicit_query_parameter::class)->methods['get']->parameters ?? [ ]);

		$result = $sut->name;

		$this->assertSame('required', $result);
	}

	public function test_extractors_shouldContainFromQuery_whenNoExtractorsDeclared(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\implied_query_parameter::class)->methods['get']->parameters ?? [ ]);

		$result = self::getFirst($sut->extractors);

		$this->assertInstanceOf(FromQuery::class, $result);
	}

	public function test_extractors_shouldContainOneFromQuery_whenExplicitlyDeclared(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\explicit_query_parameter::class)->methods['get']->parameters ?? [ ]);

		$result = \iterator_to_array($sut->extractors, preserve_keys: false);

		$this->assertSame(1, \count($result));
		$this->assertInstanceOf(FromQuery::class, $result[0]);
	}

	public function test_extractors_shouldContainFromBody_whenDeclared(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\body_parameter::class)->methods['post']->parameters ?? [ ]);

		$result = \iterator_to_array($sut->extractors, preserve_keys: false);

		$this->assertSame(1, \count($result));
		$this->assertInstanceOf(FromBody::class, $result[0]);
	}

	public function test_types_shouldThrow_whenUsingIntersectionParameter(): void {
		// Arrange
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\get_with_intersection_parameter::class)->methods['get']->parameters ?? [ ]);

		// Assert
		$this->expectException(ResourceException::class);

		// Act
		\iterator_to_array($sut->types);
	}

	public function test_type_shouldThrow_whenUsingIntersectionInsideUnionParameter(): void {
		// Arrange
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\get_with_intersection_inside_union_parameter::class)->methods['get']->parameters ?? [ ]);

		// Assert
		$this->expectException(ResourceException::class);

		// Act
		\iterator_to_array($sut->types);
	}

	public function test_type_shouldThrow_whenTypeMissing(): void {
		// Arrange
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\get_with_missing_type_parameter::class)->methods['get']->parameters ?? [ ]);

		// Assert
		$this->expectException(ResourceException::class);

		// Act
		\iterator_to_array($sut->types);
	}

	public function test_type_shouldReturnSingleItem_whenSingleTypeDeclared(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\explicit_query_parameter::class)->methods['get']->parameters ?? [ ]);

		$result = \iterator_to_array($sut->types, preserve_keys: false);

		$this->assertSame([ 'string' ], $result);
	}

	public function test_type_shouldReturnExpectedResult_whenUsingUnionParameter(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\get_with_union_parameter::class)->methods['get']->parameters ?? [ ]);

		$result = \iterator_to_array($sut->types, preserve_keys: false);

		$this->assertSame([ 'string', 'int', 'float' ], $result);
	}

	public function test_validators_shouldBeEmpty_whenNoValidatorsDeclared(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\get_parameter_without_validator::class)->methods['get']->parameters ?? [ ]);

		$result = \iterator_to_array($sut->validators, preserve_keys: false);

		$this->assertSame([ ], $result);
	}

	public function test_validators_shouldContainNonEmptyValidator_whenDeclared(): void {
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\get_parameter_with_validator::class)->methods['get']->parameters ?? [ ]);

		$result = \iterator_to_array($sut->validators, preserve_keys: false);

		$this->assertSame(1, \count($result));
		$this->assertInstanceOf(NonEmpty::class, $result[0]);
	}

	public function test_isOptional_shouldBeTrue_and_defaultValue_shouldHaveExpectedValue_whenDefaultValueGiven(): void {
		$expectedDefaultValue = ParameterTest\get_with_optional_parameter::DEFAULT_VALUE;
		$sut = self::getFirst(Endpoint::fromClass(ParameterTest\get_with_optional_parameter::class)->methods['get']->parameters ?? [ ]);

		$isOptionalResult = $sut->isOptional;
		$defaultValueResult = $sut->defaultValue;

		$this->assertTrue($isOptionalResult);
		$this->assertSame($expectedDefaultValue, $defaultValueResult);
	}

	/**
	 * @template T
	 * @param iterable<T> $iter
	 * @return T
	 */
	private static function getFirst(iterable $iter): mixed {
		foreach ($iter as $item) {
			return $item;
		}
		throw new \Exception('Expected iterable to yield at least one item');
	}

}
