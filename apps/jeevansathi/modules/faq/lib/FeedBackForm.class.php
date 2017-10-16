<?php

class FeedBackForm extends BaseForm
{
	function __construct($api=0,$defaults = array(),$options = array(), $CSRFSecret = null)
	{
		if($api)
			$this->api=1;
		else
			if($api)
			$this->api=0;
		parent::__construct($defaults, $options, $CSRFSecret);
	}
	 public function configure()
	{
		$arrFAQCategoryKey = array(	"",
									"delete",
									"initiate",
									"edit",
									"login",
									"retrieve",
									"search",
									"Photo",
									"Payment",
									"Abuse",
									"Suggestion",
									"Other",
									"wapsite",
									"AppAndroid",
									"AppApple");
		$arrFAQCategory = array("Please select an option",
								"Profile Deletion",
								"Contact initiation",
								"Edit Basic information",
								"Login to jeevansathi.com",
								"Retrieve username/password",
								"Search for perfect match",
								"Photo Upload",
								"Membership/Payment Related Queries",
								"Report as Fake/Spam",
								"Suggestions",
								"Others",
								"Mobile site",
								"Android",
								"Apple");

		$arrChoices = array_combine($arrFAQCategoryKey,$arrFAQCategory);

		$this->setWidgets(array(
		'name'          => new sfWidgetFormInputText(array()),
		'username'      => new sfWidgetFormInputText(array()),
		'email'         => new sfWidgetFormInputText(),
		'category'      => new sfWidgetFormSelect(array('choices'=>$arrChoices)),
		'message' 		=> new sfWidgetFormTextarea(array()),
		));

		//Label of form
		$this->widgetSchema->setLabels(array(
		'name'          => 'Name :',
		'username'      => 'Username :',
		'email'         => 'Email :',
		'category'      => 'Category :',
		'message' 		=> 'Your Reason :',
		));

		$this->widgetSchema->setNameFormat('feed[%s]');
		
		//Validator
		
		$this->setValidators($this->getValidatorApiArray($arrChoices));
		if($this->api)
			$this->disableLocalCSRFProtection();
		
			
	}
	public function getValidatorApiArray($arrChoices)
	{
		if(!$this->api)
		{
			return array(
			'name'          => new sfvalidatorPass(),							
			'username'		=> new sfvalidatorPass(),
			'email'         => new sfValidatorEmail(array(),array('required'=>'Please enter your Email address','invalid' => 'Please enter valid email id')),
			'category'      => new sfValidatorChoice(array('choices'=>array_keys($arrChoices)),array('required'=>'Please Specify a Category', 'invalid'=>'Invalid Choice')),
			'message' 		=> new sfvalidatorPass(),
			);
		}
		else
		{
			return array(
			'name'          => new sfvalidatorPass(),							
			'username'		=> new sfvalidatorPass(),
			'email'         => new sfValidatorEmail(array(),array('required'=>'Please enter your Email address','invalid' => 'Please enter valid email id')),
			'category'      => new sfValidatorChoice(array('choices'=>array_keys($arrChoices)),array('required'=>'Please Specify a Category', 'invalid'=>'Invalid Choice')),
			'message' 		=> new sfvalidatorString(array(),array('required'=>'please provide a valid reason')),
			);
		}
	}

}	
?>
