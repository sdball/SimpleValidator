<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorMinTest extends Validator_TestCase {

  /**
   */
  public function testRuleMin() {
    $validator = new SimpleValidator();

    $this->assertInvalid(
      $validator->singleValidate(2, 'min=5')
    );

    $this->assertValid(
      $validator->singleValidate('10', 'min=10')
    );

    $this->assertValid(
      $validator->singleValidate(10, 'min=10')
    );

    $this->assertValid(
      $validator->singleValidate(15, 'min=10')
    );
  }

}

