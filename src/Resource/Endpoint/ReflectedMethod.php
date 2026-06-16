<?php

namespace Slendium\Framework\Resource\Endpoint;

use Exception;
use Override;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;

use Slendium\Http\Request;
use Slendium\Http\Response as HttpResponse;

use Slendium\Framework\Resource;
use Slendium\Framework\Resource\Argument;
use Slendium\Framework\Resource\ArgumentException;
use Slendium\Framework\Resource\Method as ResourceMethod;
use Slendium\Framework\Resource\Method\Content;
use Slendium\Framework\Resource\Method\ResponseFactory;
use Slendium\Framework\Resource\Method\Status;
use Slendium\Framework\Resource\RequestInfo;
use Slendium\Framework\Resource\ResourceException;

/**
 * @internal
 * @author C. Fahner
 * @copyright Slendium 2026
 */
final class ReflectedMethod implements Method {

	#[Override]
	public string $name {
		get => \strtoupper($this->reflector->getName());
	}

	#[Override]
	public iterable $parameters {
		get => $this->get_parameters();
	}

	#[Override]
	public iterable $responses {
		get => $this->get_responses();
	}

	public function __construct(private readonly ReflectionMethod $reflector) { }

	#[Override]
	public function invokeInternal(Resource $resource, Request $request): HttpResponse {
		$requestInfo = new RequestInfo($request);

		$arguments = [ ];
		$errors = [ ];
		foreach ($this->parameters as $parameter) {
			$argument = $parameter->extractValidatedArgument($requestInfo);
			if ($argument instanceof Argument) {
				$arguments[] = $argument->value;
			} else {
				$errors[] = $argument;
			}
		}

		if (\count($errors) > 0) {
			return self::respondBadRequest($errors);
		}

		$result = $this->reflector->invokeArgs($resource, $arguments);
		return match(true) {
			$result === null => self::respondMethodNotAllowed(),
			$result instanceof HttpResponse => $result,
			$result instanceof ResourceMethod => $result->invoke(),
			default => throw ResourceException::forUnsupportedMethodReturnType($this->name, \get_debug_type($result))
		};
	}

	/** @param array<ArgumentException> $errors */
	#[Content\Text, Status\BadRequest]
	private static function respondBadRequest(array $errors): HttpResponse {
		return ResponseFactory::reflect(__METHOD__)->setBody("Bad Request\n\n".self::stringifyErrors($errors));
	}

	#[Content\Text, Status\MethodNotAllowed]
	private static function respondMethodNotAllowed(): HttpResponse {
		return ResponseFactory::reflect(__METHOD__)->setBody('Method Not Allowed');
	}

	/** @param iterable<Exception> $errors */
	private static function stringifyErrors(iterable $errors): string {
		$out = '';
		foreach ($errors as $error) {
			$out .= "{$error->getMessage()}\n";
		}
		return $out;
	}

	/** @return iterable<Parameter> */
	private function get_parameters(): iterable {
		foreach ($this->reflector->getParameters() as $param) {
			yield new Parameter($param);
		}
	}

	/** @return iterable<Response> */
	private function get_responses(): iterable {
		$type = $this->reflector->getReturnType();
		return match(true) {
			$type instanceof ReflectionNamedType => $this->getResponsesForNamedType($type),
			$type instanceof ReflectionUnionType => $this->getResponsesForUnionType($type),
			$type === null => throw ResourceException::forMissingMethodReturnType($this->name),
			default => throw ResourceException::forUnsupportedMethodReturnType($this->name, $type)
		};
	}

	/** @return iterable<Response> */
	private function getResponsesForUnionType(ReflectionUnionType $union): iterable {
		foreach ($union->getTypes() as $type) {
			if ($type instanceof ReflectionIntersectionType) {
				throw ResourceException::forUnsupportedMethodReturnType($this->name, $type);
			}

			yield from $this->getResponsesForNamedType($type);
		}
	}

	/** @return iterable<Response> */
	private function getResponsesForNamedType(ReflectionNamedType $type): iterable {
		if ($type->getName() === HttpResponse::class) {
			yield new Response($this->reflector);
		} else if (\is_subclass_of($type->getName(), Resource\Method::class, allow_string: true)) {
			yield from Response::getAllFromClass($type->getName());
		} else {
			throw ResourceException::forUnsupportedMethodReturnType($this->name, $type->getName());
		}
	}

}
