<?php

class ArrayChunkify {
  
  /**@#+
   * @access private
   */
  
  /**
   * This holds input profile id Array
   *
   * @var array
   */
  private $inputProfileArray;
  
  /**
   * This holds the current pointer on number of chunks
   * 
   * @var integer
   */
  private $iterator;
  
  /**
   * This holds the number of chunks in which the input profile id array will be broken
   *
   * @var integer
   */
  private $numberOfChunks;

  /**
   * This holds the count of elements in input profile id array (for computation purpose).
   *
   * @var integer
   */
  private $numberOfArrayItems;
  /**@#-
  */

  /**
   * __construct
   *
   * @param $profileIds array
   * @param $chunkSize integer
   * @param $preserveKeys boolean
   */
  public function __construct($profileIds = null, $chunkSize, $preserveKeys = true) {
    
    if ((is_array($profileIds) !== true) && (!is_numeric($chunkSize) && ($chunkSize <= 0)) && (is_bool($preserveKeys) !== true)) {
      echo "One of the input parameter is not properly specified. Please read the documentation before usage.";
      exit;
    }

    $this->inputProfileArray = $profileIds;
    $this->chunkSize = $chunkSize;
    $this->numberOfArrayItems = count($this->inputProfileArray);
    $this->numberOfChunks = floor($this->numberOfArrayItems / $this->chunkSize);
    $this->numberOfChunks += (($this->numberOfArrayItems % $this->chunkSize) === 0) ? 0 : 1;
    $this->iterator = 0;
    $this->preserveKeys = $preserveKeys;
  } // end of __construct

  /**
   * Checks whether there are more elements to chunkify
   *
   * <p>
   * This function checks whether there are more elements in the input array which can be chunkified.
   * </p>
   *
   * @access private
   * @return boolean
   */
  private function _hasMoreElements() {
    return $this->iterator < $this->numberOfChunks;
  } // end of _hasMoreElements

  /**
   * Get next chunk of profile ids
   *
   * <p>
   * This function gets next chunk from input profile id array. 
   * It slices the input array into specified chunk size.
   * </p>
   *
   * @access private
   * @return array
   */
  private function _getNextChunk() {
  
    $profileArray =  null;
    
    $tempProfileArray = null;

    if ($this->_hasMoreElements()) {
      
      $tempProfileArray = array_slice($this->inputProfileArray, 0, $this->chunkSize, $this->preserveKeys);
      $numberOfProfiles = count($tempProfileArray);
      
      for ($i = 0; $i < $numberOfProfiles; ++$i) {
        $profileArray[$tempProfileArray[$i]] = $tempProfileArray[$i];
      }
      
      $this->iterator += 1; 
    }
    else {
      $profileArray = null;
    }
    return $profileArray; 
  } // end of _getNextChunk

  /**
   * Get next chunk of input profile id array.
   *
   * <p>
   * This function first calls _getNextChunk and then splices the input profile id array by chunk size amount,
   * so as to remove the chunk from input profile id array which was chunkified. (saves memory and optimized)
   * </p>
   *
   * @access public
   * @return array/null
   */
  public function chunkify() {
    
    if ($this->inputProfileArray && $this->chunkSize > 0) {
    
        $returnArray = $this->_getNextChunk();
        array_splice($this->inputProfileArray, 0, $this->chunkSize);
        return $returnArray ? $returnArray : null;
    }
    else {
      // End of input profiles
      return null;
    } 
   } // end of chunkify

  /**
   * Dump member variables of this class.
   *
   * <p>
   * This function dumps member variables of this class.
   * </p>
   * 
   * @access public
   */
  public function dumpVariables() {
    print_r($this->inputProfileArray);
    echo "\n";
    print_r($this->chunkSize);
    echo "\n";
    print_r($this->numberOfArrayItems);
    echo "\n";
    print_r($this->numberOfChunks);
    echo "\n";
    print_r($this->iterator);
    echo "\n";
  } // end of dumpVariables

} // end of ArrayChunkify

