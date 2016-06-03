<?php

/**
 * This task updates the jeevansathi_mailer.DAILY_MAILER_COUNT_LOG table counts
 * by reading the log details from a file 
 */

class updateOpenRateCountTask extends sfBaseTask
{
    protected function configure()
    {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));

        $this->namespace        = 'mailer';
        $this->name             = 'updateOpenRateCount';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [mailer|INFO] task does things.
        Call it with:
        [php symfony mailer:updateOpenRateCount|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {   
        // SET BASIC CONFIGURATION
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        
		$fetch_date = date("Y-m-d",time()-86400);
		$filepath = "/var/www/html/web/uploads/csv_files/";
		$originalFilename = $filepath."mailer_open_rate_log.txt";
		$filename = $filepath."mailer_open_rate_log_buffer.txt";
		rename($originalFilename, $filename);
        $file = fopen("$filename", "r");
        $mailerTypeArr = array();
        
        if ($file) {
            while (($line = fgets($file, 4096)) !== false) {
                $mailerType = @substr($line, 0, strpos($line, '#'));
                $mailerId = filter_var($line, FILTER_SANITIZE_NUMBER_INT);
                if(!empty($mailerId)){
                    if(in_array($mailerId,array_keys($mailerTypeArr))){
                        $mailerTypeArr[$mailerId]++;
                    } else {
                        $mailerTypeArr[$mailerId] = 1;
                    }
                }
            }
            if (!feof($file)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($file);

            // Remove Null Entries
            $mailerTypeArr = array_filter($mailerTypeArr);

            foreach($mailerTypeArr as $key=>$val){
                $jsMailerCountObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
                $jsMailerCountObj->updateMailerOpenRateCount($key, $val);
            }
        }

    }
}
