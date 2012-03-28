<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../SimpleValidator.inc';
require_once __DIR__ . '/ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorTest extends PHPUnit_Framework_TestCase {

  public function dataNumeric() {
    return array(
      array(true, '1'),
      array(true, 1),
      array(true, '1.0'),
      array(true, '00001'),
      array(false, 'What\'s up doc?')
    );  
  }

  public function testFormSample() {
    $validation_rules = array(
        'title'       => 'required phrase maxlength=255',
        'description' => 'required phrase maxlength=255'
    );
    $form_data = array(
        'title'       => 'This is a title',
        'description' => 'A description of the bank'
    );
    $expected = array();
    
    $validator = new SimpleValidator($validation_rules);
    $errors    = $validator->validate($form_data);
    $this->assertEquals($expected, $errors);
    
    // reusing validator and rules
    $form_data = array(
        'title'       => 'This is a title',
        'description' => ''
    );
    $expected = array(
        'description' => $validator->errorForRequired
    );
    
    $errors = $validator->validate($form_data);
    $this->assertEquals($expected, $errors);
  }
  
  public function testKeysInRulesAndNotInData() {
        
    $validation_rules = array(
        'title'       => 'required',
        'description' => 'required'
    );
    $validator = new SimpleValidator($validation_rules);
    
    $form_data = array(
        'description' => 'A description of the bank'
    );
    $expected = array(
        'title' => $validator->errorForRequired
    );
    
    $errors    = $validator->validate($form_data);
    $this->assertEquals($expected, $errors);
  }
  
  public function testIgnoreEmptyFieldWithoutRequiredRule() {
        
    $validation_rules = array(
        'title'       => 'required phrase',
        'description' => 'phrase'
    );
    $validator = new SimpleValidator($validation_rules);
    
    $form_data = array(
        'title'       => 'A sample title',
        'description' => ''
    );
    $expected = array();
    
    $errors    = $validator->validate($form_data);
    $this->assertEquals($expected, $errors);
  }

  public function testIgnoreUnexpectedKeys() {
    $validation_rules = array(
        'title'       => 'required phrase maxlength=255'
    );
    $form_data = array(
        'title'       => 'This is a title',
        'description' => 'A description of the bank'
    );
    $expected = array();
    
    $validator = new SimpleValidator($validation_rules);
    $validator->ignoreUnexpectedKeys = True;
    $errors    = $validator->validate($form_data);
  }

  /**
   * @expectedException SimpleValidatorException
   */
  public function testUnexpectedKeysException() {
    $validation_rules = array(
        'title'       => 'required phrase maxlength=255'
    );
    $form_data = array(
        'title'       => 'This is a title',
        'description' => 'A description of the bank'
    );
    $expected = array();
    
    $validator = new SimpleValidator($validation_rules);
    $validator->ignoreUnexpectedKeys = false;
    $errors = $validator->validate($form_data);
  }
  
  /**
   * @dataProvider dataNumeric
   */
  public function testNumeric($expected_success, $value) {
    $validator = new SimpleValidator();
    if($expected_success) {
      $this->assertEquals(
        NULL,
        $validator->singleValidate($value, 'numeric')
      );
    }
    else {
      $this->assertEquals(
        $validator->errorForNumeric,
        $validator->singleValidate($value, 'numeric')
      );
    }
  
  }

}

/* End of file ValidatorTest.php */