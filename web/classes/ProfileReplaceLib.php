<?php
/**
 * Description of ProfileReplaceLib
 * This is a wrapper library on store classes
 * To Execute REPLACE INTO... Statments  on JPROFILE* Stores (like JPROFILE,JPROFILE_CONTACT,JPROFILE_ALERTS etc),
 * It will be used in Non-symfony code for warpping all Queries which are Updating JPROFILE* Tables
 *
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
     * JPROFILE Store Object
     * @var Object
     */
    private $objJProfileStore = null;

    /**
     * JPROFILE_EDUCATION Store Object
     * @var Object
     */
    private $objProfileEducationStore = null;

    /**
     * JPROFILE_CONTACT Store Object
     * @var Object
     */
    private $objProfileContactStore = null;

    /**
     * JPROFILE_CONTACT Store Object
     * @var Object
     */
    private $objProfileHobbyStore = null;

    /**
     * JP_NTIME Store Object
     * @var Object
     */
    private $objProfileNTimesStore = null;

    /**
     * JP_CHRISTIAN Store Object
     * @var Object
     */
    private $objProfileChristianStore = null;

    /**
     * JPROFILE_ALERTS Store Object
     * @var Object
     */
    private $objProfileAlertsStore = null;

    /**
     * JPROFILE_ALERTS Store Object
     * @var Object
     */
    private $objProfileHoroscopeForScreenStore = null;

    /**
     * ASTRO_DETAILS Store Object
     * @var Object
     */
    private $objProfileAstroDetailsStore = null;



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
        $this->objJProfileStore = new JPROFILE($dbname);
        $this->objProfileEducationStore = new NEWJS_JPROFILE_EDUCATION($dbname);
        $this->objProfileContactStore = new NEWJS_JPROFILE_CONTACT($dbname);
        $this->objProfileHobbyStore = new NEWJS_HOBBIES($dbname);
        $this->objProfileNTimesStore = new NEWJS_JP_NTIMES($dbname);
        $this->objProfileChristianStore = new NEWJS_JP_CHRISTIAN($dbname);
        $this->objProfileAlertsStore = new newjs_JPROFILE_ALERTS($dbname);

        $this->objProfileHoroscopeForScreenStore = new NEWJS_HOROSCOPE_FOR_SCREEN($dbname);
        $this->objProfileAstroDetailsStore = new NEWJS_ASTRO($dbname);
    }
    /**
     * __destruct
     */
    public function __destruct() {
        unset($this->objJProfileStore);
        unset($this->objProfileContactStore);
        unset($this->objProfileEducationStore);
        unset($this->objProfileHobbyStore);
        unset($this->objProfileNTimesStore);
        unset($this->objProfileChristianStore);
        unset($this->objProfileAlertsStore);

        unset($this->objProfileHoroscopeForScreenStore);
        unset($this->objProfileAstroDetailsStore);

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
            self::$instance = new JProfileUpdateLib($dbname);
        }

        //Compare Current DB Name and if its different changeConnection
        //and set new connection with desired dbname
        if(self::$instance->currentDBName !== $dbname) {
            self::$instance->currentDBName = $dbname;
            self::$instance->objJProfileStore->setConnection($dbname);
            self::$instance->objProfileEducationStore->setConnection($dbname);
            self::$instance->objProfileContactStore->setConnection($dbname);
            self::$instance->objProfileHobbyStore->setConnection($dbname);
            self::$instance->objProfileNTimesStore->setConnection($dbname);
            self::$instance->objProfileChristianStore->setConnection($dbname);
            self::$instance->objProfileAlertsStore->setConnection($dbname);

            self::$instance->objProfileHoroscopeForScreenStore->setConnection($dbname);
            self::$instance->objProfileAstroDetailsStore->setConnection($dbname);
        }

        return self::$instance;
    }

    /**
     * replaceASTRO_DETAILS
     * @param $iProfileID
     * @param $arrParams
     * @return bool
     */
    public function replaceASTRO_DETAILS($iProfileID,$arrParams=array())
    {
        try{
            $this->objProfileAstroDetailsStore->update($iProfileID,$arrParams);
        } catch (Exception $ex) {
            jsException::log($ex);
            return false;
        }
    }

    /**
     * replaceASTRO_DETAILS
     * @param $iProfileID
     * @param $arrParams
     * @return bool
     */
    public function replaceHOROSCOPE_FOR_SCREEN($iProfileID,$arrParams=array())
    {
        try{
            $this->objProfileHoroscopeForScreenStore->replaceRecord($iProfileID,$arrParams);
        } catch (Exception $ex) {
            jsException::log($ex);
            return false;
        }
    }
}
?>
