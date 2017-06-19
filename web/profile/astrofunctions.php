<?php

/*
Function Encode(sIn)
    dim x, y, abfrom, abto
    Encode="": ABFrom = ""

    For x = 0 To 25: ABFrom = ABFrom & Chr(65 + x): Next 
    For x = 0 To 25: ABFrom = ABFrom & Chr(97 + x): Next 
    For x = 0 To 9: ABFrom = ABFrom & CStr(x): Next 

    abto = Mid(abfrom, 14, Len(abfrom) - 13) & Left(abfrom, 13)
    For x=1 to Len(sin): y = InStr(abfrom, Mid(sin, x, 1))
        If y = 0 Then
             Encode = Encode & Mid(sin, x, 1)
        Else
             Encode = Encode & Mid(abto, y, 1)
        End If
    Next
End Function 
*/


function getChecksum($sIn)
{
	for($i=0;$i<=25;$i++)
		$ABFrom.= chr(65+$i);
	for($i=0;$i<=25;$i++)
		$ABFrom.= chr(97+$i);
	for($i=0;$i<=9;$i++)
		$ABFrom.= $i;

	$abto=substr($ABFrom,14,strlen($ABFrom)-13).substr($ABFrom,0,14);
	
	for($i=0;$i<=strlen($sIn);$i++)
	{
		$y=strpos($ABFrom,substr($sIn,$i,1));
		if($y==0)
			$Encode.=substr($sIn,$i,1);
		else
			$Encode.=substr($abto,$y,1);
	}

return $Encode."&".$sIn;
}

/*Function Decode(sIn_all)
    	dim x, y, ABFrom, abto,pid,sIn
	Decode="": ABFrom = ""

	a=Split(sIn_all,"&")
        sIn=a(0)
        pid=a(1)
                                                                                                 
    For x = 0 To 25: ABFrom = ABFrom & Chr(65 + x): Next 
    For x = 0 To 25: ABFrom = ABFrom & Chr(97 + x): Next 
    For x = 0 To 9: ABFrom = ABFrom & CStr(x): Next 

    abto = Mid(abfrom, 14, Len(abfrom) - 13) & Left(abfrom, 13)
    For x=1 to Len(sin): y=InStr(abto, Mid(sin, x, 1))
        If y = 0 then
            Decode = Decode & Mid(sin, x, 1)
        Else
            Decode = Decode & Mid(abfrom, y, 1)
        End If
    Next

	If Decode=pid then
		return true
	Else
		return false
	End If

End Function
*/
function decode($sIn_all)
{
	list($sIn,$pid)=explode("&",$sIn_all);
	for($i=0;$i<=25;$i++)
                $ABFrom.= chr(65+$i);
        for($i=0;$i<=25;$i++)
                $ABFrom.= chr(97+$i);
        for($i=0;$i<=9;$i++)
                $ABFrom.= $i;

	$abto=substr($ABFrom,14,strlen($ABFrom)-13).substr($ABFrom,0,14);
	for($i=0;$i<=strlen($sIn);$i++)
	{
		$y=strpos($abto,substr($sIn,$i,1));
		if($y==0)
			$Decode.=substr($sIn,$i,1);
		else
			$Decode.=substr($ABFrom,$y,1);
	}
	if($Decode==$pid)
		return true;
	else
		return false;
	
}

/*
echo $value="146908";
echo "<br>";
echo $var=Encode($value);
echo "<br>";
echo $var1=Decode($var.'1');
*/
?>
