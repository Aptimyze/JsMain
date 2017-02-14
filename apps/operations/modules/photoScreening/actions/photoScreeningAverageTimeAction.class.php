<?php

/**
 * photoScreening actions.
 *
 * @package    operation
 * @subpackage photoScreening
 * @author     Prashant Pal
 */
class photoScreeningAverageTimeAction extends sfActions
	{
	   const NO_OF_HOURS_IN_A_DAY=24;
	   private static $month_arr = array("Jan"=> "01","Feb"=> "02","Mar"=> "03","Apr"=> "04","May"=> "05","Jun"=> "06","Jul"=> "07","Aug"=> "08","Sep"=> "09","Oct"=> "10","Nov"=> "11","Dec"=> "12");

		/**
		* Executes index action
		* *
		* @param sfRequest $request A request object
		*/


		public function executePhotoScreeningAverageTime(sfWebRequest $request)
		{ 
			$this->cid = $request->getParameter('cid');
			$month_arr =  photoScreeningAverageTimeAction::$month_arr;
					$mon=date("m");
					foreach($month_arr as $key => $value)
						{
							$mykey = $key;
							if($value==$mon)
								{
									$mon=$mykey;
								}
						}
					$this->monthArr = array_keys(crmParams::$monthOrder);
					$this->mon=$mon;
					$this->yearArr = range(date('Y'),2004);
					$db= new PICTURE_PHOTOSCREEN_MASTER_TRACKING('newjs_slave');
					// if any queue is selected
					 if(isset($_POST['submit']))
						{
							$Total_hours = photoScreeningAverageTimeAction::NO_OF_HOURS_IN_A_DAY; 
							$val = $request->getParameter('queue'); 
							$mon =$request->getParameter('monthValue');
							$month=$month_arr[$mon];
							$year=$request->getParameter('yearValue');
							$num = self::cal_days_in_month(CAL_GREGORIAN, $month, $year);
							for($i=1;$i<=$num;$i++)
								$ddarr[] = $i;
							$this->ddarr=$ddarr;
							for($i=0;$i<$Total_hours;$i++)
								$hharr[]=$i;
							$this->hharr=$hharr;
							if($val==QUEUE1)     // if accept reject queue is selected
								{
									$newarr=$db->getAvgtimeQueue1New($month,$year);        // for new profile array
									$editarr=$db->getAvgtimeQueue1Edit($month,$year);    // for edit profile array			
								}
					// if process completion queue (QUEUE2) is selected 
							else if($val==QUEUE2)
								{	
									$newarr=$db->getAvgtimeQueue2New($month,$year);
									$editarr=$db->getAvgtimeQueue2Edit($month,$year);    
								}
							else
								{
									 $newarr=$db->getAvgtimeQueue3New($month,$year);
									$editarr=$db->getAvgtimeQueue3Edit($month,$year);        
								}
							$num=$num*2;
							$k=1;
					// to calculate the total avg time for a day

						for($i=1;$i<=$num;$i++)
							{
								for($j=0;$j<$Total_hours;$j++)
									{
										$total[$i]=$total[$i] + $newarr[$j][$k];
									}
								$total[$i]=$total[$i]/$Total_hours;
								$i++;
								for($j=0;$j<$Total_hours;$j++)
									{	
										$total[$i]=$total[$i] + $editarr[$j][$k];	
									}
								$total[$i]=$total[$i]/$Total_hours;	
								$k++;
							}
						$this->newarr=$newarr;
						$this->editarr=$editarr;
						$this->total=$total;
						$this->num=$num;
						$this->setTemplate('avgProcessingTimeQueue2');
						}
				else
					$this->setTemplate('avgProcessingTimeQueue');
		}

		private static function cal_days_in_month($calendar, $month, $year)
		{
        		return date('t', mktime(0, 0, 0, $month, 1, $year)); 
		}
	}

?>
