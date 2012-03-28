<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorZipTest extends Validator_TestCase {

  public function testRuleZip() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate('50312', 'zip')
    );

    $this->assertValid(
      $validator->singleValidate('98226-1234', 'zip')
    );

    $this->assertValid(
      $validator->singleValidate('12345', 'zip')
    );
    
    $this->assertInvalid(
      $validator->singleValidate('999', 'zip')
    );
    
    $this->assertInvalid(
      $validator->singleValidate('abcdef', 'zip')
    );
  }

}

