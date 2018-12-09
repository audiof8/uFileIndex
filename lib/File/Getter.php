<?php
namespace UFileIndex\File;
use UFileIndex\File;

/**
 * Класс для загрузки файла с удаленного хоста
 * Class Getter
 * @package UFileIndex\File
 */
class Getter {
	/**
	 * Загружает файл с удаленного хоста
	 * @param string $filePath - путь до загружаемого файла на удаленном хосте
	 * @param string $localFilePath - путь куда файл будет сохранен
	 * @param array $connectCredentials - параметры для подключения
	 * 'host' - адрес хоста ip или домен
	 * 'port' - порт
	 * 'user' - логин пользователя
	 * 'password' - пароль
	 * 'private_key' - путь до файла приватного ключа
	 * 'public_key' - путь до файла публичного ключа
	 * 'passphrase' - пароль, если приватный ключ им защищен
	 *
	 * @return File
	 * @throws \ErrorException
	 */
	public static function getRemote(string $filePath, string $localFilePath, array $connectCredentials) : File {
		if (!function_exists('ssh2_connect')) {
			throw new \ErrorException('Необходимо расширение https://pecl.php.net/package/ssh2');
		}

		self::checkCredentials($connectCredentials);

		$port = $connectCredentials['post'] ?? null;
		$session = ssh2_connect($connectCredentials['host'], $port);

		if (!$session) {
			throw new \ErrorException('Не удалось создать подключение к удаленному хосту');
		}

		if (!empty($connectCredentials['public_key']) && !empty($connectCredentials['private_key'])) {
			$passphrase = $connectCredentials['passphrase'] ?? null;
			$auth = ssh2_auth_pubkey_file(
				$session,
				$connectCredentials['user'],
				$connectCredentials['public_key'],
				$connectCredentials['private_key'],
				$passphrase
			);
		} else {
			$auth = ssh2_auth_password($session, $connectCredentials['user'], $connectCredentials['password']);
		}

		if (!$auth) {
			ssh2_disconnect($session);
			throw new \ErrorException('Не удалось авторизоваться');
		}

		if (!ssh2_scp_recv($session, $filePath, $localFilePath)) {
			ssh2_disconnect($session);
			throw new \ErrorException('Не удалось загрузить файл ' . $filePath);
		}

		ssh2_disconnect($session);
		return new File($localFilePath);
	}

	/**
	 * Проверяет параметры для поключения к удаленному хосту
	 * @param array $connectCredentials - параметры для подключения
	 * 'host' - адрес хоста ip или домен
	 * 'port' - порт
	 * 'user' - логин пользователя
	 * 'password' - пароль
	 * 'private_key' - путь до файла приватного ключа
	 * 'public_key' - путь до файла публичного ключа
	 * 'passphrase' - пароль, если приватный ключ им защищен
	 *
	 * @throws \ErrorException
	 */
	private static function checkCredentials(array $connectCredentials) {
		if (empty($connectCredentials['host'])) {
			$checkFailList[] = 'Отсутсвует необходимый параметр host';
		}
		if (empty($connectCredentials['user'])) {
			$checkFailList[] = 'Отсутсвует необходимый user';
		}
		if (empty($connectCredentials['password']) && empty($connectCredentials['private_key']) && empty($connectCredentials['public_key'])) {
			$checkFailList[] = 'Для авторизации необходим параметр password или ключи private_key и public_key';
		}

		if (!empty($checkFailList)) {
			throw new \ErrorException(implode(PHP_EOL, $checkFailList));
		}
	}
}