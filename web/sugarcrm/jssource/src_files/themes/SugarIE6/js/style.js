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
 


YAHOO.util.Event.onDOMReady(function() 
{
    // This enables the iframe workaround for IE6 for the dropdown menus
    try {
        YAHOO.widget.AutoComplete.prototype.useIFrame = true;
    } 
    catch (e) {
    }
    // Bug 32389 - Fix styling of UpgradeWizard 'Upgrade Steps' box; can't be done thru CSS
    var nodes = YAHOO.util.Selector.query('.detail tr td[scope="row"]');
    for (var i = 0; i < nodes.length; i++) {
        nodes[i].style.backgroundColor = '#f6f6f6';
    }
    var nodes = YAHOO.util.Selector.query('.small tr td[scope="row"] table tr td, .small tr td[scope="row"] table tr th');
    for (var i = 0; i < nodes.length; i++) {
        nodes[i].style.backgroundColor = 'transparent';
    }
});

/**
 * shows mouseover popup for moduleTabExtraMenu and globalLinks
 */
YAHOO.util.Event.onContentReady('moduleTabExtraMenu', function(){
    document.getElementById('moduleTabExtraMenu').onmouseover = function(){
        handle = YAHOO.util.Selector.query('a','moduleTabExtraMenu',true);
        handle.id = 'moduleTabExtraMoreHandle';
        menu = YAHOO.util.Selector.query('ul.cssmenu','moduleTabExtraMenu',true);
        menu.id = 'moduleTabExtraMoreMenu';
        tbButtonMouseOver('moduleTabExtraMoreHandle',95,15,0);
    }
});
YAHOO.util.Event.onContentReady('globalLinks', function(){
    items = document.getElementById('globalLinks').getElementsByTagName('li');
    for (var i = 0; i < items.length; i++) {
        if ( !YAHOO.util.Selector.query('ul.cssmenu',items[i],true) )
            continue;
        items[i].onmouseover = function(){
            idName = 'globalLinks'+Math.round(Math.random()*10000).toString()
            handle = YAHOO.util.Selector.query('a',this,true);
            if ( handle.id == '' )
                handle.id = idName+'MoreHandle';
            menu = YAHOO.util.Selector.query('ul.cssmenu',this,true);
            if ( menu.id == '' )
                menu.id = idName+'MoreMenu';
            tbButtonMouseOver(handle.id,25,'',-6);
        }
    }
});
YAHOO.util.Event.onContentReady('subModuleList', function(){
    items = document.getElementById('subModuleList').getElementsByTagName('li');
    for (var i = 0; i < items.length; i++) {
        if ( items[i].className != 'subTabMore' )
            continue;
        items[i].onmouseover = function(){
            idName = 'subModuleList'+Math.round(Math.random()*10000).toString()
            handle = YAHOO.util.Selector.query('a',this,true);
            if ( handle.id == '' )
                handle.id = idName+'MoreHandle';
            menu = YAHOO.util.Selector.query('ul.cssmenu',this,true);
            if ( menu.id == '' )
                menu.id = idName+'MoreMenu';
            tbButtonMouseOver(handle.id,20,-200,0);
        }
    }
});
