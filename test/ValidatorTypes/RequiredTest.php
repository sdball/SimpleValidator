<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorRequiredTest extends Validator_TestCase {

  /**
   */
  public function testRuleRequired() {
    $validator = new SimpleValidator();

    $this->assertInvalid(
      $validator->singleValidate('', 'required')
    );

    $this->assertValid(
      $validator->singleValidate('Value here', 'required')
    );
  }

}

