<?php

class cronCommunityPagesTitleChangeTask extends sfBaseTask
{
    protected function configure()
    {
        $this->namespace           = 'cron';
        $this->name                = 'CommunityPagesTitleChange';
        $this->briefDescription    = 'Cron to change the TITLE entries of newjs.COMMINTITY_PAGES and newjs.COMMUNITY_PAGES_MAPPING';
        $this->detailedDescription = <<<EOF
      Cron to change the TITLE entries of newjs.COMMINTITY_PAGES and newjs.COMMUNITY_PAGES_MAPPING
      Call it with:[php symfony cron:CommunityPagesTitleChange|INFO]
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }
    
    protected function execute($arguments = array(), $options = array()){
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        
        
        $communityPagesObjMaster = new NEWJS_COMMUNITY_PAGES("newjs_masterDDL");
        $communityPagesObjSlave = new NEWJS_COMMUNITY_PAGES("newjs_slave");
        $communityPagesMappingObjMaster = new NEWJS_COMMUNITY_PAGES_MAPPING("newjs_masterDDL");
        $communityPagesMappingObjSlave = new NEWJS_COMMUNITY_PAGES_MAPPING("newjs_slave");
        
        //Create backup table before performing title changes;
        $communityPagesObjMaster->createBackupTable();
        $communityPagesMappingObjMaster->createBackupTable();
        
        //Change TITLE in newjs.COMMUNITY_PAGES
        $details = $communityPagesObjSlave->getAll();
        $this->updateData($communityPagesObjMaster, $details);
        
        //Change TITLE in newjs.COMMUNITY_PAGES_MAPPING
        $details = $communityPagesMappingObjMaster->getAll();
        $this->updateData($communityPagesMappingObjMaster, $details);
        unset($details);
    }
    
    public function updateData($masterObj, $details){
        foreach($details as $key => $val){
            $titleArr = explode("-", $val["TITLE"]);
            if(strpos($titleArr[0],"Matrimony") !==FALSE){
                unset($titleArr[0]);
                $titleStr = implode("-", $titleArr);
                while(strpos(substr($titleStr,0,1), ' ') !== FALSE){
                    $titleStr = substr($titleStr,1);
                }
                $masterObj->update($val["ID"],$titleStr);
            }
        }
    }
}

