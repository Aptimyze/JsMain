<?php
include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(23, new lime_output_color());

$t->is(GoogleRemarketing::getCasteTag("18"), "Arora", "Hindu-Arora");
$t->is(GoogleRemarketing::getCasteTag("18,365"), "Khatri", "Khatri");
$t->is(GoogleRemarketing::getCasteTag("425"), "Maratha", "Maratha");
$t->is(GoogleRemarketing::getCasteTag(FieldMap::getFieldLabel("caste_group_array", "494")), "Maratha", "Maratha");
$t->is(GoogleRemarketing::getCasteTag("71"), "Jat", "Jat");
$t->is(GoogleRemarketing::getCasteTag("118, 18"), "Rajput", "Rajput");
$t->is(GoogleRemarketing::getCasteTag("167"), "", "Sikh-Arora");

