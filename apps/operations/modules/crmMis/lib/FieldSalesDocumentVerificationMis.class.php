<?php

// Author : Neha Gupta
// This class handles all the logics related to Field Sales Document Verification MIS.

class FieldSalesDocumentVerificationMis
{
	private $start_dt;
	private $end_dt;

	public function __construct($start_dt, $end_dt)
	{
		$this->start_dt = $start_dt;
		$this->end_dt = $end_dt;
	}

	public function getAgentNameForHierarchy($cid) 
	{
            $agentAllocObj = new AgentAllocationDetails();
            $execname = $agentAllocObj->fetchAgentName($cid);

            $misObj = new misGenerationhandler();
            if($misObj->isPrivilege_P_MG_TRNG($execname))
            {
                  $boss = $misObj->get_SLHDO();
                  if(!$boss)
                  	die("Please give 'Sales Head - Overall' privilege to atleast and to only one user.");
                  else
                  	return $boss;
            }		
            return $execname;
	}

	public function getHierarchyData($cid)
	{
		$execname = $this->getAgentNameForHierarchy($cid);

            $hierarchyObj = new hierarchy($execname);
            $allReporters = $hierarchyObj->getAllReporters();

	      $fsepmObj = new FieldSalesExecutivePerformanceMis($allReporters, $this->start_dt, $this->end_dt);
            $background_color = $fsepmObj->getBackgroundColor($allReporters);
	      $allReporters = $fsepmObj->getEligibleExecutives($allReporters, $this->start_dt, $this->end_dt);

	      if($allReporters)
	      {
		      $hierarchyData = $hierarchyObj->getHierarchyData($allReporters);
	      }

            return array($allReporters, $hierarchyData, $background_color);
	}

	public function fetchVerifiedDocumentsCount($allReporters)
	{
		$pvdObj = new PROFILE_VERIFICATION_DOCUMENTS('newjs_slave');

		foreach($allReporters as $uploaded_by)
		{
			$cntArr[$uploaded_by] = $pvdObj->countVerifiedDocuments($this->start_dt, $this->end_dt, $uploaded_by);
		}
		return $cntArr;
	}

	public function generateTeamWiseData($ddarr, $allReporters, $cntArr)
	{
		foreach($allReporters as $uploaded_by)
		{
			$h_obj = new hierarchy($uploaded_by);
			$h = $h_obj->getAllReporters();
			unset($h[0]);

			if($h && is_array($h))
			{
				foreach($h as $rep)
				{
					foreach($ddarr as $dd)
					{
						if($cntArr[$rep][$dd])
						{
							$cntArr[$uploaded_by][$dd] += $cntArr[$rep][$dd];
						}
					}
				}					
			}
			unset($h);
			unset($h_obj);			
		}
		return $cntArr;
	}

	public function fetchVerifiedDocumentsCountTotal($cntArr, $ddarr)
	{
		foreach($cntArr as $uploaded_by => $data)
		{
			$cntArr[$uploaded_by]['TOTAL'] = 0;
			foreach($ddarr as $dd)
			{
				if($data[$dd])
				{
					$cntArr[$uploaded_by]['TOTAL'] += $data[$dd];
				}
			}
		}
		return $cntArr;
	}
	
	public function createExcelFormatOutput($cntArr, $ddarr, $header, $displayDate)
	{
		$header .= "\n\nManager/Supervisor/Executive";

		foreach($ddarr as $dd)
		{
			$header .= "\t$dd";
		}
		$header .= "\tTOTAL\n";

		foreach($cntArr as $uploaded_by => $data)
		{
			$message .= "$uploaded_by\t";
			foreach($ddarr as $dd)
			{
				$message .= "$data[$dd]\t";				
			}
			$message .= $data['TOTAL']."\n";
		} 

		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Field_Sales_Document_Verification_MIS_".$displayDate.".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $header."\n".$message;
		die;
	}
}
?>
