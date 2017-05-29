<?php
class MoveFiles
{
	private $ftpConnection;
	private $defaultUsername;
	private $defaultPassword;
	private $defaultHost;
	private $getCase;

	public function getGetCase()
	{
		return $this->getCase;
	}
	public function __construct($getImage="")
	{
		$this->defaultUsername="";
		$this->defaultPassword="";
		$this->defaultHost="";
		if($getImage)
			$this->getCase=1;
	}

	public function makeFTPConnection($username='',$password='',$hostname='')
	{
		if($username=='' || $password=='' || $hostname=='')
			$this->ftp = array('username'=>$this->defaultUsername, 'password'=>$this->defaultPassword, 'hostname'=>$this->defaultHost);
		else
			$this->ftp = array('username'=>$username, 'password'=>$password, 'hostname'=>$hostname);

		/*
		$this->ftpConnection = ftp_connect($this->ftp['hostname']) or die('could not connect');
		ftp_login($this->ftpConnection, $this->ftp['username'], $this->ftp['password']);
		*/
		$this->ftpConnection = ssh2_connect($this->ftp['hostname'],2525) or die('could not connect');
		if(ssh2_auth_password($this->ftpConnection,$this->ftp['username'],$this->ftp['password']))
			;
		else
			die('could not connect');
	}

	public function makeDir($path)
	{
		/*		
		if (@ftp_chdir($this->ftpConnection, $path))
		{
		}
		else
		{
			
			ftp_mkdir($this->ftpConnection,$path);
			ftp_chmod($this->ftpConnection, 0777, $path);
			
		}
		*/
			$sftp = ssh2_sftp($this->ftpConnection);
			ssh2_sftp_mkdir($sftp,$path,0777);
	}

	public function copyFiles($localFileName,$remoteFileName)
	{
		/*
		$output = ftp_put($this->ftpConnection, $remoteFileName, $localFileName, FTP_BINARY);
		return $output;
		*/
		return ssh2_scp_send($this->ftpConnection,$localFileName,$remoteFileName,0777);//0644
	}

	public function getFiles($localFileName,$remoteFileName)
	{
		/*
		ftp_get($this->ftpConnection, $localFileName, $remoteFileName, FTP_BINARY);
		*/
		ssh2_scp_recv($this->ftpConnection,$remoteFileName,$localFileName);
	}
}
?>
