<?php

/**
 * Simple PHP User agent
 *
 * @link      http://github.com/ornicar/php-user-agent
 * @version   1.0
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 *
 * Documentation: http://github.com/ornicar/php-user-agent/blob/master/README.markdown
 * Tickets:       http://github.com/ornicar/php-user-agent/issues
 */

class phpUserAgent
{
  protected $userAgentString;
  protected $browserName;
  protected $browserVersion;
  protected $operatingSystem;
  protected $operatingSystemVersion;
  protected $engine;
  protected $deviceType;

  public function __construct($userAgentString, phpUserAgentStringParser $userAgentStringParser = null)
  {
	$this->detectDevice();
    	$this->configureFromUserAgentString($userAgentString, $userAgentStringParser);
  }

  /**
   * Get the browser name
   *
   * @return string the browser name
   */
  public function getBrowserName()
  {
    return $this->browserName;
  }

  /**
   * Set the browser name
   *
   * @param   string  $name the browser name
   */
  public function setBrowserName($name)
  {
    $this->browserName = $name;
  }

  /**
   * Get the browser version
   *
   * @return string the browser version
   */
  public function getBrowserVersion()
  {
    return $this->browserVersion;
  }

  /**
   * Set the browser version
   *
   * @param   string  $version the browser version
   */
  public function setBrowserVersion($version)
  {
    $this->browserVersion = $version;
  }

  /**
   * Get the operating system name
   *
   * @return  string the operating system name
   */
  public function getOperatingSystem()
  {
    return $this->operatingSystem;
  }

  /**
   * Set the operating system name
   *
   * @param   string $operatingSystem the operating system name
   */
  public function setOperatingSystem($operatingSystem)
  {
    $this->operatingSystem = $operatingSystem;
  }

  /**
   * Get the operating system version
   *
   * @return  string the operating system version
   */
  public function getOperatingSystemVersion()
  {
    return $this->operatingSystemVersion;
  }

  /**
   * Set the operating system version
   *
   * @param   string $operatingSystemVersion the operating system version
   */
  public function setOperatingSystemVersion($operatingSystemVersion)
  {
    $this->operatingSystemVersion = $operatingSystemVersion;
  }

  /**
   * Get the engine name
   *
   * @return  string the engine name
   */
  public function getEngine()
  {
    return $this->engine;
  }

  /**
   * Set the engine name
   *
   * @param   string $operatingSystem the engine name
   */
  public function setEngine($engine)
  {
    $this->engine = $engine;
  }

  /**
   * Get the device type
   *
   * @return  string the device type
   */
  public function getDeviceType()
  {
    return $this->deviceType;
  }

  /**
   * Set the device type
   *
   * @param   string $deviceType the device type
   */
  public function setDeviceType($deviceType)
  {
    $this->deviceType = $deviceType;
  }

  /**
   * Get the user agent string
   *
   * @return  string the user agent string
   */
  public function getUserAgentString()
  {
    return $this->userAgentString;
  }

  /**
   * Set the user agent string
   *
   * @param   string $userAgentString the user agent string
   */
  public function setUserAgentString($userAgentString)
  {
    $this->userAgentString = $userAgentString;
  }

  /**
   * Tell whether this user agent is unknown or not
   *
   * @return boolean  true if this user agent is unknown, false otherwise
   */
  public function isUnknown()
  {
    return empty($this->browserName);
  }

  /**
   * @return string combined browser name and version
   */
  public function getFullName()
  {
    return $this->getBrowserName().' '.$this->getBrowserVersion();
  }

  public function __toString()
  {
    return $this->getFullName();
  }

  /**
   * Configure the user agent from a user agent string
   * @param   string                    $userAgentString        the user agent string
   * @param   phpUserAgentStringParser  $userAgentStringParser  the parser used to parse the string
   */
  public function configureFromUserAgentString($userAgentString, phpUserAgentStringParser $userAgentStringParser = null)
  {
    if(null === $userAgentStringParser)
    {
      $userAgentStringParser = new phpUserAgentStringParser();
    }

    $this->setUserAgentString($userAgentString);

    $this->fromArray($userAgentStringParser->parse($userAgentString));
  }

  /**
   * Convert the user agent to a data array
   *
   * @return  array data
   */
  public function toArray()
  {
    return array(
      'user_agent'  	  	  => $this->getUserAgentString(),
      'browser_name'      	  => $this->getBrowserName(),
      'browser_version'   	  => $this->getBrowserVersion(),
      'operating_system'  	  => $this->getOperatingSystem(),
      'operating_system_version'  => $this->getOperatingSystemVersion(),
      'engine'  	  	  => $this->getEngine(),
      'device_type'  	  	  => $this->getDeviceType()
    );
  }

  /**
   * Configure the user agent from a data array
   *
   * @param array $data
   */
  public function fromArray(array $data)
  {
    $this->setBrowserName($data['browser_name']);
    $this->setBrowserVersion($data['browser_version']);
    $this->setOperatingSystem($data['operating_system']);
    $this->setOperatingSystemVersion($data['operating_system_version']);
    $this->setEngine($data['engine']);
  }

	public function detectDevice()
	{
		if(MobileCommon::isMobile()) 	// 1. Check for mobile environment.
			$device = "MOBILE";
		elseif(MobileCommon::isTabletMobile())	// 2. Check for tablet environment
			$device = "TABLET";
		else 
			$device = "DESKTOP";
		
    		$this->setDeviceType($device);
	}
}
