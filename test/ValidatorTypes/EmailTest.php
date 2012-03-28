<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorEmailTest extends Validator_TestCase {

  /**
   * @dataProvider data_Addresses
   */
  public function testRuleEmail($valid, $email) {
    $validator = new SimpleValidator();
    
    $method = $valid ? 'assertValid' : 'assertInvalid';
    
    $this->$method(
      $validator->singleValidate($email, 'email')
    );
  }
  
  public function data_Addresses() {
    // http://blogs.msdn.com/b/testing123/archive/2009/02/05/email-address-test-cases.aspx
    return array(
        array(TRUE, 'email@domain.com'),              // Valid email
        array(TRUE, 'firstname.lastname@domain.com'), // Email contains dot in the address field
        array(TRUE, 'email@subdomain.domain.com'),    // Email contains dot with subdomain
        array(TRUE, 'firstname+lastname@domain.com'), // Plus sign is considered valid character
        //array(TRUE, 'email@123.123.123.123'),         // Domain is valid IP address
        //array(TRUE, 'email@[123.123.123.123]'),       // Square bracket around IP address is considered valid
        //array(TRUE, '"email"@domain.com'),            // Quotes around email is considered valid
        array(TRUE, '1234567890@domain.com'),         // Digits in address are valid
        array(TRUE, 'email@domain-one.com'),          // Dash in domain name is valid
        array(TRUE, '_______@domain.com'),            // Underscore in the address field is valid
        array(TRUE, 'email@domain.name'),             // .name is valid Top Level Domain name
        array(TRUE, 'email@domain.co.jp'),            // Dot in Top Level Domain name also considered valid (use co.jp as example here)
        array(TRUE, 'firstname-lastname@domain.com'), // Dash in address field is valid
    
        array(FALSE, 'plainaddress'),                 // Missing @ sign and domain
        array(FALSE, '#@%^%#$@#$@#.com'),             // Garbage
        array(FALSE, '@domain.com'),                  // Missing username
        array(FALSE, 'email.domain.com'),             // Missing @
        array(FALSE, 'email@domain@domain.com'),      // Two @ sign
        array(FALSE, '.email@domain.com'),            // Leading dot in address is not allowed
        array(FALSE, 'email.@domain.com'),            // Trailing dot in address is not allowed
        array(FALSE, 'email..email@domain.com'),      // Multiple dots
        array(FALSE, 'あいうえお@domain.com'),          // Unicode char as address
        array(FALSE, 'email@domain.com (Joe Smith)'), // Text followed email is not allowed
        array(FALSE, 'email@domain'),                 // Missing top level domain (.com/.net/.org/etc)
        array(FALSE, 'email@-domain.com'),            // Leading dash in front of domain is invalid
        array(FALSE, 'email@111.222.333.44444'),      // Invalid IP format
        array(FALSE, 'email@domain..com'),            // Multiple dots
    );
  }

}

