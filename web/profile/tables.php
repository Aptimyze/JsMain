<?php
	
	function get_relation($relation)
	{
		$RELATION=array("1" => "Self",
						"2" => "Parent/Guardian",
						"3" => "Sibling",
						"4" => "Friend",
						"5" => "Marriage Bureau",
						"6" => "Other");
						
		return $RELATION["$relation"];
	}
?>