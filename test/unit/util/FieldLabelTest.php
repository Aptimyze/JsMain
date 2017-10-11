<?php
include(dirname(__FILE__).'/../../bootstrap/unit.php');
$t = new lime_test(16, new lime_output_color());
$t->is(FieldMap::getFieldLabel("country","51"),"India","Problem in country");
$t->is(FieldMap::getFieldLabel("caste","13"),"Christian: Pentecost","Problem in Caste");
$t->is(FieldMap::getFieldLabel("occupation","11"),"Corporate Planning","Problem in occupation");
$t->is(FieldMap::getFieldLabel("height","8"),"4' 7&quot; (1.40 mts)","Prob in height");
$t->is(FieldMap::getFieldLabel("city_india","PU01"),"Amritsar","Problem in city_india");
$t->is(FieldMap::getFieldLabel("city_usa","7"),"USA - Connecticut","Problem in city_usa");
$t->is(FieldMap::getFieldLabel("religion","3"),"Christian","Problem in religion");
$t->is(FieldMap::getFieldLabel("community","14"),"Himachali/Pahari","Problem in community");
$t->is(FieldMap::getFieldLabel("education","14"),"M.Pharma","Problem in education ");
$t->is(FieldMap::getFieldLabel("income_level","6"),"Rs.4,00,001 - 5,00,000","Problem in income_level");
$t->is(FieldMap::getFieldLabel("education_label","3"),"Diploma","Problem in education_label");
$t->is(FieldMap::getFieldLabel("family_background","5"),"Civil Services","Problem in family_background");
$t->is(FieldMap::getFieldLabel("mother_occupation","3"),"Service-Private","Problem in mother_occupation");
$t->is(FieldMap::getFieldLabel("caste_small","11"),"-Protestant","Problem in caste_small");
$t->is(FieldMap::getFieldLabel("community_small","2"),"Nicobarese","Problem in community_small");
$t->is(FieldMap::getFieldLabel("city","405"),"Avellaneda","Problem in city");
$t->is(FieldMap::getFieldLabel("flagval","job_info"),"8192","Problem in flagval");
$t->is(FieldMap::getFieldLabel("relation","5"),"marriagebureau","Problem in relation");
$t->is(FieldMap::getFieldLabel("maththab","5"),"Ismaili","Problem in maththab");
$t->is(FieldMap::getFieldLabel("income_map","13"),"$ 1 - 1.5lac","Problem in income_map");
$t->is(FieldMap::getFieldLabel("baptised","Y"),"Yes","Problem in baptised");
$t->is(FieldMap::getFieldLabel("clean_shaven","Y"),"Yes","Problem in clean_shaven");
?>
