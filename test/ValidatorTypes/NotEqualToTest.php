<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorNotEqualToTest extends Validator_TestCase {

  /**
   */
  public function testRuleNotEqualTo() {
  
    $validation_rules = array(
        'choice_one'   => 'required',
        'choice_two'   => 'required notEqualTo=choice_one',
        'choice_three' => 'required notEqualTo=choice_one|choice_two'
    );
    $validator = new SimpleValidator($validation_rules);
        
    $good_form_data = array(
        'choice_one'   => 'red',
        'choice_two'   => 'blue',
        'choice_three' => 'green'
    );

    $errors = $validator->validate($good_form_data);
    $this->assertEquals(array(), $errors);
    
    
    $bad_form_data = array(
        'choice_one'   => 'red',
        'choice_two'   => 'red',
        'choice_three' => 'green'
    );
    
    $expected_fail = array(
      'choice_two'   => $validator->errorForNotEqualTo
    );
    
    $errors = $validator->validate($bad_form_data);
    $this->assertEquals($expected_fail, $errors);
    
    
    $bad_form_data = array(
        'choice_one'   => 'red',
        'choice_two'   => 'blue',
        'choice_three' => 'blue'
    );
    
    $expected_fail = array(
      'choice_two'   => $validator->errorForNotEqualTo
    );
    
    $errors = $validator->validate($bad_form_data);
    $this->assertEquals($expected_fail, $errors);
  }
}
?>
