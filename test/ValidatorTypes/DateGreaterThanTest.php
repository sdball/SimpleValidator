<?php
error_reporting(E_ALL | E_STRICT);

require_once __DIR__ . '/../../SimpleValidator.inc';
require_once __DIR__ . '/../ValidatorTestCase.php';

/**
 * @group lib
 * @group validator
 */
class ValidatorDateGreaterThanTest extends Validator_TestCase {

  /**
  */
  public function testDateGreaterThanValid() {
  
    $validation_rules = array(
        'start_date'         => 'required',
        'end_date'           => 'dateGreaterThan=start_date'
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    
    $this->assertEquals(
        array(/* no erros*/),
        $validator->validate(array(
            'start_date'    => '1/1/11',
            'end_date'      => '2/1/11'
        )),
        "Problem when both are valid on " . __LINE__
    );
  }
  
  public function testDateGreaterThanInvalid() {
  
    $validation_rules = array(
        'start_date'         => 'required',
        'end_date'           => 'dateGreaterThan=start_date'
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    
    $this->assertEquals(
        array(
            'end_date' => $validator->errorForDateGreaterThan
        ),
        $validator->validate(array(
            'start_date'    => '2/1/11',
            'end_date'      => '1/1/11'
        )),
        "Problem when second is before on " . __LINE__
    );
    $this->assertEquals(
        array(
            'end_date' => $validator->errorForDate
        ),
        $validator->validate(array(
            'start_date'    => '2/1/11',
            'end_date'      => 'This is not a date'
        )),
        "Problem when second is not a date on " . __LINE__
    );
  }
  
  public function testDateGreaterThanNotRequired() {
  
    $validation_rules = array(
        'start_date'         => '',
        'end_date'           => 'dateGreaterThan=start_date'
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    
    $this->assertEquals(
        array(/*no errors*/),
        $validator->validate(array(
            'start_date'    => '',
            'end_date'      => '1/1/11'
        )),
        "Problem when first is not required on " . __LINE__
    );
    
    $this->assertEquals(
        array(/*no errors*/),
        $validator->validate(array(
            'start_date'    => '',
            'end_date'      => ''
        )),
        "Problem when both are not required on " . __LINE__
    );
  }
  
  public function testDateGreaterThanDateInvalid() {
  
    $validation_rules = array(
        'start_date'         => '',
        'end_date'           => 'dateGreaterThan=start_date'
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    
    $this->assertEquals(
        array(
            'end_date' => $validator->errorForDate
        ),
        $validator->validate(array(
            'start_date'    => '',
            'end_date'      => 'string not date'
        )),
        "Problem when date is invalid on " . __LINE__
    );
  }
  
  public function testDateGreaterThanEpoch() {
  
    $validation_rules = array(
        'start_date'         => 'date',
        'end_date'           => 'dateGreaterThan=start_date'
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    
    $this->assertEquals(
        array(/*no errors*/),
        $validator->validate(array(
            'start_date'    => '2/01/2011',
            'end_date'      => '6/01/11'
        )),
        "Problem when date is in epoch and greater than on " . __LINE__
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    
    $this->assertEquals(
        array(
            'end_date' => $validator->errorForDateGreaterThan
        ),
        $validator->validate(array(
            'start_date'    => '6/01/11',
            'end_date'      => '2/01/2011'
        )),
        "Problem when date is in epoch and less than on " . __LINE__
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    
    $this->assertEquals(
        array(/*no errors*/),
        $validator->validate(array(
            'start_date'    => '2/01/11',
            'end_date'      => '6/01/2011'
        )),
        "Problem when date is in epoch and greater than on " . __LINE__
    );
    
    $validator = new SimpleValidator($validation_rules);
    
    $this->assertEquals(
        array(
            'end_date' => $validator->errorForDateGreaterThan
        ),
        $validator->validate(array(
            'start_date'    => '6/01/2011',
            'end_date'      => '2/01/11'
        )),
        "Problem when date is in epoch and less than on " . __LINE__
    );
  }
}

