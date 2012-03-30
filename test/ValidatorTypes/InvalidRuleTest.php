<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorInvalidRuleTest extends Validator_TestCase {

  /**
   * @expectedException SimpleValidatorException
   */
  public function testInvalidRuleException() {
    $validator = new SimpleValidator();
    $validator->singleValidate('sample text', 'alphaNumBlahBlah');
  }

}

