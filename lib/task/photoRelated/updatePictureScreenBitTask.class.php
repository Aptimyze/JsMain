<?php
/*@author Bhavana Kadwal
 * This task updates the PICTURE_FOR_SCREEN_NEW having ordering 0, screen bit from 00***** by [prefix]*****
 */
class updatePictureScreenBitTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(new sfCommandOption('prefix', null, sfCommandOption::PARAMETER_OPTIONAL, 'Prefix value', '12')));
    $this->addOptions(array(new sfCommandOption('screen_bit', null, sfCommandOption::PARAMETER_OPTIONAL, 'Screen Bit update', '0022222')) );
    $this->addOptions(array(new sfCommandOption('duration', null, sfCommandOption::PARAMETER_OPTIONAL, 'time duration for cron in minutes', 30)));
    $this->namespace        = 'picture';
    $this->name             = 'updatePictureScreenBit';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [picture:updatePictureScreenBit|INFO] task does things.
Call it with:

  [php symfony picture:updatePictureScreenBit|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $pictureForScreenNewObj = new PICTURE_FOR_SCREEN_NEW();
    $pictureForScreenNewObj->insertPictureScreenBackupBit($options);
    $pictureForScreenNewObj->updatePictureScreenBit($options);
  }
}
