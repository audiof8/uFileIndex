<?php
namespace UFileIndex\Settings;
use UFileIndex\File;

/**
 * Инициализирует настройки библиотеки
 * Class Init
 * @package UFileIndex\Settings
 */
class Init {
	/** @var array MIME тип файла настроек */
	const MIME_TYPE = ['json' => 'application/json'];
	/** @var string путь до файла с начтройками по умолчанию */
	const DEFAULT_SETTINGS = __DIR__ . '/default.json';
	/** @var string сообщение об ошибке проверки типа файла настроек */
	const MIME_TYPE_CHECK_FAIL = 'Файл настроек должен быть в формате json';

	/** @var File $settingsFile - файл настроек */
	private $settingsFile;

	/**
	 * @constructor.
	 *
	 * @param File $settingsFile - файл настроек
	 *
	 * @throws \ErrorException
	 */
	public function __construct(File $settingsFile) {
		$this->settingsFile = $settingsFile;
		$this->checkSettingsFileType();
	}

	/**
	 * Проверяет тип файла настроек
	 *
	 * @throws \ErrorException
	 */
	private function checkSettingsFileType() {
		if ( !in_array($this->settingsFile->getMimeType(), self::MIME_TYPE) ) {
			throw new \ErrorException(self::MIME_TYPE_CHECK_FAIL);
		}
	}

	/**
	 * Загружает настройки
	 *
	 * @return Container
	 */
	public function loadSettings() : Container {
		$settings = json_decode(file_get_contents( $this->settingsFile->getRealPath() ), true);
		$settingsContainer = new Container();
		return $settingsContainer
			->setMaxFileSize( (int) ($settings['max_file_size'] ?? 0) )
			->setAllowedMimeTypeList($settings['allowed_mime_type'] ?? [] )
			->setMaxPacketSize( (int) ($settings['max_packet_size'] ?? 0) );
	}
}