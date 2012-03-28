<?php

/**
* SimpleValidatorException : simple exception for SimpleValidator
*/
class SimpleValidatorException extends Exception {}


/**
 * SimpleValidator : easy object oriented form validation with pre-built
 * rules for common inputs.
 *
 * @author Stephen Ball
 */
class SimpleValidator
{
  // $rules is an array of keys to validation rules
  //   e.g. {'firstname': 'required alpha'}
  function __construct($rules = array(), $errorMessages = array())
  {
    $this->rules = $rules;
    $this->errorMessages = $errorMessages;
    $this->ignoreUnexpectedKeys = False;
    $this->errors = array();
    $this->data = array();
    
    // error messages
    $this->errorForAlpha           = 'Please enter only alphabetic characters.';
    $this->errorForChoices         = 'Please enter a valid value.';
    $this->errorForCity            = 'Please enter a valid city name.';
    $this->errorForDate            = 'Please enter a valid date.';
    $this->errorForDateISO         = 'Please enter a valid ISO date.';
    $this->errorForDateGreaterThan = 'Please enter a date that is before {date}.';
    $this->errorForEmail           = 'Please enter a valid email address.';
    $this->errorForEqualTo         = 'Please enter the same value again.';
    $this->errorForFloat           = 'Please enter a valid floating point number.';
    $this->errorForLength          = 'Please enter {length} characters.';
    $this->errorForMax             = 'Please enter a value less than or equal to {max}.';
    $this->errorForMaxLength       = 'Please enter no more than {maxlength} characters.';
    $this->errorForMin             = 'Please enter a value greater than or equal to {min}.';
    $this->errorForMinLength       = 'Please enter no fewer than {minlength} characters.';
    $this->errorForNotEqualTo      = 'Please enter a different value.';
    $this->errorForNumeric         = 'Please enter numbers only.';
    $this->errorForPhone           = 'Please enter a valid phone number.';
    $this->errorForPhrase          = 'Please enter a valid phrase.';
    $this->errorForRequired        = 'This field is required.';
    $this->errorForURL             = 'Please enter a valid URL.';
    $this->errorForZip             = 'Please enter a valid zip code.';
  }
  
  
  // ====================
  // = public functions =
  // ====================
  
  /**
   * validate : checks $data against the rules described in $this->rules
   *
   * @param array $data : data array to validate
   * @return array $errors : array of error messages keyed the same as $data
   * @author Stephen Ball
   */
  public function validate($data)
  {
    $this->data = $data;
    
    if (!is_array($this->rules)) {
      throw new SimpleValidatorException("Invalid ruleset.", 1);
    }
    
    // "required_if" turns into "required" or is eliminated
    // depending on the condition
    foreach ($this->rules as $key => $rule) {
      $this->rules[$key] = $this->parse_required_if($rule);
    }
    
    // find any keys that are in the rules, but not in the data
    // if any of these keys have "required" then assign the error
    $keysInRulesAndNotInData = array_diff_key($this->rules, $data);
    foreach ($keysInRulesAndNotInData as $key => $ruleString) {
      $rules = explode(' ', $ruleString);
      if (in_array('required', $rules)) {
        $this->errors[$key] = $this->errorForRequired;
      }
    }
    
    foreach ($data as $key => $valueToValidate) {
      if (is_string($valueToValidate)) {
        $valueToValidate = trim($valueToValidate);
      }
      if (!isset($this->rules[$key])) {
        if ($this->ignoreUnexpectedKeys) {
          // ignore the unexpected key and skip to the next key
          continue;
        } else {
          throw new SimpleValidatorException("Rule not found for $key", 1);
        }
      }
      $rule = $this->rules[$key];
      $validationResult = $this->singleValidate($valueToValidate, $rule);
      
      if ($validationResult) {
        $this->errors[$key] = $validationResult;
      }
      
    } // end foreach ($data as $key => $valueToValidate)
    return $this->errors;
  }
  
  
  public function singleValidate($valueToValidate, $rule)
  {
    $rules = explode(' ', $rule);
    
    // "required" is an exception to the regular rules
    $requiredError = $this->required($valueToValidate);
    if ($requiredError !== null) {
      if (in_array('required', $rules)) {
        // if field is required and empty
        // set the error and don't bother checking the other rules
        return $requiredError;
      }
      // if not required and empty then don't bother with further checks
      // unless we're dealing with "equalTo"
      if (strpos($rule, 'equalTo') === False) {
        return;
      }
    }
    
    foreach ($rules as $validationFunction) {
      $validationFunction = str_replace(' ', '', $validationFunction);
      $extra = null;
      if (strpos($validationFunction, '=') !== False) {
        list($validationFunction, $extra) = explode('=', $validationFunction);
      }
      // suggested by Kevin Otte: throw a known exception if invalid rule specified
      if (!method_exists($this, $validationFunction)) {
        throw new SimpleValidatorException("Invalid rule specified: $validationFunction", 1);
      }
      $ruleResult = $this->$validationFunction($valueToValidate, $extra);
      if ($ruleResult !== null) {
        return $ruleResult;
      }
    }
  }
  
  
  // =====================
  // = protected functions =
  // =====================
  /**
   * alpha : returns error message if $value is not alphabetic characters only
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function alpha($value)
  {
    if (!ctype_alpha($value)) {
      return $this->errorForAlpha;
    }
  }
  
  /**
   * choices : returns error message if $value is not in $valid_choices
   *
   * @param mixed $value
   * @param string $valid_choices (comma separated)
   * @return mixed
   * @author Stephen Ball
   */
  protected function choices($value, $valid_choices)
  {
    if (is_array($value)) {
      foreach ($value as $v) {
        $error = $this->choices($v, $valid_choices);
        if ($error) {
          return $error;
        }
      }
    } else {
      $valid_choices = explode(',', $valid_choices);
      if (!in_array($value, $valid_choices)) {
        return $this->errorForChoices;
      }
    }
  }
  
  /**
   * city : returns error message if $value is not a valid city name
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function city($value)
  {
    if (!preg_match("/^[a-z][a-z \-.&]*$/i", $value)) {
      return $this->errorForCity;
    }
  }
  
  /**
   * date : returns error message if $value is an invalid date
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function date($value)
  {
    if (($timestamp = strtotime($value)) === false) {
      return $this->errorForDate;
    }
    
    $month = date('m', $timestamp);
    $day   = date('d', $timestamp);
    $year  = date('Y', $timestamp);

    if (!checkdate($month, $day, $year)) {
      return $this->errorForDate;
    }
  }
  
  /**
   * dateISO : returns error message if $value is an invalid ISO date
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function dateISO($value)
  {
    $matches = array();
    if (!preg_match('/(\d{4})-(\d{2})-(\d{2})/', $value, $matches)) {
      return $this->errorForDateISO;
    }
    $year  = $matches[1];
    $month = $matches[2];
    $day   = $matches[3];
    if (!checkdate($month, $day, $year)) {
      return $this->errorForDateISO;
    }
  }

  /**
   * dateGreaterThan : returns error message if $value is before another date
   *
   * @param string $value      data for validation 
   * @param string $keyToMatch key of value to compare to
   *
   * @return mixed
   */
  protected function dateGreaterThan($value, $keyToMatch) {
    if ($this->date($value)) {
      return $this->errorForDate;
    }
    if (strtotime($value) < strtotime($this->data[$keyToMatch])) {
      return $this->errorForDateGreaterThan;
    }
  }
  
  /**
   * email : returns error message if $value is not a valid email address
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function email($value)
  {
    // http://www.regular-expressions.info/email.html
    if (!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i', $value)) {
      return $this->errorForEmail;
    }
  }
  
  /**
   * equalTo : returns error if $value is not equal to $data[$keyToMatch]
   *
   * optional: $keyToMatch can include a name for key to make the error 
   * message clearer to the end user
   *
   * @param string $value 
   * @param string $keyToMatch 
   * @return mixed
   * @author Stephen Ball
   */
  protected function equalTo($value, $keyToMatch)
  {
    $fieldName = $keyToMatch;
    if (strpos($keyToMatch, '|') !== False) {
      list($keyToMatch, $fieldName) = explode('|', $keyToMatch);
    }
    $fieldName = str_replace('_', ' ', $fieldName);
    if (!isset($this->data[$keyToMatch])) {
      return str_replace('{equalto}', $fieldName, $this->errorForEqualTo);
    }
    if ($value != $this->data[$keyToMatch]) {
      return str_replace('{equalto}', $fieldName, $this->errorForEqualTo);
    }
  }
  
  /**
   * float : returns error message if $value is not a valid floating point
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function float($value)
  {
    if (!preg_match('/^[0-9]*\.?[0-9]+$/', $value)) {
      return $this->errorForFloat;
    }
  }
  
  /**
   * length : returns error message if length of $value is not $length
   *
   * @param string $value 
   * @param int $length 
   * @return mixed
   * @author Stephen Ball
   */
  protected function length($value, $length)
  {
    if (strlen($value) != intval($length)) {
      return str_replace('{length}', $length, $this->errorForLength);
    }
  }
  
  /**
   * max : returns error message if $value is greater than $max
   *
   * @param string $value 
   * @param string $max 
   * @return void
   * @author Stephen Ball
   */
  protected function max($value, $max)
  {
    if (intval($value) > intval($max)) {
      return str_replace('{max}', $max, $this->errorForMax);
    }
  }
  
  /**
   * maxlength : returns error message if $value is longer than $maxlength
   *
   * @param string $value 
   * @param int $length 
   * @return mixed
   * @author Stephen Ball
   */
  protected function maxlength($value, $maxlength)
  {
    if (strlen($value) > $maxlength) {
      return str_replace('{maxlength}', $maxlength, $this->errorForMaxLength);
    }
  }
  
  /**
   * min : returns error message if $value is lesser than $min
   *
   * @param string $value 
   * @param string $min 
   * @return void
   * @author Stephen Ball
   */
  protected function min($value, $min)
  {
    if (intval($value) < intval($min)) {
      return str_replace('{min}', $min, $this->errorForMin);
    }
  }
  
  /**
   * minlength : returns error message if $value is shorter than $minlength
   *
   * @param string $value 
   * @param int $minlength 
   * @return mixed
   * @author Stephen Ball
   */
  protected function minlength($value, $minlength)
  {
    if (strlen($value) < $minlength) {
      return str_replace('{minlength}', $minlength, $this->errorForMinLength);
    }
  }
  
  /**
   * none : no validation, explicitly
   *
   * @param string $value 
   * @return void
   * @author Stephen Ball
   */
  protected function none($value)
  {
    return;
  }
  
  /**
   * notEqualTo : returns error if $value is equal to $data[$keyToDiffer]
   *
   * optional: $keyToDiffer can include a name for key to make the error
   * message clearer to the end user
   *
   * @param string $value 
   * @param string $keyToDiffer 
   * @return mixed
   * @author Stephen Ball
   */
  protected function notEqualTo($value, $keyToDiffer)
  {
    $fieldName = $keyToDiffer;
    if (strpos($keyToDiffer, '|') !== False) {
      list($keyToDiffer, $fieldName) = explode('|', $keyToDiffer);
    }
    $fieldName = str_replace('_', ' ', $fieldName);
    if (isset($this->data[$keyToDiffer])) {
      if ($value == $this->data[$keyToDiffer]) {
        return str_replace('{notequalto}', $fieldName, $this->errorForNotEqualTo);
      }
    }
  }
  
  /**
   * numeric : returns error message if $value is not numbers only
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function numeric($value)
  {
    if (!ctype_digit($value)) {
      return $this->errorForNumeric;
    }
  }
  
  /**
   * phone : returns error message if $value not a valid US phone number
   *
   * @param string $value
   * @return mixed
   * @author Stephen Ball
   */
  protected function phone($value)
  {
    if (!preg_match('/^1?-?(\d{3})?-?(\d{2})?\d-?(\d{4})$/', $value)) { 
      return $this->errorForPhone;
    }
  }
  
  /**
   * phrase : returns error message if $value is not a valid phrase
   *
   * i.e. alphabetic characters and some punctionation
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function phrase($value)
  {
    if (!preg_match("/^[a-z \-.'&]*$/i", $value)) {
      return $this->errorForPhrase;
    }
  }
  
  /**
   * required : returns error message if $value is empty
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function required($value)
  {
    if (empty($value)) {
      return $this->errorForRequired;
    }
  }
  
  /**
   * url : returns error message if $value is not a valid URL
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function url($value)
  {
    if (!preg_match('/^https?:\/\/[A-Z0-9.-]+\.[A-Z]{2,4}/i', $value)) {
      return $this->errorForURL;
    }
  }
  
  /**
   * zip : returns error message if $value is not a valid US Postal Code
   *
   * @param string $value 
   * @return mixed
   * @author Stephen Ball
   */
  protected function zip($value)
  {
    if (!preg_match('/^\d{5}-?(\d{4})?$/', $value)) {
      return $this->errorForZip;
    }
  }
  
  // =====================
  // = private functions =
  // =====================
  /**
   * parse_required_if
   *
   * Modifies rule string (depending on conditions) to replace
   * required_if with either "required" or nothing.
   *
   * @param string $ruleString
   * @return string $ruleString
   * @author Stephen Ball
   */
  private function parse_required_if($ruleString)
  {
    if (preg_match('/required_if=(\w+->\w+)/', $ruleString, $matches)) {
      $requirement = $matches[1];
      
      list($key, $special_value) = explode('->', $requirement);
      
      if (isset($this->data[$key])) {
        if ($this->data[$key] == $special_value) {
          // all conditions are met, so rewrite the rules
          // for this field to be "required"
          return preg_replace(
            '/required_if=\w+->\w+/', 'required', $ruleString);
        }
      }
    }
    // conditions are not met
    // rewrite the rules to remove the 'required_if' statement
    return preg_replace('/\s?required_if=\w+->\w+\s?/', '', $ruleString);
  }
}

?>
