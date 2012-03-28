<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorAlphaTest extends Validator_TestCase {

  public function testRuleAlpha() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate('abcdef', 'alpha')
    );
    
    $this->assertInvalid(
      $validator->singleValidate('999', 'alpha')
    );
  }

}

