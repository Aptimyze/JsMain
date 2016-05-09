<?php
	class APIlityAuthentication {
		// class variables
		var $clientEmail;
		var $email;
		var $password;
		var $developerToken;
		var $userAgent;
		var $applicationToken;

		// constructor
		function APIlityAuthentication(
		  $email,
		  $password,
		  $developerToken,
		  $clientEmail,
		  $applicationToken
		) {
			$this->clientEmail = $clientEmail;
			$this->email = $email;
			$this->password = $password;
			if (!USE_SANDBOX) {
				$this->developerToken = $developerToken;
			}
			else {
				$this->developerToken = $email."++".CURRENCY_FOR_SANDBOX;
			}
			// hard-wire the user agent
			$this->userAgent = "Google APIlity PHP Library for AdWords";

			$this->applicationToken = $applicationToken;

			// set the headers upon authentication context creation if soapclients
			// already exist
			global $soapClients;
			if (isset($soapClients)) $soapClients->setSoapHeaders($this);
		}

		// get functions
		function getClientEmail() {
			return $this->clientEmail;
		}

		function getEmail() {
			return $this->email;
		}

		function getPassword() {
			return $this->password;
		}

		function getDeveloperToken() {
			if (!USE_SANDBOX) {
				return $this->developerToken;
			}
			else {
				return ($this->email)."++".CURRENCY_FOR_SANDBOX;
			}
		}

		function getApplicationToken() {
			return $this->applicationToken;
		}

		function getUserAgent() {
			return $this->userAgent;
		}

		// this will return a valid header for soap clients
		function getHeader() {
			return "<email>".$this->getEmail()."</email>
							<password>".$this->getPassword()."</password>
							<useragent>".$this->getUserAgent()."</useragent>
							<developerToken>".$this->getDeveloperToken()."</developerToken>
							<clientEmail>".$this->getClientEmail()."</clientEmail>
							<applicationToken>".$this->getApplicationToken()."</applicationToken>";
		}

		// set functions
		function setClientEmail($newClientEmail) {
			$this->clientEmail = $newClientEmail;
			global $soapClients;
			$soapClients->setSoapHeaders($this);
		}

		function setEmail($newEmail) {
			$this->email = $newEmail;
			global $soapClients;
			$soapClients->setSoapHeaders($this);
		}

		function setPassword($newPassword) {
			$this->password = $newPassword;
			global $soapClients;
			$soapClients->setSoapHeaders($this);
		}

		function setApplicationToken($applicationToken) {
			$this->applicationToken = $applicationToken;
			global $soapClients;
			$soapClients->setSoapHeaders($this);
		}

		function setDeveloperToken($newDeveloperToken) {
			if (!USE_SANDBOX) {
				$this->developerToken = $newDeveloperToken;
			}
			else {
				$this->developerToken = $this->getEmail()."++".CURRENCY_FOR_SANDBOX;
			}

			global $soapClients;
			$soapClients->setSoapHeaders($this);
		}
	}
?>