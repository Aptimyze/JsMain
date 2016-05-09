<?php
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004 - 2009 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

/**
 * ContactsViewRetrieveEmailUsername.php
 * 
 * This class overrides SugarView and provides an implementation for the RetrieveEmailUsername
 * method used for returning the information about an email address
 * 
 * @author Collin Lee
 * */
 
require_once('include/MVC/View/SugarView.php');
require_once("include/JSON.php");
class ContactsViewRetrieveEmail extends SugarView {
	
 	function ContactsViewRetrieveEmail(){
 		parent::SugarView();
 	}
 	
 	function process() {
		$this->display();
 	}

 	function display(){
	require_once("custom/modules/Leads/config.php");
	require_once("include/utils/Jsde_duplicate.php");
	    $data = array();
	    $data['target'] = $_REQUEST['target'];
	    //$partitionsArray=array("connected");
        if(!empty($_REQUEST['email'])) 
	{
	        $db = DBManagerFactory::getInstance();
		$email=$GLOBALS['db']->quote(trim($_REQUEST['email']));
		$duplicateObj=new Duplicate;
		if($_REQUEST['leadID'])
		{
			$lead=trim($_REQUEST['leadID']);
			$ignoreList[]=$lead;
		        $leadInfo=$duplicateObj->getLeadDetailInSugar($lead);
		        if($leadInfo["PROFILEID"])
                		$ignoreProfile[]=$leadInfo["PROFILEID"];
		}
		$duplicate=$duplicateObj->isDuplicateEmail($email,$ignoreList,$ignoreProfile);
		if($duplicate)
			$data['email']=array("EMAIL"=>$email,
					"error"=>1
					);
		else
			$data['email']='';
	        /*$emailCaps = $GLOBALS['db']->quote(strtoupper(trim($_REQUEST['email'])));
		$leadID=$_REQUEST['leadID'];
		$query="SELECT email_address FROM email_addresses e,email_addr_bean_rel b WHERE e.id = b.email_address_id and email_address_caps='$emailCaps' and b.bean_module='Leads'";
		if($leadID)
			$query.=" AND b.bean_id!='$leadId'";
	        $result = $db->query($query);
		if($db->getRowCount($result)==0)
                {
                        $query="SELECT enquirer_email_id_c FROM sugarcrm.leads_cstm WHERE enquirer_email_id_c='$email'";
                        if($leadID)
                                $query.=" AND id_c!='$leadID'";
                        $result=$db->query($query);
		}
		if($db->getRowCount($result)==0)
		{
			foreach($partitionsArray as $partition)
			{
				$tableName1="sugarcrm_housekeeping.".$partition."_email_addresses";
				$tableName2="sugarcrm_housekeeping.".$partition."_email_addr_bean_rel";
				$query="SELECT email_address FROM $tableName1 e,$tableName2 b WHERE e.id = b.email_address_id and email_address_caps='$emailCaps' and b.bean_module='Leads'";
				if($leadID)
					$query.=" AND b.bean_id!='$leadId'";
				$result=$db->query($query);
				if($db->getRowCount($result))
					break;	
				$tableName="sugarcrm_housekeeping.".$partition."_leads_cstm";
				$query="SELECT enquirer_email_id_c from $tableName WHERE enquirer_email_id_c='$email'";
				if($leadID)
					$sql.=" AND id_c!='$leadID'";
				$result = $db->query($query);
				if($db->getRowCount($result))				
					break;
			}
		}
		if($row = $db->fetchByAssoc($result)) 
		{
			$error=1;
			$row['error']=$error;	
		        $data['email'] = $row;
			
		} 
		else 
		{
			$data['email'] = '';
		}
		if(!$error && $check_js)
		{
			$result2 = $db->query("SELECT EMAIL FROM newjs.JPROFILE WHERE EMAIL = '$email'");
			if($row2=$db->fetchByAssoc($result2))
			{	
				$row2['error']=1;	
				$data['email'] = $row2;
			}
		}*/
        }
		$json = new JSON(JSON_LOOSE_TYPE);
		echo $json->encode($data); 
 	}	
}
?>
