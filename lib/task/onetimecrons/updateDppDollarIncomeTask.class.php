<?php

class updateDppDollarIncomeTask extends sfBaseTask {

        protected function configure() {
                $this->addOptions(array(
                    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
                ));

                $this->namespace = 'oneTimeCron';
                $this->name = 'updateDppDollarIncome';
                $this->briefDescription = 'cron to update dpp income';
                $this->detailedDescription = <<<EOF
		Call it with:

		[php symfony oneTimeCron:updateDppDollarIncome]
EOF;
        }

        protected function execute($arguments = array(), $options = array()) {
                if (!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

                ini_set('memory_limit', '512M');
                $limit = 2000;
                $offset = 0;
                $incrementValue = 2000;
                $profileIdStr = "";
                $lIncome = "12";
                for ($activeServerId = 0; $activeServerId <= 2; $activeServerId++) {
                        $shardSlave = JsDbSharding::getShardDbName($activeServerId, 1);
                        $shardMaster = JsDbSharding::getShardDbName($activeServerId);
                        $jpartnerSlaveObj = new newjs_JPARTNER($shardSlave);
                        $jpartnerMasterObj = new newjs_JPARTNER($shardMaster);
                        while (1) {
                                $profileData = $jpartnerSlaveObj->getDppDataForProfiles($limit, $offset,array("LINCOME"=>array("op"=>"!=","val"=>0),"LINCOME_DOL"=>array("op"=>"=","val"=>0)));
                                if (count($profileData) == 0) {
                                        break;
                                }
                                foreach ($profileData as $key => $value) {
                                        $rArr["minIR"] = $value["LINCOME"];
                                        $rArr["maxIR"] = $value["HINCOME"];
                                        $dArr["minID"] = $lIncome;
                                        $dArr["maxID"] = $value['HINCOME_DOL'];
                                        $incomeMapObj = new IncomeMapping($rArr, $dArr);
                                        $incomeMapArr = $incomeMapObj->incomeMapping();
                                        $Income = $incomeMapArr['istr'];
                                        $jpartnerMasterObj->updateIncomeDollarValueForProfile($value["PROFILEID"], $lIncomeDol, $Income, $value["LINCOME_DOL"]);
                                        unset($incomeMapObj);
                                }
                                $offset +=$incrementValue;
                                unset($profileData);
                        }
                        $offset = 0;
                        unset($jpartnerSlaveObj);
                        unset($jpartnerMasterObj);
                }
        }

}
