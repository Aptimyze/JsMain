<?php

/**
 * This Class is a factory class used to return object of class CompanyList / InstituteList / SchoolList based on imput parameter.
 */

class ImportAutoSugFactory
{
    /**
      This function is used to return object of the class CompanyList / InstituteList / SchoolList
      @param $designation - four possible values (school/collg/PGcollg/org) to create an object of associated class
      @return object
     **/

    static public function getAutoSugAgent($designation)
    {
        switch ($designation) {

            case 'school':
                $suggestion = new NW_SCHOOLLIST();
                break;

            case 'collg':
            case 'PGcollg':    
                $suggestion = new NW_INSTITUTELIST();
                break;

            case 'org':
                $suggestion = new NW_COMPANYLIST();
                break;
            case 'gothra':
                $suggestion= new newjs_GOTHRA_LIST();
                break;
            case 'dioceses':
                $suggestion=new newjs_DIOCESES();
                break;
            case 'subcaste':
                $suggestion = new NEWJS_SUBCASTE();
                break;
            default:
                $suggestion = NULL;
        }
        return $suggestion;
    }
}    
