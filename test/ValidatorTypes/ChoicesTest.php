<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorChoicesTest extends Validator_TestCase {

  public function testRuleChoices() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate('two', 'choices=one,two,three,four')
    );

    $this->assertValid(
      $validator->singleValidate(array('one', 'two'), 'choices=one,two,three,four')
    );
    
    $this->assertInvalid(
      $validator->singleValidate(array('one', 'six'), 'choices=one,two,three,four')
    );

    // large list of choices
    $choice  = $this->randChoice();
    $options = "choices=" . $choice;
    for ($i=0;$i<100;$i++) {
      $options .= ',' . $this->randChoice();
    }
    
    $this->assertValid(
      $validator->singleValidate($choice, $options)
    );

    $this->assertInvalid(
      $validator->singleValidate('none', 'choices=one,two,three,four')
    );
  }
  
  private function randChoice() {
    return md5(uniqid(rand(), TRUE));
  }
  
  

}

