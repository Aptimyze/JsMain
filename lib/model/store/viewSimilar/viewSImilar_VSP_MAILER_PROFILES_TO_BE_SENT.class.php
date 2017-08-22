<?php

/* This class provided functions for similar profile Mailer populate table
 */

class viewSimilar_VSP_MAILER_PROFILES_TO_BE_SENT extends TABLE {

    public function __construct($dbname = "newjs_master") {
        parent::__construct($dbname);
    }

    /** This store function is used to get profiles to whom the mailer has to be sent
     * @param $totalscripts : number of scripts running
     * @param $currentScript: current script running
     * @return array array of profileds
     */
    public function getProfilesToSendMail($totalScripts, $currentScript) {
        try {

            $sql = "SELECT PROFILEID,USER1,USER2,USER3,USER4,USER5,INTERESTS_SENT,TYPE FROM viewSimilar.VSP_MAILER_PROFILES_TO_BE_SENT WHERE IS_CALCULATED = :IS_CALC and PROFILEID%:TOTALSCRIPTS = :CURRENTSCRIPT";
            $prep = $this->db->prepare($sql);
            $prep->bindValue(":IS_CALC", 'N', PDO::PARAM_STR);
            $prep->bindValue(":TOTALSCRIPTS", $totalScripts, PDO::PARAM_INT);
            $prep->bindValue(":CURRENTSCRIPT", $currentScript, PDO::PARAM_INT);
            $prep->execute();
            while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
                $calculatedProfiles[$row['PROFILEID']] = $row;
                unset($calculatedProfiles[$row['PROFILEID']]['PROFILEID']);
            }
            return $calculatedProfiles;
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

    /** This store function is used to set profiles to whom the mailer has to be sent
     * @param $totalscripts : number of scripts running
     * @param $currentScript: current script running
     * @return array array of profileds
     */
    public function setProfilesToSendMail($profileId, $userArray,$noOfEoi,$typeOfEoi) {
        try {
            $i = 1;
            foreach ($userArray as $key => $val) {
                $insertStr1 .= "USER" . $i . ",";
                $insertStr2 .= ":USER" . $i . ",";
                $i++;
            }
            $insertStr1 = trim($insertStr1, ',');
            $insertStr2 = trim($insertStr2, ',');
            if ($insertStr2) {
                $sql = "INSERT INTO viewSimilar.VSP_MAILER_PROFILES_TO_BE_SENT(PROFILEID,IS_CALCULATED," . $insertStr1 . ",INTERESTS_SENT,TYPE) VALUES(:PROFILEID,:IS_CALC," . $insertStr2 . ",:INTERESTS,:TYPE)";
                $prep = $this->db->prepare($sql);
                $prep->bindValue(":PROFILEID", $profileId, PDO::PARAM_INT);
                $prep->bindValue(":IS_CALC", 'N', PDO::PARAM_STR);
                $prep->bindValue(":INTERESTS", $noOfEoi, PDO::PARAM_INT);
                $prep->bindValue(":TYPE", $typeOfEoi, PDO::PARAM_STR);
                $i = 1;
                foreach ($userArray as $val)
                    $prep->bindValue(":USER" . $i++, $val, PDO::PARAM_INT);
                $prep->execute();
            }
        } catch (PDOException $e) {
            throw new jsException($e);
        }
    }

}
