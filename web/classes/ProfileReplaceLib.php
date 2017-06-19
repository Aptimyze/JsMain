<?php
/**
 * Description of ProfileReplaceLib
 * This is a wrapper library on store classes
 * To Execute REPLACE INTO... Statments  on JPROFILE* Stores (like JPROFILE,JPROFILE_CONTACT,JPROFILE_ALERTS etc),
 * It will be used in Non-symfony code for warpping all Queries which are Updating JPROFILE* Tables
 * <code>
 * include_once(JsConstants::$docRoot."/classes/ProfileReplaceLib.php");
 * 
 * </code>
 * @author Kunal Verma
 * @created 22nd June 2016
 */
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

/**
 * ProfileReplaceLib Wrapper Library
 */
class ProfileReplaceLib
{
    /**
     *
     * @var Object
     */
    private static $instance = null;

    /**
     * HOROSCOPE_FOR_SCREEN Store Object
     * @var Object
     */
    private $objProfileHoroscopeForScreenStore = null;

    /**
     * ASTRO_DETAILS Store Object
     * @var Object
     */
    private $objProfileAstroDetailsStore = null;

    /**
     * HOROSCOPE Store Object
     * @var Object
     */
    private $objProfileHoroscopeStore = null;

    /**
     * HOROSCOPE_COMPATIBILITY Store Object
     * @var Object
     */
    private $objProfileHoroscopeCompatibilityStore = null;

    /**
     * AUTO_EXPIRY Store Object
     * @var Object
     */
    private $objProfileAutoExpiry = null;

    /**
     *
     * @var String
     */
    private $currentDBName = null;

    /**
     * Constructor function
     */
    private function __construct($dbname="")
    {
        $this->currentDBName = $dbname;
        $this->objProfileHoroscopeForScreenStore = new NEWJS_HOROSCOPE_FOR_SCREEN($dbname);
        $this->objProfileAstroDetailsStore = ProfileAstro::getInstance($dbname);
        $this->objProfileHoroscopeStore = new newjs_HOROSCOPE($dbname);
        $this->objProfileHoroscopeCompatibilityStore = new NEWJS_HOROSCOPE_COMPATIBILITY($dbname);
        $this->objProfileAutoExpiry = new ProfileAUTO_EXPIRY($dbname);
    }
    /**
     * __destruct
     */
    public function __destruct() {
        unset($this->objProfileHoroscopeForScreenStore);
        unset($this->objProfileAstroDetailsStore);
        unset($this->objProfileHoroscopeStore);
        unset($this->objProfileHoroscopeCompatibilityStore);
        unset($this->objProfileAutoExpiry);
        self::$instance = null;
    }
    /**
     * To Stop clone of this class object
     */
    private function __clone() {}

    /**
     * To stop unserialize for this class object
     */
    private function __wakeup() {}

    /**
     * Current DB Name like newjs_master
     * @return type
     */
    public function getCurrentDBName()
    {
        return $this->currentDBName?$this->currentDBName:"newjs_master";
    }

    /**
     * Get Instance
     * @return Object of JProfileUpdateLib
     */
    public static function getInstance($dbname="")
    {
        if (null === self::$instance) {
            self::$instance = new ProfileReplaceLib($dbname);
        }

        //Compare Current DB Name and if its different changeConnection
        //and set new connection with desired dbname
        if(self::$instance->currentDBName !== $dbname) {
            self::$instance->currentDBName = $dbname;
            self::$instance->objProfileHoroscopeForScreenStore->setConnection($dbname);
            self::$instance->objProfileAstroDetailsStore = ProfileAstro::getInstance($dbname);
            self::$instance->objProfileHoroscopeStore->setConnection($dbname);
            self::$instance->objProfileHoroscopeCompatibilityStore->setConnection($dbname);
            
            unset($this->objProfileAutoExpiry);
            self::$instance->objProfileAutoExpiry = new ProfileAUTO_EXPIRY($dbname);
        }

        return self::$instance;
    }

    /**
     * replaceASTRO_DETAILS
     * @param $iProfileID
     * @param $arrParams
     * @return bool
     */
    public function replaceASTRO_DETAILS($iProfileID,$arrParams)
    {
        try{
            $this->objProfileAstroDetailsStore->replaceRecord($iProfileID,$arrParams);
        } catch (Exception $ex) {
            jsCacheWrapperException::logThis($ex);
            return false;
        }
    }

    /**
     * replaceASTRO_DETAILS
     * @param $iProfileID
     * @param $arrParams
     * @return bool
     */
    public function replaceAUTOEXPIRY($iProfileID,$type,$date)
    {
        try{
            $this->objProfileAutoExpiry->replace($iProfileID,$type,$date);
        } catch (Exception $ex) {
            jsCacheWrapperException::logThis($ex);
            return false;
        }
    }
    
    /**
     * replaceHOROSCOPE_FOR_SCREEN
     * @param $iProfileID
     * @param $arrParams
     * @return bool
     */
    public function replaceHOROSCOPE_FOR_SCREEN($iProfileID,$arrParams=array())
    {
        try{
            $this->objProfileHoroscopeForScreenStore->replaceRecord($iProfileID,$arrParams);
        } catch (Exception $ex) {
            jsCacheWrapperException::logThis($ex);
            return false;
        }
    }

    /**
     * replaceHOROSCOPE_FOR_SCREEN
     * @param $iProfileID
     * @param $arrParams
     * @return bool
     */
    public function replaceHOROSCOPE($iProfileID,$arrParams=array())
    {
        try{
            $this->objProfileHoroscopeStore->replaceRecord($iProfileID,$arrParams);
        } catch (Exception $ex) {
            jsCacheWrapperException::logThis($ex);
            return false;
        }
    }

    /**
     * copyAllHoroscopFromScreen
     * @return bool
     */
    public function copyAllHoroscopFromScreen()
    {
        try{
            $this->objProfileHoroscopeStore->copyAllHoroscopFromScreen();
        } catch (Exception $ex) {
            jsCacheWrapperException::logThis($ex);
            return false;
        }
    }

    /**
     * replaceHOROSCOPE_COMPATIBILITY
     * @param $iProfileID
     * @param $arrParams
     * @return bool
     */
    public function replaceHOROSCOPE_COMPATIBILITY($iProfileID,$iOtherProfileID)
    {
        try{
            $this->objProfileHoroscopeCompatibilityStore->replaceRecord($iProfileID,$iOtherProfileID);
        } catch (Exception $ex) {
            jsCacheWrapperException::logThis($ex);
            return false;
        }
    }
}
?>
