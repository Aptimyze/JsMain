<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

require_once('include/Dashlets/Dashlet.php');


class iFrameDashlet extends Dashlet {
    var $displayTpl = 'modules/Home/Dashlets/iFrameDashlet/display.tpl';
    var $configureTpl = 'modules/Home/Dashlets/iFrameDashlet/configure.tpl';
    var $defaultURL = 'http://apps.sugarcrm.com/dashlet/5.2.0/sugarcrm-news-dashlet.html?lang=@@LANG@@&edition=@@EDITION@@&ver=@@VER@@';
    var $url;

    function iFrameDashlet($id, $options = null) {
        parent::Dashlet($id);
        $this->isConfigurable = true;
        
        if(empty($options['title'])) { 
            $this->title = translate('LBL_DASHLET_TITLE', 'Home');
            $this->title = translate('LBL_DASHLET_DISCOVER_SUGAR_PRO', 'Home');
        } else {
            $this->title = $options['title'];
        }
        if(empty($options['url'])) { 
            $this->url = $this->defaultURL;
            $this->url = 'http://apps.sugarcrm.com/dashlet/5.2.0/go-pro.html?lang=@@LANG@@&edition=@@EDITION@@&ver=@@VER@@';
        } else {
            $this->url = $options['url'];
        }

        if(empty($options['height']) || (int)$options['height'] < 1 ) { 
            $this->height = 315; 
        } else {
            $this->height = (int)$options['height'];
        }

    }

    function displayOptions() {
        global $app_strings;
        $ss = new Sugar_Smarty();
        $ss->assign('titleLBL', translate('LBL_DASHLET_OPT_TITLE', 'Home'));
		$ss->assign('urlLBL', translate('LBL_DASHLET_OPT_URL', 'Home'));
		$ss->assign('heightLBL', translate('LBL_DASHLET_OPT_HEIGHT', 'Home'));
        $ss->assign('title', $this->title);
        $ss->assign('url', $this->url);
        $ss->assign('id', $this->id);
        $ss->assign('height', $this->height);
        $ss->assign('saveLBL', $app_strings['LBL_SAVE_BUTTON_LABEL']);
        
        return  $ss->fetch('modules/Home/Dashlets/iFrameDashlet/configure.tpl');        
    }

    function saveOptions($req) {
        $options = array();
        
        if ( isset($req['title']) ) {
            $options['title'] = $req['title'];
        }
        if ( isset($req['url']) ) {
            $options['url'] = $req['url'];
        }
        if ( isset($req['height']) ) {
            $options['height'] = (int)$req['height'];
        }

        return $options;
    }

    function display(){

        $sugar_edition = 'COM';

        $out_url = str_replace(
            array('@@LANG@@','@@VER@@','@@EDITION@@'),
            array($GLOBALS['current_language'],$GLOBALS['sugar_config']['sugar_version'],$sugar_edition),
            $this->url);
        return parent::display() . "<iframe class='teamNoticeBox' src='".$out_url."' height='".$this->height."px'></iframe>";
    }
}