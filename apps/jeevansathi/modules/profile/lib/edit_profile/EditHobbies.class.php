<?php
class EditHobbies extends EditProfileComponent {
	public function submit() {
		$now = date("Y-m-d H:i:s");
		$this->request = $this->action->getRequest();
		$HOBBY = $this->request->getParameter('hobbies_arr');
		$MUSIC = $this->request->getParameter('music_arr');
		$INTEREST = $this->request->getParameter('interest_arr');
		$BOOK = $this->request->getParameter('book_arr');
		$MOVIE = $this->request->getParameter('movies_arr');
		$CUISINE = $this->request->getParameter('cuisine_arr');
		$LANGUAGE = $this->request->getParameter('language_arr');
		$DRESS = $this->request->getParameter('dress_arr');
		$SPORTS = $this->request->getParameter('sports_arr');
		$hobbies = $this->loginProfile->getHobbies();
		$arr = array();
		if (is_array($HOBBY)) $arr = array_merge($arr, $HOBBY);
		if (is_array($INTEREST)) $arr = array_merge($arr, $INTEREST);
		if (is_array($MUSIC)) $arr = array_merge($arr, $MUSIC);
		if (is_array($BOOK)) $arr = array_merge($arr, $BOOK);
		if (is_array($MOVIE)) $arr = array_merge($arr, $MOVIE);
		if (is_array($SPORTS)) $arr = array_merge($arr, $SPORTS);
		if (is_array($CUISINE)) $arr = array_merge($arr, $CUISINE);
		if (is_array($LANGUAGE)) $arr = array_merge($arr, $LANGUAGE);
		$language_all = HobbyLib::getHobbyLabel('hobbies_language', "", 1);
		if (is_array($DRESS)) $arr = array_merge($arr, $DRESS);
		$hobbiesSubmitted = implode($arr, ",");
		$hobbies_orig = $this->loginProfile->getHobbies(1);
		//Language comes from lifestyle layer and should be retained
		if ($hobbies_orig[HOBBY]) {
			$hobby_array = explode(",", $hobbies_orig[HOBBY]);
			$language_to_retain = array();
			foreach ($hobby_array as $hobbyValue) if (array_key_exists($hobbyValue, $language_all)) $language_to_retain[] = $hobbyValue;
		}
		if (count($language_to_retain)) $hobbiesSubmitted.= $hobbiesSubmitted ? "," . implode(",", $language_to_retain) : implode(",", $language_to_retain);
		//Language retention ends
		//Check if any change in a field of hobbies
		$hobbyArr = array('HOBBY' => $hobbiesSubmitted, 'FAV_BOOK' => trim($this->request->getParameter('fav_book')), 'FAV_VAC_DEST' => trim($this->request->getParameter('fav_vac_dest')), 'FAV_FOOD' => trim($this->request->getParameter('fav_food')), 'FAV_MOVIE' => trim($this->request->getParameter('fav_movies')), 'FAV_TVSHOW' => trim($this->request->getParameter('fav_tvshow')),);
		$toChange = count($hobbies_orig)?false:true;
		if(count($hobbies_orig)) foreach ($hobbies_orig as $key => $value) {
			if ($hobbyArr[$key] != $value) {
				$toChange = true;
				break;
			}
		}
		$cur_flag = $this->loginProfile->getSCREENING();
		//Screening flag calculation
		foreach (array('FAV_BOOK', 'FAV_VAC_DEST', 'FAV_FOOD', 'FAV_MOVIE', 'FAV_TVSHOW') as $field) {
			if ($hobbyArr[$field]) {
				if ($hobbyArr[$field] != $hobbies_orig[$field]) $cur_flag = Flag::removeFlag($field, $cur_flag);
			} else {
				$cur_flag = Flag::setFlag($field, $cur_flag);
			}
		}
		//Screening flag calculation ends
		if ($toChange) {
			$this->loginProfile->editHobby($hobbyArr);
			if ($hobbies_orig[HOBBY] != $hobbiesSubmitted) {
				$keywords = $this->loginProfile->getKEYWORDS();
				$pos = strpos($keywords, "|");
				if ($pos) $key = substr($keywords, 0, $pos);
				else $key = $keywords;
				foreach (explode(",", $hobbiesSubmitted) as $hob_value) $hob_str.= HobbyLib::getHobbyLabel('hobbies', $hob_value) . ",";
				//Remove comma from last
				if ($hob_str) $hob_str = substr($hob_str, 0, -1);
				$now = date("Y-m-d H:i:s");
				$newKeywords = addslashes(stripslashes($key . "|" . $hob_str));
				$jprofile_vals['KEYWORDS']=$newKeywords;
			}
			$jprofile_vals['SCREENING']=$cur_flag;
			$jprofile_vals['MOD_DT']=$now;
			$this->loginProfile->edit($jprofile_vals);
			unset($hobbyArr[HOBBY]);
			$hobbyArr[KEYWORDS] = $newKeywords;
			$hobbyArr[MOD_DT] = $now;
			$hobbyArr[PROFILEID] = $this->action->profileId;
			$hobbyArr[SCREENING] = $cur_flag;
			log_edit($hobbyArr);
		}
	}
	public function display() {
		$hobbies = $this->loginProfile->getHobbies("onlyValues");
		$selectedHobbies = $hobbies[HOBBY];
		$this->action->HOBBY = HobbyLib::getHobbyLabel("hobbies_hobby", "", 1);
		$this->action->INTEREST = HobbyLib::getHobbyLabel("hobbies_interest", "", 1);
		$this->action->MUSIC = HobbyLib::getHobbyLabel("hobbies_music", "", 1);
		$this->action->DRESS = HobbyLib::getHobbyLabel("hobbies_dress", "", 1);
		$this->action->BOOK = HobbyLib::getHobbyLabel("hobbies_book", "", 1);
		$this->action->MOVIE = HobbyLib::getHobbyLabel("hobbies_movie", "", 1);
		$this->action->CUISINE = HobbyLib::getHobbyLabel("hobbies_cuisine", "", 1);
		$this->action->SPORTS = HobbyLib::getHobbyLabel("hobbies_sports", "", 1);
		$this->action->HOBBY_str = $this->createSelectedString($this->action->HOBBY, $selectedHobbies);
		$this->action->INTEREST_str = $this->createSelectedString($this->action->INTEREST, $selectedHobbies);
		$this->action->MUSIC_str = $this->createSelectedString($this->action->MUSIC, $selectedHobbies);
		$this->action->DRESS_str = $this->createSelectedString($this->action->DRESS, $selectedHobbies);
		$this->action->BOOK_str = $this->createSelectedString($this->action->BOOK, $selectedHobbies);
		$this->action->MOVIE_str = $this->createSelectedString($this->action->MOVIE, $selectedHobbies);
		$this->action->CUISINE_str = $this->createSelectedString($this->action->CUISINE, $selectedHobbies);
		$this->action->SPORTS_str = $this->createSelectedString($this->action->SPORTS, $selectedHobbies);
		$this->action->FAV_MOVIES = $hobbies[FAV_MOVIE];
		$this->action->FAV_TVSHOW = $hobbies[FAV_TVSHOW];
		$this->action->FAV_BOOK = $hobbies[FAV_BOOK];
		$this->action->FAV_VAC_DEST = $hobbies[FAV_VAC_DEST];
		$this->action->FAV_FOOD = $hobbies[FAV_FOOD];
	}
	public function getTemplateName() {
		return "profile_edit_hobbies";
	}
	public function getLayerHeading() {
		return "Hobbies and Interests";
	}
	private function createSelectedString($hobbyList, $selectedHobbies) {
		if ($selectedHobbies) {
			$hobby_array = explode(",", $selectedHobbies);
			foreach ($hobby_array as $hobbyValue) if (array_key_exists($hobbyValue, $hobbyList)) $hobby_selected[] = $hobbyValue;
		}
		if (count($hobby_selected) > 1) $hobby_selected_str = implode($hobby_selected, "','");
		else $hobby_selected_str = "'" . $hobby_selected[0] . "'";
		return $hobby_selected_str;
	}
}
