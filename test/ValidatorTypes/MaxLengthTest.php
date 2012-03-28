<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorMaxLengthTest extends Validator_TestCase {

  /**
   */
  public function testRuleMaxLength() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate('123', 'maxlength=9')
    );

    $this->assertValid(
      $validator->singleValidate('123456789', 'maxlength=9')
    );

    $this->assertInvalid(
      $validator->singleValidate('1234567890', 'maxlength=9')
    );
  }

}
