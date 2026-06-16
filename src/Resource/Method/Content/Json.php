<?php

namespace Slendium\Framework\Resource\Method\Content;

use Attribute;

use Slendium\Framework\Builders\ResponseBuilder;
use Slendium\Framework\Resource\Method\Content;

/**
 * Applies the header `content-type: application/json` to the response.
 *
 * @since 1.0
 * @author C. Fahner
 * @copyright Slendium 2026
 */
#[Attribute(Attribute::TARGET_METHOD)]
class Json extends Content {

	/** @since 1.0 */
	const MEDIA_TYPE = 'application/json';

	private const JSON_FLAGS = \JSON_PRETTY_PRINT
		| \JSON_UNESCAPED_SLASHES
		| \JSON_UNESCAPED_UNICODE
		| \JSON_THROW_ON_ERROR;

	/**
	 * @since 1.0
	 * @param array<mixed> $data
	 */
	public static function applyBody(ResponseBuilder $builder, array $data): ResponseBuilder {
		return $builder->setBody(\json_encode($data, self::JSON_FLAGS));
	}

	/** @since 1.0 */
	public function __construct() {
		parent::__construct(self::MEDIA_TYPE);
	}

}
