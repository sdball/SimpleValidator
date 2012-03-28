<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorRequiredIfTest extends Validator_TestCase {

  public function testRuleRequiredIfToRequired() {
  
    $validation_rules = array(
        'new_member' => 'required choices=yes,no',
        'email'      => 'required_if=new_member->yes'
    );
    
    $data = array(
        'new_member' => 'yes',
        'email'      => 'example@example.com'
    );
    
    $expected = array();
    
    $validator = new SimpleValidator($validation_rules);
    $errors    = $validator->validate($data);
    $this->assertEquals($expected, $errors);
  }

  public function testRuleRequiredIfToNotRequiredAndFail() {
  
    $validation_rules = array(
        'new_member' => 'required choices=yes,no',
        'email'      => 'required_if=new_member->yes'
    );
    
    $data = array(
        'new_member' => 'yes',
        'email'      => ''
    );
    
    
    $validator = new SimpleValidator($validation_rules);
    
    $expected = array(
        'email' => $validator->errorForRequired
    );
    
    $errors    = $validator->validate($data);
    $this->assertEquals($expected, $errors);
  }

  public function testRuleRequiredIfToNotRequired() {
  
    $validation_rules = array(
        'new_member' => 'required choices=yes,no',
        'email'      => 'required_if=new_member->yes'
    );
    
    $data = array(
        'new_member' => 'no',
        'email'      => 'example@example.com'
    );
    
    $expected = array();
    
    $validator = new SimpleValidator($validation_rules);
    $errors    = $validator->validate($data);
    $this->assertEquals($expected, $errors);
  }

}

