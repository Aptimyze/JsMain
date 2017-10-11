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
//Created by Neha
require_once('include/MVC/Controller/SugarController.php');
require_once('custom/modules/Leads/LeadsInListView.php'); 
require_once('include/utils/Jsutils.php');
require_once('../classes/authentication.class.php');
class LeadsController extends SugarController{
	function LeadsController(){
		parent::SugarController();
	}
	function pre_editview(){
		//IF we have a prospect id leads convert it to a lead
		if (empty($this->bean->id) && !empty($_REQUEST['return_module']) &&$_REQUEST['return_module'] == 'Prospects' ) {
			require_once('modules/Prospects/Prospect.php');
			$prospect=new Prospect();
			$prospect->retrieve($_REQUEST['return_id']);
			foreach($prospect->field_defs as $key=>$value)
			{
				if ($key == 'id' or $key=='deleted' )continue;
				if (isset($this->bean->field_defs[$key])) {
					$this->bean->$key = $prospect->$key;
				} 
			}
			$_POST['is_converted']=true;
		}
		return true;	
	}
	function action_editview(){
		$this->view = 'edit';
		return true;
	}
	function action_listview() {
        $this->view_object_map['bean'] = $this->bean;
        $this->view = 'list';
        $GLOBALS['view'] = $this->view;
        $this->bean = new LeadsInListView();
    } 	
	function action_Register_Lead(){
		$register_lead_id=$this->bean->id;
		global $db;
		$status=$this->bean->status;
		if($this->bean->js_source_c)
			$js_source=$this->bean->js_source_c;
		else
			$js_source=fetchLeadSource($this->bean->source_c,$this->bean->campaign_id,$db);
			if($this->bean->jsprofileid_c){
				$sql1="select PROFILEID,INCOMPLETE from newjs.JPROFILE where USERNAME='".$this->bean->jsprofileid_c."'";
				$rs = $db->query($sql1);
                    		$row = $db->fetchByAssoc($rs);
				$profileid=$row[PROFILEID];
				$incomplete=$row[INCOMPLETE];
			if($incomplete=='N'){
				$checksum=md5($profileid)."i".$profileid;
				$protect_obj=new protect;
				$echecksum=$protect_obj->js_encrypt($checksum);
				if($this->bean->disposition_c==30 || $this->bean->disposition_c==1)
					$go_url="/social/addPhotos?";
				elseif(in_array($this->bean->disposition_c,array(29,2,31,3)))
					$go_url='/P/viewprofile.php?ownview=1&';
				if($go_url){
			echo "<script language=javascript>window.open('".$_SERVER['HOST_URL'].$go_url."checksum=$checksum&echecksum=$echecksum&CMGFRMMMMJS=LEAD');
				document.location='index.php?module=Leads&action=DetailView&record=$register_lead_id';</script>";die;}
				else $incomp=1;
			}
			if($incomp || $incomplete=='Y'){
			echo "<script language=javascript>window.open('".$_SERVER['HOST_URL']."/P/sugarcrm_registration/registration_page1.php?record_id=$register_lead_id&from_sugar_exec=Y&sugar_incomplete=Y&source=$js_source&secondary_source=C');
			document.location='index.php?module=Leads&action=DetailView&record=$register_lead_id';</script>";die;
		}
		}
		else{
			echo "<script language=javascript>window.open('".$_SERVER['HOST_URL']."/P/sugarcrm_registration/registration_page1.php?record_id=$register_lead_id&from_sugar_exec=Y&source=$js_source&secondary_source=C');
			document.location='index.php?module=Leads&action=DetailView&record=$register_lead_id';</script>";
		}
		//header("Location: index.php?module=Leads&action=DetailView&record=$register_lead_id");
}
}
?>
