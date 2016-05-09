<?php
/**
 * CLASS ProfileDocumentVerificationService
 * This class is responsible to handle operations related to profile documentation.
 * @author Lavesh Rawat / Reshu Rajput
 * @package jeevansathi
*/
class ProfileDocumentVerificationService
{
	private $maxTimeToScreenAfterAllocation = 30; // in minutes
	private $minTimeToScreenAfterUpdate = 30; //in minutes

	/**
	* This function allocates a profile to screening user.
	* @param pid profileid to be assigned.
	* @param name screening user to which profile need to be assigned.
	*/
	public function allotProfile($pid,$name)
	{
		$PROFILE_VERIFICATION_DOCUMENTS_SCREENING = new PROFILE_VERIFICATION_DOCUMENTS_SCREENING;
		$PROFILE_VERIFICATION_DOCUMENTS_SCREENING->insertDocuments($pid,$name);
	}

	 /**
        * This function get total no of profiles with non deleted and unscreened documents 
        * @return result : count of profiles
        */
        public function getTotalUnscreenedProfileCount()
        {
                $PROFILE_VERIFICATION_DOCUMENTS = new PROFILE_VERIFICATION_DOCUMENTS;
		$whereCondition["VERIFIED_FLAG"] = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFIED_FLAG_ENUM["UNDER_SCREENING"];
	        $whereCondition["DELETED_FLAG"] = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DELETED_FLAG_ENUM["NOT_DELETED"];
		$result=$PROFILE_VERIFICATION_DOCUMENTS->getDocuments("count(distinct(`PROFILEID`))",$whereCondition);
		return $result[0]["count(distinct(`PROFILEID`))"];
        }
	
	/**
	* This function decides the profile to be assigned to screening user.
	* @param name screening user to which profile need to be assigned.
	* @return array containing profileid and status(updateAllotTime) to tell if we need to update allocation time
	*/
	public function fetchProfileToAllot($name)
	{
		$date = new DateTime();
		$date->sub(new DateInterval('PT'.$this->maxTimeToScreenAfterAllocation.'M'));
		$time = $date->format('Y-m-d H:i:s');

		/* profile which have been allotted to a specific screening user and havent been screened yet and has not crossed maximum time. */
		$PROFILE_VERIFICATION_DOCUMENTS_SCREENING = new PROFILE_VERIFICATION_DOCUMENTS_SCREENING;
		$infoArr = $PROFILE_VERIFICATION_DOCUMENTS_SCREENING->userAllottedProfiles($time,'greater',$name);
		$updateAllotTime = NULL;

		if(is_null($infoArr))
		/* profile which have been allotted to screening user(including loggedin) and havent been screened yet in a max time */
		{
			$infoArr = $PROFILE_VERIFICATION_DOCUMENTS_SCREENING->userAllottedProfiles($time,'less');
			$updateAllotTime = 1;
		}

		if(is_null($infoArr))
		/* allot profile to user based on oldest 1st. */
		{
			$date = new DateTime();
			$date->sub(new DateInterval('PT'.$this->minTimeToScreenAfterUpdate.'M'));
			$time = $date->format('Y-m-d H:i:s');
			$PROFILE_VERIFICATION_DOCUMENTS = new PROFILE_VERIFICATION_DOCUMENTS;
			$infoArr = $PROFILE_VERIFICATION_DOCUMENTS->allottProfile($time);
			$updateAllotTime = 1;
		}
		if(is_array($infoArr))
		{
			$returnArr['PROFILEID'] = $infoArr["PROFILEID"];
			$returnArr['updateAllotTime'] = $updateAllotTime;
			return $returnArr;
		}
		return NULL;
	}


	/**
	* This function list array of unscreened documents of id.
	* @param pid profileid to be assigned.
	* @return arr array containing info.
	*/
	public function getUnscreenedDocuments($pid)
	{
		$PROFILE_VERIFICATION_DOCUMENTS =  new PROFILE_VERIFICATION_DOCUMENTS;
		$whereCondition["PROFILEID"] = $pid;
		$whereCondition["VERIFIED_FLAG"] = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFIED_FLAG_ENUM["UNDER_SCREENING"];
		$whereCondition["DELETED_FLAG"] = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DELETED_FLAG_ENUM["NOT_DELETED"];
		$arr = $PROFILE_VERIFICATION_DOCUMENTS->getDocuments('',$whereCondition);
		if(is_array($arr))
		{
			foreach($arr as $k=>$v)
			{
				$arr[$k]['DOCURL'] = PictureFunctions::getCloudOrApplicationCompleteUrl($v["DOCURL"]);
                if(JsConstants::$whichMachine == 'prod')
                    $arr[$k]['DOCURL'] = str_replace("www.jeevansathi.com", "crm.jeevansathi.com",$arr[$k]['DOCURL']);
				$arr[$k]['VERIFICATION_VALUE'] = $this->getVerificationValue($v["ATTRIBUTE"],$v["VERIFICATION_VALUE"]);
			}
			return $arr;
		}
		return NULL;
	}

	/*This function is used to required information of get all documents based on given conditions order by DOCUMENT_TYPE
	* @param where : array of field value mapping for where condition
	* @param details: fields required to be fetched
	* @return result : details of all documents
	*/ 
	public function getAllProfileDocuments($where,$details="")
	{
		 if(!is_array($where))
                        throw new jsException("No where condition passed in getAllProfileDocuments in ProfileDocumentVerificationService.class.php");
                $jsadminProfileVerificationDocumentsObj = new PROFILE_VERIFICATION_DOCUMENTS();
                $documents = $jsadminProfileVerificationDocumentsObj->getDocuments($details,$where,"DOCUMENT_TYPE");
		if(is_array($documents))
                {
                	foreach($documents as $k=>$v)
                        	$documents[$k]["DOCURL"]= PictureFunctions::getCloudOrApplicationCompleteUrl($v["DOCURL"]);
                }
                return $documents;

	}
	
	/*This function is used to insert documennts id db.
	* @param profile : profile object 
	* @param execName : name of executive who uploaded the documents
	* @param docs : array of documents to be inserted
	* @return result : true if inserted successfully
	*/
	public function performDbInsert($profile,$execName,$docs)
	{
		if(!$profile || !$execName || !is_array($docs))
			 throw new jsException("No profile/execname/docs passed in performDbInsert in ProfileDocumentVerificationService.class.php");
		$i=0;
		foreach($docs as $k=>$v)
		{
			$docid[$i]=$v["docId"];
			$profileId[$i] = $profile->getPROFILEID();
			$attribute[$i] = $k;
			$doc[$i] = $v["docType"];
			$docFormat[$i] = $v["fileType"];
			$docUrl[$i] = $v["fileUrl"];
			$uploadedBy[$i] = is_array($execName)?$execName[$i]:$execName;
			$getField = "get".PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_FIELD_ENUM[$k]["0"];
			$verificationValue[$i] = $profile->$getField();
			$moduleName[$i]="VERIFICATION_DOCUMENTS";
                        $moduleType[$i]="DOCURL";
                        $status[$i]=IMAGE_SERVER_STATUS_ENUM::$onAppServer;
                        $i++;
                }
                $jsadminProfileVerificationDocumentsObj = new PROFILE_VERIFICATION_DOCUMENTS();
		$jsadminProfileVerificationDocumentsObj->startTransaction();
                $result = $jsadminProfileVerificationDocumentsObj->insertbulkDocuments($docid,$profileId,$attribute,$doc,$docFormat,$docUrl,$verificationValue,$uploadedBy);
                if($result)
                {
			// Inserted in image server log table 
                        $imageServerLogObj = new ImageServerLog();
                        $result = $imageServerLogObj->insertBulk($moduleName,$docid,$moduleType,$status);
                }
		$jsadminProfileVerificationDocumentsObj->commitTransaction();
		return $result;	
	}

	/*This function used to get list of documenst valid for profile based marital status in which proof of divorce is not valid
	* @param  profile: profile object 
	* @return documentListMapping : array of valid attribute and document mapping
	*/
	public function getDocumentsList($profile)
	{
        	$documentListMapping = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_DOCUMENT;
		foreach($documentListMapping as $k=>$v)
		{
			$field ="get". PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_FIELD_ENUM[$k]["0"];
			$value= $profile->$field();
			if(!$value)
				unset($documentListMapping[$k]);
			elseif($k == "DIVORCE" && ($value!="D" && $value!="A"))// Only annuled and divorced cases 
				unset($documentListMapping[$k]);
		}
       		return $documentListMapping;
		
	}
	
	/*This function is used to get view link for attributes if any document is uploaded for the same
	* @param profileId : profile id 
	* @return documentView : array of attributes which have any document uploaded
	*/
	public function getDocumentViewList($profileId)
	{
		if($profileId =="")
			throw new jsException("No profileid passed in getDocumentViewList in ProfileDocumentVerificationService.class.php");
		$jsadminProfileVerificationDocumentsObj = new PROFILE_VERIFICATION_DOCUMENTS();	
		$result = $jsadminProfileVerificationDocumentsObj->getDocuments("ATTRIBUTE",array('PROFILEID'=>$profileId,'DELETED_FLAG'=>PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DELETED_FLAG_ENUM["NOT_DELETED"]),"","","ATTRIBUTE");
		if(is_array($result))
		{
			foreach($result as $k=>$v)
			{
				$documentView[$v["ATTRIBUTE"]] = $v["ATTRIBUTE"];
			}
			return $documentView;
		}
		return null;
	}

	/*This function is used to perform saving of uploaded images on server 
	* @param docs : array of uploaded documents
	* @param profileId : profile for which documents are uploaded
	* @return docs : array of documents with document id ,url and type
	*/
	public function performUpload($docs,$profileId)
	{
		$jsadminProfileVerificationDocAutoIncrementObj  = new PROFILE_VERIFICATION_DOC_AUTOINCREMENT();
		if(!is_array($docs) || !$profileId)
			 throw new jsException("No docs/profileid passed in performUpload in ProfileDocumentVerificationService.class.php");
		foreach($docs as $k=>$v)
		{
			$docId = $jsadminProfileVerificationDocAutoIncrementObj->getAutoIncrementDocumentId();
			$saveUrl = $this->getSaveUrlDoc($docId,$profileId,$v["fileType"]);
			$displayUrl = $this->getDisplayUrlDoc($docId,$profileId,$v["fileType"]);
			$pictureFunctionsObj = new PictureFunctions();
			$result = $pictureFunctionsObj->moveImage($v["fileName"],$saveUrl);
			
			if($result)
			{
				$docs[$k]["fileUrl"] = $displayUrl;
				$docs[$k]["docId"] = $docId;
			}
			else
				return null;
		}
		return $docs;
	}

	/* This function is used to get array of valid attribute and document type mapping documents uploaded
	* @param docs : array of attribute and document type mapping in uploaded form
	* @param files : array of uploaded files
	* @return docData : Array of documents to be inserted
	*/
	public function getDocumentsToInsert($docs=null,$files=null)
	{
		if(!is_array($files) || !is_array($docs))
                        throw new jsException("No files/docs passed in getDocumentsToInsert in ProfileDocumentVerificationService.class.php");
		foreach($docs as $key=>$val)
		{
			if($val!="" && PROFILE_VERIFICATION_DOCUMENTS_ENUM::verifyAttributeDoc($key,$val))
			{
				if(array_key_exists($key,$files))
				{
					$docData[$key]["docType"]=$val;
					if($files[$key]["type"] == "image/gif")             //Get the format of pic being uploaded
                                        	$format_of_pic = "gif";
                                        else if ($files[$key]["type"] == "image/jpeg")
                                        	$format_of_pic = "jpeg";
                                        else if ($files[$key]["type"] == "image/jpg")
                                        	$format_of_pic = "jpg";
					$docData[$key]["fileType"] = $format_of_pic;
					$docData[$key]["fileName"] = $files[$key]["tmp_name"];
				}
				else
					return false;
			}
				
		}		
		return $docData;		
	}

	/*This function is used to validate files as per requirement
	* @param files : array of uploaded files
	* @return fileArr : array of errors or valid files
	*/

	public function validateFiles($files=null)
	{
		if(!is_array($files))
                        throw new jsException("No files passed in validateFiles in ProfileDocumentVerificationService.class.php");
		$file_type_array = sfConfig::get("app_photo_formats_to_check");
		$fileArr = null;
		foreach($files as $key=>$value)
		{
			if($value["name"]!="")
			{
				if($value["error"]!=0)
					$fileArr["Error"][$key]="Some Error occurred Please try Again!!";
				elseif(!in_array($value["type"],$file_type_array))
					$fileArr["Error"][$key]="Use a valid image file ('jpg','jpeg','gif')";
				elseif($value["size"] > (sfConfig::get("app_max_photo_size")*1024*1024)	)
					$fileArr["Error"][$key]="Use a smaller image (Upto 6 MB)";
				else
					$fileArr["Valid"][$key]=$value;
			}
			
		}
		return $fileArr;
	}
	
	/*This function is used to get file path on server
	* @param docId : document id to be saved
	* @param profileId : profile id
	* @param type : gif/jpeg/jpg format type of image
	* @return saveUrl : url where image need to be saved
	*/

	public function getSaveUrlDoc($docId,$profileId,$type="")
        {
                $saveUrl = "";
                if(!$type)
                        $type=".jpg";
                elseif(!strstr($type,"."))
                        $type=".".$type;

                $docUrlId=$this->docEncyption($docId,$profileId);
                $saveUrl=sfConfig::get("sf_upload_dir")."/VerificationDocument/".$docUrlId.$type;
                return $saveUrl;
        }


	/*This function is used to get file path to be stored in database
        * @param docId : document id to be saved
        * @param profileId : profile id
        * @param type : gif/jpeg/jpg format type of image
        * @return displayUrl : url need to be stored
        */

	public function getDisplayUrlDoc($docId,$profileId,$type="")
        {
                $displayUrl = "";
                if(!$type)
                        $type=".jpg";
                elseif(!strstr($type,"."))
                        $type=".".$type;

                $docUrlId=$this->docEncyption($docId,$profileId);
                $displayUrl="JS/uploads/VerificationDocument/".$docUrlId.$type;
                return $displayUrl;
        }

	/*This function is used to create doc name by decypting docid and profileid
	* @param docId : document id
	* @param profileId : profileId
	* @return docUrlId : doc name 	
	*/
	public function docEncyption($docId,$profileId)
        {
                $docCrypt=md5($docId);
                $profileIdCrypt=md5($profileId);
                $docUrlId=$docId."ii".$docCrypt."ii".$profileIdCrypt;
                return $docUrlId;
        }

	public function trackScreening($name,$arr1)
	{
		$PROFILE_VERIFICATION_DOCUMENTS_SCREENING_TRACKING = new PROFILE_VERIFICATION_DOCUMENTS_SCREENING_TRACKING;
		foreach($arr1 as $k=>$v)
		{
			$arr[$k]["DOCUMENT_ID"] = $v["DOCUMENT_ID"]; 
			$arr[$k]["SCREENED_TIME"] = date("Y-m-d H:i:s");
			$arr[$k]["SCREENED_BY"] = $name;
		}
		$PROFILE_VERIFICATION_DOCUMENTS_SCREENING_TRACKING->insertDocuments($arr);
	}
	
	/* This function is used to mark a document as deleted and insert its entry in deleted table
	* @param docId : documenst id to be deleted
	* @param deletedByUser : executive deleted the document
	*/
	public function deleteDocumentById($docId,$deletedByUser)
	{
		if($docId =="" || $deletedByUser =="")
	                throw new jsException("No docid/deletedby user passed in deleteDocumentById in ProfileDocumentVerificationService.class.php");
                $jsadminProfileVerificationDocumentsObj = new PROFILE_VERIFICATION_DOCUMENTS();
                $docid[]= $docId;
		$deletedBy[]= $deletedByUser;
		$jsadminDeletedProfileVerificationDocumentsObj = new JSADMIN_DELETED_PROFILE_VERIFICATION_DOCUMENTS();
		$jsadminProfileVerificationDocumentsObj->startTransaction();
	        $result = $jsadminDeletedProfileVerificationDocumentsObj->insertbulkDocuments($docid,$deletedBy);
		$paramArr = Array($docId=>PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DELETED_FLAG_ENUM["DELETED"]);
		$jsadminProfileVerificationDocumentsObj->multipleDocumentIdUpdate($paramArr,"DELETED_FLAG"); 
		$jsadminProfileVerificationDocumentsObj->commitTransaction();                       
                
	}
	
	/* This funxtion is used by photo transfer task to image server for editing docurl from server to image server
	* @param paramArr : single key value pair of docurl
	* @param docId : document id
	* @param pid : profile id
	* @return result: true if successfully editted
	*/ 
	public function edit($paramArr,$docId,$pid="")
	{
		if($docId =="")
                        throw new jsException("No docid passed in edit in ProfileDocumentVerificationService.class.php");
                $jsadminProfileVerificationDocumentsObj = new PROFILE_VERIFICATION_DOCUMENTS();
                foreach($paramArr as $key=>$value)
		{
			$param[$docId]=$value;
		}
		$result = $jsadminProfileVerificationDocumentsObj->multipleDocumentIdUpdate($param,"DOCURL");
		return $result;

	}
	
	/* This function is used to get values(from jprofile) need to be verified for each attribute for a profile
	* @param profile : profile obj 
	* @return result : array of attribute and corresponding values to be displayed
	*/
	public function getProfileVerificationValue($profile)
	{
		if(!$profile)
                        throw new jsException("No profile passed in getProfileVerificationValue in ProfileDocumentVerificationService.class.php");
		foreach(PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_FIELD_ENUM as $att=>$field)
		{
			$getField= "get".$field["0"];
			$value= $profile->$getField();
			$result[$att]= $this->getVerificationValue($att,$value);
		}
		return $result;
	}

	/* This function is used to get display values given attribute 
        * @param attribute : attribute of the given value
	* @param value : value for which display value is required 
        * @return value : corresponding value to be displayed
        */
	
	public function getVerificationValue($attribute,$value)
	{
		if(!$attribute)
			 throw new jsException("No attribute passed in getVerificationValue in ProfileDocumentVerificationService.class.php");
		if($value)
		{
			switch(PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTE_FIELD_ENUM[$attribute]["0"])
			{
				case "INCOME":
					$value = FieldMap::getFieldLabel("income_level",$value);
					break;
				case "EDU_LEVEL_NEW" :
					$value =  FieldMap::getFieldLabel("education", $value);
					break;
				case "MSTATUS":
					$value = FieldMap::getFieldLabel("marital_status",$value);
					break;
			}
		}
		return $value;			
	}

	/* filterAllowedVerificationDocs :filter allowable docs and map into desired profile based array
	* @param : $files array
	* @return : $filesToUpload array
	*/
	public function filterAllowedVerificationDocs($files)
	{
		if(is_array($files))
		{
			
		    	if($files['tmp_name'] && $files['name'])
		    	{
		    		$profileid = $files["profileid"]/*9397643;*/;
		    		$proofType = $files["proofType"]/*$key;*/;
		    		if(PROFILE_VERIFICATION_DOCUMENTS_ENUM::verifyAttributeDoc($proofType,$files["docType"],false))
		    		{
		    			$filesToUpload[$profileid][$proofType]["docType"] = $files["docType"];
		    			$filesToUpload[$profileid][$proofType]["fileType"] = PictureFunctions::mapPictureFormatType($files["type"]);   
		    			$filesToUpload[$profileid][$proofType]["fileName"] = $files["tmp_name"];
		    		}
		    		else
		    		{
		    			return null;	
		    		}
		    	}
	    	return $filesToUpload;
    	}
    	else
    		return null;
	}
}
?>