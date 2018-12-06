<?php
namespace UFileIndex\Restriction\Rules;
use UFileIndex\File;
use UFileIndex\Settings\Container;

/**
 * Правило для проверки размера файла
 * Class MaxSize
 * @package UFileIndex\Restriction\Rules
 */
class MaxSize implements Rule {
	/** @const string - сообщение о непройденной проверке  */
	const CHECK_FAIL_MESSAGE = 'Размер файла превышает установленное ограничение %d байт';

	/** @var int $maxFileSize - максимально допустимый размер файла */
	private $maxFileSize;

	/**
	 * @constructor.
	 *
	 * @param Container $settings - настройки
	 */
	public function __construct(Container $settings) {
		$this->maxFileSize = $settings->getMaxFileSize();
	}

	/**
	 * @inheritdoc
	 */
	public function check( File $file ) : bool {
		return !$this->maxFileSize ? : $this->maxFileSize >= $file->getSize();
	}

	/** @inheritdoc */
	public function getCheckFailMessage(): string {
		return sprintf(self::CHECK_FAIL_MESSAGE, $this->maxFileSize);
	}
}