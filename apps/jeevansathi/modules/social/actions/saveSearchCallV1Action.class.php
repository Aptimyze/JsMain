<?php

//This class performs the save search functioning 
class saveSearchCallV1Action extends sfActions {

    public function execute($request) {
        $perform = $request->getParameter("perform");      //perform param provide the action to be performed
        if ($perform == "delete") {
            $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
            $ID = $request->getParameter("profileid");	
            $PROFILEID = $loggedInProfileObj->getPROFILEID(); 	//profileID of user from post
            //logout case handling
            if ($loggedInProfileObj && $loggedInProfileObj->getPROFILEID() == '') {
                $this->forward("static", "logoutPage");
            }
            
            elseif($ID!=$PROFILEID)					//profileID from post matches with the user profileID
            die("profileid not match"); 
            else {
                $SearchID = $request->getParameter("searchid");
                $objRemoveSaveSearch = new UserSavedSearches($loggedInProfileObj);
                $objRemoveSaveSearch->deleteRecord($SearchID);
            }
        }

        if ($perform == "listing") {
            $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
            if ($loggedInProfileObj && $loggedInProfileObj->getPROFILEID() == '') {
                $this->forward("static", "logoutPage");
            } else {
				$UserSaveSearch = $objSaveSearch->countRecord();
				if($UserSaveSearch==0)
					die("no save search");				//if there is no save search
                $userSavedSearchesObj = new UserSavedSearches($loggedInProfileObj);
                $savedSearches = $userSavedSearchesObj->getSavedSearches();		//get the searches saved by user
                
                if ($savedSearches && is_array($savedSearches)) {
                    foreach ($savedSearches as $k => $v) {
                        $arr1[] = $v["SEARCH_NAME"];
                        $arr2[] = $v["ID"];
                    }
                }
                print_r($savedSearches);				// save searches with their savesearchid associated with the search name
                die;
            }
        }
        if ($perform == "savesearch") {

            $saveSearchName = trim($request->getParameter('saveSearchName'));		//save search name given by user to save search
            $searchId = $request->getParameter('searchId');							//searchid of the particular search
            $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			//logout case handling
            if ($loggedInProfileObj && $loggedInProfileObj->getPROFILEID() == '') {
                $this->forward("static", "logoutPage");
            } 
            else {
                $objSaveSearch = new UserSavedSearches($loggedInProfileObj);
                $UserSaveSearch = $objSaveSearch->countRecord();
                if ($UserSaveSearch == SearchConfig::$maxSaveSearchesAllowed) {
                    die("max limit reached");
                }
                if (!$saveSearchName)        //At this stage if save Search Name is not set then die
                    die("save search cannot be blank");

                $savedSearches = $objSaveSearch->getSavedSearches();
                //print_r($savedSearches);die;
                foreach ($savedSearches as $k => $v) {
                    if (in_array($saveSearchName, $v))    //If saveSearchName already exists in the saved searches then die
                        die("Search Name Same");
                }
                $SearchParamtersLayer = new SearchParamtersLayer;
                $SearchParamtersObj = $SearchParamtersLayer->setSearchParamters($request, $loggedInProfileObj);
                if ($loggedInProfileObj->getGENDER() == $SearchParamtersObj->getGENDER())
                    die("same gender search");
                $UserSavedSearches = new UserSavedSearches($loggedInProfileObj);
                $success = $UserSavedSearches->SaveSearch($SearchParamtersObj, $saveSearchName, $saveSearchId); //Insert into database
                echo($success);			// save search id for that particular search
                die;
                if ($success == '0')  //If no row inserted then error
                    die("Insert Error");
                $key = $loggedInProfileObj->getPROFILEID() . "SAVESEARCH";
            }
        }
    }

}
