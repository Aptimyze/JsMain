<?php

class socialTracking
{

	public function __construct()
	{
		$this->trackingObj = new SOCIAL_TRACKING();
	}

	public function trackAddPhotosPage(sfWebRequest $request)
	{
		$this->trackingObj->addCountAddPhotos();
	}

	public function trackPermissionPage(sfWebRequest $request)
	{
		$this->trackingObj->addCountPermission($request->getParameter('importSite'));
	}

	public function trackAlbumsPage(sfWebRequest $request)
	{
		$this->trackingObj->addCountAlbums($request->getParameter('importSite'));
	}

	public function trackPhotosPage(sfWebRequest $request)
	{
		$this->trackingObj->addCountPhotos($request->getParameter('importSite'));
	}

	public function trackSaveImagePage(sfWebRequest $request)
	{
		$this->trackingObj->addCountSave($request->getParameter('importSite'));
	}
}

?>
