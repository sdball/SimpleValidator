<?php
/**
 * Validator TestCase Base
 */

/**
 * Validator Test Case with customer assert commands
 *
 * The validator is a little odd in that for valid data it returns null
 * and for invalid data is returns a string
 *
 * NOTE: we aren't checking for the correct string, just a string
 *
 * @author Craig Davis <craig@there4development.com>
 */
abstract class Validator_TestCase extends PHPUnit_Framework_TestCase {

  /** 
   * Assert that a validator result is valid
   *
   * @param mixed $result string == false, null == true
   *
   * @return void
   */
  public function assertValid($result) {
    $this->assertInternalType('null', $result, "Failed to validate");
  }
  
  /** 
   * Assert that a validator result is invalid
   *
   * @param mixed $result string == false, null == true
   *
   * @return void
   */
  public function assertInvalid($result) {
    $this->assertInternalType(
        'string',
        $result,
        "This was not supposed to pass"
    );
  }
  
}

/* End of file ValidatorTestCase.php */
