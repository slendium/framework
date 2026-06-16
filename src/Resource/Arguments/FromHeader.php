<?php

namespace Slendium\Framework\Resource\Arguments;

use Attribute;
use Override;

use Slendium\Http\Message\Headers;

use Slendium\Framework\Resource\Argument;
use Slendium\Framework\Resource\ArgumentExtractor;
use Slendium\Framework\Resource\RequestInfo;

/**
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class FromHeader implements ArgumentExtractor {

	/** @since 1.0 */
	public function __construct(

		/** @var lowercase-string&non-empty-string */
		private ?string $name = null,

	) { }

	#[Override]
	public function extract(RequestInfo $info, string $name): ?Argument {
		$header = $this->name === null
			? Headers::getFirst($info->request, self::convertParameterNameToFieldName($name))
			: Headers::getFirst($info->request, $this->name);

		return $header === null
			? null
			: new Argument($header);
	}

	/**
	 * @param non-empty-string $name
	 * @return lowercase-string&non-empty-string
	 */
	private static function convertParameterNameToFieldName(string $name): string {
		return $name
			|> (fn($x) => \str_replace('_', '-', $name))
			|> \strtolower(...);
	}

}
