<?php

class Jira_JiraDetails extends TABLE
{
	public function __construct($dbname = "") {
		parent::__construct($dbname);
	}

	public function insertRecords($jiraArr)
	{
		try
		{
			$sql = "INSERT into Jira.JiraDetails(JIRA_ID,RELEASE_NAME,RELEASE_DATE,STORY_POINTS,ASSIGNEE,SUMMARY,EPIC,SPRINT_NAME,SPRINT_STARTDATE,SPRINT_ENDDATE) VALUES ";
			$i=0;
			foreach($jiraArr as $key=>$values)
			{
				$paramArr[] = "(:JIRA_ID".$i.",:RELEASE_NAME".$i.",:RELEASE_DATE".$i.",:STORY_POINTS".$i.",:ASSIGNEE".$i.",:SUMMARY".$i.",:EPIC".$i.",:SPRINT_NAME".$i.",:SPRINT_STARTDATE".$i.",:SPRINT_ENDDATE".$i.")";
				$i++;				
			}
			$sql = $sql.implode(",",$paramArr);
			
			$res = $this->db->prepare($sql);
			$i=0;
			foreach($jiraArr as $key=>$value)
			{
				$res->bindValue(":JIRA_ID".$i,$key, PDO::PARAM_STR);
				$res->bindValue(":RELEASE_NAME".$i,$value["ReleaseName"], PDO::PARAM_STR);
				$res->bindValue(":RELEASE_DATE".$i,$value["ReleaseDate"], PDO::PARAM_STR);
				$res->bindValue(":STORY_POINTS".$i,$value["StoryPoints"], PDO::PARAM_STR);
				$res->bindValue(":ASSIGNEE".$i,$value["assignee"], PDO::PARAM_STR);
				$res->bindValue(":SUMMARY".$i,$value["summary"], PDO::PARAM_STR);
				$res->bindValue(":EPIC".$i,$value["Epic"], PDO::PARAM_STR);
				$res->bindValue(":SPRINT_NAME".$i,$value["sprint_name"], PDO::PARAM_STR);
				$res->bindValue(":SPRINT_STARTDATE".$i,$value["sprint_startDate"], PDO::PARAM_STR);
				$res->bindValue(":SPRINT_ENDDATE".$i,$value["sprint_endDate"], PDO::PARAM_STR);

				$i++;
			}

			$res->execute();
		}
		catch(Exception $e)
		{
			return false;
		}
	}
}
