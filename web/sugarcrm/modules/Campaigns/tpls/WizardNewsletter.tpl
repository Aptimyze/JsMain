{*
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
/*********************************************************************************

 ********************************************************************************/
*}

{$ROLLOVERSTYLE}
<form  id="wizform" name="wizform" method="POST" action="index.php">
	<input type="hidden" name="module" value="Campaigns">
	<input type="hidden" name="record" value="{$ID}">
	<input type="hidden" id="action" name="action">
	<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
	<input type="hidden" name="return_id" value="{$RETURN_ID}">
	<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
	<input type='hidden' name='campaign_type' value="{$MOD.LBL_NEWSLETTER_FORENTRY}">
	<input type="hidden" id="wiz_total_steps" name="totalsteps" value="{$TOTAL_STEPS}">
	<input type="hidden" id="wiz_current_step" name="currentstep" value='1'>
	<input type="hidden" id="direction" name="wiz_direction" value='exit'>		

<p>
	<div id ='buttons'>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
			<tr>
				<td align="left" width='30%'>
					<table border="0" cellspacing="0" cellpadding="0" ><tr>
						<td><div id="back_button_div"><input id="wiz_back_button" type='button' title="{$APP.LBL_BACK}" class="button" onclick="javascript:navigate('back');"  name="back" value="  {$APP.LBL_BACK}"></div></td>
						<td><div id="cancel_button_div"><input id="wiz_cancel_button" title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="this.form.action.value='WizardHome'; this.form.module.value='Campaigns'; this.form.record.value='{$RETURN_ID}';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}"></div></td>
						<td nowrap="nowrap">
						<div id="save_button_div">
							<input id="wiz_submit_button" class="button" onclick="this.form.action.value='WizardNewsletterSave';this.form.direction.value='continue';gatherTrackers();"  accesKey="{$APP.LBL_SAVE_BUTTON_TITLE}" type="{$HIDE_CONTINUE}" name="button" value="{$MOD.LBL_SAVE_CONTINUE_BUTTON_LABEL}" ><input id="wiz_submit_finish_button" class="button" onclick="this.form.action.value='WizardNewsletterSave';this.form.direction.value='exit';gatherTrackers();"  accesKey="{$APP.LBL_SAVE_BUTTON_TITLE}" type="submit" name="button" value="{$MOD.LBL_SAVE_EXIT_BUTTON_LABEL}" >
						</div></td>
						<td><div id="next_button_div"><input id="wiz_next_button" type='button' title="{$APP.LBL_NEXT_BUTTON_LABEL}" class="button" onclick="javascript:navigate('next');" name="button" value="{$APP.LBL_NEXT_BUTTON_LABEL}"></div></td>
					</tr></table>
				</td>
				<td  align="right" width='70%'><div id='wiz_location_message'></td>
				
			</tr>
		</table>
	</div>
</p>
<table class='other view' cellspacing="1">
<tr>
<td scope='row' rowspan='2' width="10%" style="vertical-align: top;">
<div id='nav' >
{$NAV_ITEMS}

</div>

</td>
<td class='edit view' rowspan='2' width='100%'>
<div id="wiz_message"></div>
<div id=wizard>

{$STEPS}


</div>
</td>
</tr>
</table>
</form>

<script type="text/javascript" src="include/javascript/popup_parent_helper.js?s={$SUGAR_VERSION}&c={$JS_CUSTOM_VERSION}"></script>
<script type="text/javascript" language="javascript" src="modules/Campaigns/wizard.js"></script>
<script type='text/javascript' src='include/javascript/sugar_grp_overlib.js'></script>
<div id='overDiv' style='position:absolute; visibility:hidden; z-index:1000;'></div>

{$WIZ_JAVASCRIPT}
{$DIV_JAVASCRIPT}
{$JAVASCRIPT}
<script language="javascript">
{$HILITE_ALL}
</script>

