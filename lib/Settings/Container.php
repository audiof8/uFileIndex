<?php
namespace UFileIndex\Settings;
/**
 * Class Container
 * @package UFileIndex\Settings
 */
class Container {
	/** @var array список разрешенных mime типов */
	private $allowedMimeTypeList = [];
	/** @var int максимальный размер файла в байтах */
	private $maxFileSize;
	/** @var int максимальный размер пакета для чтения из файла */
	private $maxPacketSize;

	/**
	 * Возвращает список разрешенных mime типов
	 * @return array
	 */
	public function getAllowedMimeTypeList(): array {
		return $this->allowedMimeTypeList;
	}

	/**
	 * Возвращает максимальный размер файла в байтах
	 * @return mixed
	 */
	public function getMaxFileSize() : int {
		return (int) $this->maxFileSize;
	}

	/**
	 * Возвращает максимальный размер пакета для чтения из файла
	 * @return mixed
	 */
	public function getMaxPacketSize() : int {
		return (int) $this->maxPacketSize;
	}

	/**
	 * Устанавливает список разрешенных mime типов
	 * @param array $allowedMimeTypeList - список разрешенных mime типов
	 *
	 * @return Container
	 */
	public function setAllowedMimeTypeList( array $allowedMimeTypeList ) : Container {
		$this->allowedMimeTypeList = $allowedMimeTypeList;
		return $this;
	}

	/**
	 * Устанавливает максимальный размер файла в байтах
	 * @param int $maxFileSize - максимальный размер файла в байтах
	 *
	 * @return Container
	 */
	public function setMaxFileSize(int $maxFileSize ) : Container {
		$this->maxFileSize = $maxFileSize;
		return $this;
	}

	/**
	 * Устанавливает размер пакета для чтения из файла
	 * @param int $maxPacketSize - размер пакета для чтения из файла
	 *
	 * @return Container
	 */
	public function setMaxPacketSize(int $maxPacketSize ) : Container{
		$this->maxPacketSize = $maxPacketSize;
		return $this;
	}
}