<?php
$viewdefs ['Campaigns'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_TEST_BUTTON_TITLE}" accessKey="{$MOD.LBL_TEST_BUTTON_KEY}" class="button" onclick="this.form.return_module.value=\'Campaigns\'; this.form.return_action.value=\'TrackDetailView\';this.form.action.value=\'Schedule\';this.form.mode.value=\'test\'" type="{$ADD_BUTTON_STATE}" name="button" value="{$MOD.LBL_TEST_BUTTON_LABEL}">',
          ),
          4 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_QUEUE_BUTTON_TITLE}" accessKey="{$MOD.LBL_QUEUE_BUTTON_KEY}" class="button" onclick="this.form.return_module.value=\'Campaigns\'; this.form.return_action.value=\'TrackDetailView\';this.form.action.value=\'Schedule\'" type="{$ADD_BUTTON_STATE}" name="button" value="{$MOD.LBL_QUEUE_BUTTON_LABEL}">',
          ),
          5 => 
          array (
            'customCode' => '<input title="{$APP.LBL_MAILMERGE}" accessKey="{$APP.LBL_MAILMERGE_KEY}" class="button" onclick="this.form.return_module.value=\'Campaigns\'; this.form.return_action.value=\'TrackDetailView\';this.form.action.value=\'MailMerge\'" type="submit" name="button" value="{$APP.LBL_MAILMERGE}">',
          ),
          6 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_MARK_AS_SENT}" class="button" onclick="this.form.return_module.value=\'Campaigns\'; this.form.return_action.value=\'TrackDetailView\';this.form.action.value=\'DetailView\';this.form.mode.value=\'set_target\'" type="{$TARGET_BUTTON_STATE}" name="button" value="{$MOD.LBL_MARK_AS_SENT}"><input title="mode" class="button" id="mode" name="mode" type="hidden" value="">',
          ),
          7 => 
          array (
            'customCode' => '<script>{$MSG_SCRIPT}</script>',
          ),
        ),
        'links' => 
        array (
          0 => '<input type="button" class="button" onclick="javascript:window.location=\'index.php?module=Campaigns&action=WizardHome&record={$fields.id.value}\';" value="{$MOD.LBL_TO_WIZARD_TITLE}" />',
          1 => '<input type="button" class="button" onclick="javascript:window.location=\'index.php?module=Campaigns&action=TrackDetailView&record={$fields.id.value}\';" value="{$MOD.LBL_TRACK_BUTTON_LABEL}" />',
          2 => '<input type="button" class="button" onclick="javascript:window.location=\'index.php?module=Campaigns&action=RoiDetailView&record={$fields.id.value}\';" value="{$MOD.LBL_TRACK_ROI_BUTTON_LABEL}" />',
        ),
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
            'name' => 'name',
            'label' => 'LBL_CAMPAIGN_NAME',
          ),
          1 => '',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'label' => 'LBL_CAMPAIGN_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'username_c',
            'label' => 'LBL_USERNAME',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'start_date',
            'label' => 'LBL_CAMPAIGN_START_DATE',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'end_date',
            'label' => 'LBL_CAMPAIGN_END_DATE',
          ),
          1 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'newspaper_c',
            'label' => 'LBL_NEWSPAPER',
          ),
          1 => 
          array (
            'name' => 'edition_c',
            'label' => 'LBL_EDITION',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'newspaper_edition_c',
            'studio' => 'visible',
            'label' => 'LBL_NEWSPAPER_EDITION_C',
          ),
          1 => 
          array (
            'name' => 'ad_heading_c',
            'studio' => 'visible',
            'label' => 'LBL_AD_HEADING_C',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'language_c',
            'studio' => 'visible',
            'label' => 'LBL_LANGUAGE_C',
          ),
          1 => 
          array (
            'name' => 'mobile_no_c',
            'label' => 'LBL_MOBILE_NO_C',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'email_id_c',
            'label' => 'LBL_EMAIL_ID_C',
          ),
          1 => 
          array (
            'name' => 'email_id_password_c',
            'label' => 'LBL_EMAIL_ID_PASSWORD_C',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'actual_cost',
            'label' => '{$MOD.LBL_CAMPAIGN_ACTUAL_COST} ({$CURRENCY})',
          ),
          1 => 
          array (
            'name' => 'expected_revenue',
            'label' => '{$MOD.LBL_CAMPAIGN_EXPECTED_REVENUE} ({$CURRENCY})',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'content',
            'label' => 'LBL_CAMPAIGN_CONTENT',
          ),
        ),
      ),
    ),
  ),
);
?>
