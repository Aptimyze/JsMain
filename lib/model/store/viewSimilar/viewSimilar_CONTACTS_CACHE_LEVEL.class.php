<?php

/* This class provided functions for similar profile functionality
 * @author : Akash Kumar
 * @created : July 14, 2014
 */

class viewSimilar_CONTACTS_CACHE_LEVEL extends TABLE {
        /* This will connect to matchalert slave by default */

        public function __construct($dbname = "newjs_bmsSlave") {
                parent::__construct($dbname);
        }

        /** This store function is used to get profiles vied by a user
         * @param $viewedGender : Gender of profile viewed
         * @param $viewed : Profile ID of viewed profile
         * @return array array of profiled viewed by viewed profile ID
         */
        public function getViewedProfiles($viewedGender, $viewed) {
                try {
                        if($viewedGender!="MALE" and $viewedGender!="FEMALE" )
                        {
                        ValidationHandler::getValidationHandler("","Viewed Gender in viewSimilar_CONTACTS_CACHE_LEVEL -> getViewedProfiles is not set",1);
                        }
                        
                        $sql = "SELECT SQL_CACHE RECEIVER FROM viewSimilar.CONTACTS_CACHE_LEVEL1_" . $viewedGender . " WHERE SENDER=:SENDER";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":SENDER", $viewed, PDO::PARAM_INT);
                        $prep->execute();
                        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                                $contactsViewed[] = $row['RECEIVER'];
                        }
                        return $contactsViewed;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

        /** This store function is used to get suggested profiles when user contacted min number of specified users
         * @param $viewedOppositeGender : Opposite gender of profiled viewed
         * @param $viewedContactsStr : viewed profile contact string i.e string of profile IDs being contacted
         */
        public function getSuggestedProf($viewedOppositeGender, $viewedContactsStr, $lAge, $hAge) {
                try {
			$lAge=0;$hAge=100;
                        $viewedContactsStr = explode(",", $viewedContactsStr);
                        $i = 0;
                        $inStatement = "";
                        $j = "";
                        foreach ($viewedContactsStr as $key => $value) {
                                if ($i != 0) {
                                        $j = ",";
                                }
                                $inStatement.=$j . ":VIEWEDSTR" . $i;
                                $i++;
                        }
                        $i = 0;
                        $sql = "SELECT SQL_CACHE SENDER,RECEIVER,CONSTANT_VALUE,PRIORITY FROM viewSimilar.CONTACTS_CACHE_LEVEL2_" . $viewedOppositeGender . " WHERE SENDER IN (" . $inStatement . ") AND AGE >= :lowerAge AND AGE <=:higherAge";
                        $prep = $this->db->prepare($sql);
                        $prep->bindValue(":lowerAge",$lAge,PDO::PARAM_INT); 
                        $prep->bindValue(":higherAge",$hAge,PDO::PARAM_INT);
                        foreach ($viewedContactsStr as $key => $value) {
                                $prep->bindValue(":VIEWEDSTR" . $i, $value, PDO::PARAM_LOB);
                                $i++;
                        }
                        $prep->execute();
                        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                                $suggestedProf[$row['SENDER']][] = $row['RECEIVER'];
                                $constantVal[$row['SENDER']][] = $row['CONSTANT_VALUE'];
								$priority[$row['SENDER']][] = $row['PRIORITY'];                       
						}
                        $result['suggestedProf'] = $suggestedProf;
                        $result['constantVal'] = $constantVal;
						$result['priority'] = $priority;
                        return $result;
                } catch (PDOException $e) {
                        throw new jsException($e);
                }
        }

}

?>
