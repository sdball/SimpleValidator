<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorNumericTest extends Validator_TestCase {

  /**
   */
  public function testRuleNumeric() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate('1234', 'numeric')
    );
    
    $this->assertInvalid(
      $validator->singleValidate('abc', 'numeric')
    );
  }

}

