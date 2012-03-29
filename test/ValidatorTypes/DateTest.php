<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorDateTest extends Validator_TestCase {

  /**
  * @dataProvider data_Dates
  */
  public function testRuleDate($valid, $date) {
    $validator = new SimpleValidator();
    
    $method = $valid ? 'assertValid' : 'assertInvalid';
    
    $this->$method(
      $validator->singleValidate($date, 'date')
    );
  }
  
  public function data_Dates() {
    return array(
        array(TRUE, '1/1/11'),
        array(TRUE, '1/1/2011'),
        array(TRUE, '11/1/2011'),
        array(TRUE, '11/11/2011'),
        array(TRUE, '01/01/2011'),
        array(TRUE, '12/1/00'),
    
        array(FALSE, '10/40/2011'),
        array(FALSE, '13/12/2011'),
        array(FALSE, 'a/12/2011'),
        array(FALSE, 'this is a fish'),
        array(FALSE, '12/32/32767'),
        array(FALSE, '12/30/40000'),
        array(FALSE, '12/1/-1000')
    );
    
  }

}

