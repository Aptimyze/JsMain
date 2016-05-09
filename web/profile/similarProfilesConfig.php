<?php

/**
  * This file contains values of all configurable variables being used in fetching profiles for the similar profiles section on the detailed profile page.
  * Author: Prinka Wadhwa
**/

$suggAlgoScoreConst = 0.1; //constant used in contacts-algo
$suggAlgoMinimumNoOfContactsRequired=10; //minimum no of contacts required for executing contacts algo
$suggAlgoMaxLengthOfEachField = 16; //to be dislayed in the similar profile section of view profile page
$suggAlgoNoOfResults = 15; //total no of results to be shown for a profile
$suggAlgoNoOfResultsToBeShownAtATime=5; //max no of results to be shown at a time //changes for this variable to made in modules/profile/config/module.yml
$suggAlgoNoOfResultsToBeFetched=16; //no of results to be fetched from search, so that viewed and inactivated profiles can be removed
$suggAlgoTimeToStoreResultsInMemcache=24*60*60; //total time for which logged-out similar profile results are stored in memcache
$suggAlgoNoOfResultsForEOI = 18; //EOI Recommendation - total no of results to be shown
$suggAlgoNoOfResultsNoOfPages = 3;
?>
