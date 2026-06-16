<?php

namespace Slendium\Framework\Resource;

use Override;

use Slendium\Http\Field;
use Slendium\Http\Base\FieldSet;
use Slendium\Http\ReadOnlyField;

use Slendium\Framework\Builders\ResponseBuilder;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
class MutableResponse implements ResponseBuilder {

	#[Override]
	public FieldSet $headers;

	#[Override]
	public iterable $body = [ ];

	#[Override]
	public FieldSet $trailers;

	/** @since 1.0 */
	public function __construct() {
		$this->headers = new FieldSet;
		$this->trailers = new FieldSet;
	}

	#[Override]
	public function setHeader(string $name, string $value): self {
		$this->headers->replace(new ReadOnlyField($name, $value));
		return $this;
	}

	#[Override]
	public function addHeader(string $name, string $value): self {
		$this->headers->append(new ReadOnlyField($name, $value));
		return $this;
	}

	#[Override]
	public function setBody(iterable|string $body): self {
		$this->body = \is_string($body)
			? [ $body ]
			: $body;
		return $this;
	}

}
