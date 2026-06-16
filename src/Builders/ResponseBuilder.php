<?php

namespace Slendium\Framework\Builders;

use Override;
use Stringable;

use Slendium\Http\Base\FieldSet;
use Slendium\Http\Field;
use Slendium\Http\Response;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
interface ResponseBuilder extends Response {

	#[Override]
	public FieldSet $headers { get; set; }

	#[Override]
	public iterable $body { get; set; }

	#[Override]
	public FieldSet $trailers { get; set; }

	/**
	 * @since 1.0
	 * @param lowercase-string&non-empty-string $name
	 * @return $this
	 */
	public function setHeader(string $name, string $value): self;

	/**
	 * @since 1.0
	 * @param lowercase-string&non-empty-string $name
	 * @return $this
	 */
	public function addHeader(string $name, string $value): self;

	/**
	 * @since 1.0
	 * @param iterable<Stringable|string>|string $body
	 * return $this
	 */
	public function setBody(iterable|string $body): self;

}
