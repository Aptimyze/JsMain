<?php

/**
 *
 */
class ExclusiveProposalMatchMailerTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name', 'jeevansathi'),
        ));

        $this->namespace = 'mailer';
        $this->name = 'ExclusiveProposalMailerTask';
        $this->briefDescription = 'Proposal Mail - JS Exclusive';
        $this->detailedDescription = <<<EOF
		The [ExclusiveProposalMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:ExclusiveProposalMailerTask|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()){
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $exclusiveObj = new ExclusiveFunctions();
        $result = $exclusiveObj->getReceiverAndAgentDetailsforProposalMail();
        if(is_array($result) && !empty($result))
            $exclusiveObj->sendProposalMail($result);
    }

}
