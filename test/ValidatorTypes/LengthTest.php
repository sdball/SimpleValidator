<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorLengthTest extends Validator_TestCase {

  /**
   */
  public function testRuleLength() {
    $validator = new SimpleValidator();

    $this->assertInvalid(
      $validator->singleValidate('123', 'length=9')
    );

    $this->assertValid(
      $validator->singleValidate('123456789', 'length=9')
    );

    $this->assertInvalid(
      $validator->singleValidate('12345678', 'length=9')
    );
  }

}
