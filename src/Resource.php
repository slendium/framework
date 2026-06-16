<?php

namespace Slendium\Framework;

use Slendium\Http\Response;

/**
 * An HTTP resource definition.
 *
 * A resource defines the capabilities of an HTTP endpoint. Its actual behavior and available
 * {@see Resource\Method}s may vary per incoming request.
 *
 * A module's resources live in the `Resources` namespace relative to the {@see Module} implementation.
 * For example, the module defined at `Slendium\SliModules\Auth\AuthModule` would have its resources
 * in the namespace `Slendium\SliModules\Auth\Resources\*`. The class name of a resource becomes part
 * of the {@see Application\ActionId}. A resource with a FQN of `Slendium\SliModules\Auth\Resources\users\roles`
 * would be accessible through the token `"<configured-module-name>/users/roles"`.
 *
 * To define an HTTP-method for the resource, simply add a class-method with the same name as the
 * HTTP-method. The class-method must be `public` and must declare a return type that is covariant
 * with `Method|Response|null`. To ensure the method's possible responses can be properly documented
 * (eg. for OpenAPI), declare the specific class that will be returned (eg. `?CrudPostMethod`).
 *
 * ## Examples
 *
 * For trivial cases the {@see Resource\Method\ResponseModifier}s can be applied directly to the
 * resource's class-method in combination with a {@see Response} return type hint.
 *
 * ```php
 * class my_resource implements Resource {
 *
 * 	#[Content\Json]
 * 	public function post(): Response {
 * 		return ResponseFactory::reflect(__METHOD__)
 * 			|> Content\Json::applyBody(?, [ 'data' => '...' ]);
 * 	}
 *
 * }
 * ```
 *
 * Resources with complex logic and multiple potential response variants should provide custom implementations
 * of {@see Resource\Method}.
 *
 * ```php
 * class my_resource implements Resource {
 *
 * 	public function get(#[NonEmpty] string $id): MyResourceGetMethod {
 * 		return new MyResourceGetMethod($this->service, $id);
 * 	}
 *
 * 	public function post(#[FromBody] array $values): ?MyResourcePostMethod {
 * 		return $this->service->canInsert()
 * 			? new MyResourcePostMethod($this->service, $values)
 * 			: null;
 * 	}
 *
 * }
 * ```
 *
 * It is also possible to mix inline responses with reusable method implementations. The returned
 * responses below will all be merged into a single list. Note that the inline response is only
 * considered if the return type directly mentions the `Response` interface.
 *
 * ```php
 * class my_resource implements Resource {
 *
 * 	#[Content\Text]
 * 	public function get(): MyResourceGetMethod|Response|null {
 * 		return match($this->someCase()) {
 * 			1 => ResponseFactory::reflect(__METHOD__)->setBody('Ok'),
 * 			2 => new MyResourceGetMethod(),
 * 			3 => new MyResourceAlternativeGetMethod(),
 * 			default => null
 * 		};
 * 	}
 *
 * }
 * ```
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface Resource { }
