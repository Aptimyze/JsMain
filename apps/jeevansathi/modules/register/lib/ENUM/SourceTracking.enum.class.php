<?php
/**
 * SourceTrackingEnum class
 * Creates constant variable used in SourceHandling classes.
 *
 * Below is the demonstration on how to use this class
 * <code>
 * //if want to fetch preset auto Suggested constant 
 * reg_page_url=SourceTrackingEnum::REG_PAGE_URL;
 * </code>
 * 
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage register
 * @author    Nitesh Sethi<nitesh.s@jeevansathi.com>
 * @copyright 2013 Nitesh Sethi
 */
class SourceTrackingEnum
{
	public static $REG_PAGE_URL="'/profile/registration_new.php'";
	public static $UNKNOWN_SOURCE="unknown";
	public static $SOURCE_IP="IP";
	public static $REG_PAGE_1_FLAG="REG_PAGE_1";
	public static $HOME_PAGE_FLAG="HOME_PAGE";

public static $filterFieldsStr="GENDER,LAGE,HAGE,PARTNER_MSTATUS,PARTNER_RELIGION,PARTNER_CASTE,PARTNER_COUNTRYRES,PARTNER_CITYRES,PARTNER_MTONGUE,LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL";
}
