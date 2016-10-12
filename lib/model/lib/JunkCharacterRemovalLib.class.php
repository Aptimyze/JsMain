<?php 
	/**
	* To remove junk characters from given field.
	*/
	class JunkCharacterRemovalLib
	{
		
		public function removeJunkCharacters($fieldName,$fieldValue)
		{
			$junkCharacterRemovalLib = new JunkCharacterRemovalLib();	
			switch ($fieldName) {
				case 'about':
					return $junkCharacterRemovalLib->removeJunkAbout($fieldValue);			
					break;
				case 'openFields':
					return $junkCharacterRemovalLib->removeJunkOpenFields($fieldValue);
				default:
					# code...
					break;
			}
		}

		private function removeJunkAbout($about)
	    {
	        $about =  preg_replace('/[.]{4,}/','...',$about);
	        $about = preg_replace('/([^\w.])\1+/','$1',$about);
	        return $about;
	    }

	    private function removeJunkOpenFields($text)
	    {
	        $five_unique = count( array_unique( str_split( preg_replace('/[^a-z]/i','',$text)))) > 5 ? 1 : 0;

	        $space_vowels = 1;
	       
	        // check for english
	        if (strlen($text) == mb_strlen($text, 'utf-8'))
	        {
		        $space_vowels = preg_match('/(?=.*\s+)(?=.*[aeiou]+)/i',$text); 
	        }
	       
	        return $five_unique && $space_vowels;
	    }
	}

 ?>