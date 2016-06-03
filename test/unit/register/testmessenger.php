<?php 
include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(8, new lime_output_color());
 
$tests = array(
  array(true, 'hemant123', 'valid value'),
  array(false, 'hemant 123', 'invalid format space not allowed'),
  array(true, 'hemant123@@jeevansathi.com', 'valid value'),
  array(false, 'hemant!()', 'invalid value special char not allowed'),
  array(false, 'gmail', 'invalid value gmail not allowed'),
  array(false, 'gma', 'invalid value less than 4 char'),
  array(false, '1234', 'invalid value less than 1 alphabate'),
  array(true, 'e.g. raj1983, vicky1980 ', 'valid value')
);
 
$v = new jsValidatorMessenger();
 
$t->diag("Testing jsValidatorMessenger");
 
foreach($tests as $test)
{
  list($validity, $value, $message) = $test;
 
  try
  {
    $v->clean($value);
    $catched = false;
  }
  catch(sfValidatorError $e)
  {
    $catched = true;
  }
 
  $t->ok($validity != $catched, $message);
}
?>
