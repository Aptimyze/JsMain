<?php
/* 
 * Factory Class for allotment of profiles for Photo Screening.
 * @package    jeevansathi
 * @subpackage photoScreening->PhotoScreenprofileAllotmentFactory
 * @author     Akash Kumar
  */
class PhotoScreenProfileAllotmentFactory
{
        /**
	 * This factory is used to use mail or Profile allotment library
	**/
        public function getAllot($paramArr)
	{
                if($paramArr["SOURCE"]=="mail")
                        return new PhotoScreenMailAllot();
                else
                        return new PhotoScreenProfileAllot();
        }
        
}
?>


