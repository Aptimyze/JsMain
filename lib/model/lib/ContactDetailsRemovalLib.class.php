<?php 
	/**
	* this library is used for removing contact info from text fields
	*/
	class ContactDetailsRemovalLib	
	{
		
		function __construct()
		{
			$this->emailRegex = "/(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/";

			$this->mobileRegex = "/([0-9][^a-zA-Z0-9]*){6,11}/";

			$this->linkRegex = "/(https?|ftp)?:?(\/\/)?([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|in|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*/";

		}


		private function removeEmailText($text,$replacedText="")
		{
			return preg_replace($this->emailRegex, $replacedText, $text);
		}

		private function removeNumberText($text,$replacedText="")
		{
			return preg_replace($this->mobileRegex, $replacedText, $text);
		}

		private function removeLinkText($text,$replacedText="")
		{
			return preg_replace($this->linkRegex, $replacedText, $text);
		}

		private function removeNameText($text,$name,$replacedText="")
		{
			return str_replace((explode(" ",$name)),"", $text);
		}
	}
 ?>