<?php

namespace Slendium\FrameworkTests\Resource;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Slendium\Http\Base\FieldSet;
use Slendium\Http\Message;
use Slendium\Http\Message\Headers;
use Slendium\Http\ReadOnlyField;

use Slendium\Framework\Resource\ArgumentException;
use Slendium\Framework\Resource\Handler;
use Slendium\Framework\Resource\ResourceException;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class HandlerTest extends TestCase {

	public function test_handle_shouldRejectRequestWithoutMethod(): void {
		$request = new MockedRequest;
		$resource = new HandlerTest\weather;

		$result = Headers::getFirst(Handler::handle($request, $resource), ':status')?->value;

		$this->assertIsNumeric($result);
		$this->assertGreaterThanOrEqual(400, $result);
		$this->assertLessThan(500, $result);
	}

	public function test_handle_shouldReturn405_whenUndeclaredMethodGiven(): void {
		$request = MockedRequest::post();
		$resource = new HandlerTest\weather;

		$result = Headers::getFirst(Handler::handle($request, $resource), ':status')?->value;

		$this->assertSame('405', $result);
	}

	public function test_handle_shouldAutoIncludeOptions_whenNotDeclaredExplicitly(): void {
		$request = MockedRequest::options();
		$resource = new HandlerTest\weather;

		$result = (Headers::getFirst(Handler::handle($request, $resource), 'allow')->value ?? '')
			|> (fn($x) => \explode(',', $x))
			|> (fn($x) => \array_map(fn($v) => \trim($v), $x));
		\natsort($result);

		$this->assertSame([ 'GET', 'HEAD', 'OPTIONS' ], \array_values($result));
	}

	public function test_handle_shouldExecuteGet_whenNoExplicitHeadMethodExists(): void {
		$request = MockedRequest::head();
		$resource = new HandlerTest\weather;

		$result = Handler::handle($request, $resource)
			|> (fn($x) => Headers::getFirst($x, 'x-method')?->value);

		$this->assertSame('get', $result);
	}

	public function test_handle_shouldExecuteHead_whenExplicitlyDeclared(): void {
		$request = MockedRequest::head();
		$resource = new HandlerTest\explicit_head;

		$result = Handler::handle($request, $resource)
			|> (fn($x) => Headers::getFirst($x, 'x-method')?->value);

		$this->assertSame('head', $result);
	}

	public function test_handle_shouldDropResponseBody_whenMethodIsHead(): void {
		$headRequest = MockedRequest::head();
		$resourceWithHead = new HandlerTest\explicit_head;
		$resourceWithoutHead = new HandlerTest\weather;

		$explicitHeadResult = self::stringifyBody(Handler::handle($headRequest, $resourceWithHead));
		$implicitHeadResult = self::stringifyBody(Handler::handle($headRequest, $resourceWithoutHead));

		$this->assertSame('', $explicitHeadResult);
		$this->assertSame('', $implicitHeadResult);
	}

	public function test_handle_shouldExecuteOptions_whenExplicitlyDeclared(): void {
		$request = MockedRequest::options();
		$resource = new HandlerTest\explicit_options;

		$response = Handler::handle($request, $resource);

		$this->assertNull(Headers::getFirst($response, 'allow'));
	}

	public function test_handle_shouldReturn400_whenMissingRequiredArgument(): void {
		$request = MockedRequest::get();
		$resource = new HandlerTest\required_argument;

		$result = Headers::getFirst(Handler::handle($request, $resource), ':status')?->value;

		$this->assertSame('400', $result);
	}

	public function test_handle_shouldProcessRequiredParameter_whenGiven(): void {
		$body = 'dd06572b-30d4-4714-81b1-87ffffb8300a';
		$request = MockedRequest::get([ new MockedPathField(query: [ 'reflected' => $body ]) ]);
		$resource = new HandlerTest\required_argument;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame($body, $result);
	}

	public function test_handle_shouldProcessOptionalParameter_whenGiven(): void {
		$body = '0d3346bd-1849-4508-a183-411c2983f263';
		$request = MockedRequest::get([ new MockedPathField(query: [ 'reflected' => $body ]) ]);
		$resource = new HandlerTest\optional_argument;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame($body, $result);
	}

	public function test_handle_shouldPrefillOptionalParameter_whenOmitted(): void {
		$request = MockedRequest::get();
		$resource = new HandlerTest\optional_argument;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame(HandlerTest\optional_argument::DEFAULT_REFLECTED_VALUE, $result);
	}

	public function test_handle_shouldThrow_whenParameterHasIntersectionType(): void {
		// Arrange
		$request = MockedRequest::get([ new MockedPathField(query: [ 'intersection' => 4 ]) ]);
		$resource = new HandlerTest\intersection_argument;

		// Assert
		$this->expectException(ResourceException::class);

		// Act
		Handler::handle($request, $resource);
	}

	public function test_handle_shouldAcceptFirstMatch_whenParameterHasUnionType(): void {
		$request = MockedRequest::get([ new MockedPathField(query: [ 'union' => 1.23 ]) ]);
		$resource = new HandlerTest\union_argument;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame('string', $result);
	}

	public function test_handle_shouldAcceptBodyArg(): void {
		$expectedResult = 'beac20e3-45e1-47cc-9941-9a960b235b0e';
		$request = MockedRequest::post(body: new MockedEncodedBody([ 'input' => $expectedResult ]));
		$resource = new HandlerTest\body_argument;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame($expectedResult, $result);
	}

	public function test_handle_shouldAcceptCookieArg(): void {
		$expectedResult = 'fdc53d81-d993-4956-aa90-df7c2423b462';
		$request = MockedRequest::get([ new MockedCookieField([ 'input' => $expectedResult ]) ]);
		$resource = new HandlerTest\cookie_argument;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame($expectedResult, $result);
	}

	public function test_handle_shouldAcceptHeaderArg(): void {
		$expectedImpliedNameResult = 'b1a39cc3-f36f-4058-84d0-8e87ee10c735';
		$expectedExplicitNameResult = '749d79a1-9783-425f-ba97-88a1b0a04430';
		$request = MockedRequest::get([
			new ReadOnlyField('x-implied-name', $expectedImpliedNameResult),
			new ReadOnlyField('x-explicit-name', $expectedExplicitNameResult)
		]);
		$resource = new HandlerTest\header_arguments;

		$response = Handler::handle($request, $resource);

		$this->assertSame($expectedImpliedNameResult, Headers::getFirst($response, 'x-implied-name')?->value);
		$this->assertSame($expectedExplicitNameResult, Headers::getFirst($response, 'x-explicit-name')?->value);
	}

	public function test_handle_shouldReturn400_whenMissingRequiredHeader(): void {
		$request = MockedRequest::get();
		$resource = new HandlerTest\header_arguments;

		$result = Headers::getFirst(Handler::handle($request, $resource), ':status')?->value;

		$this->assertSame('400', $result);
	}

	public function test_handle_shouldTakeAltNamesIntoAccount(): void {
		$expectedResult = '9605983d-4ad8-403f-9ffb-45bffdf8a0ae';
		$request = MockedRequest::get([ new MockedPathField(query: [ 'legacy' => $expectedResult ]) ]);
		$resource = new HandlerTest\query_argument_with_alt_names;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame($expectedResult, $result);
	}

	public function test_handle_shouldProcessExtractorsInOrder(): void {
		$expectedResult = '603cfdf3-5b35-4f15-b583-64342ba31c16';
		$request = MockedRequest::post([ new MockedPathField(query: [ 'input' => $expectedResult ]) ]);
		$resource = new HandlerTest\body_then_query_argument;

		$result = self::stringifyBody(Handler::handle($request, $resource));

		$this->assertSame($expectedResult, $result);
	}

	public function test_handle_shouldReturn400_whenArgumentDoesNotValidate(): void {
		$request = MockedRequest::get([ new MockedPathField(query: [ 'input' => '' ]) ]);
		$resource = new HandlerTest\non_empty_string_query_argument;

		$result = Headers::getFirst(Handler::handle($request, $resource), ':status')?->value;

		$this->assertSame('400', $result);
	}

	public function test_handle_shouldReturnExpectedResult_whenArgumentDoesValidate(): void {
		$expectedResult = 'a2647976-6c13-43fd-bab9-de5c55a98d7e';
		$request = MockedRequest::get([ new MockedPathField(query: [ 'input' => $expectedResult ]) ]);
		$resource = new HandlerTest\non_empty_string_query_argument;

		$result = Handler::handle($request, $resource);

		$this->assertSame('200', Headers::getFirst($result, ':status')?->value);
		$this->assertSame($expectedResult, self::stringifyBody($result));
	}

	private static function stringifyBody(Message $message): string {
		$out = '';
		foreach ($message->body as $chunk) {
			$out .= $chunk;
		}
		return $out;
	}

}
