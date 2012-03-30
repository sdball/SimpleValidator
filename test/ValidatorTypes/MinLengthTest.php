<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorMinLengthTest extends Validator_TestCase {

  /**
   */
  public function testRuleMinLength() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate('1234', 'minlength=3')
    );

    $this->assertValid(
      $validator->singleValidate('123', 'minlength=3')
    );

    $this->assertInvalid(
      $validator->singleValidate('12', 'minlength=3')
    );
  }

}
