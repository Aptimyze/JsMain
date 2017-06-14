<?php
/*This class is used to insert in REG_TRACK_CHANNEL table*/
class REG_TRACK_CHANNEL extends TABLE
{

	public function __construct($dbname="")
	{
		parent::__construct($dbname);
	}
        /*
        This function is used to insert record in the TRACK_TIEUP_VARIABLE table for an entry 
        of a profile id for a page type and through a channel
        @param- profile id, page that has been completed, channel through which page has been accessed
        */
        public function insert($profileid,$page,$chpage) 
        {
         try{
          $date=date("Y-m-d H:i:s");
          $sql1="INSERT IGNORE INTO MIS.REG_TRACK_CHANNEL(PROFILEID,PAGE_TYPE,ENTRY_DT,CHANNEL) VALUES (:ID,:PAGE,:DATE,:CHANNEL)";
          $res1=$this->db->prepare($sql1);
          $res1->bindValue(":ID", $profileid, PDO::PARAM_INT);
          $res1->bindValue(":PAGE", $page, PDO::PARAM_STR);
          $res1->bindValue(":DATE", $date, PDO::PARAM_STR);
          $res1->bindValue(":CHANNEL", $chpage, PDO::PARAM_STR);
          $res1->execute();
        }
        catch(PDOException $e){
          throw new jsException($e);
        }

      }

         /* This function fetches the number of completed registration from different Channels(Desktop,MS,NewMs,Android,ios)
          * in last 1 hour.
          */       
         public function getHourlyRegistrationData()
         {
          try
          {
            $sql = "SELECT COUNT(PROFILEID) AS COUNT,HOUR(ENTRY_DT) AS HOUR ,DATE(ENTRY_DT) AS DT,CHANNEL FROM MIS.REG_TRACK_CHANNEL 
            WHERE  
            ENTRY_DT >=  DATE_SUB(now(),INTERVAL '12:00' HOUR_MINUTE) AND  
            PAGE_TYPE='yourInfo' AND
            CASE WHEN HOUR(now()) = 0  THEN
            ( DATE(ENTRY_DT) = DATE(SUBDATE(now(),'1 day')) OR DATE(ENTRY_DT) = DATE(now()) ) AND 
            HOUR(ENTRY_DT) IN (23)
            ELSE
            ENTRY_DT >= DATE(now())  AND
            HOUR(ENTRY_DT) >= HOUR(now()) - 1 AND
            HOUR(ENTRY_DT) < HOUR(now()) 
            END
            GROUP BY HOUR,CHANNEL";
            $prep=$this->db->prepare($sql);
            $prep->execute();
            $arrResult = $prep->fetchAll(PDO::FETCH_ASSOC);
            if($prep->rowCount() == 0)
            {
              return 0;
            }
            return $arrResult;
          } 
          catch (Exception $ex)
          {
            throw new jsException($ex,"Some issue in registrationMonitorData function REG_TRACK_CHANNEL Store");
          }
        }

      /* This function fetches the number of completed registration from different Channels(Desktop,MS,NewMs,Android,ios)
       * for last 7 days and calculates the MIN,MAX and AVG for each channel at each hour.
       */       
      public function getRegistrationMonitoringData()
      {
        try
        {
          $sql = "SELECT CEIL(AVG(CNT)) AS Avg,MIN(CNT) AS Min,MAX(CNT) AS Max,STD(CNT) AS StDev,HOUR,CHANNEL FROM (SELECT COUNT(PROFILEID) AS CNT,DATE(ENTRY_DT) AS DT, HOUR(ENTRY_DT) AS HOUR, CHANNEL FROM MIS.REG_TRACK_CHANNEL WHERE PAGE_TYPE='yourInfo' AND ENTRY_DT >= DATE(SUBDATE(now(),'7 day')) AND DATE(ENTRY_DT) != DATE(now()) GROUP BY DATE(ENTRY_DT),HOUR(ENTRY_DT),CHANNEL ORDER BY HOUR,CHANNEL ) AS T GROUP BY HOUR,CHANNEL";
          $prep=$this->db->prepare($sql);
          $prep->execute();
          $arrResult = $prep->fetchAll(PDO::FETCH_ASSOC);
          if($prep->rowCount() == 0)
          {
            return 0;
          }
          return $arrResult;
        } 
        catch (Exception $ex)
        {
          throw new jsException($ex,"Some issue in registrationMonitorData function REG_TRACK_CHANNEL Store");
        }
      }
    }
    ?>