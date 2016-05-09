<?php

class FetchAutoSugData
{
    
    private $autoSugAgent;    

    public function __construct($designation)
    {
        $this->designation = $designation;
        $this->autoSugAgent = ImportAutoSugFactory::getAutoSugAgent($designation);        //fetching the polymorphic object
    }    

    public function getAutoSugRecords ($suggestion, $limit)
    {
        if (isset($this->autoSugAgent)){
            if ($this->designation == "subcaste") {
                $suggestion = "%" . $suggestion;
            }
            $resultset = $this->autoSugAgent->viewRecords($suggestion . "%",$limit);
            
            return $resultset;
        }    
    }
}    
