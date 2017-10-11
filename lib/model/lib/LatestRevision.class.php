<?php
//This class fetches the current revision number from localStorageRevision.txt and is called from AuthFilter.class.php
class LatestRevision{
	public function getLatestRevision(){
		$r_N_U_M = file_get_contents(JsConstants::$cronDocRoot.'/capistrano/localStorageRevision.txt');
		if($r_N_U_M == false)
			return 0;
		else
			return $r_N_U_M;			
	}
}