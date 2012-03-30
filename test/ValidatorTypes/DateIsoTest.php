<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorDateIsoTest extends Validator_TestCase {

  /**
  * @dataProvider data_Dates
  */
  public function testRuleDate($valid, $date) {
    $validator = new SimpleValidator();

    $method = $valid ? 'assertValid' : 'assertInvalid';
    
    $this->$method(
      $validator->singleValidate($date, 'dateISO')
    );
  }
  
  public function data_Dates() {
    return array(
        array(TRUE, '2001-10-12'),
        array(TRUE, '2001-12-31'),
    
        array(FALSE, '2001-02-29'),
        array(FALSE, '2020-14-20'),
        array(FALSE, '99-08-03'),
    );
  }

}

