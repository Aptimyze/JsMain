<?php

/**
 * report generation
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Nitesh Sethi
 * @version    SVN: $Id: actions.class.php 23810 2014-07-04 03:07:44 Nitesh Sethi $
 */
/**
 * <p></p>
 *
 *
 * @author Nitesh Sethi
 */

class kidProfileReportAction extends sfAction
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$date= $request->getParameter("date");
		$time=time();
		$dateArr=explode("-",$date);

		if(checkdate ( $dateArr[1] , $dateArr[2] , $dateArr[0] ) && $time-strtotime($date)>3*24*60*60)
		{
			$data = "Source, Sec_Source, Group, Adnetwork, Account, Campaign, Adgroup, Keyword, Match, LMD, Entry Date, Profileid, Activated, Age, Gender, Character Length, Profile Posted By, Photo, Country, City, Community, Income, n(sum), Incomplete\n";
			$dbKeyword=new MIS_KEYWORD_PROFILE_REPORT();
			$row=$dbKeyword->selectRecord($date);
			foreach($row as $k=>$v)
			{
				$data.= $v['Source'].", ".$v['Sec_Source'].", ".$v['Group'].", ".$v['Adnetwork'].", ".$v['Account'].", ".$v['Campaign'].", ".$v['Adgroup'].", ".$v['Keyword'].", ".$v['Match'].", ".$v['LMD'].", ".$v['Entry_Date'].", ".$v['Profileid'].", ".$v['Activated'].", ".$v['Age'].", ".$v['Gender'].", ".$v['Character_Length'].", ".$v['Posted_By'].", ".$v['Photo'].", ".$v['Country'].", ".$v['City'].", ".$v['Community'].", ".$v['Income'].", ".$v['n_sum'].", ".$v['Incomplete']."\n ";
			}
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=".$date.".csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			print chr(255) . chr(254) . mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
		}
		else
		{
			die("Please proper date in following format yyyy-mm-dd and it should be before past three days");
		}
		return sfView::NONE;
		die;

	}
}
?>
