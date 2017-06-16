<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorInteger validates an integer. It also converts the input value to an integer.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorInteger.class.php 22018 2009-09-14 16:56:28Z fabien $
 */
class sfValidatorSibling extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * max: The maximum value allowed
   *  * min: The minimum value allowed
   *
   * Available error codes:
   *
   *  * max
   *  * min
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('max', '"%value%" must be at most %max%.');
    $this->addMessage('min', '"%value%" must be at least %min%.');

    $this->addOption('min');
    $this->addOption('max');
	$this->addOption('formValues');
    $this->setMessage('invalid', '"%value%" is not an integer.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
	if($value>4)
	{
		$value=intval(4);
		$clean=$value;
	}
	else
    $clean = intval($value);
    
	$formValues=$this->getOption('formValues');
    if (strval($clean) != $value)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    if ($this->hasOption('max') && $clean > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => $this->getOption('max')));
    }

    if ($this->hasOption('min') && $clean < $this->getOption('min'))
    {
      throw new sfValidatorError($this, 'min', array('value' => $value, 'min' => $this->getOption('min')));
    }

    if(isset($formValues[T_BROTHER]) && isset($formValues[M_BROTHER]))
    {
		if($formValues[T_BROTHER] < $formValues[M_BROTHER])
			 throw new sfValidatorError($this,"Married brother cant be greater than total brother" );
	}
	 if(isset($formValues[T_SISTER]) && isset($formValues[M_SISTER]))
    {
		if($formValues[T_SISTER] < $formValues[M_SISTER])
			 throw new sfValidatorError($this,"Married sister cant be greater than total sister" );
	}

    return $clean;
  }
}
