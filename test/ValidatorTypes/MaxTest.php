<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorMaxTest extends Validator_TestCase {

  /**
   */
  public function testRuleMax() {
    $validator = new SimpleValidator();

    $this->assertInvalid(
      $validator->singleValidate(100, 'max=99')
    );

    $this->assertInvalid(
      $validator->singleValidate("100", 'max=99')
    );

    $this->assertValid(
      $validator->singleValidate('10', 'max=10')
    );

    $this->assertValid(
      $validator->singleValidate(10, 'max=10')
    );

    $this->assertValid(
      $validator->singleValidate(5, 'max=10')
    );
  }

}

