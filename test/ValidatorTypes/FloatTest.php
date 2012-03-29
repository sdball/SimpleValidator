<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorFloatTest extends Validator_TestCase {

  /**
   */
  public function testRuleFloat() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate('1234.456', 'float')
    );

    $this->assertValid(
      $validator->singleValidate(1234, 'float')
    );

    $this->assertValid(
      $validator->singleValidate(1234.78, 'float')
    );
    
    $this->assertInvalid(
      $validator->singleValidate('abc', 'float')
    );
  }

}

