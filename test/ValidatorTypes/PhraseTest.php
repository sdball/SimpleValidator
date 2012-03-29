<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorPhraseTest extends Validator_TestCase {

  /**
   */
  public function testRulePhrase() {
    $validator = new SimpleValidator();
    
    $this->assertValid(
      $validator->singleValidate('Text with spaces', 'phrase')
    );

    $this->assertInvalid(
      $validator->singleValidate('Text-with/extracharacters', 'phrase')
    );
  }

}

