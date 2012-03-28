<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorNoneTest extends Validator_TestCase {

  /**
   */
  public function testRuleNone() {
    $validator = new SimpleValidator();

    $this->assertValid(
      $validator->singleValidate($this->randChoice(), 'none')
    );
  
    $this->assertValid(
      $validator->singleValidate("4%6&#$@\}", 'none')
    );

  }
  
  private function randChoice() {
    return md5(uniqid(rand(), TRUE));
  }

}

