<?php 
	
	/**
	* To remove junk characters from given field.
	*/
	class JunkCharacterRemovalLib
	{
		public function __construct()
		{
			$this->file_path = JsConstants::$cronDocRoot."/lib/utils/junkCharacters/spam_character_trained.txt";
			$this->accepted_characters = 'abcdefghijklmnopqrstuvwxyz ';
		}
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
				case 'familyInfo':
					return $this->removeJunkFamilyInfo($fieldValue);			
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
	    	if (strlen($about) == mb_strlen($about, 'utf-8'))
	        {
	        	$about =  preg_replace('/[.]{4,}/','...',$about);
	        	$about = preg_replace('/([^\w.])\1+/','$1',$about);

	        	foreach ( JunkCharacterEnums::$ABOUT_WEBSITE_TEXT as $about_text) {
	        		if ( stristr($about,$about_text) !== FALSE)
	        		{
	        			return "";
	        		}
	        	}
	        }
	        // calling function to check whether about me had junk character.
	       return $this->checkGibberish($about);
	    }


	    /**
	     * removes junk from family info
	     * @param  string $familyInfo 
	     * @return boolean             returns 0 or 1 
	     */
	    private function removeJunkFamilyInfo($familyInfo)
	    {
	    	foreach ( JunkCharacterEnums::$FAMILY_INFO_WEBSITE_TEXT as $family_info_text) {
	    		if ( stristr($familyInfo,$family_info_text) !== FALSE)
	    		{
	    			return 0;
	    		}
	    	}
	    	if ( $this->checkGibberish($familyInfo) !== "" )
	    	{
	    		return $this->removeJunkOpenFields($familyInfo);
	    	}
	    	else
	    	{
	    		return 0;
	    	}
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

	    /**
	     * function is written to check whether the text is gibberish or not?
	     * @param  string $text 
	     * @return string empty string or original string, depending upon the fact whether text is spammy or not?
	     */
	    private function checkGibberish($text='')
	    {
	    	$isGibberish = $this->test($text,$this->file_path);
	        if ( $isGibberish !== -1 )
	        {
	        	if ( $isGibberish )
	        	{
	        		return "";
	        	}
	        }
	        return $text;
	    }

	    private function test($text, $lib_path, $raw=false)
	    {
	    	if(file_exists($lib_path) === false)
	    	{
	    //                  TODO throw error?
	    		return -1;
	    	}
	    	$trained_library = unserialize(file_get_contents($lib_path));
	    	if(is_array($trained_library) === false)
	    	{
	    //                 TODO throw error?
	    		return -1;
	    	}

	    	$value = self::_averageTransitionProbability($text, $trained_library['matrix']);
	    	if($raw === true)
	    	{
	    		return $value;
	    	}

	    	if($value <= $trained_library['threshold'])
	    	{
	    		return true;
	    	}

	    	return false;
	    }

	    private function normalise($line)
	    {
	    //          Return only the subset of chars from accepted_chars.
	    //          This helps keep the  model relatively small by ignoring punctuation, 
	    //          infrequenty symbols, etc.
	    	return preg_replace('/[^a-z\ ]/', '', strtolower($line));
	    }

	    private function _averageTransitionProbability($line, $log_prob_matrix)
	    {

	    //          Return the average transition prob from line through log_prob_mat.
	    	$log_prob = 1.0;
	    	$transition_ct = 0;

	    	$pos = array_flip(str_split($this->accepted_characters));
	    	$filtered_line = str_split($this->normalise($line));
	    	$a = false;
	    	foreach ($filtered_line as $b)
	    	{
	    		if($a !== false)
	    		{
	    			$log_prob += $log_prob_matrix[$pos[$a]][$pos[$b]];
	    			$transition_ct += 1;
	    		}
	    		$a = $b;
	    	}
	          # The exponentiation translates from log probs to probs.
	    	return exp($log_prob / max($transition_ct, 1));
	    }

	}

 ?>