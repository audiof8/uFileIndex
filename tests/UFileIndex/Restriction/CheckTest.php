<?php
namespace Tests\UFileIndex\Restriction;
use Tests\UFileIndex\FileMock;
use UFileIndex\Restriction\Check;
use PHPUnit\Framework\TestCase;
use UFileIndex\Restriction\CheckFailException;
use UFileIndex\Restriction\Rules\MaxSize;
use UFileIndex\Restriction\Rules\MimeType;
use UFileIndex\Settings\Container;

class CheckTest extends TestCase {
	use FileMock;

	public function testRules() {
		/** @var $file \UFileIndex\File|\PHPUnit_Framework_MockObject_MockObject */
		$file = $this->getFileMock($this);

		$file->expects($this->any())->method('getMimeType')->willReturn('image/png');
		$file->expects($this->any())->method('getSize')->willReturn(2048);

		$testSettings = new Container();
		$testSettings->setMaxFileSize(1024);

		try {
			Check::rules($testSettings, $file);
			$this->fail('Не выброшено исключение о превышении размера проверяемого файла');
		} catch (CheckFailException $e) {
			$maxSizeRule = new MaxSize($testSettings);
			$this->assertEquals($maxSizeRule->getCheckFailMessage(), $e->getMessage());
		}

		$testSettings->setAllowedMimeTypeList( ['txt' => 'text/plain'] )
			->setMaxFileSize(4096);
		try {
			Check::rules($testSettings, $file);
			$this->fail('Не выброшено исключение о несоответствии mime типа файла');
		} catch (CheckFailException $e) {
			$mimeTypeRule = new MimeType($testSettings);
			$this->assertEquals($mimeTypeRule->getCheckFailMessage(), $e->getMessage());
		}
	}
}
