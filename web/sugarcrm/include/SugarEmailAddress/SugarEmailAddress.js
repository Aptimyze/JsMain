/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2010 SugarCRM Inc.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/
(function(){if(SUGAR.EmailAddressWidget)return;var Dom=YAHOO.util.Dom;SUGAR.EmailAddressWidget=function(module){if(!SUGAR.EmailAddressWidget.count[module])SUGAR.EmailAddressWidget.count[module]=0;this.count=SUGAR.EmailAddressWidget.count[module];SUGAR.EmailAddressWidget.count[module]++;this.module=module;this.id=this.module+this.count;if(document.getElementById(module+'_email_widget_id'))
document.getElementById(module+'_email_widget_id').value=this.id;SUGAR.EmailAddressWidget.instances[this.id]=this;}
SUGAR.EmailAddressWidget.instances={};SUGAR.EmailAddressWidget.count={};SUGAR.EmailAddressWidget.prototype={emailTemplate:'<tr id="emailAddressRow">'+'<td nowrap="NOWRAP"><input type="text" name="emailAddress{$index}" id="emailAddress0" size="30"/></td>'+'<td><span>&nbsp;</span><img id="removeButton0" name="0" src="index.php?entryPoint=getImage&amp;themeName=Sugar&amp;imageName=delete_inline.gif"/></td>'+'<td align="center"><input type="radio" name="emailAddressPrimaryFlag" id="emailAddressPrimaryFlag0" value="emailAddress0" enabled="true" checked="true"/></td>'+'<td align="center"><input type="checkbox" name="emailAddressOptOutFlag[]" id="emailAddressOptOutFlag0" value="emailAddress0" enabled="true"/></td>'+'<td align="center"><input type="checkbox" name="emailAddressInvalidFlag[]" id="emailAddressInvalidFlag0" value="emailAddress0" enabled="true"/></td>'+'<td><input type="hidden" name="emailAddressVerifiedFlag0" id="emailAddressVerifiedFlag0" value="true"/></td>'+'<td><input type="hidden" name="emailAddressVerifiedValue0" id="emailAddressVerifiedValue0" value=""/></td></tr>',numberEmailAddresses:0,replyToFlagObject:new Object(),verifying:false,enterPressed:false,tabPressed:false,emailView:"",emailIsRequired:false,prefillEmailAddresses:function(tableId,o){for(i=0;i<o.length;i++){o[i].email_address=o[i].email_address.replace('&#039;',"'");this.addEmailAddress(tableId,o[i].email_address,o[i].primary_address,o[i].reply_to_address,o[i].opt_out,o[i].invalid_email);}},retrieveEmailAddress:function(event)
{
	var callbackFunction=function success(data)
				{
					var vals=YAHOO.lang.JSON.parse(data.responseText);
					var target=vals.target;
					event=this.getEvent(event);
					if(vals.email)
					{
						var email=vals.email;
						if(email!=''&&/\d+$/.test(target))
						{
							var matches=target.match(/\d+$/);
							var targetNumber=matches[0];
							var optOutEl=Dom.get(this.id+'emailAddressOptOutFlag'+targetNumber);
							if(optOutEl)
							{
								optOutEl.checked=email['opt_out']==1?true:false;
							}
							var invalidEl=Dom.get(this.id+'emailAddressInvalidFlag'+targetNumber);
							if(invalidEl)
							{
								invalidEl.checked=email['invalid_email']==1?true:false;
							}
							if(email['error']==1)
                                                        {
                                                                document.getElementById('err').innerHTML='<font color="red">Duplicate Record!</font>';
                                                        }
                                                        else
                                                        {
                                                                document.getElementById('err').innerHTML='';
                                                        }
						}
					}
					else
                                        {
                                                document.getElementById('err').innerHTML='';
                                        }
					var index=/[a-z]*\d?emailAddress(\d+)/i.exec(target)[1];
					var verifyElementFlag=Dom.get(this.id+'emailAddressVerifiedFlag'+index);
					if(verifyElementFlag.parentNode.childNodes.length>1)
					{
						verifyElementFlag.parentNode.removeChild(verifyElementFlag.parentNode.lastChild);
					}
					var verifiedTextNode=document.createElement('span');
					verifiedTextNode.innerHTML='';
					verifyElementFlag.parentNode.appendChild(verifiedTextNode);
					verifyElementFlag.value="true";
					this.verifyElementValue=Dom.get(this.id+'emailAddressVerifiedValue'+index);
					this.verifyElementValue.value=Dom.get(this.id+'emailAddress'+index).value;
					this.verifying=false;
					var savePressed=false;
					if(event)
					{
						var elm=document.activeElement||event.explicitOriginalTarget;
						if(typeof elm.type!='undefined'&&/submit|button/.test(elm.type.toLowerCase()))
						{
							savePressed=true;
						}
					}
					if(savePressed||this.enterPressed)
					{
						setTimeout("SUGAR.EmailAddressWidget.instances."+this.id+".forceSubmit()",2100);}else if(this.tabPressed){Dom.get(this.id+'emailAddressPrimaryFlag'+index).focus();
					}
				}
				var event=this.getEvent(event);
				var targetEl=this.getEventElement(event);
				var index=/[a-z]*\d?emailAddress(\d+)/i.exec(targetEl.id)[1];
				var verifyElementFlag=Dom.get(this.id+'emailAddressVerifiedFlag'+index);
				this.verifyElementValue=Dom.get(this.id+'emailAddressVerifiedValue'+index);
				verifyElementFlag.value=(trim(targetEl.value)==''||targetEl.value==this.verifyElementValue.value)?"true":"false"
				if(verifyElementFlag.parentNode.childNodes.length>1)
				{
					verifyElementFlag.parentNode.removeChild(verifyElementFlag.parentNode.lastChild);
				}
				//if(/emailAddress\d+$/.test(targetEl.id)&&isValidEmail(targetEl.value)&&!this.verifying&&verifyElementFlag.value=="false")
				if(/emailAddress\d+$/.test(targetEl.id)&&targetEl.value!=''&&isValidEmail(targetEl.value)&&!this.verifying)
				{
					var formName=Dom.get(this.emailView);
                                        var record=formName.record.value;
					verifiedTextNode=document.createElement('span');
					verifyElementFlag.parentNode.appendChild(verifiedTextNode);
					verifiedTextNode.innerHTML=SUGAR.language.get('app_strings','LBL_VERIFY_EMAIL_ADDRESS');
					this.verifying=true;
					var cObj=YAHOO.util.Connect.asyncRequest('GET','index.php?&module=Contacts&action=RetrieveEmail&leadID='+record+'&target='+targetEl.id+'&email='+targetEl.value,{success:callbackFunction,failure:callbackFunction,scope:this});
				}
				else if(!isValidEmail(targetEl.value))
                                {
                                        document.getElementById('err').innerHTML='<font color="red">Invalid Email!</font>';
                                }
                                else if(!targetEl.value)
                                {
                                        document.getElementById('err').innerHTML='';
                                }
},handleKeyDown:function(event){/*var e=this.getEvent(event);var eL=this.getEventElement(e);if((kc=e["keyCode"])){this.enterPressed=(kc==13)?true:false;this.tabPressed=(kc==9)?true:false;if(this.enterPressed||this.tabPressed){this.retrieveEmailAddress(e);if(this.enterPressed);this.freezeEvent(e);}}*/},getEvent:function(event){return(event?event:window.event);},getEventElement:function(e){return(e.srcElement?e.srcElement:(e.target?e.target:e.currentTarget));},freezeEvent:function(e){if(e.preventDefault)e.preventDefault();e.returnValue=false;e.cancelBubble=true;if(e.stopPropagation)e.stopPropagation();return false;},addEmailAddress:function(tableId,address,primaryFlag,replyToFlag,optOutFlag,invalidFlag){if(this.addInProgress)
return;this.addInProgress=true;if(!address)
address="";var insertInto=Dom.get(tableId);var parentObj=insertInto.parentNode;var newContent=document.createElement("input");var nav=new String(navigator.appVersion);var newContentPrimaryFlag;if(SUGAR.isIE){var rv=-1;var navAg=navigator.userAgent;var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");if (re.exec(navAg)!= null)rv = parseFloat( RegExp.$ );if(rv>9){newContentPrimaryFlag=document.createElement("input");newContentPrimaryFlag.innerHTML="<input name='emailAddressPrimaryFlag' />";}else{newContentPrimaryFlag=document.createElement("input");}}else{newContentPrimaryFlag=document.createElement("input");}
var newContentReplyToFlag=document.createElement("input");var newContentOptOutFlag=document.createElement("input");var newContentInvalidFlag=document.createElement("input");var newContentVerifiedFlag=document.createElement("input");var newContentVerifiedValue=document.createElement("input");var removeButton=document.createElement("img");var tbody=document.createElement("tbody");var tr=document.createElement("tr");var td1=document.createElement("td");var td2=document.createElement("td");var td3=document.createElement("td");var td4=document.createElement("td");var td5=document.createElement("td");var td6=document.createElement("td");var td7=document.createElement("td");var td8=document.createElement("td");newContent.setAttribute("type","text");newContent.setAttribute("name",this.id+"emailAddress"+this.numberEmailAddresses);newContent.setAttribute("id",this.id+"emailAddress"+this.numberEmailAddresses);newContent.setAttribute("size","30");if(address!=''){newContent.setAttribute("value",address);}
removeButton.setAttribute("id",this.id+"removeButton"+this.numberEmailAddresses);removeButton.setAttribute("class","id-ff-remove");removeButton.setAttribute("name",this.numberEmailAddresses);removeButton.eaw=this;removeButton.setAttribute("src","index.php?entryPoint=getImage&themeName="+SUGAR.themes.theme_name+"&imageName=id-ff-remove.png");removeButton.onclick=function(){this.eaw.removeEmailAddress(this.name);};newContentPrimaryFlag.setAttribute("type","radio");newContentPrimaryFlag.setAttribute("name",this.id+"emailAddressPrimaryFlag");newContentPrimaryFlag.setAttribute("id",this.id+"emailAddressPrimaryFlag"+this.numberEmailAddresses);newContentPrimaryFlag.setAttribute("value",this.id+"emailAddress"+this.numberEmailAddresses);newContentPrimaryFlag.setAttribute("enabled","true");newContentReplyToFlag.setAttribute("type","radio");newContentReplyToFlag.setAttribute("name",this.id+"emailAddressReplyToFlag");newContentReplyToFlag.setAttribute("id",this.id+"emailAddressReplyToFlag"+this.numberEmailAddresses);newContentReplyToFlag.setAttribute("value",this.id+"emailAddress"+this.numberEmailAddresses);newContentReplyToFlag.setAttribute("enabled","true");newContentReplyToFlag.eaw=this;newContentReplyToFlag['onclick']=function(){var form=document.forms[this.eaw.emailView];if(!form){form=document.forms['editContactForm'];}
var nav=new String(navigator.appVersion);if(nav.match(/MSIE/gim)){for(i=0;i<form.elements.length;i++){var id=new String(form.elements[i].id);if(id.match(/emailAddressReplyToFlag/gim)&&form.elements[i].type=='radio'&&id!=this.eaw.id){form.elements[i].checked=false;}}}
for(i=0;i<form.elements.length;i++){var id=new String(form.elements[i].id);if(id.match(/emailAddressReplyToFlag/gim)&&form.elements[i].type=='radio'&&id!=this.eaw.id){this.eaw.replyToFlagObject[this.eaw.id]=false;}}
if(this.eaw.replyToFlagObject[this.id]){this.eaw.replyToFlagObject[this.id]=false;this.checked=false;}else{this.eaw.replyToFlagObject[this.id]=true;this.checked=true;}}
newContentOptOutFlag.setAttribute("type","checkbox");newContentOptOutFlag.setAttribute("name",this.id+"emailAddressOptOutFlag[]");newContentOptOutFlag.setAttribute("id",this.id+"emailAddressOptOutFlag"+this.numberEmailAddresses);newContentOptOutFlag.setAttribute("value",this.id+"emailAddress"+this.numberEmailAddresses);newContentOptOutFlag.setAttribute("enabled","true");newContentOptOutFlag.eaw=this;newContentOptOutFlag['onClick']=function(){this.eaw.toggleCheckbox(this)};newContentInvalidFlag.setAttribute("type","checkbox");newContentInvalidFlag.setAttribute("name",this.id+"emailAddressInvalidFlag[]");newContentInvalidFlag.setAttribute("id",this.id+"emailAddressInvalidFlag"+this.numberEmailAddresses);newContentInvalidFlag.setAttribute("value",this.id+"emailAddress"+this.numberEmailAddresses);newContentInvalidFlag.setAttribute("enabled","true");newContentInvalidFlag.eaw=this;newContentInvalidFlag['onClick']=function(){this.eaw.toggleCheckbox(this)};newContentVerifiedFlag.setAttribute("type","hidden");newContentVerifiedFlag.setAttribute("name",this.id+"emailAddressVerifiedFlag"+this.numberEmailAddresses);newContentVerifiedFlag.setAttribute("id",this.id+"emailAddressVerifiedFlag"+this.numberEmailAddresses);newContentVerifiedFlag.setAttribute("value","true");newContentVerifiedValue.setAttribute("type","hidden");newContentVerifiedValue.setAttribute("name",this.id+"emailAddressVerifiedValue"+this.numberEmailAddresses);newContentVerifiedValue.setAttribute("id",this.id+"emailAddressVerifiedValue"+this.numberEmailAddresses);newContentVerifiedValue.setAttribute("value",address);this.emailView=(this.emailView=='')?'EditView':this.emailView;addToValidateVerified(this.emailView,this.id+"emailAddressVerifiedFlag"+this.numberEmailAddresses,'bool',false,SUGAR.language.get('app_strings','LBL_VERIFY_EMAIL_ADDRESS'));tr.setAttribute("id",this.id+"emailAddressRow"+this.numberEmailAddresses);td1.setAttribute("nowrap","NOWRAP");td3.setAttribute("align","center");td4.setAttribute("align","center");td5.setAttribute("align","center");td6.setAttribute("align","center");td1.appendChild(newContent);td1.appendChild(document.createTextNode(" "));spanNode=document.createElement('span');spanNode.innerHTML='&nbsp;';td2.appendChild(spanNode);if(this.numberEmailAddresses!=0||typeof(this.emailIsRequired)=="undefined"||!this.emailIsRequired)
td2.appendChild(removeButton);td3.appendChild(newContentPrimaryFlag);td4.appendChild(newContentReplyToFlag);td5.appendChild(newContentOptOutFlag);td6.appendChild(newContentInvalidFlag);td7.appendChild(newContentVerifiedFlag);td8.appendChild(newContentVerifiedValue);tr.appendChild(td1);tr.appendChild(td2);tr.appendChild(td3);if(typeof(this.module)!='undefined'&&this.module=='Users'){tr.appendChild(td4);}else{tr.appendChild(td5);tr.appendChild(td6);}
tr.appendChild(td7);tr.appendChild(td8);tbody.appendChild(tr);insertInto.appendChild(tbody);parentObj.insertBefore(Dom.get('targetBody'),insertInto);if(primaryFlag=='1'||(this.numberEmailAddresses==0)){newContentPrimaryFlag.setAttribute("checked",'true');}
if(replyToFlag=='1'){newContentReplyToFlag.setAttribute("checked","true");}
if(replyToFlag=='1'){this.replyToFlagObject[newContentReplyToFlag.id]=true;}else{this.replyToFlagObject[newContentReplyToFlag.id]=false;}
if(optOutFlag=='1'){newContentOptOutFlag.setAttribute("checked",'true');}
if(invalidFlag=='1'){newContentInvalidFlag.setAttribute("checked","true");}
newContent.eaw=this;newContent.onblur=function(e){this.eaw.retrieveEmailAddress(e)};newContent.onkeydown=function(e){this.eaw.handleKeyDown(e)};addToValidate(this.emailView,this.id+'emailAddress'+this.numberEmailAddresses,'email',this.emailIsRequired,SUGAR.language.get('app_strings','LBL_EMAIL_ADDRESS_BOOK_EMAIL_ADDR'));this.numberEmailAddresses++;this.addInProgress=false;},removeEmailAddress:function(index){removeFromValidate(this.emailView,this.id+'emailAddress'+index);var oNodeToRemove=Dom.get(this.id+'emailAddressRow'+index);oNodeToRemove.parentNode.removeChild(oNodeToRemove);var removedIndex=parseInt(index);if(this.numberEmailAddresses!=removedIndex){for(var x=removedIndex+1;x<this.numberEmailAddresses;x++){Dom.get(this.id+'emailAddress'+x).setAttribute("name",this.id+"emailAddress"+(x-1));Dom.get(this.id+'emailAddress'+x).setAttribute("id",this.id+"emailAddress"+(x-1));if(Dom.get(this.id+'emailAddressInvalidFlag'+x)){Dom.get(this.id+'emailAddressInvalidFlag'+x).setAttribute("id",this.id+"emailAddressInvalidFlag"+(x-1));}
if(Dom.get(this.id+'emailAddressOptOutFlag'+x)){Dom.get(this.id+'emailAddressOptOutFlag'+x).setAttribute("id",this.id+"emailAddressOptOutFlag"+(x-1));}
if(Dom.get(this.id+'emailAddressPrimaryFlag'+x)){Dom.get(this.id+'emailAddressPrimaryFlag'+x).setAttribute("id",this.id+"emailAddressPrimaryFlag"+(x-1));}
Dom.get(this.id+'emailAddressVerifiedValue'+x).setAttribute("id",this.id+"emailAddressVerifiedValue"+(x-1));Dom.get(this.id+'emailAddressVerifiedFlag'+x).setAttribute("id",this.id+"emailAddressVerifiedFlag"+(x-1));var rButton=Dom.get(this.id+'removeButton'+x);rButton.setAttribute("name",(x-1));rButton.setAttribute("id",this.id+"removeButton"+(x-1));Dom.get(this.id+'emailAddressRow'+x).setAttribute("id",this.id+'emailAddressRow'+(x-1));}}
this.numberEmailAddresses--;if(this.numberEmailAddresses==0){return;}
var primaryFound=false;for(x=0;x<this.numberEmailAddresses;x++){if(Dom.get(this.id+'emailAddressPrimaryFlag'+x).checked){primaryFound=true;}}
if(!primaryFound){Dom.get(this.id+'emailAddressPrimaryFlag0').checked=true;Dom.get(this.id+'emailAddressPrimaryFlag0').value=this.id+'emailAddress0';}},toggleCheckbox:function(el)
{var form=document.forms[this.emailView];if(!form){form=document.forms['editContactForm'];}
if(SUGAR.isIE){for(i=0;i<form.elements.length;i++){var id=new String(form.elements[i].id);if(id.match(/emailAddressInvalidFlag/gim)&&form.elements[i].type=='checkbox'&&id!=el.id){form.elements[i].checked=false;}}
el.checked=true;}},forceSubmit:function(){var theForm=Dom.get(this.emailView);if(theForm){theForm.action.value='Save';if(!check_form(this.emailView)){return false;}
if(this.emailView=='EditView'){theForm.submit();}else if(this.emailView=='QuickCreate'){SUGAR.subpanelUtils.inlineSave(theForm.id,theForm.module.value.toLowerCase());}}}};emailAddressWidgetLoaded=true;})();
