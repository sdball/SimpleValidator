<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorCityTest extends Validator_TestCase {

  /**
   * @dataProvider data_GoodCities
   */
  public function testRuleValidCity($city) {
    $validator = new SimpleValidator();
    
    $this->assertValid(
      $validator->singleValidate($city, 'city')
    );
  }
  
  /**
   * @dataProvider data_BadCities
   */
  public function testRuleInvalidCity($city) {
    $validator = new SimpleValidator();
    
    $this->assertInvalid(
      $validator->singleValidate($city, 'city')
    );
  }
  
  public function data_GoodCities() {
    return array(
        array('Gastonia'),
        array('Charlotte'),
        array('St. Michelle'),
        array('West-Side')
    );
  }

  public function data_BadCities() {
    return array(
        array('Gast0nia'),
        array('Char\tte'),
        array("St.\tMichelle"),
        array('West 99th')
    );
  }

}

