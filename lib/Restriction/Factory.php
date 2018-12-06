<?php
namespace UFileIndex\Restriction;
use UFileIndex\Settings\Container;
use UFileIndex\Restriction\Rules\MaxSize;
use UFileIndex\Restriction\Rules\MimeType;
use UFileIndex\Restriction\Rules\Rule;
/**
 * Фабрика правил для проверки файла
 * Class Factory
 * @package UFileIndex\Restriction
 */
class Factory {
	/**
	 * Создает список правил для проверки файла
	 * @param Container $settings - настройки
	 *
	 * @return Rule[]
	 */
	public static function create(Container $settings) : array {
		$rulesList = [];

		if ($settings->getMaxFileSize() > 0) {
			$rulesList[] = new MaxSize($settings);
		}

		if (!empty( $settings->getAllowedMimeTypeList() )) {
			$rulesList[] = new MimeType($settings);
		}

		return $rulesList;
	}
}