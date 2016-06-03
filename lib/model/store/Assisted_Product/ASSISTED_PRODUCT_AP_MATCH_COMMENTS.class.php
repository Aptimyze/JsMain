<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ASSISTED_PRODUCT_AP_MATCH_COMMENTS extends TABLE
{
    public function __construct($dbname='')
        {
                parent::__construct($dbname);
        }
    public function getProfilesMatching($viewerProfile,$viewedProfile){
        if($viewerProfile && $viewedProfile)
                {
                        try
                        {
                                $sql = "SELECT PROFILEID FROM Assisted_Product.AP_MATCH_COMMENTS WHERE PROFILEID=:Viewer AND MATCH_ID=:Viewed";
                                $res = $this->db->prepare($sql);
                                $res->bindValue(":Viewer", $viewerProfile, PDO::PARAM_INT);
                                $res->bindValue(":Viewed", $viewedProfile, PDO::PARAM_INT);
                                $res->execute();
                                if($result = $res->fetch(PDO::FETCH_ASSOC))
                                        return true;
                        }
                        catch(PDOException $e)
                        {
                                throw new jsException($e);
                        }
                }
                return false;
    }
}
