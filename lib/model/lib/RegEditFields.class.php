<?php
 /*
	This is auto-generated class by running lib/utils/RegFieldClassBuilder.php
	This class should not be updated manually.
	Created on 2015-07-14
 */
	class RegEditFields
	{
		/*This function will return all the fields of this page*/
    	public static function getPageFields($page,$returnArr='')
    	{
			switch($page){
  case "DP3":
					$page_obj=new PageFields("DP3");

					$field=new Field(101);
					$field->setName("HIJAB_MARRIAGE");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Willing to wear hijab :<br/> after marriage? ");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_MUSLIM:HIJAB_MARRIAGE");
					$page_obj->setField(101,"","",$field);

					$field=new Field(100);
					$field->setName("HIJAB");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Hijab :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_MUSLIM:HIJAB");
					$page_obj->setField(100,"","",$field);

					$field=new Field(99);
					$field->setName("SUNNAH_CAP");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Sunnah Cap :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:SUNNAH_CAP");
					$page_obj->setField(99,"","",$field);

					$field=new Field(98);
					$field->setName("SUNNAH_BEARD");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Sunnah beard :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:SUNNAH_BEARD");
					$page_obj->setField(98,"","",$field);

					$field=new Field(97);
					$field->setName("UMRAH_HAJJ");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Umrah/Hajj :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:UMRAH_HAJJ");
					$page_obj->setField(97,"","",$field);

					$field=new Field(96);
					$field->setName("QURAN");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Do you read the Quran :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:QURAN");
					$page_obj->setField(96,"","",$field);

					$field=new Field(95);
					$field->setName("ZAKAT");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Zakat :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_MUSLIM:ZAKAT");
					$page_obj->setField(95,"","",$field);

					$field=new Field(94);
					$field->setName("FASTING");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Fasting :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:FASTING");
					$page_obj->setField(94,"","",$field);

					$field=new Field(93);
					$field->setName("NAMAZ");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Namaz :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:NAMAZ");
					$page_obj->setField(93,"","",$field);

					$field=new Field(92);
					$field->setName("MATHTHAB");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Ma'thab :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:MATHTHAB");
					$page_obj->setField(92,"","",$field);

					$field=new Field(91);
					$field->setName("HOROSCOPE_MATCH");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Horoscope match :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:HOROSCOPE_MATCH");
					$page_obj->setField(91,"","",$field);

					$field=new Field(90);
					$field->setName("CLEAN_SHAVEN");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Are you clean-shaven? :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_SIKH:CLEAN_SHAVEN");
					$page_obj->setField(90,"","",$field);

					$field=new Field(89);
					$field->setName("WEAR_TURBAN");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Do you wear turban? :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_SIKH:WEAR_TURBAN");
					$page_obj->setField(89,"","",$field);

					$field=new Field(88);
					$field->setName("TRIM_BEARD");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Do you trim your beard? :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_SIKH:TRIM_BEARD");
					$page_obj->setField(88,"","",$field);

					$field=new Field(87);
					$field->setName("CUT_HAIR");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Do you cut your hair? :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_SIKH:CUT_HAIR");
					$page_obj->setField(87,"","",$field);

					$field=new Field(86);
					$field->setName("AMRITDHARI");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Are you a Amritdhari? :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_SIKH:AMRITDHARI");
					$page_obj->setField(86,"","",$field);

					$field=new Field(85);
					$field->setName("NAKSHATRA");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Nakshatra (m) :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:NAKSHATRA");
					$page_obj->setField(85,"","",$field);

					$field=new Field(84);
					$field->setName("ANCESTRAL_ORIGIN");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Specify City/Town :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:ANCESTRAL_ORIGIN");
					$page_obj->setField(84,"","",$field);

					$field=new Field(82);
					$field->setName("MANGLIK");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Manglik :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:MANGLIK");
					$page_obj->setField(82,"","",$field);

					$field=new Field(83);
					$field->setName("RASHI");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Rashi/ Moon sign :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:RASHI");
					$page_obj->setField(83,"","",$field);

					$field=new Field(81);
					$field->setName("GOTHRA");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Gotra /Gothram :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:GOTHRA");
					$page_obj->setField(81,"","",$field);

					$field=new Field(80);
					$field->setName("SUBCASTE");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Subcaste :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SUBCASTE");
					$page_obj->setField(80,"","",$field);

					$field=new Field(79);
					$field->setName("FAMILYINFO");
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Write about your Family :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILYINFO");
					$page_obj->setField(79,"","",$field);

					$field=new Field(78);
					$field->setName("PARENT_CITY_SAME");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Do you live with your parents :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PARENT_CITY_SAME");
					$page_obj->setField(78,"","",$field);

					$field=new Field(77);
					$field->setName("M_SISTER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:M_SISTER");
					$page_obj->setField(77,"","",$field);

					$field=new Field(76);
					$field->setName("T_SISTER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Sister(s) :");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:T_SISTER");
					$page_obj->setField(76,"","",$field);

					$field=new Field(75);
					$field->setName("M_BROTHER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:M_BROTHER");
					$page_obj->setField(75,"","",$field);

					$field=new Field(74);
					$field->setName("T_BROTHER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Brother(s) :");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:T_BROTHER");
					$page_obj->setField(74,"","",$field);

					$field=new Field(73);
					$field->setName("MOTHER_OCC");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Mother's Occupation :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:MOTHER_OCC");
					$page_obj->setField(73,"","",$field);

					$field=new Field(72);
					$field->setName("FAMILY_BACK");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Father's Occupation :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:FAMILY_BACK");
					$page_obj->setField(72,"","",$field);

					$field=new Field(71);
					$field->setName("FAMILY_STATUS");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Family Status :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILY_STATUS");
					$page_obj->setField(71,"","",$field);

					$field=new Field(70);
					$field->setName("FAMILY_TYPE");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Family Type :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILY_TYPE");
					$page_obj->setField(70,"","",$field);

					$field=new Field(69);
					$field->setName("FAMILY_VALUES");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Family Values :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILY_VALUES");
					$page_obj->setField(69,"","",$field);

					$field=new Field(68);
					$field->setName("SAMPRADAY");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Sampraday :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_JAIN:SAMPRADAY");
					$page_obj->setField(68,"","",$field);

					$field=new Field(67);
					$field->setName("NAME_OF_USER");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Your Name :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("NAME_OF_USER");
					$page_obj->setField(67,"","",$field);

					$field=new Field(17);
					$field->setName("RECORD_ID");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("NO_TABLE");
					$page_obj->setField(17,"","",$field);

					$field=new Field(120);
					$field->setName("NATIVE_CITY");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("native_place");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select City");
					$field->setTableName("NATIVE_PLACE:NATIVE_CITY");
					$page_obj->setField(120,"","",$field);

					$field=new Field(102);
					$field->setName("WORKING_MARRIAGE");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Can the girl work after : marriage?");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JP_MUSLIM:WORKING_MARRIAGE");
					$page_obj->setField(102,"","",$field);

					$field=new Field(103);
					$field->setName("SPEAK_URDU");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Speak Urdu :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SPEAK_URDU");
					$page_obj->setField(103,"","",$field);

					$field=new Field(104);
					$field->setName("DIOCESE");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Diocese :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_CHRISTIAN:DIOCESE");
					$page_obj->setField(104,"","",$field);

					$field=new Field(105);
					$field->setName("BAPTISED");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Baptised :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_CHRISTIAN:BAPTISED");
					$page_obj->setField(105,"","",$field);

					$field=new Field(107);
					$field->setName("READ_BIBLE");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Do you read Bible : <br />everyday? ");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_CHRISTIAN:READ_BIBLE");
					$page_obj->setField(107,"","",$field);

					$field=new Field(108);
					$field->setName("OFFER_TITHE");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Do you offer Tithe : <br /> regularly? ");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_CHRISTIAN:OFFER_TITHE");
					$page_obj->setField(108,"","",$field);

					$field=new Field(109);
					$field->setName("SPREADING_GOSPEL");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Interested in spreading :<br />the Gospel?");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_CHRISTIAN:SPREADING_GOSPEL");
					$page_obj->setField(109,"","",$field);

					$field=new Field(110);
					$field->setName("ZARATHUSHTRI");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Are you a Zarathushtri :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_PARSI:ZARATHUSHTRI");
					$page_obj->setField(110,"","",$field);

					$field=new Field(111);
					$field->setName("PARENTS_ZARATHUSHTRI");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Are both parents :<br /> Zarathushtri?");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JP_PARSI:PARENTS_ZARATHUSHTRI");
					$page_obj->setField(111,"","",$field);

					$field=new Field(118);
					$field->setName("NATIVE_COUNTRY");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select Country");
					$field->setTableName("NATIVE_PLACE:NATIVE_COUNTRY");
					$page_obj->setField(118,"","",$field);

					$field=new Field(119);
					$field->setName("NATIVE_STATE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("native_place");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Family based out of:");
					$field->setBlankValue("");
					$field->setBlankLabel("Select State");
					$field->setTableName("NATIVE_PLACE:NATIVE_STATE");
					$page_obj->setField(119,"","",$field);
break;
  case "MP3":
					$page_obj=new PageFields("MP3");

					$field=new Field(10);
					$field->setName("CITY_RES");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("9");
					$field->setLabel("City");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:CITY_RES");
					$page_obj->setField(10,"a","",$field);

					$field=new Field(34);
					$field->setName("INCOME");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Annual Income");
					$field->setBlankValue("");
					$field->setBlankLabel("Select Income");
					$field->setTableName("JPROFILE:INCOME");
					$page_obj->setField(34,"a","",$field);

					$field=new Field(30);
					$field->setName("OCCUPATION");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Work Area");
					$field->setBlankValue("");
					$field->setBlankLabel("Select Occupation");
					$field->setTableName("JPROFILE:OCCUPATION");
					$page_obj->setField(30,"a","",$field);

					$field=new Field(35);
					$field->setName("EDU_LEVEL_NEW");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Highest Degree");
					$field->setBlankValue("");
					$field->setBlankLabel("Select Degree");
					$field->setTableName("JPROFILE:EDU_LEVEL_NEW");
					$page_obj->setField(35,"a","",$field);

					$field=new Field(7);
					$field->setName("PINCODE");
					$field->setFieldType("text");
					$field->setConstraintClass("pin");
					$field->setJsValidation("validate_pin");
					$field->setDependentField("10");
					$field->setLabel("Pincode");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PINCODE");
					$page_obj->setField(7,"a","",$field);
break;
  case "MR":
					$page_obj=new PageFields("MR");

					$field=new Field(13);
					$field->setName("MTONGUE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("* Mother Tongue/Community:");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("REG_LEAD:MTONGUE");
					$page_obj->setField(13,"","",$field);

					$field=new Field(5);
					$field->setName("DTOFBIRTH");
					$field->setFieldType("date");
					$field->setConstraintClass("dob");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("* Date of Birth of Boy / Girl:");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REG_LEAD:DTOFBIRTH");
					$page_obj->setField(5,"","",$field);

					$field=new Field(3);
					$field->setName("RELATIONSHIP");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("* I am looking for:");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("REG_LEAD:RELATIONSHIP");
					$page_obj->setField(3,"","",$field);

					$field=new Field(18);
					$field->setName("PHONE_MOB");
					$field->setFieldType("mobile");
					$field->setConstraintClass("mobile");
					$field->setJsValidation("validate_contact");
					$field->setDependentField("19");
					$field->setLabel("* Mobile Number:");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REG_LEAD:ISD-isd,PHONE_MOB-mobile");
					$page_obj->setField(18,"","",$field);

					$field=new Field(1);
					$field->setName("EMAIL");
					$field->setFieldType("text");
					$field->setConstraintClass("email");
					$field->setJsValidation("validate_email");
					$field->setDependentField("");
					$field->setLabel("* Email:");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REG_LEAD:EMAIL");
					$page_obj->setField(1,"","",$field);

					$field=new Field(16);
					$field->setName("SOURCE");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REG_LEAD:SOURCE");
					$page_obj->setField(16,"","",$field);
break;
  case "DP6":
					$page_obj=new PageFields("DP6");

					$field=new Field(66);
					$field->setName("F_INCOME");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check INCOME filter is set or ");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:INCOME");
					$page_obj->setField(66,"a","",$field);

					$field=new Field(65);
					$field->setName("F_MTONGUE");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check MTONGUE filter is set or");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:MTONGUE");
					$page_obj->setField(65,"a","",$field);

					$field=new Field(64);
					$field->setName("F_CITY_RES");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check CITY_RES filter is set o");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:CITY_RES");
					$page_obj->setField(64,"a","",$field);

					$field=new Field(63);
					$field->setName("F_COUNTRY_RES");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check SOUNTRY_RES filter is se");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:COUNTRY_RES");
					$page_obj->setField(63,"a","",$field);

					$field=new Field(62);
					$field->setName("F_CASTE");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check CASTE filter is set or n");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:CASTE");
					$page_obj->setField(62,"a","",$field);

					$field=new Field(61);
					$field->setName("F_RELIGION");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check RELIGION filter is set o");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:RELIGION");
					$page_obj->setField(61,"a","",$field);

					$field=new Field(60);
					$field->setName("F_MSTATUS");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check Mstatus filter is set or");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:MSTATUS");
					$page_obj->setField(60,"a","",$field);

					$field=new Field(59);
					$field->setName("F_AGE");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("check Age filter is set or not");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("FILTERS:AGE");
					$page_obj->setField(59,"a","",$field);
break;
  case "DP4":
					$page_obj=new PageFields("DP4");

					$field=new Field(58);
					$field->setName("NATURE_HANDICAP");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Nature of handicap :");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:NATURE_HANDICAP");
					$page_obj->setField(58,"b","",$field);

					$field=new Field(45);
					$field->setName("CONTACT");
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Your Contact Address :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:CONTACT");
					$page_obj->setField(45,"b","",$field);

					$field=new Field(48);
					$field->setName("SHOWADDRESS");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SHOWADDRESS");
					$page_obj->setField(48,"b","",$field);

					$field=new Field(47);
					$field->setName("SHOWMESSENGER");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SHOWMESSENGER");
					$page_obj->setField(47,"b","",$field);

					$field=new Field(49);
					$field->setName("MESSENGER_CHANNEL");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("messenger_channel");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Messenger Channel");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:MESSENGER_CHANNEL");
					$page_obj->setField(49,"b","",$field);

					$field=new Field(46);
					$field->setName("MESSENGER_ID");
					$field->setFieldType("text");
					$field->setConstraintClass("messenger_id");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Messenger ID :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:MESSENGER_ID");
					$page_obj->setField(46,"b","",$field);

					$field=new Field(42);
					$field->setName("HANDICAPPED");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Challenged :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:HANDICAPPED");
					$page_obj->setField(42,"b","",$field);

					$field=new Field(44);
					$field->setName("HIV");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("validate_radio");
					$field->setDependentField("");
					$field->setLabel("HIV :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:HIV");
					$page_obj->setField(44,"b","",$field);

					$field=new Field(43);
					$field->setName("BLOOD_GROUP");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Blood Group :");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:BLOOD_GROUP");
					$page_obj->setField(43,"b","",$field);

					$field=new Field(41);
					$field->setName("WORK_STATUS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Work Status :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:WORK_STATUS");
					$page_obj->setField(41,"a","",$field);

					$field=new Field(40);
					$field->setName("JOB_INFO");
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Professional Background :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:JOB_INFO");
					$page_obj->setField(40,"a","",$field);

					$field=new Field(39);
					$field->setName("EDUCATION");
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Educational Background :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:EDUCATION");
					$page_obj->setField(39,"a","",$field);
break;
  case "DP5":
					$page_obj=new PageFields("DP5");

					$field=new Field(55);
					$field->setName("P_HRS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_rupee");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_HRS");
					$page_obj->setField(55,"","",$field);

					$field=new Field(54);
					$field->setName("P_LRS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_LRS");
					$page_obj->setField(54,"","",$field);

					$field=new Field(56);
					$field->setName("P_LDS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Annual Income :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_LDS");
					$page_obj->setField(56,"","",$field);

					$field=new Field(53);
					$field->setName("P_HHEIGHT");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_height");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_HHEIGHT");
					$page_obj->setField(53,"","",$field);

					$field=new Field(52);
					$field->setName("P_LHEIGHT");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Height :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_LHEIGHT");
					$page_obj->setField(52,"","",$field);

					$field=new Field(57);
					$field->setName("P_HDS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_dollar");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_HDS");
					$page_obj->setField(57,"","",$field);

					$field=new Field(51);
					$field->setName("P_HAGE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("partner_age");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_HAGE");
					$page_obj->setField(51,"","",$field);

					$field=new Field(50);
					$field->setName("P_LAGE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Age :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPARTNER:P_LAGE");
					$page_obj->setField(50,"","",$field);

					$field=new Field(113);
					$field->setName("SPOUSE");
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Describe your : desired partner");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SPOUSE");
					$page_obj->setField(113,"","",$field);
break;
  case "MP2":
					$page_obj=new PageFields("MP2");

					$field=new Field(8);
					$field->setName("HEIGHT");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Height");
					$field->setBlankValue("");
					$field->setBlankLabel("Select Height");
					$field->setTableName("JPROFILE:HEIGHT");
					$page_obj->setField(8,"a","",$field);

					$field=new Field(9);
					$field->setName("COUNTRY_RES");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Country living in");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:COUNTRY_RES");
					$page_obj->setField(9,"a","",$field);

					$field=new Field(12);
					$field->setName("HAVECHILD");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("havechild");
					$field->setJsValidation("validate_select");
					$field->setDependentField("11");
					$field->setLabel("Have Children");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:HAVECHILD");
					$page_obj->setField(12,"a","",$field);

					$field=new Field(11);
					$field->setName("MSTATUS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("mstatus");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Marital Status");
					$field->setBlankValue("");
					$field->setBlankLabel("Select Marital Status");
					$field->setTableName("JPROFILE:MSTATUS");
					$page_obj->setField(11,"a","",$field);

					$field=new Field(15);
					$field->setName("CASTE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("caste");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("Caste");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:CASTE");
					$page_obj->setField(15,"a","",$field);

					$field=new Field(14);
					$field->setName("RELIGION");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("11");
					$field->setLabel("Religion");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:RELIGION");
					$page_obj->setField(14,"a","",$field);

					$field=new Field(114);
					$field->setName("REG_ID");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("REG_ID");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("NO_TABLE");
					$page_obj->setField(114,"","",$field);
break;
  case "DP2":
					$page_obj=new PageFields("DP2");


                                        $field=new Field(30);
                                        $field->setName("OCCUPATION");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_req");
                                        $field->setJsValidation("validate_select");
                                        $field->setDependentField("");
                                        $field->setLabel("Work Area<u>*</u> :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE:OCCUPATION");
                                        $page_obj->setField(30,"a","",$field);

                                        $field=new Field(34);
                                        $field->setName("INCOME");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_req");
                                        $field->setJsValidation("validate_select");
                                        $field->setDependentField("");
                                        $field->setLabel("Annual Income<u>*</u> :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE:INCOME");
                                        $page_obj->setField(34,"a","",$field);

                                        $field=new Field(35);
                                        $field->setName("EDU_LEVEL_NEW");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_req");
                                        $field->setJsValidation("validate_select");
                                        $field->setDependentField("");
                                        $field->setLabel("Highest Degree<u>*</u> :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE:EDU_LEVEL_NEW");
                                        $page_obj->setField(35,"a","",$field);

                                        $field=new Field(37);
                                        $field->setName("RES_STATUS");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Resident Status :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE:RES_STATUS");
                                        $page_obj->setField(37,"a","",$field);

					$field=new Field(38);
					$field->setName("YOURINFO");
					$field->setFieldType("textarea");
					$field->setConstraintClass("yourinfo");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Write about your<u>*</u> :<br/> Education<br/> Work<br/> Family");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:YOURINFO");
					$page_obj->setField(38,"a","",$field);

                                        $field=new Field(128);
                                        $field->setName("OTHER_UG_DEGREE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Other Graduation Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:OTHER_UG_DEGREE");
                                        $page_obj->setField(128,"","",$field);

                                        $field=new Field(127);
                                        $field->setName("OTHER_PG_DEGREE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Other PG Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:OTHER_PG_DEGREE");
                                        $page_obj->setField(127,"","",$field);

                                        $field=new Field(125);
                                        $field->setName("PG_COLLEGE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("PG College :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:PG_COLLEGE");
                                        $page_obj->setField(125,"","",$field);

                                        $field=new Field(124);
                                        $field->setName("EDUCATION");
                                        $field->setFieldType("textarea");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("");
                                        $field->setTableName("JPROFILE:EDUCATION");
                                        $page_obj->setField(124,"","",$field);

                                        $field=new Field(123);
                                        $field->setName("DEGREE_UG");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Graduation Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:UG_DEGREE");
                                        $page_obj->setField(123,"","",$field);

                                        $field=new Field(122);
                                        $field->setName("DEGREE_PG");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("PG Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:PG_DEGREE");
                                        $page_obj->setField(122,"","",$field);

                                        $field=new Field(121);
                                        $field->setName("COLLEGE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Graduation College :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:COLLEGE");
                                        $page_obj->setField(121,"","",$field);

break;
  case "DP1":
					$page_obj=new PageFields("DP1");

					$field=new Field(16);
					$field->setName("SOURCE");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SOURCE");
					$page_obj->setField(16,"e","",$field);

					$field=new Field(22);
					$field->setName("PROMO");
					$field->setFieldType("checkbox");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Send me third-party marketing ");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PROMO");
					$page_obj->setField(22,"e","",$field);

					$field=new Field(21);
					$field->setName("PHONE_RES");
					$field->setFieldType("landline");
					$field->setConstraintClass("landline");
					$field->setJsValidation("validate_contact");
					$field->setDependentField("");
					$field->setLabel("LandLine Number :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:ISD-isd,STD-std,PHONE_RES-landline");
					$page_obj->setField(21,"d","",$field);

					$field=new Field(15);
					$field->setName("CASTE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("caste");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("Caste :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:CASTE");
					$page_obj->setField(15,"c","",$field);
                                        
                                        $field=new Field(133);
					$field->setName("SECT_MUSLIM");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("sectMuslim");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("Caste :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:SECT");
					$page_obj->setField(133,"c","",$field);

					$field=new Field(14);
					$field->setName("RELIGION");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("11");
					$field->setLabel("Religion :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:RELIGION");
					$page_obj->setField(14,"c","",$field);

					$field=new Field(12);
					$field->setName("HAVECHILD");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("havechild");
					$field->setJsValidation("validate_select");
					$field->setDependentField("11");
					$field->setLabel("Have Children :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:HAVECHILD");
					$page_obj->setField(12,"c","",$field);

					$field=new Field(11);
					$field->setName("MSTATUS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("mstatus");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Marital Status :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:MSTATUS");
					$page_obj->setField(11,"c","",$field);

					$field=new Field(10);
					$field->setName("CITY_RES");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("9");
					$field->setLabel("City :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:CITY_RES");
					$page_obj->setField(10,"b","",$field);

					$field=new Field(9);
					$field->setName("COUNTRY_RES");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Country living in :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:COUNTRY_RES");
					$page_obj->setField(9,"b","",$field);

					$field=new Field(8);
					$field->setName("HEIGHT");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Height :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:HEIGHT");
					$page_obj->setField(8,"b","",$field);

					$field=new Field(1);
					$field->setName("EMAIL");
					$field->setFieldType("text");
					$field->setConstraintClass("email");
					$field->setJsValidation("validate_email");
					$field->setDependentField("");
					$field->setLabel("Email :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:EMAIL");
					$page_obj->setField(1,"a","",$field);

					$field=new Field(2);
					$field->setName("PASSWORD");
					$field->setFieldType("password");
					$field->setConstraintClass("password");
					$field->setJsValidation("validate_password");
					$field->setDependentField("");
					$field->setLabel("Password :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PASSWORD");
					$page_obj->setField(2,"a","",$field);

					$field=new Field(3);
					$field->setName("RELATIONSHIP");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Create Profile For :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:RELATION");
					$page_obj->setField(3,"a","",$field);

					$field=new Field(4);
					$field->setName("GENDER");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_radio");
					$field->setDependentField("3");
					$field->setLabel("Gender :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:GENDER");
					$page_obj->setField(4,"b","",$field);

					$field=new Field(5);
					$field->setName("DTOFBIRTH");
					$field->setFieldType("date");
					$field->setConstraintClass("dob");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Date of Birth :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:DTOFBIRTH");
					$page_obj->setField(5,"b","",$field);

					$field=new Field(13);
					$field->setName("MTONGUE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("Mother Tongue<u>*</u> :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:MTONGUE");
					$page_obj->setField(13,"c","",$field);

					$field=new Field(18);
					$field->setName("PHONE_MOB");
					$field->setFieldType("mobile");
					$field->setConstraintClass("mobile");
					$field->setJsValidation("validate_contact");
					$field->setDependentField("19");
					$field->setLabel("Mobile Number :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:ISD-isd,PHONE_MOB-mobile");
					$page_obj->setField(18,"d","",$field);

					$field=new Field(115);
					$field->setName("SHOWPHONE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SHOWPHONE_RES");
					$page_obj->setField(115,"","",$field);

					$field=new Field(116);
					$field->setName("SHOWMOBILE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SHOWPHONE_MOB");
					$page_obj->setField(116,"","",$field);

					$field=new Field(7);
					$field->setName("PINCODE");
					$field->setFieldType("text");
					$field->setConstraintClass("pin");
					$field->setJsValidation("validate_pin");
					$field->setDependentField("10");
					$field->setLabel("Pincode :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PINCODE");
					$page_obj->setField(7,"b","",$field);

					$field=new Field(23);
					$field->setName("TERMSANDCONDITIONS");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("validate_terms");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("NO_TABLE");
					$page_obj->setField(23,"e","",$field);

					$field=new Field(17);
					$field->setName("RECORD_ID");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("NO_TABLE");
					$page_obj->setField(17,"e","",$field);
break;
  case "MP1":
					$page_obj=new PageFields("MP1");

					$field=new Field(18);
					$field->setName("PHONE_MOB");
					$field->setFieldType("mobile");
					$field->setConstraintClass("mobile");
					$field->setJsValidation("validate_contact");
					$field->setDependentField("19");
					$field->setLabel("Mobile Number");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REGISTRATION_PAGE1:ISD-isd,PHONE_MOB-mobile");
					$page_obj->setField(18,"d","",$field);

					$field=new Field(13);
					$field->setName("MTONGUE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("Mother Tongue");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REGISTRATION_PAGE1:MTONGUE");
					$page_obj->setField(13,"c","",$field);

					$field=new Field(5);
					$field->setName("DTOFBIRTH");
					$field->setFieldType("date");
					$field->setConstraintClass("dob");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Date of Birth");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REGISTRATION_PAGE1:DTOFBIRTH");
					$page_obj->setField(5,"b","",$field);

					$field=new Field(4);
					$field->setName("GENDER");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_radio");
					$field->setDependentField("3");
					$field->setLabel("Gender");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REGISTRATION_PAGE1:GENDER");
					$page_obj->setField(4,"b","",$field);

					$field=new Field(3);
					$field->setName("RELATIONSHIP");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Create Profile For");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("REGISTRATION_PAGE1:RELATION");
					$page_obj->setField(3,"a","",$field);

					$field=new Field(1);
					$field->setName("EMAIL");
					$field->setFieldType("text");
					$field->setConstraintClass("email");
					$field->setJsValidation("validate_email");
					$field->setDependentField("");
					$field->setLabel("Your Email");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REGISTRATION_PAGE1:EMAIL");
					$page_obj->setField(1,"a","",$field);

					$field=new Field(2);
					$field->setName("PASSWORD");
					$field->setFieldType("password");
					$field->setConstraintClass("password");
					$field->setJsValidation("validate_password");
					$field->setDependentField("");
					$field->setLabel("Password");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REGISTRATION_PAGE1:PASSWORD");
					$page_obj->setField(2,"a","",$field);

					$field=new Field(16);
					$field->setName("SOURCE");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("REGISTRATION_PAGE1:SOURCE");
					$page_obj->setField(16,"a","",$field);
break;
  case "MP5":
					$page_obj=new PageFields("MP5");

					$field=new Field(118);
					$field->setName("NATIVE_COUNTRY");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select Country");
					$field->setTableName("NATIVE_PLACE:NATIVE_COUNTRY");
					$page_obj->setField(118,"","",$field);

					$field=new Field(120);
					$field->setName("NATIVE_CITY");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("native_place");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select City");
					$field->setTableName("NATIVE_PLACE:NATIVE_CITY");
					$page_obj->setField(120,"","",$field);

					$field=new Field(119);
					$field->setName("NATIVE_STATE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("native_place");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("Family based out of:");
					$field->setBlankValue("");
					$field->setBlankLabel("Select State");
					$field->setTableName("NATIVE_PLACE:NATIVE_STATE");
					$page_obj->setField(119,"","",$field);

					$field=new Field(84);
					$field->setName("ANCESTRAL_ORIGIN");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Specify City/Town :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:ANCESTRAL_ORIGIN");
					$page_obj->setField(84,"","",$field);

					$field=new Field(81);
					$field->setName("GOTHRA");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Gotra /Gothram :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:GOTHRA");
					$page_obj->setField(81,"","",$field);

					$field=new Field(72);
					$field->setName("FAMILY_BACK");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Father's Occupation :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:FAMILY_BACK");
					$page_obj->setField(72,"","",$field);

					$field=new Field(73);
					$field->setName("MOTHER_OCC");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Mother's Occupation :");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:MOTHER_OCC");
					$page_obj->setField(73,"","",$field);

					$field=new Field(74);
					$field->setName("T_BROTHER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Brother(s) :");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:T_BROTHER");
					$page_obj->setField(74,"","",$field);

					$field=new Field(75);
					$field->setName("M_BROTHER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:M_BROTHER");
					$page_obj->setField(75,"","",$field);

					$field=new Field(76);
					$field->setName("T_SISTER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Sister(s) :");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:T_SISTER");
					$page_obj->setField(76,"","",$field);

					$field=new Field(77);
					$field->setName("M_SISTER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:M_SISTER");
					$page_obj->setField(77,"","",$field);

					$field=new Field(79);
					$field->setName("FAMILYINFO");
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Write about your Family :");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILYINFO");
					$page_obj->setField(79,"","",$field);
break;
  case "MP4":
					$page_obj=new PageFields("MP4");

					$field=new Field(38);
					$field->setName("YOURINFO");
					$field->setFieldType("textarea");
					$field->setConstraintClass("yourinfo");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Write about your family background, education, work, interests & hobbies. (Provide atleast 100 characters)");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:YOURINFO");
					$page_obj->setField(38,"","",$field);
break;
  case "APP1":
					$page_obj=new PageFields("APP1");
                                        $field=new Field(82);
                                        $field->setName("MANGLIK");
                                        $field->setFieldType("radio");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Manglik :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("");
                                        $field->setTableName("JPROFILE:MANGLIK");
                                        $page_obj->setField(82,"","",$field);

					$field=new Field(3);
					$field->setName("RELATIONSHIP");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:RELATION");
					$page_obj->setField(3,"a","",$field);

					$field=new Field(4);
					$field->setName("GENDER");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_radio");
					$field->setDependentField("3");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:GENDER");
					$page_obj->setField(4,"a","",$field);

					$field=new Field(5);
					$field->setName("DTOFBIRTH");
					$field->setFieldType("date");
					$field->setConstraintClass("dob");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:DTOFBIRTH");
					$page_obj->setField(5,"a","",$field);

					$field=new Field(8);
					$field->setName("HEIGHT");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:HEIGHT");
					$page_obj->setField(8,"a","",$field);

					$field=new Field(9);
					$field->setName("COUNTRY_RES");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:COUNTRY_RES");
					$page_obj->setField(9,"a","",$field);

					$field=new Field(10);
					$field->setName("CITY_RES");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("9");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:CITY_RES");
					$page_obj->setField(10,"a","",$field);

					$field=new Field(7);
					$field->setName("PINCODE");
					$field->setFieldType("text");
					$field->setConstraintClass("pin");
					$field->setJsValidation("validate_pin");
					$field->setDependentField("10");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PINCODE");
					$page_obj->setField(7,"a","",$field);

					$field=new Field(11);
					$field->setName("MSTATUS");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("mstatus");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:MSTATUS");
					$page_obj->setField(11,"a","",$field);

					$field=new Field(12);
					$field->setName("HAVECHILD");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("havechild");
					$field->setJsValidation("validate_select");
					$field->setDependentField("11");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:HAVECHILD");
					$page_obj->setField(12,"a","",$field);

					$field=new Field(13);
					$field->setName("MTONGUE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:MTONGUE");
					$page_obj->setField(13,"a","",$field);

					$field=new Field(14);
					$field->setName("RELIGION");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("11");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:RELIGION");
					$page_obj->setField(14,"a","",$field);

					$field=new Field(15);
					$field->setName("CASTE");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("caste");
					$field->setJsValidation("validate_select");
					$field->setDependentField("14");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:CASTE");
					$page_obj->setField(15,"a","",$field);

					$field=new Field(35);
					$field->setName("EDU_LEVEL_NEW");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:EDU_LEVEL_NEW");
					$page_obj->setField(35,"a","",$field);

					$field=new Field(30);
					$field->setName("OCCUPATION");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:OCCUPATION");
					$page_obj->setField(30,"a","",$field);

					$field=new Field(34);
					$field->setName("INCOME");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_req");
					$field->setJsValidation("validate_select");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:INCOME");
					$page_obj->setField(34,"a","",$field);

					$field=new Field(1);
					$field->setName("EMAIL");
					$field->setFieldType("text");
					$field->setConstraintClass("email");
					$field->setJsValidation("validate_email");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:EMAIL");
					$page_obj->setField(1,"a","",$field);

					$field=new Field(2);
					$field->setName("PASSWORD");
					$field->setFieldType("password");
					$field->setConstraintClass("password");
					$field->setJsValidation("validate_password");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PASSWORD");
					$page_obj->setField(2,"a","",$field);

					$field=new Field(18);
					$field->setName("PHONE_MOB");
					$field->setFieldType("mobile");
					$field->setConstraintClass("mobile");
					$field->setJsValidation("validate_contact");
					$field->setDependentField("19");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:ISD-isd,PHONE_MOB-mobile");
					$page_obj->setField(18,"a","",$field);

					$field=new Field(16);
					$field->setName("SOURCE");
					$field->setFieldType("hidden");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:SOURCE");
					$page_obj->setField(16,"a","",$field);
                                        
                                        $field=new Field(128);
                                        $field->setName("OTHER_UG_DEGREE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Other Graduation Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:OTHER_UG_DEGREE");
                                        $page_obj->setField(128,"","",$field);

                                        $field=new Field(127);
                                        $field->setName("OTHER_PG_DEGREE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Other PG Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:OTHER_PG_DEGREE");
                                        $page_obj->setField(127,"","",$field);

                                        $field=new Field(125);
                                        $field->setName("PG_COLLEGE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("PG College :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:PG_COLLEGE");
                                        $page_obj->setField(125,"","",$field);

                                        $field=new Field(124);
                                        $field->setName("EDUCATION");
                                        $field->setFieldType("textarea");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("");
                                        $field->setTableName("JPROFILE:EDUCATION");
                                        $page_obj->setField(124,"","",$field);

                                        $field=new Field(123);
                                        $field->setName("DEGREE_UG");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Graduation Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:UG_DEGREE");
                                        $page_obj->setField(123,"","",$field);

                                        $field=new Field(122);
                                        $field->setName("DEGREE_PG");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("PG Degree :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:PG_DEGREE");
                                        $page_obj->setField(122,"","",$field);

                                        $field=new Field(121);
                                        $field->setName("COLLEGE");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Graduation College :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Please Select");
                                        $field->setTableName("JPROFILE_EDUCATION:COLLEGE");
                                        $page_obj->setField(121,"","",$field);
                                        
                                        $field=new Field(67);
                                        $field->setName("NAME_OF_USER");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Full Name :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("");
                                        $field->setTableName("NAME_OF_USER:NAME");
                                        $page_obj->setField(67,"","",$field);

          $field=new Field(91);
					$field->setName("HOROSCOPE_MATCH");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Horoscope match is necessary?");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:HOROSCOPE_MATCH");
					$page_obj->setField(91,"","",$field);

                                        $field=new Field(133);
                                        $field->setName("DISPLAYNAME");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setDependentField("");
                                        $field->setLabel("");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("");
                                        $field->setTableName("NAME_OF_USER:DISPLAY");
                                        $page_obj->setField(133,"","",$field);


                                        $field=new Field(132);
					$field->setName('SECT');
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setTableName("JPROFILE:SECT");
                                        $page_obj->setField(132,"","",$field);

                                        break;


case "APP2":
					$page_obj=new PageFields("APP2");

					$field=new Field(38);
					$field->setName("YOURINFO");
					$field->setFieldType("textarea");
					$field->setConstraintClass("yourinfo");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:YOURINFO");
					$page_obj->setField(38,"a","",$field);
break;
  case "CP":
					$page_obj=new PageFields("CP");

					$field=new Field(2);
					$field->setName("PASSWORD");
					$field->setFieldType("password");
					$field->setConstraintClass("password");
					$field->setJsValidation("validate_password");
					$field->setDependentField("");
					$field->setLabel("");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:PASSWORD");
					$page_obj->setField(2,"a","",$field);
break;
  case "APP3":
					$page_obj=new PageFields("APP3");

					$field=new Field(81);
					$field->setName("GOTHRA");
					$field->setFieldType("text");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Gotra");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:GOTHRA");
					$page_obj->setField(81,"","",$field);

					$field=new Field(79);
					$field->setName("FAMILYINFO");
					$field->setFieldType("textarea");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("About Family");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILYINFO");
					$page_obj->setField(79,"","",$field);

					$field=new Field(77);
					$field->setName("M_SISTER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Of which Married");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:M_SISTER");
					$page_obj->setField(77,"","",$field);

					$field=new Field(76);
					$field->setName("T_SISTER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Sisters");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:T_SISTER");
					$page_obj->setField(76,"","",$field);

					$field=new Field(75);
					$field->setName("M_BROTHER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Of which Married");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:M_BROTHER");
					$page_obj->setField(75,"","",$field);

					$field=new Field(74);
					$field->setName("T_BROTHER");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Brothers");
					$field->setBlankValue("");
					$field->setBlankLabel("Select");
					$field->setTableName("JPROFILE:T_BROTHER");
					$page_obj->setField(74,"","",$field);

					$field=new Field(73);
					$field->setName("MOTHER_OCC");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Mother's Occupation");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:MOTHER_OCC");
					$page_obj->setField(73,"","",$field);

					$field=new Field(72);
					$field->setName("FAMILY_BACK");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("string");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Father's Occupation");
					$field->setBlankValue("");
					$field->setBlankLabel("Please Select");
					$field->setTableName("JPROFILE:FAMILY_BACK");
					$page_obj->setField(72,"","",$field);

					$field=new Field(71);
					$field->setName("FAMILY_STATUS");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Family Status");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILY_STATUS");
					$page_obj->setField(71,"","",$field);

					$field=new Field(70);
					$field->setName("FAMILY_TYPE");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Family Type");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILY_TYPE");
					$page_obj->setField(70,"","",$field);

					$field=new Field(69);
					$field->setName("FAMILY_VALUES");
					$field->setFieldType("radio");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Family Values");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILY_VALUES");
					$page_obj->setField(69,"","",$field);

					$field=new Field(117);
					$field->setName("FAMILY_INCOME");
					$field->setFieldType("dropdown");
					$field->setConstraintClass("dropdown_not_req");
					$field->setJsValidation("");
					$field->setDependentField("");
					$field->setLabel("Family Income");
					$field->setBlankValue("");
					$field->setBlankLabel("");
					$field->setTableName("JPROFILE:FAMILY_INCOME");
					$page_obj->setField(117,"","",$field);
                                        $field=new Field(118);
                                        $field->setName("NATIVE_COUNTRY");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("dropdown_not_req");
                                        $field->setJsValidation("validate_select");
                                        $field->setDependentField("");
                                        $field->setLabel("");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Select Country");
                                        $field->setTableName("NATIVE_PLACE:NATIVE_COUNTRY");
                                        $page_obj->setField(118,"","",$field);

                                        $field=new Field(120);
                                        $field->setName("NATIVE_CITY");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("native_place");
                                        $field->setJsValidation("validate_select");
                                        $field->setDependentField("");
                                        $field->setLabel("");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Select City");
                                        $field->setTableName("NATIVE_PLACE:NATIVE_CITY");
                                        $page_obj->setField(120,"","",$field);

                                        $field=new Field(119);
                                        $field->setName("NATIVE_STATE");
                                        $field->setFieldType("dropdown");
                                        $field->setConstraintClass("native_place");
                                        $field->setJsValidation("validate_select");
                                        $field->setDependentField("");
                                        $field->setLabel("Family based out of:");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("Select State");
                                        $field->setTableName("NATIVE_PLACE:NATIVE_STATE");
                                        $page_obj->setField(119,"","",$field);

                                        $field=new Field(84);
                                        $field->setName("ANCESTRAL_ORIGIN");
                                        $field->setFieldType("text");
                                        $field->setConstraintClass("string");
                                        $field->setJsValidation("");
                                        $field->setDependentField("");
                                        $field->setLabel("Specify City/Town :");
                                        $field->setBlankValue("");
                                        $field->setBlankLabel("");
                                        $field->setTableName("JPROFILE:ANCESTRAL_ORIGIN");
                                        $page_obj->setField(84,"","",$field);
break;
}

				return($page_obj);
			}
			public static function getFieldArray($page){
				switch($page){
case 'DP3':
						$field_array=array('HIJAB_MARRIAGE','HIJAB','SUNNAH_CAP','SUNNAH_BEARD','UMRAH_HAJJ','QURAN','ZAKAT','FASTING','NAMAZ','MATHTHAB','HOROSCOPE_MATCH','CLEAN_SHAVEN','WEAR_TURBAN','TRIM_BEARD','CUT_HAIR','AMRITDHARI','NAKSHATRA','ANCESTRAL_ORIGIN','MANGLIK','RASHI','GOTHRA','SUBCASTE','FAMILYINFO','PARENT_CITY_SAME','M_SISTER','T_SISTER','M_BROTHER','T_BROTHER','MOTHER_OCC','FAMILY_BACK','FAMILY_STATUS','FAMILY_TYPE','FAMILY_VALUES','SAMPRADAY','NAME_OF_USER','RECORD_ID','NATIVE_CITY','WORKING_MARRIAGE','SPEAK_URDU','DIOCESE','BAPTISED','READ_BIBLE','OFFER_TITHE','SPREADING_GOSPEL','ZARATHUSHTRI','PARENTS_ZARATHUSHTRI','NATIVE_COUNTRY','NATIVE_STATE');
					break;
case 'MP3':
						$field_array=array('CITY_RES','INCOME','OCCUPATION','EDU_LEVEL_NEW','PINCODE');
					break;
case 'MR':
						$field_array=array('MTONGUE','DTOFBIRTH','RELATIONSHIP','PHONE_MOB','EMAIL','SOURCE');
					break;
case 'DP6':
						$field_array=array('F_INCOME','F_MTONGUE','F_CITY_RES','F_COUNTRY_RES','F_CASTE','F_RELIGION','F_MSTATUS','F_AGE');
					break;
case 'DP4':
						$field_array=array('NATURE_HANDICAP','CONTACT','SHOWADDRESS','SHOWMESSENGER','MESSENGER_CHANNEL','MESSENGER_ID','HANDICAPPED','HIV','BLOOD_GROUP','WORK_STATUS','JOB_INFO','EDUCATION');
					break;
case 'DP5':
						$field_array=array('P_HRS','P_LRS','P_LDS','P_HHEIGHT','P_LHEIGHT','P_HDS','P_HAGE','P_LAGE','SPOUSE');
					break;
case 'MP2':
						$field_array=array('HEIGHT','COUNTRY_RES','HAVECHILD','MSTATUS','CASTE','RELIGION','REG_ID');
					break;
case 'DP2':
case 'DP2':
                                                $field_array=array('EDU_LEVEL_NEW','OCCUPATION','INCOME','RES_STATUS','OTHER_UG_DEGREE','YOURINFO','OTHER_PG_DEGREE','PG_COLLEGE','EDUCATION','DEGREE_UG','DEGREE_PG','COLLEGE');
                                        break;

case 'DP1':
						$field_array=array('SOURCE','PROMO','PHONE_RES','CASTE','RELIGION','SECT_MUSLIM','HAVECHILD','MSTATUS','CITY_RES','COUNTRY_RES','HEIGHT','EMAIL','PASSWORD','RELATIONSHIP','GENDER','DTOFBIRTH','MTONGUE','PHONE_MOB','SHOWPHONE','SHOWMOBILE','PINCODE','TERMSANDCONDITIONS','RECORD_ID');
					break;
case 'MP1':
						$field_array=array('PHONE_MOB','MTONGUE','DTOFBIRTH','GENDER','RELATIONSHIP','EMAIL','PASSWORD','SOURCE');
					break;
case 'MP5':
						$field_array=array('NATIVE_COUNTRY','NATIVE_CITY','NATIVE_STATE','ANCESTRAL_ORIGIN','GOTHRA','FAMILY_BACK','MOTHER_OCC','T_BROTHER','M_BROTHER','T_SISTER','M_SISTER','FAMILYINFO');
					break;
case 'MP4':
						$field_array=array('YOURINFO');
					break;
case 'APP1':
						$field_array=array('RELATIONSHIP','GENDER','DTOFBIRTH','HEIGHT','COUNTRY_RES','CITY_RES','PINCODE','MSTATUS','HAVECHILD','MTONGUE','RELIGION','CASTE','EDU_LEVEL_NEW','OCCUPATION','INCOME','EMAIL','PASSWORD','PHONE_MOB','SOURCE','OTHER_UG_DEGREE','OTHER_PG_DEGREE','PG_COLLEGE','EDUCATION','DEGREE_UG','DEGREE_PG','COLLEGE','NAME_OF_USER','HOROSCOPE_MATCH',"MANGLIK");
					break;
case 'APP2':
						$field_array=array('YOURINFO');
					break;
case 'CP':
						$field_array=array('PASSWORD');
					break;
case 'APP3':
						$field_array=array('GOTHRA','FAMILYINFO','M_SISTER','T_SISTER','M_BROTHER','T_BROTHER','MOTHER_OCC','FAMILY_BACK','FAMILY_STATUS','FAMILY_TYPE','FAMILY_VALUES','FAMILY_INCOME',"NATIVE_COUNTRY","NATIVE_STATE","NATIVE_CITY","NATIVE_CITY","NATIVE_STATE","NATIVE_COUNTRY","ANCESTRAL_ORIGIN");
					break;
}
					return $field_array;
				}}
