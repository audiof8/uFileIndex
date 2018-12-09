<?php
namespace UFileIndex;
use UFileIndex\File\Getter;
use UFileIndex\File\Index;
use UFileIndex\Restriction\Check;
use UFileIndex\Settings\Container;
use UFileIndex\Settings\Init;

/**
 * Фасад для библиотеки
 * Class Facade
 * @package UFileIndex
 */
class Facade {
	/**
	 * Возвращает индекс вхождения строки
	 * @param string $needle - искомая строка
	 * @param string $filePath - путь к файлу
	 * @param string $settingsFilePath - путь к файлу настроек
	 * @param array $remoteFileAuthCredentials - список параметров для аутентификации см Getter::getRemote
	 *
	 * @return array
	 * @throws \ErrorException
	 */
	public static function getIndexList(
		string $needle,
		string $filePath,
		string $settingsFilePath = '',
		array $remoteFileAuthCredentials = []) : array {

		if ($needle === '') {
			return [];
		}
		set_time_limit(0);

		$settingsFilePath = $settingsFilePath ? : Init::DEFAULT_SETTINGS;
		$settings = new Init( new File($settingsFilePath) );
		$settingsContainer = $settings->loadSettings();

		if (!empty($remoteFileAuthCredentials)) {
			$tempFilePath = self::getTempFilePath($filePath);
			Getter::getRemote($filePath, $tempFilePath, $remoteFileAuthCredentials);
			$file = new File($tempFilePath);
		} else {
			$file = new File($filePath);
		}

		Check::rules($settingsContainer, $file);
		$index = new Index($file, $settingsContainer);
		$indexList = $index->getIndex($needle);

		if (!empty($remoteFileAuthCredentials)) {
			unlink($file->getRealPath());
		}

		return $indexList;
	}

	/**
	 * Возвращает путь до временного файла
	 * @param string $remoteFilePath - путь до файла на удаленном хосте
	 *
	 * @return string
	 */
	private static function getTempFilePath(string $remoteFilePath) : string {
		$fileInfo = new \SplFileInfo($remoteFilePath);
		return __DIR__ . DIRECTORY_SEPARATOR . $fileInfo->getBasename();
	}
}