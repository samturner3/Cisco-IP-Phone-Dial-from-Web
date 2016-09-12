<?php

// Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Report all errors
error_reporting(E_ALL);

  // the cisco phone has ip address 192.168.0.129
  // and we are gone tell that phone to dial extension 301.
  // since the priority is 0, it will dial this number even if
  // there is a call in progress..
	

  $number2dial=$_POST["number"];
  $data='<CiscoIPPhoneExecute><ExecuteItem Priority="1" URL="Dial:'.$number2dial.'"/></CiscoIPPhoneExecute>';
  $ipv4=$_POST["phoneip"];
  
  //echo 'Data= ' . $data . '.';
  
  echo $data;
	echo "Command sent to phone.";
	echo "<br>";
	echo "<a href='http://192.168.1.7/ciscoServices/dialFromWeb/webDial.html'>New Call</a>";
  $response=cisco_voip_phone_xml_push($ipv4,$data);
  return;


  // push xml-like packet to cisco voip phone...
  function cisco_voip_phone_xml_push($ipv4,$data,$uid="root",$pwd="test2")
  {
     $auth=base64_encode($uid.":".$pwd);
     $xml="XML=".urlencode($data);

     $post="POST /CGI/Execute HTTP/1.0\r\n";
     $post.="Host: $ipv4\r\n";
     $post.="Authorization: Basic $auth\r\n";
     $post.="Connection: close\r\n";
     $post.="Content-Type: application/x-www-form-urlencoded\r\n";
     $post.="Content-Length: ".strlen($xml)."\r\n\r\n";

     $response="";
     $fp=fsockopen($ipv4,80,$errno,$errstr,30);
     if(!$fp) return false;

     fputs($fp,$post.$xml);
     while(!feof($fp))
     {
        $response=fgets($fp,128);
     }
     fclose($fp);
     return $response;
     echo $response;
  }
echo $response;

?>
