<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.edit.php');
class LeadsViewEdit extends ViewEdit {
     function LeadsViewEdit(){
          parent::ViewEdit();
     }
     function display() {
		global $app_list_strings;
global $mod_strings;
global $current_user;
$city=$current_user->get_city();
$fieldName = 'lead_source'; //change this to the name of your field
$dropDownName = 'Product_value_list'; //change this to the name of the dropdown
$dropDownName1 = 'City_list'; //change this to the name of the dropdown
//Added for Trac # 845 - removal of save and continue button
$this->ev->showVCRControl=false;
$e=$this->ev->defs['panels'];
$panelArray = array();
foreach($e as $panel_label=>$panel_data) {
           if($panel_label != '' && $panel_label != 'default')
           {
                     $tempArray = array($panel_label,$mod_strings[strtoupper($panel_label)]);
                     array_push($panelArray,$tempArray);
           }
}
$prePop = 'document.getElementById(\''.$fieldName.'\').onchange();';
//$prePop.= ' document.getElementById(\'product_c\').onchange();';
print '<script>var arr1= new Array();';
print 'var selVal=0;';
                     print 'var fieldName =\''.$fieldName .'\';';
			foreach($app_list_strings[$dropDownName] as $key=>$val)
			{
				 if($val != '')
				 {
					      print "arr1.push(\"$key\",\"$val\");";
				 }
			}
			foreach($app_list_strings[$dropDownName1] as $key=>$val)
			{
				 if($val == $city)
					      print 'var selVal=\''.$key.'\';';
			}
print '</script>';
$js=<<<EOQ
                  <script>
		//document.getElementById('city_c').value=selVal;
     		document.getElementById(fieldName).onchange = function() {
                  if(document.getElementsByName(fieldName)[0].value!='4')
                  {
			   document.getElementById('LBL_EDITVIEW_PANEL5').style.display="none";
                  }
		  else
			   document.getElementById('LBL_EDITVIEW_PANEL5').style.display="block";
                      };
     /*document.getElementById('product_c').onchange = function() {
		var code_p= document.getElementById('product_c').value;
		var code_v= document.getElementById('product_value_c[]').value;
        	var code_v_idx = 0;
		var select_ticket = document.getElementById('product_value_c[]').options;
		select_ticket.length=0;
		var l = 0;

		for(k=0; k<arr1.length; k+=2) {
			 if(arr1[k].substr(0,1) == code_p || arr1[k] == '') {
				 select_ticket.length++;
				 select_ticket[select_ticket.length-1].value = arr1[k];
				 select_ticket[select_ticket.length-1].text = arr1[k+1];
				 if(code_v == arr1[k]){
					 code_v_idx = l;
				 }
				 l++;
         		}
        	}
		if(code_p == ''){
                select_ticket[select_ticket.length-1].value = '';
		 select_ticket[select_ticket.length-1].text = 'Select from Product';
		}
		document.getElementById('product_value_c[]').selectedIndex = code_v_idx;;
	};*/
       $prePop;
	if(document.EditView)
		document.EditView.status.onchange();
       </script>
EOQ;
	    echo '<link rel="stylesheet" href="../css/autoSuggest_jq_css.css" type="text/css">'; 	
            parent::display();
            echo $js;
       }
}
?>
