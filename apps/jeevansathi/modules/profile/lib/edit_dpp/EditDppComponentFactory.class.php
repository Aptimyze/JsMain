<?php
class EditDppComponentFactory {
	public static function createDppComponent($type, $action) {
		switch ($type) {
			case "PPBD":
				return new EditDppBasicInfo($action);
			break;
			case "PPRE":
				return new EditDppReligionEthnicity($action);
			break;
			case "PPLA":
				return new EditDppLifeStyle($action);
			break;
			case "PPEO":
				return new EditDppEducation($action);
			break;
			case "PPA":
				return new EditDppAbtPartner($action);
			break;
			case "FILTER":
				return new EditDppFilter($action);
			break;
		}
	}
}
