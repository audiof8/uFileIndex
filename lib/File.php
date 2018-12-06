<?php
namespace UFileIndex;
use UFileIndex\Helpers\MimeType;

/**
 * Класс файла
 * Class File
 * @package UFileIndex
 */
class File {
	/** @var string сообщение об ошибке файл не найлен или не доступен для чтения */
	const FILE_NOT_FOUND = 'Ошибка. Файл %s не найден или не доступен для чтения.';
	/** @var \SplFileInfo $info - информация о файле */
	private $info;

	/**
	 * @constructor.
	 * @param string $filePath - абсолютный путь до файла
	 *
	 * @throws \ErrorException
	 */
	public function __construct(string $filePath) {
		$this->info = new \SplFileInfo( self::convertPathSlashes($filePath) );
		if (!$this->info->isFile() || !$this->info->isReadable()) {
			throw new \ErrorException( sprintf(self::FILE_NOT_FOUND, $filePath) );
		}
	}

	/**
	 * Возвращает MIME тип файла
	 *
	 * @return string
	 */
	public function getMimeType() : string {
		if (class_exists('finfo')) {
			$mimeType = new \finfo( FILEINFO_MIME_TYPE );

			return $mimeType->file( $this->getRealPath() );
		}

		//Ненадежный спопсоб
		return MimeType::getByExtension( $this->info->getExtension() );
	}

	/**
	 * Возвращает размер файла в байтах
	 *
	 * @return int
	 */
	public function getSize() : int {
		return $this->info->getSize();
	}

	/**
	 * Возвращает абсолютный путь до файла
	 *
	 * @return string
	 */
	public function getRealPath() : string {
		return $this->info->getRealPath() ? : '';
	}

	/**
	 * Преобразует слэши в пути файла на установленные в константе DIRECTORY_SEPARATOR
	 * @param string $filePath - путь до файла
	 *
	 * @return string
	 */
	public static function convertPathSlashes(string $filePath) : string {
		return str_replace(['/', '\\'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $filePath);
	}
}