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

var arr; 
var arr1; 
if(document.EditView)
{
function Check() { 
if(document.EditView.caste_c || document.EditView.religion_c) { 
	var caste_c = document.EditView.caste_c.options; 
	arr = new Array; 
	for(i=0; i<caste_c.length; i++) { 
arr.push(caste_c[i].value, caste_c[i].text); 
} 
} 
initData();
}
/*if(document.EditView.product_value_c || document.EditView.product_c) { 
	var product_value_c = document.EditView.product_value_c.options; 
	arr1 = new Array; 
	for(i=0; i<product_value_c.length; i++) { 
arr1.push(product_value_c[i].value, product_value_c[i].text); 
} 
}
initProduct(); */
} 
 
function initData(){ 
if(document.EditView)
{
	var current_p= document.EditView.religion_c; 
	var code_p = current_p.value; 
	var current_v= document.EditView.caste_c; 
	var code_v = current_v.value; 
	var code_v_idx = 0; 
 
	var select_ticket = document.EditView.caste_c.options; 
	select_ticket.length=0; 
	var l = 0; 
	for(k=0; k<arr.length; k+=2) { 
	 if(arr[k].substr(0,1) == code_p || arr[k] == '') { 
	 select_ticket.length++; 
	 select_ticket[select_ticket.length-1].value = arr[k]; 
	 select_ticket[select_ticket.length-1].text = arr[k+1]; 
	 if(code_v == arr[k]){ 
		 code_v_idx = l; 
	 } 
	 l++; 
	 } 
	} 
	if(code_p == ''){ 
		select_ticket[select_ticket.length-1].value = ''; 
	 select_ticket[select_ticket.length-1].text = 'Select from religion'; 
	} 
	document.EditView.caste_c.selectedIndex = code_v_idx;; 
}
}

 
/*function initProduct(){
        var current_p= document.EditView.product_c;              
	var code_p = current_p.value;
        var current_v= document.EditView.product_value_c;
        var code_v = current_v.value;
        var code_v_idx = 0;

        var select_ticket = document.EditView.product_value_c.options;
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
        document.EditView.product_value_c.selectedIndex = code_v_idx;;
} */
if(document.EditView)
{
if (window.addEventListener) 
window.addEventListener("load", Check, false); 
else if (window.attachEvent)
window.attachEvent("onload", Check);
else if (document.getElementById)
window.onload=Check;
}
