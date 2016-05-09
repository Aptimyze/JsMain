<?php 
include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(9, new lime_output_color());
 
$tests = array(
  array(false, '', 'empty value'),
  array(false, 'hemant123@jeevansathi.com', 'invalid domain'),
  array(false, 'hemant123@@dontreg.com', 'invalid domain'),
  array(false, 'hemant123@@jeevansathi.com', 'invalid format of email'),
  array(true, 'hemant123@jeevanthi.com', 'valid value'),
  array(false, 'hema@gmail.com', 'invalid value less length gmail'),
  array(true, 'xyzqwe@gmail.com', 'valid value length gmail'),
  array(false, 'bankim@gmail.co', 'duplicate email id'),
  array(false, 'bankim hello@gmail.co', 'spcae not allowed'),
);
 
$v = new jsValidatorMail();
 
$t->diag("Testing jsValidatorMail");
 
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
