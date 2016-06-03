<?php
/**
 * @brief This class is used to handle all functionalities related log table
 * @author Reshu Rajput
 * @created 2013-05-09
 */

class ImageServerLog
{

	/*
        * This function is used to insert bulk values in LOG table in form of array for each parameter required. 
        *It will change the module name and image type into respective enum to be inserted in the table according to the Enum classes
        *@param :moduleName It will take module name array like PICTURE which will be converted into enum using IMAGE_SERVER_MODULE_NAME_ENUM
        *@param :moduleId It will take picture id values array
        *@param :imageType It will take imagetype array like ProfilePicUrl which will be converted into enum using IMAGE_SERVER_IMAGE_TYPE_ENUM
        *@param : status It will take array of Y/ N/ D for the transfered on cloud, not transfered and deleted respectively
        */

	public function insertBulk($moduleName,$moduleId,$imageType,$status)
        {
         	$imageServerLog = new IMAGE_SERVER_LOG;
		$imageTypeEnum=array();
		$moduleNameEnum=array();
		$moduleIds=array();
		$statusArray=array();
		if(!is_array($moduleName))
		{
			$imageTypeEnum[0]=IMAGE_SERVER_IMAGE_TYPE_ENUM::getEnum($imageType,$moduleName);
                        $moduleNameEnum[0]=IMAGE_SERVER_MODULE_NAME_ENUM::getEnum($moduleName);
			$moduleIds[0]=$moduleId;
			$statusArray[0]=$status;
		}
		else
		{
			for ($i=0;$i<count($moduleName);$i++)
        	        {
				$imageTypeEnum[$i]=IMAGE_SERVER_IMAGE_TYPE_ENUM::getEnum($imageType[$i],$moduleName[$i]);
	                	$moduleNameEnum[$i]=IMAGE_SERVER_MODULE_NAME_ENUM::getEnum($moduleName[$i]);
				$moduleIds[$i]=$moduleId[$i];
	                        $statusArray[$i]=$status[$i];
			}	
		}	
                $result= $imageServerLog->insertBulk($moduleNameEnum,$moduleIds,$imageTypeEnum,$statusArray);
		unset($imageServerLog);
		unset($imageTypeEnum);
		unset($moduleNameEnum);
                return $result;
	}

	/*
	This function is used to fetch the data required for the photo transfer cron
	@param - total instance of the cron, current instance to run, module name(PICTURE,SUCCESS etc),limit of result
	@return - resultset in the form of array
	*/
	public function fetchDataForCron($totalInstance,$currentInstance,$module,$limit)
	{
		if(!$totalInstance || !$module || !$limit)
			throw new jsException("","TOTAL INSTANCE OR MODULE NAME OR LIMIT IS BLANK IN fetchDataForCron() OF ImageServerLog.class.php");
		if(!$currentInstance && $currentInstance!=0)
			throw new jsException("","CURRENT INSTANCE IS BLANK IN fetchDataForCron() OF ImageServerLog.class.php");

		$islObj = new IMAGE_SERVER_LOG;
                $output = $islObj->fetchDataForCron($totalInstance,$currentInstance,$module,$limit);
                unset($islObj);
		return $output;
	}

	/*
	This function is used to update the IMAGE_SERVER.LOG table
	@param - id,parameter array where index has the column name to up updated and value has the value to be updated in that column
	*/
	public function updateImageServerTable($id,$paramArr)
	{
		$islObj = new IMAGE_SERVER_LOG;
		$islObj->update($id,$paramArr);
		unset($islObj);
	}

	/*
        This function is used to get all the picture details which are not yet transfered to the cloud after the certain date
        *@param : days it will take time span beyond which a picture should not be in N status
        *@return : output It will be returned containing array of autoId of the picture details not yet uploaded
        */
	public function getNotUploadedToCloud($days)
        {
                if(!$days)
                        throw new jsException("","DAYS IS BLANK IN getNotUploadedToCloud() of ImageServerLog.class.php");

                $islObj = new IMAGE_SERVER_LOG;
                $output = $islObj->getNotUploadedToCloud($days);
                unset($islObj);
                return $output;
        }

	
	public function fetchDataForArchiveCron($totalInstance,$currentInstance,$module,$months,$limit)
        {
                if(!$totalInstance || !$module || !$limit || !$months)
                        throw new jsException("","TOTAL INSTANCE OR MODULE NAME OR MONTHS OR LIMIT IS BLANK IN fetchDataForArchiveCron() OF ImageServerLog.class.php");
                if(!$currentInstance && $currentInstance!=0)
                        throw new jsException("","CURRENT INSTANCE IS BLANK IN fetchDataForArchiveCron() OF ImageServerLog.class.php");

                $islObj = new IMAGE_SERVER_LOG("newjs_slave");
                $date = date("Y-m-d", strtotime("-$months months"));
                $output = $islObj->fetchDataForArchiveCron($totalInstance,$currentInstance,$module,$date,$limit);
                unset($islObj);
                return $output;
        }

	

}
?>
