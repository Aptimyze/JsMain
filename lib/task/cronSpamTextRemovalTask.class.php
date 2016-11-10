<?php

/*
 * Author: Mohammad Shahjahan
 * This task is written for the purpose of adding multiple 
*/

class cronSpamTextRemovalTask extends sfBaseTask
{
  protected function configure()
  {
    $this->file_path = JsConstants::$cronDocRoot."/lib/utils/junkCharacters/spam_character_trained.txt";
    $this->accepted_characters = 'abcdefghijklmnopqrstuvwxyz ';



$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));


    $this->namespace        = 'cron';
    $this->name             = 'cronSpamTextRemoval';
    $this->briefDescription = 'Gets data from profile.junk_character_text and push the edited your info in the table';
    $this->detailedDescription = <<<EOF
The [cronDuplication|INFO] push data in profile.junk_character_text .
Call it with:

  [php symfony cron:cronSpamTextRemoval] 
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
      $PROFILE_JUNK_CHARACTER_TEXT = new PROFILE_JUNK_CHARACTER_TEXT;
      $result = ($PROFILE_JUNK_CHARACTER_TEXT->getOriginalText());
      foreach ($result as $key => $value) {
        $isGibberish = $this->test($value['original_text'],$this->file_path);
        if ( $isGibberish !== -1 )
        {
          if ( $isGibberish )
          {
            $PROFILE_JUNK_CHARACTER_TEXT->updateModifiedText($value['id'],"JUNK");
          }
          else
          {
            $PROFILE_JUNK_CHARACTER_TEXT->updateModifiedText($value['id'],"NOT_JUNK");
          }
        }
      }
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
