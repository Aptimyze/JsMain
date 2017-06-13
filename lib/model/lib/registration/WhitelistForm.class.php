<?php
/**
 * WhitelistForm class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage registration
 */

class WhitelistForm
{
  /*
   * Memeber Variables declaration
   */

  /*
   * array to store the shown fields received from form
   * @access Private
   * @var array
   */
  private $formFields = array();
 /*
   * array to store the hidden fields received from form
   * @access Private
   * @var array
   */
  private $hiddenFormFields = array();
  /*
   * array to store the actual fields to be recieved from form
   * @access Private
   * @var array
   */
  private $actualFormFields = array();
 /*
   * array to store the actual hidden fields to be received from form
   * @access Private
   * @var array
   */
  private $actualHiddenFormFields = array();

  /*
   * Defining Member Function
   */

  /**
   * Constructor 
   * @access Public
   * @return Void
   * <p>
   * </p>
   */
  public function __construct ($formFields,$hiddenFormFields,$errorDetailsKey) 
  {
	$this->formFields = $formFields;
	$this->hiddenFormFields = $hiddenFormFields;
	$this->actualFormFields = RegistrationEnums::$pageFields[$errorDetailsKey];
	$this->actualHiddenFormFields = RegistrationEnums::$pageHiddenFields[$errorDetailsKey];
  }
  /**
   * function performing the actions to be taken for the errors detected
   * @access Public
   * @return Void
   * <p>
   * </p>
   */

  public function getError()
  {
    $error = false;
    if(is_array($this->formFields))
	    $formDiff = array_diff($this->formFields, $this->actualFormFields);
    if (count($formDiff) != 0)
      $error = implode(",",$formDiff).",";
    if(is_array($this->hiddenFormFields))
	    $formDiff = array_diff($this->hiddenFormFields, $this->actualHiddenFormFields);
    if (count($formDiff) != 0)
      $error.= implode(",",$formDiff).",";
    $error = substr($error,0,-1);
    return $error;
  }
}
