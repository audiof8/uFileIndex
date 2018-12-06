<?php
namespace UFileIndex\Settings;
/**
 * Class Container
 * @package UFileIndex\Settings
 */
class Container {
	private $allowedMimeTypeList = [];
	private $maxFileSize;
	private $maxPacketSize;

	/**
	 * @return array
	 */
	public function getAllowedMimeTypeList(): array {
		return $this->allowedMimeTypeList;
	}

	/**
	 * @return mixed
	 */
	public function getMaxFileSize() : int {
		return (int) $this->maxFileSize;
	}

	/**
	 * @return mixed
	 */
	public function getMaxPacketSize() : int {
		return (int) $this->maxPacketSize;
	}

	/**
	 * @param array $allowedMimeTypeList
	 *
	 * @return Container
	 */
	public function setAllowedMimeTypeList( array $allowedMimeTypeList ) : Container {
		$this->allowedMimeTypeList = $allowedMimeTypeList;
		return $this;
	}

	/**
	 * @param $maxFileSize
	 *
	 * @return Container
	 */
	public function setMaxFileSize(int $maxFileSize ) : Container {
		$this->maxFileSize = $maxFileSize;
		return $this;
	}

	/**
	 * @param mixed $maxPacketSize
	 *
	 * @return Container
	 */
	public function setMaxPacketSize(int $maxPacketSize ) : Container{
		$this->maxPacketSize = $maxPacketSize;
		return $this;
	}
}