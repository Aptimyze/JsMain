<?php
function TrackEditUnsubscribe($profile,$status,$logic=0)
{
	if($status=='V')
	        $sql="INSERT IGNORE INTO MATCHALERT_TRACKING.TRACK_UNSUBSCRIBE(PROFILEID,DATE,STATUS,LOGIC) VALUES ('$profile',NOW(),'$status','$logic')";
	else
		//$sql="REPLACE INTO MATCHALERT_TRACKING.TRACK_UNSUBSCRIBE(PROFILEID,DATE,STATUS) VALUES ('$profile',NOW(),'$status')";
		$sql="UPDATE MATCHALERT_TRACKING.TRACK_UNSUBSCRIBE SET STATUS='$status' WHERE PROFILEID='$profile' and DATE=DATE(now())";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}

//To track dpp edited from matchalert link
function TrackEditDpp_MA($profile,$status,$logic=0)
{
        if($status=='V')
                $sql="INSERT IGNORE INTO MATCHALERT_TRACKING.TRACK_EDIT_DPP (PROFILEID,DATE,STATUS,LOGIC) VALUES ('$profile',NOW(),'$status','$logic')";
        else
		$sql="UPDATE MATCHALERT_TRACKING.TRACK_EDIT_DPP SET STATUS='$status' WHERE PROFILEID='$profile' and DATE=now()";
	mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}

//To track matches viewed
function TrackMatchViewed_MA($profile,$n=0,$logic=0)
{
 //        $date=date('Y-m-d');
 //        $sql_up="UPDATE MATCHALERT_TRACKING.TRACK_MATCHES_VIEWED SET COUNT=COUNT+1 WHERE DATE='$date' AND POSITION='$n' AND LOGIC='$logic'";
	// mysql_query_decide($sql_up) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_up,"ShowErrTemplate");
 //        if(mysql_affected_rows_js()==0)
 //        {
 //                $sql_ins="INSERT INTO MATCHALERT_TRACKING.TRACK_MATCHES_VIEWED (DATE,POSITION,LOGIC,COUNT) VALUES (NOW(),'$n','$logic','1')";
	// 	mysql_query_decide($sql_ins) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_ins,"ShowErrTemplate");
 //        }
}

function MatchLikedOrNor($MatchAlertlike='',$receiver='',$match='')
{
	if($MatchAlertlike && $receiver && $match)
	{
		$dt=date("Y-m-d");
		if($MatchAlertlike=='Y')
		{
			$sql="INSERT IGNORE INTO MATCHALERT_TRACKING.MATCHALERT_LIKE(RECEIVER,USER,DATE) VALUES ('$receiver','$match','$dt')";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		else
		{
			$sql="INSERT IGNORE INTO MATCHALERT_TRACKING.MATCHALERT_DISLIKE(RECEIVER,USER,DATE) VALUES ('$receiver','$match','$dt')";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
	}
}

function MatchDislikeReason($photo='',$dpp='',$reason='',$receiver,$user)
{
	if($photo || $dpp || $reason)
	{
	        $sql="UPDATE MATCHALERT_TRACKING.MATCHALERT_DISLIKE SET PHOTO='$photo', DPP='$dpp', REASON='".addslashes(stripslashes($reason))."' WHERE RECEIVER=$receiver AND USER=$user";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	}
}
?>
