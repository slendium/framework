<?php

namespace Slendium\FrameworkTests\Resource;

use PHPUnit\Framework\TestCase;
use Slendium\Http\Message\Headers;

use Slendium\Framework\Resource\MutableResponse;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class MutableResponseTest extends TestCase {

	public function test_setHeader_shouldReplaceExistingHeaders(): void {
		$sut = new MutableResponse;
		$sut->setHeader(':status', '200');
		$sut->setHeader('content-type', 'text/plain');
		$sut->setHeader('content-type', 'text/html');

		$result = Headers::getAll($sut, 'content-type')
			|> (fn($x) => \iterator_to_array($x, preserve_keys: false));

		$this->assertSame(1, \count($result));
		$this->assertSame('text/html', $result[0]->value);
	}

	public function test_addHeader_shouldAppendToExistingHeaders(): void {
		$sut = new MutableResponse;
		$sut->setHeader(':status', '200');
		$sut->addHeader('content-type', 'text/plain');
		$sut->addHeader('content-type', 'text/html');

		$result = Headers::getAll($sut, 'content-type')
			|> (fn($x) => \iterator_to_array($x, preserve_keys: false));

		$this->assertSame(2, \count($result));
		$this->assertSame('text/plain', $result[0]->value);
		$this->assertSame('text/html', $result[1]->value);
	}

	public function test_setBody_shouldConvertStringToIterable(): void {
		$sut = new MutableResponse;
		$expectedResult = 'a1310762-fb45-4ae8-9478-0da86266106d';

		$sut->setBody($expectedResult);

		$result = '';
		foreach ($sut->body as $part) {
			$result .= $part;
		}
		$this->assertSame($expectedResult, $result);
	}

	public function test_setterMethods_shouldReturnSameInstance(): void {
		$sut = new MutableResponse;

		$setHeaderResult = $sut->setHeader(':status', '200');
		$addHeaderResult = $sut->addHeader('content-type', 'text/css');
		$setBodyResult = $sut->setBody('a8d87845-6e06-4f9c-950b-033e8a7dee43');

		$this->assertSame($sut, $setHeaderResult);
		$this->assertSame($sut, $addHeaderResult);
		$this->assertSame($sut, $setBodyResult);
	}

}
