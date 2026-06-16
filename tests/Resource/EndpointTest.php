<?php

namespace Slendium\FrameworkTests\Resource;

use PHPUnit\Framework\TestCase;

use Slendium\Framework\Resource\Endpoint;
use Slendium\Framework\Resource\ResourceException;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class EndpointTest extends TestCase {

	public function test_methods_offsetGet_shouldThrow_whenNoGetMethodWasDeclared(): void {
		// Arrange
		$sut = Endpoint::fromClass(EndpointTest\no_methods::class)->methods;

		// Assert
		$this->expectException(ResourceException::class);

		// Act
		$sut['get']; // @phpstan-ignore expr.resultUnused (deliberate for testing)
	}

	public function test_methods_offsetGet_shouldThrow_whenGetWasDeclaredPrivate(): void {
		// Arrange
		$sut = Endpoint::fromClass(EndpointTest\private_get::class)->methods;

		// Assert
		$this->expectException(ResourceException::class);

		// Act
		$sut['get']; // @phpstan-ignore expr.resultUnused (deliberate for testing)
	}

	public function test_methods_offsetExists_shouldReturnFalse_whenMethodWasDeclaredPrivate(): void {
		$sut = Endpoint::fromClass(EndpointTest\public_get_private_post::class)->methods;

		$result = isset($sut['post']);

		$this->assertFalse($result);
	}

	public function test_methods_getIterator_shouldThrow_whenNoGetMethodWasDeclared(): void {
		// Arrange
		$sut = Endpoint::fromClass(EndpointTest\no_methods::class)->methods;

		// Assert
		$this->expectException(ResourceException::class);

		// Act
		foreach ($sut as $method) { }
	}

	public function test_methods_offsetGet_shouldReturnMethod_whenOptionsNotExplicitlyDeclared(): void {
		$sut = Endpoint::fromClass(EndpointTest\get_only::class)->methods;

		$result = $sut['options'];

		$this->assertNotNull($result);
	}

	public function test_methods_getIterator_shouldContainImpliedMethods(): void {
		$sut = Endpoint::fromClass(EndpointTest\get_only::class)->methods;

		$options = null;
		$head = null;
		foreach ($sut as $method) {
			if ($method->name === 'OPTIONS') {
				$options = $method;
			} else if ($method->name === 'HEAD') {
				$head = $method;
			}
		}

		$this->assertNotNull($options);
		$this->assertNotNull($head);
	}

	public function test_methods_getIterator_shouldExcludePrivateMethods(): void {
		$sut = Endpoint::fromClass(EndpointTest\public_get_private_post::class)->methods;

		$result = [ ];
		foreach ($sut as $method) {
			$result[$method->name] = true;
		}

		$this->assertTrue(isset($result['OPTIONS'], $result['HEAD'], $result['GET']));
	}

	public function test_methods_offsetGet_shouldReturnMethod_whenHeadNotExplicitlyDeclared(): void {
		$sut = Endpoint::fromClass(EndpointTest\get_only::class)->methods;

		$result = $sut['head'];

		$this->assertNotNull($result);
	}

	public function test_methods_count_shouldReturnExpectedResult(): void {
		$sut = Endpoint::fromClass(EndpointTest\get_only::class)->methods;

		$result = \count($sut);

		$this->assertSame(3, $result); // GET + implied OPTIONS + implied HEAD
	}

	public function test_methods_offsetExists_shouldReturnTrue_whenDeclaredMethodExists(): void {
		$sut = Endpoint::fromClass(EndpointTest\get_only::class)->methods;

		$result = isset($sut['get']);

		$this->assertTrue($result);
	}

	public function test_methods_offsetExists_shouldReturnTrue_whenImpliedMethodRequested(): void {
		$sut = Endpoint::fromClass(EndpointTest\get_only::class)->methods;

		$result = isset($sut['head']) && isset($sut['options']);

		$this->assertTrue($result);
	}

	public function test_methods_shouldHaveExpectedProperties_whenOptionsAndHeadExplicitlyDeclared(): void {
		// Arrange
		$sut = Endpoint::fromClass(EndpointTest\explicit_head_and_options::class)->methods;

		// Assert
		$this->assertNotNull($sut['options']);
		$this->assertSame(1, \count(\iterator_to_array($sut['options']->parameters)));
		$this->assertNotNull($sut['head']);
		$this->assertSame(1, \count(\iterator_to_array($sut['head']->parameters)));
		$this->assertNotNull($sut['get']);
		$this->assertSame(3, \count($sut));
	}

}
