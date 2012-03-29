<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorInvalidRuleSetTest extends Validator_TestCase {

  /**
   * @expectedException SimpleValidatorException
   */
  public function testInvalidRuleSetException() {
  
    // note that the rules could be an empty string
  
    $invalid_rules = "string";
    $data          = array(
        'sample'  => 'foo',
        'another' => 'bar'
    );
    $validator = new SimpleValidator($invalid_rules);
    $validator->validate($data);
  }

}

