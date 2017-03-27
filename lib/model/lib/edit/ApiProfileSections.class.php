<?php
/**
 * @class ApiProfileSections
 * Returns api Profile section object depending upon mobile or App
 * Will be used for Detailed profile page and my profile page in Mobile Site And App.
 * */
class ApiProfileSections 
{


	/**
	 * return apiProfileSection Obj
	 * @param ProfileS $profile
	 * @param isEdit $isEdit
	 * @return apiProfileSection Obj
	 */
		public static function getApiProfileSectionObj($profile,$isEdit='',$forShowStat='')
		{
			if($forShowStat=='1'){
				$apiProfileSectionObj = new ApiProfileSectionsDesktop($profile,$isEdit='');
			}
			else if(MobileCommon::isDesktop()){
				$apiProfileSectionObj = new ApiProfileSectionsDesktop($profile,$isEdit='');
			}
			else if(MobileCommon::isNewMobileSite()){
				$apiProfileSectionObj = new ApiProfileSectionsMobile($profile,$isEdit='');
			}
			else {
				$apiProfileSectionObj = new ApiProfileSectionsApp($profile,$isEdit='');
			}
				
			return $apiProfileSectionObj;
		}

}	
?>
