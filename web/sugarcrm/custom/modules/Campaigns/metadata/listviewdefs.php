<?php
// created: 2010-07-27 13:43:06
$listViewDefs['Campaigns'] = array (
  'TRACK_CAMPAIGN' => 
  array (
    'width' => '1',
    'label' => '&nbsp;',
    'link' => true,
    'customCode' => ' <a title="{$TRACK_CAMPAIGN_TITLE}" href="index.php?action=TrackDetailView&module=Campaigns&record={$ID}"><img border="0" src="{$TRACK_CAMPAIGN_IMAGE}"></a> ',
    'default' => true,
    'studio' => false,
    'nowrap' => true,
    'sortable' => false,
  ),
  'LAUNCH_WIZARD' => 
  array (
    'width' => '1',
    'label' => '&nbsp;',
    'link' => true,
    'customCode' => ' <a title="{$LAUNCH_WIZARD_TITLE}" href="index.php?action=WizardHome&module=Campaigns&record={$ID}"><img border="0" src="{$LAUNCH_WIZARD_IMAGE}"></a>  ',
    'default' => true,
    'studio' => false,
    'nowrap' => true,
    'sortable' => false,
  ),
  'NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_CAMPAIGN_NAME',
    'link' => true,
    'default' => true,
  ),
  'CAMPAIGN_TYPE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_TYPE',
    'default' => true,
  ),
  'STATUS' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_STATUS',
    'default' => true,
  ),
  'EDITION_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_EDITION',
    'default' => true,
  ),
  'START_DATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CAMPAIGN_START_DATE',
    'default' => true,
  ),
  'END_DATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_END_DATE',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'default' => true,
  ),
);
?>
