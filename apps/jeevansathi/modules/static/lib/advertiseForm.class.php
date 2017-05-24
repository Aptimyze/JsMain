<?php

class advertiseForm extends BaseForm
{
	function __construct($defaults = array(),$options = array(), $CSRFSecret = null)
	{
		parent::__construct($defaults, $options, $CSRFSecret);
	}
	public function configure()
	{
		$this->setWidgets(array(
			'organisation'     => new sfWidgetFormInputText(array()),
		    'name' => new sfWidgetFormInputText(array()),
		    'business'    => new sfWidgetFormInputText(array()),
		    'address' => new sfWidgetFormTextarea(array()),
		    'phone'    => new sfWidgetFormInputText(array()),
		    'email'    => new sfWidgetFormInputText(array()),
		    'details' => new sfWidgetFormTextarea(array()),
		));
		$this->widgetSchema->setNameFormat('advertis[%s]');

		$this->widgetSchema->setLabels(array(
		'organisation'  => '* Organisation / Company Name :',
		'name'          => '* Your Name :',
		'business'      => 'Nature of Business :',
		'address'       => '* Contact Address :',
		'phone' 		=> '* Contact Phone :',
		'email' 		=> '* E-mail :',
		'details' 		=> '* Queries about advertisement :',
		));
		

		$this->setValidators(array(
	      'organisation' => new sfValidatorString(array(),array('required'=>'Please specify Organisation / Company Name')),
	      'name'         => new sfValidatorString(array()),
	      'business'     => new sfvalidatorPass(),
	      'address'      => new sfValidatorString(array()),
	      'phone'        => new sfValidatorString(array()),
	      'email'        => new sfValidatorEmail(array(),array('required'=>'Please enter your Email address',
																'invalid' => 'Please enter valid email id')),
	      'details'      => new sfValidatorString(array()),
	    ));
	}
}
	
?>
