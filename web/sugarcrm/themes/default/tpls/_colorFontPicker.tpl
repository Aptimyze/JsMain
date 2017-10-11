{*
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2010 SugarCRM Inc.
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
*}
{if $AUTHENTICATED}
<div id="colorPicker">
    <span>|</span>
    <span id="themepickerLinkSpan">
        {$APP.LBL_THEME_PICKER}
    </span>
</div>

<div id="themepickerDialog" style="display:none;">
    <div class="hd">
        {$APP.LBL_THEME_PICKER}
    </div>	
    <div id="stylepicker" class="edit view">
        <form id="themepickerDialogForm" name="themepickerDialogForm" method="POST"
                class='themePicker' action="{$smarty.server.REQUEST_URI}">
            <table width='100%' border='0' cellpadding='0' cellspacing='1'>
            <tr>
                <td scope="row">{$APP.LBL_THEME}</td>
                <td>
                    <select name="usertheme" id="usertheme">
                    {$selectThemeOptions}
                    </select>
                </td>
           </tr>
            {if $currentTheme_groupedTabscompat}
            {capture name=SMARTY_NAVIGATION_PARADIGM_DESC}&nbsp;{$USERS_MOD.LBL_NAVIGATION_PARADIGM_DESCRIPTION}<i>{$USERS_MOD.LBL_SUPPORTED_THEME_ONLY}</i>{/capture}
            <tr>
                <td scope="row">{$USERS_MOD.LBL_NAVIGATION_PARADIGM}:</td>
                <td>
                    <select name="userthemegrouptabs" id='userthemegrouptabs'>
                        {$NAVADIGMS}
                    </select>
                </td>
            </tr>
            {/if}
            {if count($currentTheme_colors) > 1 || count($currentTheme_fonts) > 1}
            <tr>
            <td colspan="2"><hr /></td>
            </tr>
            {/if}
           {if count($currentTheme_colors) > 1}
           <tr>
                <td colspan="2">
                    <input type="hidden" name="usercolor" id="usercolor" value="">
                    <ul id="color_menu">
                    {foreach from=$currentTheme_colors item=color name=theme_colors}
                    {capture name=themeColorIcon assign=colorIcon}colors.{$color}.icon.gif{/capture}
                    <li style="background: url({sugar_getimagepath file=$colorIcon}) no-repeat center;" onclick="SUGAR.themes.changeColor('{$color}');"></li>
                    {/foreach}
                    </ul>
                </td>
           </tr>
           {/if}
           {if count($currentTheme_fonts) > 1}
           <tr>
                <td colspan="2">
                    <input type="hidden" name="userfont" id="userfont" value="">
                    <ul id="font_menu">
                    {foreach from=$currentTheme_fonts item=font name=theme_fonts}
                    {capture name=themeFontIcon assign=fontIcon}fonts.{$font}.icon.gif{/capture}
                    <li style="background: url({sugar_getimagepath file=$fontIcon}) no-repeat center;" onclick="SUGAR.themes.changeFont('{$font}');"></li>
                    {/foreach}
                    </ul>
                </td>
            </tr>
            {/if}
            </table>
        </form>
    </div>
</div>
{/if}
