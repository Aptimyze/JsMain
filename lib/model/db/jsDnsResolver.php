<?php
class jsDnsResolver
{
  public function resolveDNS($dsn, $pdoDb = true)
  {
    $pattern = "/\{([^\}]*)\}/";
    preg_match_all($pattern, $dsn, $matches);
    if(count($matches) > 0)
    {
      foreach($matches[1] as $match)
      {
        $resolvedAddr = $this->getIpAddress("_db._tcp.".$match.".", $pdoDb);
        $dsn = preg_replace("/\{".$match."\}/", $resolvedAddr, $dsn);
      } 
    }
    return $dsn;
  }

  private function getIpAddress($match, $pdoDb = true)
  {
    $srvRecords = $this->getDnsRecord($match, "SRV");
    if(count($srvRecords) > 0)
    {
      $host = $srvRecords[0]["target"];
      $port = $srvRecords[0]["port"];

      $hostIps = $this->getDnsRecord($host, "A");
  
      if(count($hostIps) > 0)
      {
        if($port)
        {
          if($pdoDb)
            return $hostIps[0]["ip"].";port=".$port;
          else
            return $hostIps[0]["ip"].":".$port;
        }
        else
          return $hostIps[0]["ip"];
      }
      else
        throw new jsDatabaseException("Not able to find dns record for ". $host);
    
      return "";
    }
    else
      throw new jsDatabaseException("Not able to find dns record for ". $match); 
    
    return "";

  }

  private function getDnsRecord($domain, $recordType = DNS_ANY)
  {
    switch($recordType)
    {
      case "SRV":
        $type = DNS_SRV;
        break;
      case "A":
        $type = DNS_A;
        break;
      default:
        $type = DNS_ANY;
        break;
    }
    return dns_get_record($domain, $type);
  }
}
