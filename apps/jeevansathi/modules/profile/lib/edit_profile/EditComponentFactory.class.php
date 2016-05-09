<?php
class EditComponentFactory {
	public static function createComponent($type) {
		switch ($type) {
			case "PBI":
				return new EditBasicInfo();
			break;
			case "PRE":
				return new EditReligionEthnicity();
			break;
			case "FLI":
				return new EditFilters();
			break;
			case "PHI":
				return new EditHobbies();
			break;
			case "SPS":
				return new EditSpouse();
			break;
			case "PCI":
				return new EditContactDetails();
			break;
			case "PLA":
				return new EditLifeStyle();
			break;
			case "SME":
				return new EditDemographics();
			break;
			case "PEO":
				return new EditEducationAndOccupation();
			break;
			case "PMF":
				return new EditProfileInfo();
			break;
			case "CUH":
				return new EditAstroDetails();
			break;
			case "PFD":
				return new EditFamilyDetails();
			break;
			case "INCOMP":
				return new EditIncompleteLayer();
			break;
		}
	}
}
?>
