<?php
/**
 * CLASS ProfileDocumentVerificationByUserService
 * This class is responsible to handle operations related to profile verification documents from PD.
 * @author Bhavana Kadwal
 * @package jeevansathi
*/
class CriticalInfoChangeDocUploadService
{
	/*This function is used to insert documennts id db.
	* @param profile : profile object 
	* @param execName : name of executive who uploaded the documents
	* @param docs : array of documents to be inserted
	* @return result : true if inserted successfully
	*/
	public function performDbInsert($profileId,$docsData)
	{
                $docPath = $this->performUpload($docsData, $profileId);
                $verificationDocumentsObj = new newjs_CRITICAL_INFO_CHANGED_DOCS();
                $result = $verificationDocumentsObj->insert($profileId, $docPath);
                if($result){
                        $editData = $verificationDocumentsObj->editedCriticalInfo($profileId);
                        $docid = $editData["ID"];
                        $moduleName = IMAGE_SERVER_MODULE_NAME_ENUM::getEnum('CRITICAL_INFO_DIVORCED_DOC');
                        $status = IMAGE_SERVER_STATUS_ENUM::$onAppServer;
                        $moduleType = "DOCUMENT_PATH";
                        $imageServerLogObj = new ImageServerLog();
                        $result = $imageServerLogObj->insertBulk($moduleName,$docid,$moduleType,$status);
                        unset($imageServerLogObj);
                        unset($verificationDocumentsObj);
                }
		return $result;	
	}

	/*This function used to get list of documenst valid for profile based marital status in which proof of divorce is not valid
	* @param  profile: profile object 
	* @return documentListMapping : array of valid attribute and document mapping
	*/
	public function getDocumentsList($profileId)
	{
                $dbObj = new newjs_CRITICAL_INFO_CHANGED_DOCS();
                $uploadedDocs = $dbObj->editedCriticalInfo($profileId);
       		return $uploadedDocs;
		
	}

	/*This function is used to perform saving of uploaded images on server 
	* @param docs : array of uploaded documents
	* @param profileId : profile for which documents are uploaded
	* @return docs : array of documents with document id ,url and type
	*/
	public function performUpload($docs,$profileId)
	{
                $prefix = rand(0,100000);
                $saveUrl = $this->getSaveUrlDoc($profileId,$docs["name"],$prefix);
                $displayUrl = $this->getDisplayUrlDoc($profileId,$docs["name"],$prefix);
                $pictureFunctionsObj = new PictureFunctions();
                $result = $pictureFunctionsObj->moveImage($docs["tmp_name"],$saveUrl);
                chmod($saveUrl,0777);
                if($result)
                        return $displayUrl;
                else
                        return null;
	}
	
	/*This function is used to get file path on server
	* @param docId : document id to be saved
	* @param profileId : profile id
	* @param type : gif/jpeg/jpg format type of image
	* @return saveUrl : url where image need to be saved
	*/

	public function getSaveUrlDoc($profileId,$type="",$pre)
        {
                $uploadDir = sfConfig::get("sf_upload_dir")."/CriticalInformation/";
                if(!is_dir($uploadDir)){
                    mkdir($uploadDir);
                }
                $displayUrl = "";
                $type = explode('.',$type);
                if(!$type)
                        $type=".jpg";
                else
                        $type=".".$type[1];

                $docUrlId=$this->docEncyption($profileId,$pre);
                $saveUrl=sfConfig::get("sf_upload_dir")."/CriticalInformation/".$docUrlId.$type;
                return $saveUrl;
        }


	/*This function is used to get file path to be stored in database
        * @param docId : document id to be saved
        * @param profileId : profile id
        * @param type : gif/jpeg/jpg format type of image
        * @return displayUrl : url need to be stored
        */

	public function getDisplayUrlDoc($profileId,$type="",$pre)
        {
                $displayUrl = "";
                $type = explode('.',$type);
                if(!$type)
                        $type=".jpg";
                else
                        $type=".".$type[1];

                $docUrlId=$this->docEncyption($profileId,$pre);
                $displayUrl="JS/uploads/CriticalInformation/".$docUrlId.$type;
                return $displayUrl;
        }

	/*This function is used to create doc name by decypting docid and profileid
	* @param docId : document id
	* @param profileId : profileId
	* @return docUrlId : doc name 	
	*/
	public function docEncyption($profileId,$pre)
        {
                $docCrypt= $pre;
                $profileIdCrypt=md5($profileId);
                $docUrlId=$docCrypt."ii".$profileIdCrypt;
                return $docUrlId;
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
                        throw new jsException("No docid passed in edit in CriticalInfoChangeDocUploadService.class.php");
                $criticalInfoObj = new newjs_CRITICAL_INFO_CHANGED_DOCS();
		$result = $criticalInfoObj->updateById($pid,$docId,$paramArr["DOCUMENT_PATH"]);
		return $result;

	}
	public function updateStatus($pid="",$status)
	{
		if($pid =="")
                        throw new jsException("No profileid passed in edit in CriticalInfoChangeDocUploadService.class.php");
                $criticalInfoObj = new newjs_CRITICAL_INFO_CHANGED_DOCS();
		$result = $criticalInfoObj->updateStatus($pid,$status);
		return $result;

	}
        public function allottProfile($dt){
                $criticalInfoObj = new newjs_CRITICAL_INFO_CHANGED_DOCS();
		$result = $criticalInfoObj->allottProfile($dt);
		return $result;
        }
}
?>