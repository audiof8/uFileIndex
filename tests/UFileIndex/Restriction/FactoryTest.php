<?php
namespace Tests\UFileIndex\Restriction;
use UFileIndex\Restriction\Factory;
use PHPUnit\Framework\TestCase;
use UFileIndex\Restriction\Rules\Rule;
use UFileIndex\Settings\Container;

/**
 * Тестирует фабрику правил для проверки правил
 * Class FactoryTest
 * @package Tests\UFileIndex\Restriction
 */
class FactoryTest extends TestCase {
	public function testCreate() {
		$testSettings = new Container();
		$testSettings->setMaxFileSize(1048576)
			->setAllowedMimeTypeList( ['txt' => 'text/plain'] );

		$restrictionRulesList = Factory::create($testSettings);

		$this->assertEquals(2, count($restrictionRulesList));

		foreach ($restrictionRulesList as $rule) {
			$this->assertInstanceOf(Rule::class, $rule);
		}

		$testSettings->setMaxFileSize(0)
			->setAllowedMimeTypeList( [] );
		$this->assertEmpty( Factory::create($testSettings) );
	}
}
