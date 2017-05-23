<?php
/**
 * jsWidgetFormMobile represents a date widget.
 *
 * @author     Amit Jaiswal
 */
class jsWidgetFormMobile extends sfWidgetForm
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * can_be_empty: Whether the widget accept an empty value (true by default)
   *  * empty_values: An array of values to use for the empty value (empty string for year, month, and day by default)
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
     $this->addOption('format', '%isd% %mobile%');
  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $mobile = array();
    if(MobileCommon::isMobile())
    	$options = array('type'=>'tel');
    else
    	$options = array();	

    $mobile['%isd%'] = $this->renderIsdWidget($name.'[isd]',$value['isd'],array_merge(array('default'=>'+91'),$options),@array_merge($this->attributes, $attributes[isd]));
    $mobile['%mobile%'] = $this->renderMobileWidget($name.'[mobile]',$value['mobile'],$options,@array_merge($this->attributes, $attributes[mobile]));
    $mobile['%std%'] = $this->renderMobileWidget($name.'[std]',$value['std'],$options, @array_merge($this->attributes, $attributes[std]));
    $mobile['%landline%'] = $this->renderMobileWidget($name.'[landline]',$value['landline'],$options, @array_merge($this->attributes, $attributes[landline]));

    return strtr($this->getOption('format'), $mobile);
  }

  /**
   * @param string $name
   * @param string $value
   * @param array $options
   * @param array $attributes
   * @return string rendered widget
   */
  protected function renderIsdWidget($name,$value,$options, $attributes)
  {
    $widget = new sfWidgetFormInputText($options, $attributes);
    return $widget->render($name,$value);
  }

  /**
   * @param string $name
   * @param string $value
   * @param array $options
   * @param array $attributes
   * @return string rendered widget
   */
  protected function renderMobileWidget($name,$value,$options, $attributes)
  {
    $widget = new sfWidgetFormInputText($options,$attributes);
    return $widget->render($name,$value);
  }
}
