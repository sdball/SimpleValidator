<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorPhoneTest extends Validator_TestCase {

  /**
   * @dataProvider data_Numbers
   */
  public function testRulePhone($valid, $phone) {
    $validator = new SimpleValidator();
    
    $method = $valid ? 'assertValid' : 'assertInvalid';
    
    $this->$method(
      $validator->singleValidate($phone, 'phone')
    );
  }
  
  public function data_Numbers() {
    return array(
        array(TRUE, '(308)-135-7895'),
        array(TRUE, '(123) 456-7890'),
        array(TRUE, '123-345-6789'),
        array(TRUE, '123 456-7890'),

        array(FALSE, '456-7890'),
        array(FALSE, '23456789'),
        array(FALSE, '1234'),

    );
  }

}

