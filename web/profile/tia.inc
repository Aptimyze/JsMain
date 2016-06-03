<?
function map_admin_reverse_logic($admin_score,$per_score)
{
        if($admin_score=='' || $per_score=='')
                return '';
	if($per_score>=100)
		$per_score=99;
		
        $SCORE[0][0]=150;
        $SCORE[0][1]=250;
                $MATCH[0][6]='R';
                $MATCH[0][7]='R';
                $MATCH[0][8]='R';
                $MATCH[0][9]='H';
        $SCORE[1][0]=251;
        $SCORE[1][1]=350;
                $MATCH[1][5]='R';
                $MATCH[1][6]='R';
                $MATCH[1][7]='R';
                $MATCH[1][8]='R';
                $MATCH[1][9]='H';

        $SCORE[2][0]=351;
        $SCORE[2][1]=450;
                $MATCH[2][3]='R';
                $MATCH[2][4]='R';
                $MATCH[2][5]='R';
                $MATCH[2][6]='R';
                $MATCH[2][7]='R';
                $MATCH[2][8]='R';
                $MATCH[2][9]='H';

        $SCORE[3][0]=451;
        $SCORE[3][1]=550;
                $MATCH[3][2]='R';
                $MATCH[3][3]='R';
                $MATCH[3][4]='R';
                $MATCH[3][5]='R';
                $MATCH[3][6]='R';
                $MATCH[3][7]='H';
                $MATCH[3][8]='H';
                $MATCH[3][9]='H';
        $SCORE[4][0]=551;
        $SCORE[4][1]=1000;
                $MATCH[4][1]='R';
                $MATCH[4][2]='R';
                $MATCH[4][3]='R';
                $MATCH[4][4]='H';
                $MATCH[4][5]='H';
                $MATCH[4][6]='H';
                $MATCH[4][7]='H';
                $MATCH[4][8]='H';
                $MATCH[4][9]='H';
        $score_d=$admin_score;
        $match_score=$per_score;
        for($i=0;$i<count($SCORE);$i++)
        {
                if($SCORE[$i][0]<=$score_d && $SCORE[$i][1]>=$score_d)
                {
                 return $recommend=$MATCH[$i][$match_score/10];
                }
        }
        return '';

}
