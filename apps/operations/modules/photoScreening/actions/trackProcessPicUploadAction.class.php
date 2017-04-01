<?php

/**
 * photoScreening actions.
 *
 * @package    operation
 * @subpackage photoScreening
 * @author     Reshu Rajput
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z 
 */
class trackProcessPicUploadAction extends sfActions {


        /**
         * Executes index action
         * *
         * @param sfRequest $request A request object
         */
		public function executeTrackProcessPicUpload(sfWebRequest $request) {
			$formArr = $request->getParameterHolder()->getAll();
			$loadTime = $request->getParameter("loadtime");
			$url = $request->getParameter("url");
			$img = get_headers($url, 1);
			$size= $img["Content-Length"];
			$obj = new PICTURE_PROCESS_INTERFACE_UPLOAD_TRACKING();
			$obj->insert($loadTime,$size);
			return sfView::NONE;
			die;
                
		}
        
}
