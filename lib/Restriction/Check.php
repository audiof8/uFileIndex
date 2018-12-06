<?php
namespace UFileIndex\Restriction;
use UFileIndex\File;
use UFileIndex\Settings\Container;
use UFileIndex\Restriction\Rules\Rule;
/**
 * Класс проверки файла
 * Class Check
 * @package UFileIndex\Restriction
 */
class Check {
	/**
	 * Проверяет файл на соответсвие установленным правилам/ограничениям
	 * @param Container $settings - настройки
	 * @param File $file - файл
	 *
	 * @return bool
	 * @throws CheckFailException
	 */
	public static function rules(Container $settings, File $file) : bool {
		$rulesList = Factory::create($settings);

		/** @var Rule $rule */
		foreach ($rulesList as $rule) {
			if (!$rule->check($file)) {
				throw new CheckFailException( $rule->getCheckFailMessage() );
			}
		}

		return true;
	}
}