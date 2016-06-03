<?php
/*
 * Response of sphinx search is handled here.
 * @author Lavesh Rawat
 * @created 2012-05-14
 */
class SphinxResponse implements ResponseHandleInterface
{
	private $resultType;
	private $responseEngine;
	private $totalResults;
	private $resultsArr;
	private $clusterArr;

	/* Getter functions*/
	function getTotalResults() 
	{ 
		return $this->totalResults; 
	}
	function getResultsArr() 
	{ 
		return $this->resultsArr; 
	}
	function getClustersResults() 
	{ 
		return $this->clusterArr; 
	}
	public function getResponseEngine()
	{
		return $this->responseEngine;	
	}
	/* Getter functions*/


	/** 
	* Constructor class
	* @param resultType string type of results (example array / xml ..)
	*/
	public function __construct($resultType='')
	{
		$this->resultType=$resultType;
		$this->responseEngine='sphinx';
	}

	/**
	* format search results into a format as specified in constructor.
	* @param res sphinx-result-array 
	*/
	public function getFormatedResults($res)
	{
		$this->totalResults = $res[0]['total_found'];
	        if(is_array($res[0]['matches']))
        	{
                	foreach($res[0]['matches'] as $k=>$v)
	                {
                        	$this->resultsArr[]=$k;
        	        }
	        }
	}

	/**
	* format cluster results into a format as specified in constructor.
	* Logic of cluster formation.
	* @param res sphinx-result-array 
	* @param groupByFieldArr list of clusters 
	*/
	public function getFormatedClusterResults($res,$groupByFieldArr)
	{
                $sphinx_to_label_mapping = searchConfig::sphinx_to_label_mapping();
                $clusterLabelMappingArray = searchConfig::clusterLabelMapping();

		$k1=0;

                foreach($groupByFieldArr as $v1)
                {
                       	$clusterName = $clusterLabelMappingArray[$v1];
			/** 
			*'Viewed' cluster is a special case where just 2 links need to be shown
			*/
                        if($v1=='VIEWED')
                        {
				$this->clusterArr[$clusterName]['Viewed']='Show';
				$this->clusterArr[$clusterName]['Not Viewed']='Show';
				$k1=$k1+1;
                        }
			elseif(in_array($v1,SearchConfig::$sliderClusters))
			{
				$this->clusterArr[$clusterName]['Slider']='Show';
			}
                        else
               		{         
				/** 
				* At the top of each cluster , we need to show All / Doesn't matter.
				* Option like Any / Online without count also need to be show just next to these clusters.
				*/
				if(in_array($v1,SearchConfig::$clustersWithDoesntMatter))
					$this->clusterArr[$clusterName]['Doesn\'t Matter']='Show';
				else
					$this->clusterArr[$clusterName]['All']='Show';

				if($v1=='LAST_ACTIVITY')
					$this->clusterArr[$clusterName]['Online']='Show';
				if($v1=='HANDICAPPED')
					$this->clusterArr[$clusterName]['Any']=0;


                                $reset=0;
                                $clusterNameForFieldLabel = $sphinx_to_label_mapping[$v1];

				/* Handling cases when sortimg of clusters is not based on count , but are predfined*/
				if(in_array($v1,array('LAST_ACTIVITY')))
				{
					$tempArr = SearchConfig::clustersOptionsOfSpecialClusters('LAST_ACTIVITY');
					foreach($tempArr as $k => $v)
						$this->clusterArr[$clusterName][$v]=0;
					unset($tempArr);
				}
				/* Handling cases when sortimg of clusters is not based on count , but are predfined*/
                                foreach($res[$k1]['matches'] as $v)
                                {
                                        if($res[$k1]['matches']=='')
                                                break;

                                        $labelVal=$v['attrs']['@groupby'];
                                        $cnt=$v['attrs']['@count'];

					/* Last Activity is a Special Case Where values of previous clusters 
					need to be added into the next one in the list*/
					if(in_array($v1,array('LAST_ACTIVITY')))
					{
						$tmp_cnt = count(FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1))+1;
						while($tmp_cnt > $labelVal)
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							$this->clusterArr[$clusterName][$label]+=$cnt;
							$labelVal++;
						}
					}
					elseif($v1=='RELATION')
					{
						if(in_array($labelVal,array(1,2,3)))
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							if(!$label)
								$label=searchConfig::$unmatchedCluster;
							$this->clusterArr[$clusterName][$label]+=$cnt;
						}
						else
							$this->clusterArr[$clusterName]['Other']+=$cnt;
							
					}
					elseif(in_array($v1,array('MANGLIK','DIET','HIV')))
					{
						$chrLabelVal = chr($labelVal);
						if( ($v1=='MANGLIK' && in_array($chrLabelVal,array('M','N','A'))) || ($v1=='DIET' && in_array($chrLabelVal,array('V','N','E','J'))) || ($v1=='HIV')  )
						{
							if($v1=='HIV')
								$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$chrLabelVal);
							else
								$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							//if(!$label)
								//$label=searchConfig::$unmatchedCluster;
							if($label)
								$this->clusterArr[$clusterName][$label]+=$cnt;
						}
					}
					elseif($v1=='HOROSCOPE')
					{
						if($labelVal==1)
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							$this->clusterArr[$clusterName][$label]+=$cnt;
						}
					}
					elseif(in_array($v1,array('HANDICAPPED','MARRIED_WORKING','GOING_ABROAD','HAVEPHOTO')))
					{
						$chrLabelVal = chr($labelVal);
						if( ($v1=='HANDICAPPED' && in_array($chrLabelVal,array(1,2,3,4))) || ($v1=='GOING_ABROAD' && in_array($chrLabelVal,array('Y'))) || ($v1=='MARRIED_WORKING' && in_array($chrLabelVal,array('Y','N'))) || ($v1=='HAVEPHOTO' && in_array($chrLabelVal,array('Y'))) )
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$chrLabelVal);
							$this->clusterArr[$clusterName][$label]+=$cnt;
							if($v1=='HANDICAPPED')
								$this->clusterArr[$clusterName]['Any']+=$cnt;
						}
					}
					else
					{
						$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
						if(!in_array($v1,array('MSTATUS','HAVECHILD')))
						{
							if(!$label)
								$label=searchConfig::$unmatchedCluster;
						}
						if($label)
							$this->clusterArr[$clusterName][$label]+=$cnt;	
					}

                                        $reset++;
                                        if($reset>10)
                                                $morethis->clusterArr[$clusterName]=1;
                                        if($reset>10)
                                                break;
                                }
				$k1=$k1+1;
                        }
                }
	}
}
