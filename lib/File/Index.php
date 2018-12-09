<?php
namespace UFileIndex\File;
use UFileIndex\File;
use UFileIndex\Settings\Container;

/**
 * Класс для нахождения индекса строки в файле
 * Class Index
 * @package UFileIndex\File
 */
class Index {
	/** @const int размер пакета для чтения из фйала по умолчанию в байтах */
	const DEFAULT_PACKET_SIZE = 1024;
	/** @var \SplFileObject - файл */
	private $file;
	/** @var int размер пакета для чтения из фйала по умолчанию в байтах  */
	private $packetSize;

	/**
	 * @constructor.
	 *
	 * @param File $file - файл
	 * @param Container $settings - настройки
	 */
	public function __construct(File $file, Container $settings) {
		$this->file = new \SplFileObject($file->getRealPath(), 'rb');
		$this->packetSize = $settings->getMaxPacketSize() ? : self::DEFAULT_PACKET_SIZE;
	}

	/**
	 * Возращает индекс искомой строки в файле
	 * @param string $needle - искомая строка
	 *
	 * @return array [<номер строки в файле> => [<номер позиции>, ...]]
	 */
	public function getIndex(string $needle) : array {
		return $this->isMd5Hash($needle) ? $this->getHashIndex($needle) : $this->getStringIndex($needle);
	}

	/**
	 * Возвращает индекс искомой строки в файле
	 * @param string $needle - искомая строка
	 *
	 * @return array
	 */
	private function getStringIndex(string $needle) : array {
		$indexList = [];
		$lineNumber = 1;
		$blockBuffer = '';
		$byteOffset = 0;

		while (!$this->file->eof()) {
			$block = $blockBuffer . $this->file->fread( $this->packetSize );
			$linesList = $this->getLines($block);

			if (empty($linesList)) {
				$blockBuffer = $block;
				continue;
			} else {
				$blockBuffer = '';
			}

			foreach ($linesList as $key => $line) {
				$positionList = $this->getPositions($needle, $line);
				if ( !empty($positionList) ) {
					$indexList[$lineNumber + $key] = $positionList;
				}
				$byteOffset += strlen($line) + strlen(PHP_EOL);
			}

			$lineNumber += count($linesList);
			$this->file->fseek($byteOffset, SEEK_SET);
		}

		if ($blockBuffer) {
			$positionList = $this->getPositions($needle, $blockBuffer);
			if ( !empty($positionList) ) {
				$indexList[$lineNumber] = $positionList;
			}
		}

		return $indexList;
	}

	/**
	 * Возращает список строк из тестового блока
	 * @param string $block - текстовый блок
	 *
	 * @return array
	 */
	private function getLines(string $block) : array {
		$linesList = explode(PHP_EOL, $block);
		array_pop($linesList);

		return $linesList;
	}

	/**
	 * Возвращает индекс хэша строки
	 * @param string $hashNeedle - хэш строки для поиска
	 *
	 * @return array
	 */
	private function getHashIndex(string $hashNeedle) : array {
		$indexList = [];

		while (!$this->file->eof()) {
			$line = $this->file->fgets();

			if ( hash_equals($hashNeedle, md5($line) ) ) {
				$indexList[$this->file->key() + 1] = [ 0 ];
			}
		}

		return $indexList;
	}

	/**
	 * Определяет является ли переданная строка хэшем
	 * @param string $needle - искомая строка
	 *
	 * @return bool
	 */
	private function isMd5Hash(string $needle) : bool {
		return preg_match('/^[a-f0-9]{32}$/', $needle);
	}

	/**
	 * Возвращает список позиций вхождений строки
	 * @param string $needle - искомая строка
	 * @param string $haystack - строка
	 *
	 * @return array
	 */
	private function getPositions(string $needle, string $haystack) : array {
		$offset = 0;
		$positionList = [];

		while (true) {
			$position = mb_stripos($haystack, $needle, $offset);
			if ($position === false) {
				return $positionList;
			}

			$positionList[] = $position;
			$offset = $position + mb_strlen($needle);
		}

		return $positionList;
	}
}