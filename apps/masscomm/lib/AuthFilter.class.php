<?php
/**
* This classs will be called automatically before all function.
* Currenly its check for authentication of user.
* Notes : changes need to be done at apps/masscomm/config/filters.yml.
* @author : lavesh
*/
class AuthFilter extends sfFilter
{
        public function execute ($filterChain)
        {
		$context = $this->getContext();
		$request = $context->getRequest();
		if(sfConfig::get('mod_'.strtolower($request->getParameter('module')).'_'.$request->getParameter('action').'_enable_login')!=='off')
		{
			if($this->isFirstCall())
			{
				$auth = new MmmAuthentication;
				$login = $auth->authenticate();
				if(!$login)
				{
					/* mmm is build on 2 i-frames */
					$loginAction = JsConstants::$ser2Url."/masscomm.php/mmm/login";
					echo '<script language="javascript">'; 
				        echo "top.location.href = '$loginAction';"; 
				        echo '</script>'; 
					exit;
				}
			}
		}
		$filterChain->execute();
	}
}
