[![Build Status](https://secure.travis-ci.org/there4/SimpleValidator.png?branch=master)](http://travis-ci.org/there4/SimpleValidator)

# Simple Validator

## Standard Usage

    require "path/to/SimpleValidator.inc";
    // array describing the keys to look for and their validation rules
    // note that the 'required' rule can appear anywhere
    // and that certain rules can accept arguments
    $rules = array(
      'firstname'        => 'required alpha',
       'lastname'        => 'required',
          'phone'        => 'phone required',
           'test'        => 'minlength=5',
        'zipcode'        => 'zip',
        'password'       => 'required minlength=8',
        'password-again' => 'equalTo=password'
    );
    
    $validator = new SimpleValidator($rules);
    // Set this to true if you don't want
    // exceptions thrown when SimpleValidator finds keys
    // that it hasn't been given rules for
    // $validator->ignoreUnexpectedKeys = True;
 
    // assuming $_POST contains the form data
    // validate returns an array of error messages
    try {
      $errors = $validator->validate($_POST);
    } catch (SimpleValidatorException $e) {
      // if validate detects an unexpected key in the data
      // then exception unless ignoreUnexpectedKeys is set to True
      ?>
      <p>SimpleValidator Error: < ?php echo $e->getMessage(); ?></p>
      < ?php
    }
    
    if (empty($errors)) {
      // no errors in the data, processing can proceed
    } else {
      // there are errors in the data
    }
    Validating a single value
    $valueToCheck = '111-111-1111';
    $errorMessage = $validator->singleValidate(
      $valueToCheck, "required phone");
    if (!empty($errorMessage)) {
      // invalid data
    } else {
      // valid data
    }

## Object Setup

### Setting up the validation rules

SimpleValidator depends on an associative array of rules that you give to it. The array defines: the keys to look for in the data to be validated and the rules to check the values for those keys against.

Typically you could just create the array, then initialize a SimpleValidator with that array:

`$validator = new SimpleValidator($rules);`

But you could also define the rules after initializing the object, perhaps if you wanted to dynamically change the rules depending on circumstances.

`$validator->rules = $new_validation_rules;`

### Custom Error Messages

Each rule has an associated error message built in. For example: the default error message for “required” is “This field is required.” You can reassign any of the error messages to ones of your choosing by assigning your own error message. The variable to set will always follow the pattern: errorFor(validation method in CapitalCase), e.g. errorForMaxLength.

`$validator->errorForRequired = 'This field cannot be left blank.';`

For rules that accept an argument, you can set a placeholder for that argument to be displayed in the error message.

    $validator->errorForMinLength = 'You must type in at least {minlength} characters.';
    // if called with minlength=5 then the error message would display as:
    // You must type in at least 5 characters.

The placeholder will always be the name of the rule (e.g. {minlength}, {maxlength}, {length}). This placeholder is optional and can be used more than once in an error message if you are feeling repetitive. “{length} shall be the number thou shalt count, and the number of the counting shall be {length}.”

## Validation Rules
<dl>
  <dt><strong>alpha</strong></dt>
  <dd>value must consist of alphabetic characters only</dd>
  
  <dt><strong>choices=red,green,blue</strong></dt>
  <dd>value must be in the comma separated list<br />in this case the value would have to be "red", "green", or "blue"</dd>

  <dt><strong>city</strong> (may not account for edge cases)</dt>
  <dd>Value must consist of characters valid for a US city name</dd>
  
  <dt><strong>date</strong></dt>
  <dd>value must be a valid date</dd>
  
  <dt><strong>email</strong></dt>
  <dd>must be a valid email address: <a class="ext-link" href="http://www.regular-expressions.info/email.html"><span class="icon">http://www.regular-expressions.info/email.html</span></a></dd>
  
  <dt><strong>equalTo=key</strong></dt>
  <dd>must be equal to data value for the given key<br />can append an optional name for the key for the error message: <strong>equalTo=key|Key_Name</strong> (_ characters will be converted to spaces on output)<br />if including a name for error output, you must change errorForEqualTo to be an error message with '{equalto}' somewhere in the string, e.g. "This value must match {equalto}."</dd>
<dt><strong>float</strong></dt>
<dd>value must be a valid floating point number</dd>
<dt><strong>length=number</strong> (e.g. length=10)</dt>
<dd>length of value must be equal to number</dd>
<dt><strong>maxlength=number</strong> (e.g. maxlength=10)</dt>
<dd>length of value must not be longer than number</dd>
<dt><strong>minlength=number</strong> (e.g. minlength=10)</dt>
<dd>length of value must not be shorter than number</dd>
<dt><strong>none</strong></dt>
<dd>no validation, explicitly</dd>
<dt><strong>notEqualTo=key</strong></dt>
<dd>must not be equal to value for "key"<br />can append an optional name for the key for the error message: <strong>notEqualTo=key|Key_Name</strong> (_ characters will be converted to spaces on output)<br />if including a name for error output, you must change errorForEqualTo to be an error message with '{notequalto}' somewhere in the string, e.g. "This value must not match {notequalto}."</dd>
<dt><strong>numeric</strong></dt>
<dd>value must consist of numbers only</dd>
<dt><strong>phone</strong></dt>
<dd>must be a valid long distance or local phone number
<pre>
###-###-#### or
###-####</pre>
</dd>
<dt><strong>phrase</strong></dt>
<dd>essentially "alpha" with allowed punctuation and spaces</dd>
<dt><strong>required</strong></dt>
<dd>may not be blank or consist of only whitespace characters</dd>
<dt><strong>required_if=field->specific_value</strong></dt>
<dd>field is required if "field" is set to "specific_value"<br />
  e.g. "required_if=color->blue" would make the current field required, if the "color" field had the value "blue"</dd>
<dt><strong>url</strong></dt>
<dd>must be a valid URL</dd>
<dt><strong>zip</strong></dt>
<dd>must be a valid US Postal Code (- characters ignored)
<pre>
##### or
#####-####
</pre>
</dd>
</dl>


## Custom Validation Rules

To add your own custom validation rules simply extend the class adding new protected (or public) methods for validation. The methods MUST match the validation rule you want to declare. For example, if you want to add a rule called “divisibleByThree” then your method must have the name “divisibleByThree”. Your custom method must take one argument (the data to be validated) and return a error message (string) if the data is not valid and null if the data is valid. Note that PHP returns null by default if the method doesn’t do so explicitly.

    class ExtraValidator extends SimpleValidator
    {
      protected function divisibleByThree($value) {
        if ($value % 3 !== 0) {
          return "Please enter a value divisible by three.";
        }
      }
    }

You use your new class exactly as you would a regular instance of SimpleValidator.

    // the numeric rule is a good idea unless you want to ensure 
    // that your divisibleByThree method properly handles string input
    $rules = array('input' => 'numeric divisibleByThree');
    $extraValidator = new ExtraValidator($rules);
    $data = array('input' => 9);
    $errors = $extraValidator->validate($data);

## Custom Validation Rules with An Argument

Creating a custom validation rule that takes an argument is exactly the same as above, but your method should take two arguments: one for the value to be validated and one for the extra data.

For example: say we’re happy with the “divisibleByThree” method we created above, but want to make it more generic so we can handle arbitrary integers: “divisibleBy=3″

    class ExtraValidator extends SimpleValidator
    {
      protected function divisibleBy($value, $factor) {
        if ($value % $factor !== 0) {
          return "Please enter a value divisible by $factor.";
        }
      }
    }
