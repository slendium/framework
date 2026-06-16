<?php

namespace Slendium\FrameworkTests\Resource\Endpoint;

use PHPUnit\Framework\TestCase;

use Slendium\Framework\Resource\Endpoint;
use Slendium\Framework\Resource\Endpoint\Method;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class MethodTest extends TestCase {

	public function test_parameters_shouldHaveExpectedCount(): void {
		$sut = Endpoint::fromClass(MethodTest\get_with_parameters::class)->methods['get'];

		$result = self::countIterable($sut->parameters ?? [ ]);

		$this->assertSame(2, $result);
	}

	public function test_parameters_shouldHaveExpectedCount_whenEvaluatingHeadMethod(): void {
		$sut = Endpoint::fromClass(MethodTest\get_with_parameters::class)->methods['head'];

		$result = self::countIterable($sut->parameters ?? [ ]);

		$this->assertSame(2, $result);
	}

	public function test_parameters_shouldHaveExpectedCount_whenEvaluatingImpliedOptionsMethod(): void {
		$sut = Endpoint::fromClass(MethodTest\get_with_parameters::class)->methods['options'];

		$result = self::countIterable($sut->parameters ?? [ ]);

		$this->assertSame(0, $result);
	}

	public function test_responses_shouldHaveExpectedCount_whenMethodAndModifiersImplied(): void {
		$sut = Endpoint::fromClass(MethodTest\get_returns_response_without_modifiers::class)->methods['get'];

		$result = self::countIterable($sut->responses ?? [ ]);

		$this->assertSame(1, $result);
	}

	public function test_responses_shouldHaveExpectedCount_whenMethodImpliedButModifiersExplicit(): void {
		$sut = Endpoint::fromClass(MethodTest\get_returns_response_with_modifiers::class)->methods['get'];

		$result = self::countIterable($sut->responses ?? [ ]);

		$this->assertSame(1, $result);
	}

	public function test_responses_shouldHaveExpectedCount_whenMethodExplicit(): void {
		$sut = Endpoint::fromClass(MethodTest\get_returns_method_with_two_responses::class)->methods['get'];

		$result = self::countIterable($sut->responses ?? [ ]);

		$this->assertSame(2, $result);
	}

	/** @param iterable<mixed> $iter */
	private static function countIterable(iterable $iter): int {
		$count = 0;
		foreach ($iter as $_) {
			$count += 1;
		}
		return $count;
	}

}
