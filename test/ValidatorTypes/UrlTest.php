<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorUrlTest extends Validator_TestCase {

  /**
   * @dataProvider data_Addresses
   */
  public function testRuleEmail($valid, $address) {
    $validator = new SimpleValidator();
    
    $method = $valid ? 'assertValid' : 'assertInvalid';
    
    $this->$method(
      $validator->singleValidate($address, 'url')
    );
  }
  
  public function data_Addresses() {
    return array(
        array(TRUE, 'http://example.com'),
        array(TRUE, 'http://www.example.com'),
        array(TRUE, 'http://www.example.com/test/'),
        array(TRUE, 'http://example.com/test/test.html'),
        array(TRUE, 'https://example.com'),
        array(TRUE, 'https://www.example.com'),

        array(FALSE, 'htt://example.com'),
        array(FALSE, 'ftp://www.example.com'),
        array(FALSE, 'http:/example.com/test/test.html'),
        array(FALSE, 'https:///example.com'),
        array(FALSE, 'https://example'),
    );
  }

}

