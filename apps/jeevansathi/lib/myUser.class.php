<?php

class myUser extends sfBasicSecurityUser
{
/*	public function signIn(){
		$this->setAuthenticated(true);
		sfContext::getInstance()->getResponse()->setCookie('tanuCookie', "tanu", time()+60*60*24*15, '/');
		$this->setAttribute("username","tanu");   
	}

	public function signOut(){
		    $this->setAuthenticated(false);
		    sfContext::getInstance()->getResponse()->setCookie('tanuCookie', "", time() - 3600, '/');
	}
*/
}
