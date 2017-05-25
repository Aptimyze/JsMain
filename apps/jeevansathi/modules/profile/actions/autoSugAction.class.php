<?php
class autoSugAction extends sfAction {
	  
	public function execute($request) {
    $obj = new AutoSuggestCaste;
    $obj->Process($request);
    unset($obj);
		return sfView::NONE;
	}
}
?>
