<?php

/*
 * Author: Reshu Rajput
 * Created: May 14, 2013
 * This cron is used to track if any image is not uploaded to image server even after a certain time span
*/

class TrackImageServerUploadTask extends sfBaseTask
{
        protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));

            $this->namespace        = 'cron';
            $this->name             = 'TrackImageServerUpload';
            $this->briefDescription = 'track timely transfers of images to image server';
            $this->detailedDescription = <<<EOF
        This cron runs periodically and is used to track that the transfer of images to the image server is happening within a defined time span.
        Call it with:

          [php symfony cron:TrackImageServerUpload]
EOF;
        }

	protected function execute($arguments = array(), $options = array())
        {
                if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
		
		$timeSpan=5;  // images still not uploaded after 5 days will be retrieved

                $imageServerLog = new ImageServerLog;
		$result = $imageServerLog->getNotUploadedToCloud($timeSpan);
		if(is_array($result))
		{
			$resultCount=count($result);
			$logTableIdArray = implode(",",$result);
			mail("lavesh.rawat@gmail.com,lavesh.rawat@jeevansathi.com,reshu.rajput@jeevansathi.com,kumar.anand@jeevansathi.com","AutoIds not Uploaded to Cloud","Auto Ids of the Pictures not yet uploaded to Cloud:$logTableIdArray Total Count of Pictures not Uploaded :$resultCount");
	
		}
		//Added by Reshu for face detection algo performance
		//$facedetectionobj = new PhotoFaceDetection();
		//$date= date("y-m-d");
		//$faceResult = $facedetectionobj->getPhotoFaceDetectionStat($date);
		//$detectPer = $faceResult["FACEDETECTED_IMAGE_COUNT"]/$faceResult["PROCESSED_IMAGE_COUNT"];
		//if($detectPer < .7)
			//mail("lavesh.rawat@gmail.com,lavesh.rawat@jeevansathi.com,reshu.rajput@jeevansathi.com","Face Detection Bad Performance","Face is getting detected by ratio :$detectPer");
		//unset($facedetectionobj);
		unset($imageServerLog);
        }
}
?>
