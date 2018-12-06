<?php
namespace UFileIndex\Restriction\Rules;
use UFileIndex\File;
use UFileIndex\Settings\Container;
/**
 * Правило для прооверки MIME типа файла
 * Class MimeType
 * @package UFileIndex\Restriction
 */
class MimeType implements Rule {
	/** @const string - сообщение о непройденной проверке  */
	const CHECK_FAIL_MESSAGE = 'MIME тип файла не указан в списке допустимых';

	/** @var array $allowedMimeTypeList - список допустимых MIME типов файлов */
	private $allowedMimeTypeList = [];

	/**
	 * @constructor.
	 *
	 * @param Container $settings - настройки
	 */
	public function __construct( Container $settings ) {
		$this->allowedMimeTypeList = $settings->getAllowedMimeTypeList();
	}

	/**
	 * @inheritdoc
	 */
	public function check( File $file ) : bool {
		return empty($this->allowedMimeTypeList) ? : in_array($file->getMimeType(), $this->allowedMimeTypeList);
	}

	/** @inheritdoc */
	public function getCheckFailMessage() : string {
		return self::CHECK_FAIL_MESSAGE;
	}
}