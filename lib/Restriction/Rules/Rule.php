<?php
namespace UFileIndex\Restriction\Rules;
use UFileIndex\File;

/**
 * Интерфейс правила для проверки файла
 * Interface Rule
 * @package UFileIndex\Restriction\Rules
 */
interface Rule {

	/**
	 * Проверяет соответсвие свойства файла правилу
	 * @param File $file
	 *
	 * @return bool
	 */
	public function check(File $file) : bool;

	/**
	 * Возвращает сообщение о непройденной проверки
	 *
	 * @return string
	 */
	public function getCheckFailMessage() : string;
}