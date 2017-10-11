<?php
$viewdefs ['Leads'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'preForm' => '<form name="vcard" action="index.php"><input type="hidden" name="entryPoint" value="vCard"><input type="hidden" name="contact_id" value="{$fields.id.value}"><input type="hidden" name="module" value="Leads"></form>',
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_CONVERTLEAD_TITLE}" accessKey="{$MOD.LBL_CONVERTLEAD_BUTTON_KEY}" type="button" class="button" onClick="document.location=\'index.php?module=Leads&action=ConvertLead&record={$fields.id.value}\'" name="convert" value="{$MOD.LBL_CONVERTLEAD}">',
          ),
          4 => 
          array (
            'customCode' => '<input title="{$APP.LBL_DUP_MERGE}" accessKey="M" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Step1\'; this.form.module.value=\'MergeRecords\';" type="submit" name="Merge" value="{$APP.LBL_DUP_MERGE}">',
          ),
          5 => 
          array (
            'customCode' => '<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Leads\'; this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">',
          ),
          6 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_REGISTER_LEAD}" class="button" onclick="this.form.return_module.value=\'Leads\';this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\';this.form.action.value=\'Register_Lead\';this.form.module.value=\'Leads\';" name="Register Lead" value="{$MOD.LBL_REGISTER_LEAD_VALUE}" type="submit" {if $HAS_DATA_ENTRY_RIGHTS ne 1}disabled="disabled"{/if}>',
          ),
        ),
        'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
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
          'file' => 'modules/Leads/Lead.js',
        ),
      ),
      'useTabs' => false,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'disposition_c',
            'studio' => 'visible',
            'label' => 'LBL_DISPOSITION_C',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status_comments_c',
            'studio' => 'visible',
            'label' => 'LBL_STATUS_COMMENTS_C',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'jsprofileid_c',
            'label' => 'LBL_JSPROFILEID',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'enquirer_email_id_c',
            'label' => 'LBL_ENQUIRER_EMAIL_ID',
          ),
          1 => 
          array (
            'name' => 'email1',
            'label' => 'LBL_EMAIL_ADDRESS',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'enquirer_mobile_no_c',
            'label' => 'LBL_ENQUIRER_MOBILE_NO_C',
          ),
          1 => 
          array (
            'name' => 'phone_mobile',
            'comment' => 'Mobile phone number of the contact',
            'label' => 'LBL_MOBILE_PHONE',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'std_enquirer_c',
            'label' => 'LBL_STD_ENQUIRER',
          ),
          1 => 
          array (
            'name' => 'enquirer_landline_c',
            'label' => 'LBL_ENQUIRER_LANDLINE',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'std_c',
            'label' => 'LBL_STD',
          ),
          1 => 
          array (
            'name' => 'phone_home',
            'label' => 'LBL_HOME_PHONE',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'p_o_box_no_c',
            'label' => 'LBL_P_O_BOX_NO',
          ),
          1 => 
          array (
            'name' => 'city_c',
            'label' => 'LBL_CITY',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS',
          ),
          1 => 
          array (
            'name' => 'primary_address_postalcode',
            'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'assistant',
            'label' => 'LBL_ASSISTANT',
          ),
          1 => 
          array (
            'name' => 'last_name',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'posted_by_c',
            'label' => 'LBL_POSTED_BY',
          ),
          1 => 
          array (
            'name' => 'age_c',
            'label' => 'LBL_AGE',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'marital_status_c',
            'label' => 'LBL_MARITAL_STATUS',
          ),
          1 => 
          array (
            'name' => 'have_children_c',
            'label' => 'LBL_HAVE_CHILDREN_C',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'gender_c',
            'label' => 'LBL_GENDER',
          ),
          1 => 
          array (
            'name' => 'height_c',
            'label' => 'LBL_HEIGHT',
          ),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'education_c',
            'label' => 'LBL_EDUCATION',
          ),
          1 => 
          array (
            'name' => 'occupation_c',
            'label' => 'LBL_OCCUPATION',
          ),
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'income_c',
            'label' => 'LBL_INCOME',
          ),
          1 => 
          array (
            'name' => 'manglik_c',
            'label' => 'LBL_MANGLIK',
          ),
        ),
        16 => 
        array (
          0 => 
          array (
            'name' => 'religion_c',
            'label' => 'LBL_RELIGION',
          ),
          1 => 
          array (
            'name' => 'caste_c',
            'label' => 'LBL_CASTE',
          ),
        ),
        17 => 
        array (
          0 => 
          array (
            'name' => 'mother_tongue_c',
            'label' => 'LBL_MOTHER_TONGUE',
          ),
          1 => 
          array (
            'name' => 'date_birth_c',
            'label' => 'LBL_DATE_BIRTH',
          ),
        ),
        18 => 
        array (
          0 => 
          array (
            'name' => 'new_mtongue_c',
            'label' => 'LBL_NEW_MTONGUE',
          ),
          1 => 
          array (
            'name' => 'subcaste_c',
            'label' => 'LBL_SUBCASTE',
          ),
        ),
        19 => 
        array (
          0 => 
          array (
            'name' => 'smoke_c',
            'studio' => 'visible',
            'label' => 'LBL_SMOKE_C',
          ),
          1 => 
          array (
            'name' => 'drink_c',
            'studio' => 'visible',
            'label' => 'LBL_DRINK_C',
          ),
        ),
        20 => 
        array (
          0 => 
          array (
            'name' => 'gothra_c',
            'label' => 'LBL_GOTHRA',
          ),
          1 => 
          array (
            'name' => 'lead_attribute_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_ATTRIBUTE',
          ),
        ),
        21 => 
        array (
          0 => 
          array (
            'name' => 'about_the_profile_c',
            'studio' => 'visible',
            'label' => 'LBL_ABOUT_THE_PROFILE',
          ),
          1 => 
          array (
            'name' => 'hobbies_c',
            'studio' => 'visible',
            'label' => 'LBL_HOBBIES',
          ),
        ),
        22 => 
        array (
          0 => 
          array (
            'name' => 'response_delivery_mode_c',
            'studio' => 'visible',
            'label' => 'LBL_RESPONSE_DELIVERY_MODE',
          ),
          1 => 
          array (
            'name' => 'internet_frequency_c',
            'studio' => 'visible',
            'label' => 'LBL_INTERNET_FREQUENCY',
          ),
        ),
        23 => 
        array (
          0 => 
          array (
            'name' => 'internet_access_c',
            'studio' => 'visible',
            'label' => 'LBL_INTERNET_ACCESS',
          ),
          1 => 
          array (
            'name' => 'user_of_competitor_c',
            'studio' => 'visible',
            'label' => 'LBL_USER_OF_COMPETITOR',
          ),
        ),
        24 => 
        array (
          0 => 
          array (
            'name' => 'school_name_c',
            'label' => 'LBL_SCHOOL_NAME',
          ),
          1 => 
          array (
            'name' => 'college_name_c',
            'label' => 'LBL_COLLEGE_NAME',
          ),
        ),
        25 => 
        array (
          0 => 
          array (
            'name' => 'work_c',
            'studio' => 'visible',
            'label' => 'LBL_WORK_C',
          ),
          1 => 
          array (
            'name' => 'current_employer_c',
            'label' => 'LBL_CURRENT_EMPLOYER',
          ),
        ),
        26 => 
        array (
          0 => 
          array (
            'name' => 'father_occupation_c',
            'studio' => 'visible',
            'label' => 'LBL_FATHER_OCCUPATION',
          ),
          1 => 
          array (
            'name' => 'decsion_maker_c',
            'studio' => 'visible',
            'label' => 'LBL_DECSION_MAKER',
          ),
        ),
        27 => 
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
        28 => 
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
        29 => 
        array (
          0 => 
          array (
            'name' => 'forward_match_count_c',
            'label' => 'LBL_FORWARD_MATCH_COUNT_C',
          ),
          1 => 
          array (
            'name' => 'file_attachment_c',
            'label' => 'LBL_FILE_ATTACHMENT_C',
          ),
        ),
        30 => 
        array (
          0 => 
          array (
            'name' => 'photo_c',
            'label' => 'LBL_PHOTO_C',
          ),
          1 => 
          array (
            'name' => 'verification_doc_c',
            'label' => 'LBL_VERIFICATION_DOC_C',
          ),
        ),
        31 => 
        array (
          0 => 
          array (
            'name' => 'horoscope_dob_c',
            'label' => 'LBL_HOROSCOPE_DOB',
          ),
          1 => 
          array (
            'name' => 'horoscope_place_of_birth_c',
            'label' => 'LBL_HOROSCOPE_PLACE_OF_BIRTH',
          ),
        ),
        32 => 
        array (
          0 => 
          array (
            'name' => 'horoscope_c',
            'label' => 'LBL_HOROSCOPE_C',
          ),
          1 => 
          array (
            'name' => 'intention_time_c',
            'studio' => 'visible',
            'label' => 'LBL_INTENTION_TIME',
          ),
        ),
        33 => 
        array (
          0 => 
          array (
            'name' => 'lead_zone_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_ZONE',
          ),
          1 => 
          array (
            'name' => 'source_c',
            'studio' => 'visible',
            'label' => 'LBL_SOURCE',
          ),
        ),
        34 => 
        array (
          0 => 
          array (
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ),
          1 => 
          array (
            'name' => 'lead_source',
            'label' => 'LBL_LEAD_SOURCE',
          ),
        ),
        35 => 
        array (
          0 => 
          array (
            'name' => 'type_c',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'edition_date_c',
            'label' => 'LBL_EDITION_DATE',
          ),
        ),
        36 => 
        array (
          0 => 
          array (
            'name' => 'expect_pay_in_c',
            'label' => 'LBL_EXPECT_PAY_IN',
          ),
          1 => 
          array (
            'name' => 'jsprofileid_c',
            'label' => 'LBL_JSPROFILEID',
          ),
        ),
        37 => 
        array (
          0 => 
          array (
            'name' => 'strength_c',
            'label' => 'LBL_STRENGTH',
          ),
          1 => 
          array (
            'name' => 'reg_followup_count_c',
            'label' => 'LBL_REG_FOLLOWUP_COUNT_C',
          ),
        ),
        38 => 
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
        39 => 
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
        40 => 
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
        41 => 
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
        42 => 
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
        43 => 
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
      ),
    ),
  ),
);
?>
