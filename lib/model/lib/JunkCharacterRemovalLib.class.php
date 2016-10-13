<?php 
	/**
	* To remove junk characters from given field.
	*/
	class JunkCharacterRemovalLib
	{
		/**
		 * Removes junk characters from given variable
		 * @param  string $fieldName  the field name on which the filter is to be added.
		 * @param  string $fieldValue the value of field name
		 * @return mixed  $about in case of about, 0 or 1 in case of open fields.
		 */
		public function removeJunkCharacters($fieldName,$fieldValue)
		{
			switch ($fieldName) {
				case 'about':
					return $this->removeJunkAbout($fieldValue);			
				case 'openFields':
					return $this->removeJunkOpenFields($fieldValue);
				default:
					# code...
					break;
			}
		}

		/**
		 * removes junk characters from about section.
		 * @param  string $about the about value
		 * @return string        changed about
		 */
		private function removeJunkAbout($about)
	    {
	        $about =  preg_replace('/[.]{4,}/','...',$about);
	        $about = preg_replace('/([^\w.])\1+/','$1',$about);
	        return $about;
	    }

	    /**
	     * Removes junk from open fields.
	     * @param  string $text the text on which the open field filters to be added
	     * @return 0 or 1       
	     */
	    private function removeJunkOpenFields($text)
	    {
	        $space_vowels = 1;
	        $five_unique = 1;
	        if (strlen($text) == mb_strlen($text, 'utf-8'))
	        {
	       		$five_unique = count( array_unique( str_split( preg_replace('/[^a-z]/i','',$text)))) > 5 ? 1 : 0;
		        $space_vowels = preg_match('/(?=.*\s+)(?=.*[aeiou]+)/i',$text); 
	        }
	        return $five_unique && $space_vowels;
	    }
	}

 ?>