<?php

namespace Slendium\FrameworkTests\Resource\Endpoint;

use PHPUnit\Framework\TestCase;

use Slendium\Framework\Resource\Endpoint;
use Slendium\Framework\Resource\Method\Status;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class ResponseTest extends TestCase {

	public function test_modifiers_shouldYieldImpliedResponse_whenNoMarkersDeclaredInline(): void {
		$sut = self::getFirst(Endpoint::fromClass(ResponseTest\get_inline_unmarked::class)->methods['get']->responses ?? [ ]);

		$result = \iterator_to_array($sut->modifiers, preserve_keys: false);

		$this->assertSame(1, \count($result));
		$this->assertInstanceOf(Status\Ok::class, $result[0]);
	}

	public function test_modifiers_shouldYieldResponseAndImplyStatusOk_whenDeclaredInline(): void {
		$sut = self::getFirst(Endpoint::fromClass(ResponseTest\get_inline_marked_implied_ok::class)->methods['get']->responses ?? [ ]);

		$result = \iterator_to_array($sut->modifiers, preserve_keys: false);

		$this->assertSame(2, \count($result));
		$this->assertNotNull(\array_find($result, fn($m) => $m instanceof Status\Ok));
	}

	public function test_modifiers_shouldYieldResponseWithoutStatusOk_whenDeclaredInline(): void {
		$sut = self::getFirst(Endpoint::fromClass(ResponseTest\get_inline_marked_not_found::class)->methods['get']->responses ?? [ ]);

		$result = \iterator_to_array($sut->modifiers, preserve_keys: false);

		$this->assertSame(2, \count($result));
		$this->assertNotNull(\array_find($result, fn($m) => $m instanceof Status\NotFound));
		$this->assertNull(\array_find($result, fn($m) => $m instanceof Status\Ok));
	}

	public function test_modifiers_shouldYieldResponse_whenDeclaredThroughMethodImplementation(): void {
		$sut = self::getFirst(Endpoint::fromClass(ResponseTest\get_with_method::class)->methods['get']->responses ?? [ ]);

		$result = \iterator_to_array($sut->modifiers, preserve_keys: false);

		$this->assertSame(2, \count($result));
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
		throw new \Exception('Expected the iterable to yield at least one item');
	}

}
