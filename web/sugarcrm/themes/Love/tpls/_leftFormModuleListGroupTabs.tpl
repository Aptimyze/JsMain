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
<div id="moduleList" class="leftList">
<ul class="shortcuts">
    <li class="TopBorder">
    	<span class="TopBorderLeft"></span>
    </li>
    {assign var="groupSelected" value=false}
    {foreach from=$groupTabs item=modules key=group name=groupList}
    {if ( in_array($MODULE_TAB,$modules.modules) && !$groupSelected ) || ($smarty.foreach.groupList.index == 0 && $defaultFirst)}
    <li class="currentShortcut">
        <a href="{sugar_link module=$modules.modules.0 link_only=1}" 
            id="grouptab_{$smarty.foreach.groupList.index}">{$group}</a>
        {foreach from=$modules.modules item=module}
        <li class="noBorder subshortcut">
            {sugar_link id="moduleTab_$name" module=$module data=$module}
        </li>
        {/foreach}
        {foreach from=$subMoreModules.$group.modules item=submodule}
        <li class="noBorder subshortcut">
            <a href="{sugar_link module=$submodule link_only=1}">{$submodule}</a>
        </li>
        {/foreach}
    	{assign var="groupSelected" value=true}
    {else}
    <li class="notCurrentShortcut">
        <a href="{sugar_link module=$modules.modules.0 link_only=1}" 
            id="grouptab_{$smarty.foreach.groupList.index}">{$group}</a>
        <ul class="cssmenu">
            {foreach from=$modules.modules item=module}
            <li class="noBorder subshortcut">
                {sugar_link id="moduleTab_$name" module=$module data=$module}
            </li>
            {/foreach}
            {foreach from=$subMoreModules.$group.modules item=submodule}
            <li class="noBorder subshortcut">
                <a href="{sugar_link module=$submodule link_only=1}">{$submodule}</a>
            </li>
            {/foreach}
        </ul>
    {/if}
    </li>
    {/foreach}
    <li class="BottomBorder">
        <span class="BottomBorderLeft"></span>
    </li>
</ul>
</div>

