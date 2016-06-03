<?php
// created: 2010-07-27 13:20:29
$searchdefs['Leads'] = array (
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 
      array (
        'width' => '10%',
        'label' => 'LBL_STARTDATE',
        'default' => true,
        'name' => 'startdate',
        'type' => 'date',
      ),
      1 => 
      array (
        'width' => '10%',
        'label' => 'LBL_ENDDATE',
        'default' => true,
        'name' => 'enddate',
        'type' => 'date',
      ),
      2 => 
      array (
        'name' => 'lead_source',
        'label' => 'LBL_LEAD_SOURCE',
        'default' => true,
      ),
      3 => 
      array (
        'width' => '10%',
        'label' => 'LBL_STRENGTH',
        'default' => true,
        'name' => 'strength_c',
      ),
      4 => 
      array (
        'width' => '10%',
        'label' => 'LBL_STATUS',
        'default' => true,
        'name' => 'status',
      ),
      5 => 
      array (
        'width' => '10%',
        'label' => 'LBL_CAMPAIGN',
        'default' => true,
        'name' => 'campaign_name',
      ),
      6 => 
      array (
        'width' => '10%',
        'label' => 'LBL_CITY',
        'default' => true,
        'name' => 'city_c',
      ),
      7 => 
      array (
        'width' => '10%',
        'label' => 'LBL_HAVE_EMAIL',
        'default' => true,
        'name' => 'have_email_c',
      ),
      8 => 
      array (
        'width' => '10%',
        'label' => 'LBL_ANY_EMAIL',
        'default' => true,
        'name' => 'email',
        'type' => 'name',
      ),
    ),
    'advanced_search' => 
    array (
      0 => 
      array (
        'width' => '10%',
        'label' => 'LBL_HOME_PHONE',
        'default' => true,
        'name' => 'phone_home',
      ),
      1 => 
      array (
        'name' => 'email',
        'label' => 'LBL_ANY_EMAIL',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      2 => 
      array (
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
        'name' => 'date_entered',
      ),
      3 => 
      array (
        'name' => 'address_postalcode',
        'label' => 'LBL_POSTAL_CODE',
        'type' => 'name',
        'default' => true,
      ),
      4 => 
      array (
        'name' => 'do_not_call',
        'label' => 'LBL_DO_NOT_CALL',
        'default' => true,
      ),
      5 => 
      array (
        'name' => 'lead_source',
        'label' => 'LBL_LEAD_SOURCE',
        'default' => true,
      ),
      6 => 
      array (
        'name' => 'status',
        'label' => 'LBL_STATUS',
        'default' => true,
      ),
      7 => 
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_ASSIGNED_TO',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
      ),
      8 => 
      array (
        'width' => '10%',
        'label' => 'LBL_MOBILE_PHONE',
        'default' => true,
        'name' => 'phone_mobile',
      ),
      9 => 
      array (
        'width' => '10%',
        'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
        'default' => true,
        'name' => 'primary_address_postalcode',
      ),
      10 => 
      array (
        'width' => '10%',
        'label' => 'LBL_PRIMARY_ADDRESS_STREET',
        'default' => true,
        'name' => 'primary_address_street',
      ),
      11 => 
      array (
        'width' => '10%',
        'label' => 'LBL_CAMPAIGN',
        'default' => true,
        'name' => 'campaign_name',
      ),
      12 => 
      array (
        'width' => '10%',
        'label' => 'LBL_AGE',
        'default' => true,
        'name' => 'age_c',
      ),
      13 => 
      array (
        'width' => '10%',
        'label' => 'LBL_GENDER',
        'default' => true,
        'name' => 'gender_c',
      ),
      14 => 
      array (
        'width' => '10%',
        'label' => 'LBL_CASTE',
        'default' => true,
        'name' => 'caste_c',
      ),
      15 => 
      array (
        'width' => '10%',
        'label' => 'LBL_MARITAL_STATUS',
        'default' => true,
        'name' => 'marital_status_c',
      ),
      16 => 
      array (
        'width' => '10%',
        'label' => 'LBL_DO_NOT_EMAIL',
        'default' => true,
        'name' => 'do_not_email_c',
      ),
      17 => 
      array (
        'width' => '10%',
        'label' => 'LBL_MOTHER_TONGUE',
        'default' => true,
        'name' => 'mother_tongue_c',
      ),
      18 => 
      array (
        'width' => '10%',
        'label' => 'LBL_EDITION_DATE',
        'default' => true,
        'name' => 'edition_date_c',
      ),
      19 => 
      array (
        'width' => '10%',
        'label' => 'LBL_RESPONSE_AD',
        'default' => true,
        'name' => 'response_ad_c',
      ),
      20 => 
      array (
        'width' => '10%',
        'label' => 'LBL_EXPECT_PAY_IN',
        'default' => true,
        'name' => 'expect_pay_in_c',
      ),
      21 => 
      array (
        'width' => '10%',
        'label' => 'LBL_EDUCATION',
        'default' => true,
        'name' => 'education_c',
      ),
      22 => 
      array (
        'width' => '10%',
        'label' => 'LBL_PRODUCT_VALUE',
        'default' => true,
        'name' => 'product_value_c',
      ),
      23 => 
      array (
        'width' => '10%',
        'label' => 'LBL_PRODUCT',
        'default' => true,
        'name' => 'product_c',
      ),
      24 => 
      array (
        'width' => '10%',
        'label' => 'LBL_STRENGTH',
        'default' => true,
        'name' => 'strength_c',
      ),
      25 => 
      array (
        'width' => '10%',
        'label' => 'LBL_MANGLIK',
        'default' => true,
        'name' => 'manglik_c',
      ),
      26 => 
      array (
        'width' => '10%',
        'label' => 'LBL_TYPE',
        'default' => true,
        'name' => 'type_c',
      ),
      27 => 
      array (
        'width' => '10%',
        'label' => 'LBL_CITY',
        'default' => true,
        'name' => 'city_c',
      ),
      28 => 
      array (
        'width' => '10%',
        'label' => 'LBL_USERNAME',
        'default' => true,
        'name' => 'username_c',
      ),
      29 => 
      array (
        'width' => '10%',
        'label' => 'LBL_INCOME',
        'default' => true,
        'name' => 'income_c',
      ),
      30 => 
      array (
        'width' => '10%',
        'label' => 'LBL_OCCUPATION',
        'default' => true,
        'name' => 'occupation_c',
      ),
      31 => 
      array (
        'width' => '10%',
        'label' => 'LBL_RELIGION',
        'default' => true,
        'name' => 'religion_c',
      ),
      32 => 
      array (
        'width' => '10%',
        'label' => 'LBL_HEIGHT',
        'default' => true,
        'name' => 'height_c',
      ),
      33 => 
      array (
        'width' => '10%',
        'label' => 'LBL_POSTED_BY',
        'default' => true,
        'name' => 'posted_by_c',
      ),
    ),
  ),
);
?>
