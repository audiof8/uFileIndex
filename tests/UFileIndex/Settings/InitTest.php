<?php
namespace Tests\UFileIndex\Restriction;
use Tests\UFileIndex\FileMock;
use UFileIndex\File;
use UFileIndex\Settings\Container;
use UFileIndex\Settings\Init;
use PHPUnit\Framework\TestCase;

/**
 * Тестирует класс инициализации настроек
 * Class InitTest
 * @package Tests\UFileIndex\Restriction
 */
class InitTest extends TestCase {
	use FileMock;

	/** @var string $testFilePath - путь до файла для хранения тестовых настроек */
	private $testFilePath;
	/** @var array $mimeTypeList - список разрешенных mime типов файлов */
	private $mimeTypeList = [];

	/** @inheritdoc */
	public function setUp() {
		$this->testFilePath = __DIR__ . '/test.json';
		$this->mimeTypeList = [
			'txt' => 'text/plain',
			'csv' => 'text/x-comma-separated-values',
			'csv_' => 'text/csv',
			'html' => 'text/html'
		];
		$settings = [
			'max_file_size' => 1024,
			'allowed_mime_type' => $this->mimeTypeList
		];
		file_put_contents($this->testFilePath, json_encode($settings));

		$this->assertFileExists($this->testFilePath);
	}

	/**
	 * Тестирует конструктор тестируемого класса
	 */
	public function test__construct() {
		/** @var $settingsFile File|\PHPUnit_Framework_MockObject_MockObject */
		$settingsFile = $this->getFileMock($this);
		$settingsFile->expects($this->at(0))->method('getMimeType')->willReturn('text/plain');
		$settingsFile->expects($this->at(1))->method('getMimeType')->willReturn(Init::MIME_TYPE['json']);

		try {
			$initSettings = new Init($settingsFile);
			$this->fail('Не выброшено исключение, что mime ти файла настроек не json');
		} catch (\ErrorException $e) {
			$this->assertEquals(Init::MIME_TYPE_CHECK_FAIL, $e->getMessage());
		}

		try {
			$initSettings = new Init($settingsFile);
		} catch (\ErrorException $e) {
			$this->fail('Выброшено исключение при верном mime типе файла');
		}
	}

	/**
	 * Тестирует метод загрузки настроек
	 */
	public function testLoadSettings() {
		try {
			$settingsFile = new File( $this->testFilePath );
			$initSettings = new Init( $settingsFile );
			$settingsContainer = $initSettings->loadSettings();

			$this->assertInstanceOf(Container::class, $settingsContainer);
			$this->assertEquals(1024, $settingsContainer->getMaxFileSize());
			$this->assertEquals(0, $settingsContainer->getMaxPacketSize());
			$this->assertEquals(json_encode($this->mimeTypeList), json_encode($settingsContainer->getAllowedMimeTypeList()));
		} catch (\ErrorException $e) {
			$this->fail('Выброшено исключение ' . $e->getMessage());
		}
	}

	/** @inheritdoc */
	public function tearDown() {
		if (file_exists( $this->testFilePath )) {
			unlink($this->testFilePath);
		}
	}
}
