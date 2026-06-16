<?php

namespace Slendium\Framework\Resource;

use Attribute;

use Slendium\Http\Response;

/**
 * Performs a specific operation on the associated {@see \Slendium\Framework\Resource}.
 *
 * Method implementations should be constructed in such a way that every response variant can be
 * automatically documented. This is done by making every possible response a separate method and
 * adding {@see ResponseModifier} attributes to it that describe the variant. To avoid duplication
 * of the response information in the attributes, you can use {@see Method\ResponseFactory::reflect()}
 * to create a new {@see \Slendium\Builders\ResponseBuilder} based on the attributes of the method it
 * is called from.
 *
 * ## Example
 *
 * An example to show a basic self-documenting, reusable method.
 *
 * ```php
 * class CrudPostMethod implements Method {
 *
 * 	public function __construct(
 * 		private readonly Service $service,
 * 		private readonly object|iterable $insertables,
 * 	) { }
 *
 * 	#[Override]
 * 	public function invoke(): Response {
 * 		return \is_iterable($this->insertables)
 * 			? $this->invokeBulk($this->insertables)
 * 			: $this->invokeSingle($this->insertables);
 * 	}
 *
 * 	#[Status\Accepted, Content\Json]
 * 	private function invokeBulk(iterable $inserts): Response {
 * 		foreach ($inserts as $insert) {
 * 			$this->insertOne(); // individual insertions may fail
 * 		}
 * 		return Method\ResponseFactory::reflect(__METHOD__)
 * 			|> Content\Json::applyBody(?, [ 'accepted' => true ]);
 * 	}
 *
 * 	private function invokeSingle(object $insert): Response {
 * 		$result = $this->service->insertOne($insert);
 * 		return match($result->type) {
 * 			Result::Inserted => $this->respondCreated($result->inserted),
 * 			Result::NotUnique => $this->respondConflict($insert),
 * 			default => $this->respondFailed()
 * 		};
 * 	}
 *
 * 	#[Status\Created, Content\Json]
 * 	private function respondCreated(object $created): Response {
 * 		return Method\ResponseFactory::reflect(__METHOD__)
 * 			|> Content\Json::applyBody(?, [ 'created' => $created ]);
 * 	}
 *
 * 	#[Status\Conflict, Content\Json]
 * 	private function respondConflict(): Response {
 * 		return Method\ResponseFactory::reflect(__METHOD__)
 * 			|> Content\Json::applyBody(?, [ 'error' => 'Not unique' ]);
 * 	}
 *
 * 	#[Status\BadRequest, Content\Json]
 * 	private function respondFailed(): Response {
 * 		return Method\ResponseFactory::reflect(__METHOD__)
 * 			|> Content\Json::applyBody(?, [ 'error' => 'Unknown error' ]);
 * 	}
 *
 * }
 * ```
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface Method {

	/** @since 1.0 */
	public function invoke(): Response;

}
