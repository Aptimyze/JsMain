<?php
$viewdefs ['Leads'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
          1 => '<input type="hidden" name="account_id" value="{if isset($smarty.request.account_id)}{$smarty.request.account_id}{else}{$bean->account_id}{/if}">',
          2 => '<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
          3 => '<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">',
        ),
        'buttons' => 
        array (
          0 => 'SAVEL',
          1 => 'CANCEL',
        ),
        'enctype' => 'multipart/form-data',
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/modules/Leads/leads_cstm.js',
        ),
	1=>
        array(
          'file' => 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
        ),
	2=>
	array(
	  'file' => '../js/autoSuggest_jq_js.js',
	),
      ),
      'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim} </script>',
      'useTabs' => false,
    ),
    'panels' => 
    array (
      'lbl_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'email1',
            'label' => 'LBL_EMAIL_ADDRESS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'enquirer_email_id_c',
            'label' => 'LBL_ENQUIRER_EMAIL_ID',
            'customCode' => '<input type="text" tabindex="101" title="" value="{$fields.enquirer_email_id_c.value}" maxlength="50" size="30" id="enquirer_email_id_c" name="enquirer_email_id_c" onblur="checkEmail(\'enquirer_email_id_c\',this,1)"/><div name="result4" id="result4"></div>',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'phone_mobile',
            'label' => 'LBL_MOBILE_PHONE',
            'customCode' => '<input id="phone_mobile" type="text" tabindex="2" title="" value="{$fields.phone_mobile.value}" maxlength="25" size="30" name="phone_mobile" onblur="xmlhttpPost(\'phone_mobile\',this)"/><div name="result1" id="result1"></div><input type="hidden" id="phone_mobile_hd">',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'enquirer_mobile_no_c',
            'label' => 'LBL_ENQUIRER_MOBILE_NO_C',
            'customCode' => '<input id="enquirer_mobile_no_c" type="text" tabindex="2" title="" value="{$fields.enquirer_mobile_no_c.value}" maxlength="25" size="30" name="enquirer_mobile_no_c" onblur="xmlhttpPost(\'enquirer_mobile_no_c\',this)"/><div name="result3" id="result3"></div><input type="hidden" id="enquirer_mobile_no_c_hd">',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'isd_c',
            'label' => 'LBL_ISD',
            'customCode' => '<input type="text" tabindex="106" title="" value="{if $fields.isd_c.value}{$fields.isd_c.value}{else}+91{/if}" maxlength="5" size="10" id="isd_c" name="isd_c"/>',
          ),
          1 => '',
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'std_c',
            'label' => 'LBL_STD',
            'customCode' => '<input id="std_c" type="text" tabindex="3" title="" value="{$fields.std_c.value}" maxlength="25" size="10" name="std_c" onblur="xmlhttpPost(\'phone_home\',\'std\',this)"/>',
          ),
          1 => 
          array (
            'name' => 'phone_home',
            'label' => 'LBL_HOME_PHONE',
            'customCode' => '<input id="phone_home" type="text" tabindex="4" title="" value="{$fields.phone_home.value}" maxlength="25" size="30" name="phone_home" onblur="xmlhttpPost(\'phone_home\',this)"/><div name="result" id="result"></div>',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'isd_enquirer_c',
            'label' => 'LBL_ISD_ENQUIRER',
            'customCode' => '<input type="text" tabindex="106" title="" value="{if $fields.isd_enquirer_c.value}{$fields.isd_enquirer_c.value}{else}+91{/if}" maxlength="5" size="10" id="isd_enquirer_c" name="isd_enquirer_c"/>',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'std_enquirer_c',
            'label' => 'LBL_STD_ENQUIRER',
            'customCode' => '<input id="std_enquirer_c" type="text" tabindex="3" title="" value="{$fields.std_enquirer_c.value}" maxlength="25" size="10" name="std_enquirer_c" onblur="xmlhttpPost(\'enquirer_landline_c\',this)"/>',
          ),
          1 => 
          array (
            'name' => 'enquirer_landline_c',
            'label' => 'LBL_ENQUIRER_LANDLINE',
            'customCode' => '<input type="text" tabindex="113" title="" value="{$fields.enquirer_landline_c.value}" maxlength="15" size="30" id="enquirer_landline_c" name="enquirer_landline_c" onblur="xmlhttpPost(\'enquirer_landline_c\',this)"/><div name="result2" id="result2"></div>',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'p_o_box_no_c',
            'label' => 'LBL_P_O_BOX_NO',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
            'displayParams' => 
            array (
              'javascript' => 'onchange="changeDisposition()"',
            ),
          ),
          1 => 
          array (
            'name' => 'disposition_c',
            'studio' => 'visible',
            'label' => 'LBL_DISPOSITION_C',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'status_comments_c',
            'studio' => 'visible',
            'label' => 'LBL_STATUS_COMMENTS_C',
            'customCode' => '<textarea tabindex="205" title="" cols="20" rows="4" name="status_comments_c" id="status_comments_c" onblur="checkStatusComments()">{$fields.status_comments_c.value}</textarea>',
          ),
          1 => '',
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'lead_zone_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_ZONE',
          ),
        ),
        12 => 
        array (
          0 => '',
          1 => '',
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS_STREET',
          ),
          1 => '',
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_postalcode',
            'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
          ),
          1 => '',
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'city_c',
            'label' => 'LBL_CITY',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'response_delivery_mode_c',
            'studio' => 'visible',
            'label' => 'LBL_RESPONSE_DELIVERY_MODE',
            'displayParams' => 
            array (
              'javascript' => 'onchange="checkDeliveryMode()"',
            ),
          ),
          1 => '',
        ),
        17 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
      'lbl_contact_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'lead_source',
            'label' => 'LBL_LEAD_SOURCE',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'posted_by_c',
            'label' => 'LBL_POSTED_BY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'assistant',
            'label' => 'LBL_ASSISTANT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'last_name',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'date_birth_c',
            'label' => 'LBL_DATE_BIRTH',
          ),
          1 => 
          array (
            'name' => 'age_c',
            'label' => 'LBL_AGE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'gender_c',
            'label' => 'LBL_GENDER',
          ),
          1 => 
          array (
            'name' => 'have_photo_c',
            'label' => 'LBL_HAVE_PHOTO',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'height_c',
            'label' => 'LBL_HEIGHT',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'marital_status_c',
            'label' => 'LBL_MARITAL_STATUS',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'have_children_c',
            'label' => 'LBL_HAVE_CHILDREN_C',
          ),
          1 => '',
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'religion_c',
            'label' => 'LBL_RELIGION',
            'displayParams' => 
            array (
              'javascript' => 'onchange="initData()"',
            ),
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'mother_tongue_c',
            'label' => 'LBL_MOTHER_TONGUE',
          ),
          1 => 
          array (
            'name' => 'new_mtongue_c',
            'label' => 'LBL_NEW_MTONGUE',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'caste_c',
            'label' => 'LBL_CASTE',
          ),
          1 => 
          array (
            'name' => 'subcaste_c',
            'label' => 'LBL_SUBCASTE',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'education_c',
            'label' => 'LBL_EDUCATION',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'occupation_c',
            'label' => 'LBL_OCCUPATION',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'income_c',
            'label' => 'LBL_INCOME',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'manglik_c',
            'label' => 'LBL_MANGLIK',
          ),
          1 => 
          array (
            'name' => 'have_email_c',
            'label' => 'LBL_HAVE_EMAIL',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'gothra_c',
            'label' => 'LBL_GOTHRA',
          ),
          1 => '',
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'smoke_c',
            'studio' => 'visible',
            'label' => 'LBL_SMOKE_C',
          ),
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'drink_c',
            'studio' => 'visible',
            'label' => 'LBL_DRINK_C',
          ),
          1 => '',
        ),
        19 => 
        array (
          0 => 
          array (
            'name' => 'school_name_c',
            'label' => 'LBL_SCHOOL_NAME',
            'customCode' => '<input type="text" tabindex="157" title="" maxlength="100" size="30" id="school_name_c" name="school_name_c" value="{$fields.school_name_c.value}"/><span id="gau" style="position:relative;clear:all"/>',
          ),
          1 => '',
        ),
        20 => 
        array (
          0 => 
          array (
            'name' => 'college_name_c',
            'label' => 'LBL_COLLEGE_NAME',
            'customCode' => '<input type="text" tabindex="159" title="" maxlength="100" size="30" id="college_name_c" name="college_name_c" value="{$fields.college_name_c.value}"/><span id="anu" style="position:relative;clear:all"/>',
          ),
          1 => '',
        ),
        21 => 
        array (
          0 => 
          array (
            'name' => 'current_employer_c',
            'label' => 'LBL_CURRENT_EMPLOYER',
            'customCode' => '<input type="text" tabindex="161" title="" maxlength="100" size="30" id="current_employer_c" name="current_employer_c" value="{$fields.current_employer_c.value}"/><span id="organ" style="position:relative;clear:all"/>',
          ),
          1 => '',
        ),
        22 => 
        array (
          0 => 
          array (
            'name' => 'work_c',
            'studio' => 'visible',
            'label' => 'LBL_WORK_C',
          ),
          1 => '',
        ),
        23 => 
        array (
          0 => 
          array (
            'name' => 'father_occupation_c',
            'studio' => 'visible',
            'label' => 'LBL_FATHER_OCCUPATION',
          ),
          1 => '',
        ),
        24 => 
        array (
          0 => 
          array (
            'name' => 'hobbies_c',
            'studio' => 'visible',
            'label' => 'LBL_HOBBIES',
          ),
          1 => '',
        ),
        25 => 
        array (
          0 => 
          array (
            'name' => 'no_of_brothers_c',
            'studio' => 'visible',
            'label' => 'LBL_NO_OF_BROTHERS',
          ),
          1 => 
          array (
            'name' => 'no_of_brothers_married_c',
            'studio' => 'visible',
            'label' => 'LBL_NO_OF_BROTHERS_MARRIED',
          ),
        ),
        26 => 
        array (
          0 => 
          array (
            'name' => 'no_of_sisters_c',
            'studio' => 'visible',
            'label' => 'LBL_NO_OF_SISTERS',
          ),
          1 => 
          array (
            'name' => 'no_of_sisters_married_c',
            'studio' => 'visible',
            'label' => 'LBL_NO_OF_SISTERS_MARRIED',
          ),
        ),
        27 => 
        array (
          0 => 
          array (
            'name' => 'user_of_competitor_c',
            'studio' => 'visible',
            'label' => 'LBL_USER_OF_COMPETITOR',
          ),
        ),
        28 => 
        array (
          0 => 
          array (
            'name' => 'lead_attribute_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_ATTRIBUTE',
          ),
          1 => '',
        ),
      ),
      'lbl_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'source_c',
            'studio' => 'visible',
            'label' => 'LBL_SOURCE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'opt_in_c',
            'label' => 'LBL_OPT_IN_C',
          ),
          1 => '',
        ),
      ),
      'lbl_editview_panel5' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'type_c',
            'studio' => 'visible',
            'label' => 'LBL_TYPE',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'edition_date_c',
            'label' => 'LBL_EDITION_DATE',
          ),
          1 => '',
        ),
      ),
      'lbl_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'photo_c',
            'label' => 'LBL_PHOTO_C',
            'customCode' => '<input type="file" id="photo_c" name="photo_c">',
          ),
          1 => 
          array (
            'name' => 'delete_photo_c',
            'label' => 'LBL_DELETE_PHOTO_C',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'decsion_maker_c',
            'studio' => 'visible',
            'label' => 'LBL_DECSION_MAKER',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'verification_doc_c',
            'label' => 'LBL_VERIFICATION_DOC_C',
            'customCode' => '<input type="file" id="verification_doc_c" name="verification_doc_c">',
          ),
          1 => 
          array (
            'name' => 'delete_verification_doc_c',
            'label' => 'LBL_DELETE_VERIFICATION_DOC_C',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'file_attachment_c',
            'label' => 'LBL_FILE_ATTACHMENT_C',
            'customCode' => '<input type="file" id="file_attachment_c" name="file_attachment_c">',
          ),
          1 => 
          array (
            'name' => 'delete_file_attachment_c',
            'label' => 'LBL_DELETE_FILE_ATTACHMENT_C',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'strength_c',
            'label' => 'LBL_STRENGTH',
          ),
          1 => 
          array (
            'name' => 'expect_pay_in_c',
            'label' => 'LBL_EXPECT_PAY_IN',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ),
          1 => 
          array (
            'name' => 'response_ad_c',
            'label' => 'LBL_RESPONSE_AD',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'about_the_profile_c',
            'studio' => 'visible',
            'label' => 'LBL_ABOUT_THE_PROFILE',
            'customCode' => '<textarea tabindex="191" title="" cols="60" rows="4" name="about_the_profile_c" id="about_the_profile_c"  onblur="aboutProfileLimitCheck();">{$fields.about_the_profile_c.value}</textarea>',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'horoscope_dob_time_c',
            'label' => 'LBL_HOROSCOPE_DOB_TIME_C',
          ),
          1 => '',
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'horoscope_place_of_birth_c',
            'label' => 'LBL_HOROSCOPE_PLACE_OF_BIRTH',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'horoscope_c',
            'label' => 'LBL_HOROSCOPE_C',
            'customCode' => '<input type="file" id="horoscope_c" name="horoscope_c">',
          ),
          1 => 
          array (
            'name' => 'delete_horoscope_c',
            'label' => 'LBL_DELETE_HOROSCOPE_C',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'internet_access_c',
            'studio' => 'visible',
            'label' => 'LBL_INTERNET_ACCESS',
          ),
          1 => 
          array (
            'name' => 'internet_frequency_c',
            'studio' => 'visible',
            'label' => 'LBL_INTERNET_FREQUENCY',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'competition_site_1_c',
            'studio' => 'visible',
            'label' => 'LBL_COMPETITION_SITE_1_C',
          ),
          1 => 
          array (
            'name' => 'competition_id_1_c',
            'label' => 'LBL_COMPETITION_ID_1_C',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'competition_site_2_c',
            'studio' => 'visible',
            'label' => 'LBL_COMPETITION_SITE_2_C',
          ),
          1 => 
          array (
            'name' => 'competition_id_2_c',
            'label' => 'LBL_COMPETITION_ID_2_C',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'competition_site_3_c',
            'studio' => 'visible',
            'label' => 'LBL_COMPETITION_SITE_3_C',
          ),
          1 => 
          array (
            'name' => 'competition_id_3_c',
            'label' => 'LBL_COMPETITION_ID_3_C',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'competition_site_4_c',
            'studio' => 'visible',
            'label' => 'LBL_COMPETITION_SITE_4_C',
          ),
          1 => 
          array (
            'name' => 'competition_id_4_c',
            'label' => 'LBL_COMPETITION_ID_4_C',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'competition_site_5_c',
            'studio' => 'visible',
            'label' => 'LBL_COMPETITION_SITE_5_C',
          ),
          1 => 
          array (
            'name' => 'competition_id_5_c',
            'label' => 'LBL_COMPETITION_ID_5_C',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'competition_site_6_c',
            'studio' => 'visible',
            'label' => 'LBL_COMPETITION_SITE_6_C',
          ),
          1 => 
          array (
            'name' => 'competition_id_6_c',
            'label' => 'LBL_COMPETITION_ID_6_C',
          ),
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => '',
        ),
      ),
    ),
  ),
);
?>
