<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorEqualToTest extends Validator_TestCase {

  public function testRuleEqualTo() {
  
    $validation_rules = array(
        'password'         => 'required',
        'password_confirm' => 'equalTo=password'
    );
    $validator = new SimpleValidator($validation_rules);

    // these match
    $this->assertEquals(
        array(/* No errors */),
        $validator->validate(array(
            'password'         => '6328EA48C4B0837EECF5',
            'password_confirm' => '6328EA48C4B0837EECF5'
        ))
    );
    
    // these values don't match
    $this->assertEquals(
        array(
            'password_confirm'   => $validator->errorForEqualTo
        ),
        $validator->validate(array(
            'password'         => '6328EA48C4B0837EECF5',
            'password_confirm' => 'C5B5BEC4E8CD4D8C9213'
        ))
    );
  }
  
  public function testRuleEqualToAlias() { 

    $validation_rules = array(
        'password'         => 'required',
        'password_confirm' => 'equalTo=password|Password'
    );
    $validator = new SimpleValidator($validation_rules);
    
    // the | is an alias to the field name
    // we use it to make a prettier error message
    $this->assertEquals(
        array(/* No errors */),
        $validator->validate(array(
            'password'         => 'aaa',
            'password_confirm' => 'aaa',
        ))
    );
  }
  
  public function testMissingEqualToValue() {

    $validation_rules = array(
        'password_confirm' => 'required equalTo=password'
    );
    $validator = new SimpleValidator($validation_rules);

    // the password field is simply missing
    $this->assertEquals(
        array(
            'password_confirm'   => $validator->errorForEqualTo
        ),
        $validator->validate(array(
            'password_confirm' => 'aaa',
        ))
    );

  }
  
  public function testRequiredAndEqualTo() {
  
    // [NOTE]
    // Best practice on this to NOT set the type of the second field (email)
    // but instead simply use the equalTo
    $validation_rules = array(
        'email'         => 'required email',
        'email_confirm' => 'required equalTo=email'
    );
    
    $validator = new SimpleValidator($validation_rules);

    $this->assertEquals(
        array(
            'email_confirm' => $validator->errorForRequired
        ),
        $validator->validate(array(
            'email'         => 'spam@example.com',
            'email_confirm' => ''
        )),
        "Problem when first is valid email, and second is empty on line " . __LINE__
    );
    
    $validation_rules = array(
        'email'         => 'required email',
        'email_confirm' => 'equalTo=email'
    );
    
    $validator = new SimpleValidator($validation_rules);

    $this->assertEquals(
        array(
            'email_confirm' => $validator->errorForEqualTo
        ),
        $validator->validate(array(
            'email'         => 'spam@example.com',
            'email_confirm' => ''
        )),
        "Problem when first is valid email, and second is empty on line " . __LINE__
    );
  }
  
  public function testNotRequiredAndEqualTo() {
  
    // [NOTE]
    // Best practice on this to NOT set the type of the second field (email)
    // but instead simply use the equalTo
    $validation_rules = array(
        'email'         => 'email',
        'email_confirm' => 'equalTo=email'
    );
    
    $validator = new SimpleValidator($validation_rules);

    $this->assertEquals(
        array(
            'email_confirm' => $validator->errorForEqualTo
        ),
        $validator->validate(array(
            'email'         => 'spam@example.com',
            'email_confirm' => ''
        )),
        "Problem when first is valid email, and second is empty on line " . __LINE__
    );
    $validator = new SimpleValidator($validation_rules);
    
    $this->assertEmpty(
        $validator->validate(array(
            'email'         => '',
            'email_confirm' => ''
        )),
        "Problem when both are empty on line " . __LINE__
    );

  }
}

/* End of file EqualToTest.php */