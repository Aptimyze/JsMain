<?php
$partitionsArray=array(
		"connected"=>array(
				"status"=>array(26=>array(31),
						14=>array(8,9,10,11),
						17=>array(17,18),
						46=>array(21,22,25),
						45=>array(12,13,14,15,16,32)
						)
				),
		"inactive"=>array(
				"status"=>array(13=>array(24),
						24=>array(23),
						12=>array(6),
						11=>array(19),						
						),
				"deleted"=>array(1),
				"mtongue"=>array(31,3,16,17)
				)
		);
$backupTablesArr=array(	"email_addresses"=>array(
					"joinField"=>"id",
					"backup"=>1
					),
			"email_addr_bean_rel"=>array(
					"joinField"=>"bean_id",
					"backup"=>1,
					"whereCondition"=>"email_addr_bean_rel.bean_module='Leads'",
					),
			"tracker"=>array(
					"joinField"=>"item_id",
					"backup"=>1,
					),
			"tasks"=>array(
				"joinField"=>"parent_id",
				"whereCondition"=>"tasks.parent_type='Leads'"
				),
			"notes"=>array(
				"joinField"=>"parent_id",
				"whereCondition"=>"notes.parent_type='Leads'",
				),
			"meetings_leads"=>array(
				"joinField"=>"lead_id"
				),
			"calls_leads"=>array(
				"joinField"=>"lead_id"
				),
			"emails_beans"=>array(
				"joinField"=>"bean_id",
				"whereCondition"=>"emails_beans.bean_module='Leads'",
				),
			"campaign_log"=>array(
				"joinField"=>"target_id"
				),
			"prospect_lists_prospects"=>array(
				"joinField"=>"related_id",
				"whereCondition"=>"prospect_lists_prospects.related_type='Leads'"
				),
			"leads_cstm"=>array(
                                        "joinField"=>"id_c",
                                        "backup"=>1
                                        )
			);

function moveLead($lead_id,$fromPartition,$toPartition,$db='')
{
	if($lead_id && $fromPartition && $toPartition)
	{
		if($fromPartition=='active')
			$fromTablePrepend="sugarcrm.";
		else
			$fromTablePrepend="sugarcrm_housekeeping.".$fromPartition."_";
		if($toPartition=='active')
			$toTablePrepend="sugarcrm.";
		else
			$toTablePrepend="sugarcrm_housekeeping.".$toPartition."_";

		$sql="REPLACE INTO ".$toTablePrepend."leads_cstm SELECT ".$fromTablePrepend."leads_cstm.* FROM ".$fromTablePrepend."leads_cstm JOIN ".$fromTablePrepend."leads ON ".$fromTablePrepend."leads_cstm.id_c=".$fromTablePrepend."leads.id WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";
		$sql="DELETE ".$fromTablePrepend."leads_cstm.* FROM ".$fromTablePrepend."leads_cstm JOIN ".$fromTablePrepend."leads ON ".$fromTablePrepend."leads_cstm.id_c=".$fromTablePrepend."leads.id WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";

		$sql="REPLACE INTO ".$toTablePrepend."email_addresses SELECT ".$fromTablePrepend."email_addresses.* FROM ".$fromTablePrepend."email_addresses JOIN ".$fromTablePrepend."email_addr_bean_rel ON ".$fromTablePrepend."email_addresses.id=".$fromTablePrepend."email_addr_bean_rel.email_address_id JOIN ".$fromTablePrepend."leads ON ".$fromTablePrepend."email_addr_bean_rel.bean_id=".$fromTablePrepend."leads.id WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";

		$sql="DELETE ".$fromTablePrepend."email_addresses.* FROM ".$fromTablePrepend."email_addresses JOIN ".$fromTablePrepend."email_addr_bean_rel ON ".$fromTablePrepend."email_addresses.id=".$fromTablePrepend."email_addr_bean_rel.email_address_id JOIN ".$fromTablePrepend."leads ON ".$fromTablePrepend."email_addr_bean_rel.bean_id=".$fromTablePrepend."leads.id WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";
			
		$sql="REPLACE INTO ".$toTablePrepend."email_addr_bean_rel SELECT ".$fromTablePrepend."email_addr_bean_rel.* FROM ".$fromTablePrepend."email_addr_bean_rel JOIN ".$fromTablePrepend."leads ON ".$fromTablePrepend."email_addr_bean_rel.bean_id=".$fromTablePrepend."leads.id WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";

		$sql="DELETE ".$fromTablePrepend."email_addr_bean_rel.* FROM ".$fromTablePrepend."email_addr_bean_rel JOIN ".$fromTablePrepend."leads ON ".$fromTablePrepend."email_addr_bean_rel.bean_id=".$fromTablePrepend."leads.id WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";

		$sql="REPLACE INTO ".$toTablePrepend."leads SELECT ".$fromTablePrepend."leads.* FROM ".$fromTablePrepend."leads WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";

		$sql="DELETE FROM ".$fromTablePrepend."leads WHERE ".$fromTablePrepend."leads.id='$lead_id'";
		mysql_query($sql,$db) or die(mysql_error());

		//echo "\n\n$sql";
	}
}
?>
