<?php
/*This class is used to execute queries on MIS.VIEW_ALBUM_LOG table
 * @author Reshu Rajput
 * @created 2013-04-05
*/
class MIS_VIEW_ALBUM_LOG extends TABLE
{
        public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
	
         /** Function insertRecord added by Reshu
        This function is used to insert record in the file.
        * @param  profileId whose album is viewed,
	* @param  viewerProfileID who viewed the album
        * @param source from where album is viewed. Source can be either of the following:
	* 'S' for Search,'C' for  My Contact,'E' for Confirmation Page, and 'D' for Profile Page/Social Page
	* @param  count is number of photos given profile id have in album
        **/
	
        public function insertRecord($profileID,$viewerProfileID='',$source,$count)
        {

//JSM-881---temp stop
return 1;

                if(!$profileID || !$source)
                        throw new jsException("","PROFILEID  OR SOURCE IS BLANK IN insertRecord() OF MIS_VIEW_ALBUM_LOG.class.php");

                try
                {
                        $sql = "INSERT IGNORE INTO MIS.VIEW_ALBUM_LOG(DATE,PROFILEID,VIEWER_PROFILEID,SOURCE,PHOTO_COUNT) VALUES (NOW(),:PID,:VPID,:SRC,:COUNT)";
                        $res = $this->db->prepare($sql);
                        $res->bindValue(":PID", $profileID, PDO::PARAM_INT);
                        $res->bindValue(":VPID", $viewerProfileID, PDO::PARAM_INT);
			$res->bindValue(":SRC", $source, PDO::PARAM_STR);
			 $res->bindValue(":COUNT", $count, PDO::PARAM_INT);
                        $res->execute();
                }
                catch(PDOException $e)
                {
                        throw new jsException($e);
                }
        }
}
?>
