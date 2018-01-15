<?php
/**
 * SubjectHandler contains get methods to get the Subject Text to be send in the mailers.
 * 
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nitesh Sethi
 * @version 1.0   SVN: $Id:  Privilege 23810 2013-01-15 nitesh.s $
 */
Class SubjectHandler
{

	/**
   *
   * This holds emailTemplate object.
   *
   * @access private
   * @var emailTemplate
   */
	private $emailTemplate;
	
	/**
   *
   * This holds subject text to be send in the mailer
   *
   * @access private
   * @var subjectCode
   */
	private $subjectCode;
	
	/**
	 * This function used to initilaize the SubjectHandler Class object.
	 * @param EmailType emailType
	 * @return  void
	 * @access public
	 */
	public function __construct(EmailTemplate $emailTemplate) {
		$this->emailTemplate=$emailTemplate;
		$this->setSubjectCode();
	}
	
	/**
	 * This function used to set Subject Code to be send in the mailer.
	 * @uses Email_Type
	 * @uses jeevansathi_mailer_MAILER_SUBJECT::getSubjectCode()
	 * @uses 
	 * @return  void
	 * @access private
	 */
	private function setSubjectCode()
	{
		$dbMailerSubject= new jeevansathi_mailer_MAILER_SUBJECT();
		$subjectCodeArr=$dbMailerSubject->getSubjectCode($this->emailTemplate->getEmailType()->getEmailID());
		$smarty=$this->emailTemplate->getSmarty();
		$count=count($subjectCodeArr);
		if($count>1)
				$this->subjectCode= $this->calculateSubjectLine($subjectCodeArr);
		else
			$this->subjectCode= $subjectCodeArr[0]["SUBJECT_CODE"];
		if(method_exists($smarty,"getTemplateVars"))
			$get_template_vars="getTemplateVars";
		else
			$get_template_vars="get_template_vars";
		if(strpos($this->subjectCode,'~')){
			$regex='/~\$(.*)`/';
			preg_match($regex,$this->subjectCode,$matches);
			//To handle two smarty variables in subject code
			if(strpos($matches[1],'~')){
				$regex1='/~\$(.*)/';
				preg_match($regex1,$matches[1],$matches1);
				$matches_single_var=str_replace($matches1[0],'',$matches[1]);
				$regex_alt='/(.*)`/';
				preg_match($regex_alt,$matches_single_var,$matches2);
				$this->subjectCode=str_replace($matches1[0]."`",$smarty->$get_template_vars($matches1[1]),$this->subjectCode);
				$this->subjectCode=str_replace("~$".$matches2[0],$smarty->$get_template_vars($matches2[1]),$this->subjectCode);
			}
			else
			$this->subjectCode=str_replace($matches[0],$smarty->$get_template_vars($matches[1]),$this->subjectCode);
		}
	}
	
	/**
	 * This function used to get Subject Code to be send in the mailer.
	 * @return  string
	 * @access public
	 */
	public function getSubjectCode()
	{
		return $this->subjectCode;
	}
	
	/**
	 * This function used to calculate Subject Code on the basis of some conditons provided
	 * @uses Profile $profile
	 * @return  string
	 * @access public
	 */
	public function calculateSubjectLine($subjectCodeArr)
	{
		
		$mailGroup=$this->emailTemplate->getEmailType()->getMailGroup();
		//for Complete profile mailer conditon if($mailGroup=){}		
		$receiver=$this->emailTemplate->getSenderProfile();
		$emailId = $this->emailTemplate->getEmailType()->getEmailID();
		if($emailId == 1748 || $emailId == 1758)
		{
			$profileArr[0] = $receiver;
			$picture = new PictureArray;
			$photoCount = $picture->getNoOfPics($profileArr);
			if($photoCount[$receiver->getPROFILEID()]>0)
			{
				// Photo is visible on accept
				if($receiver->getPHOTO_DISPLAY() == 'C')
				{
					$subjectCode = $subjectCodeArr[2]["SUBJECT_CODE"];
				}
				else
				{
					$subjectCode = $subjectCodeArr[0]["SUBJECT_CODE"];
				}
			}
			else
				$subjectCode = $subjectCodeArr[1]["SUBJECT_CODE"];
		}	
		else 
			$subjectCode = $subjectCodeArr[0]["SUBJECT_CODE"];
		return $subjectCode;
	}



















}
