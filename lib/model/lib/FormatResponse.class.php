<?php
/**
 * Class to FormatResponse
 * @author : Kunal verma
 * @Date : 8th July 2016
 */

/**
 *
 */

class FormatResponse
{
    /**
     *
     * @var Object
     */
    private static $instance = null;

    /**
     * Constructor function
     */
    private function __construct($dbname="")
    {
    }
    /**
     * __destruct
     */
    public function __destruct() {
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
     * Get Instance
     * @return Object of FormatResponse
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            $className =  __CLASS__;
            self::$instance = new $className;
        }

        return self::$instance;
    }

    /**
     * @param $enResponseType
     * @param $Var
     * @return null|void
     * @throws void
     */
    public function generate($enResponseType,$Var)
    {
        $output = null;
        switch ($enResponseType)
        {
            case FormatResponseEnums::REDIS_TO_MYSQL :
                $output = $this->convertRedisToMysql($Var);
                break;
            default :
                throw jsException::log("","Invalid FormatResponse Type");
        }

        return $output;
    }

    /**
     * @param $Var
     * @return mixed
     */
    private function convertRedisToMysql($Var)
    {
        return $Var;
    }
}
?>
