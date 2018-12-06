<?php
namespace Tests\UFileIndex;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Trait FileMock
 * @package Tests\UFileIndex
 */
trait FileMock {

	/**
	 * Возвращает макет файла для тестов
	 * @param TestCase $case - тест
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	public function getFileMock(TestCase $case) : \PHPUnit_Framework_MockObject_MockObject {
		$mockBuilder = new MockBuilder($case, 'UFileIndex\File');
		return $mockBuilder->disableOriginalConstructor()
			->getMock();
	}
}