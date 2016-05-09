<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(5, new lime_output_color());

$t->is(NumberToWords::convertNumber(1), "one", "Success");
$t->is(NumberToWords::convertNumber(10), "ten", "Success");
$t->is(NumberToWords::convertNumber(52), "fifty-two", "Success");
$t->is(NumberToWords::convertNumber(123), "one hundred and twenty-three", "Success");
$t->is(NumberToWords::convertNumber(3), "three", "Success");
