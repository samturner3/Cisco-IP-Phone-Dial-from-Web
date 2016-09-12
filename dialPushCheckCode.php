<?php

session_start();


//Get entered code from form

$enteredCode = $_POST["enteredCode"];

//echo ($_POST["enteredCode"]);

//Get other info from session.

$code = $_SESSION['code'];
$ipv4 = $_SESSION['phoneip'];
$number2dial = $_SESSION['number2dial'];

//echo $code;
//echo $enteredCode;

$command='<CiscoIPPhoneExecute><ExecuteItem Priority="1" URL="Dial:'.$number2dial.'"/></CiscoIPPhoneExecute>';


//Compare entered code and session code

if ($enteredCode == $code){
		echo '<h3>Code Correct.</h3>';
		echo "<br>";
		echo '<h3>Dialling '.$number2dial.'... </h3>';
		echo "<br>";
	    echo "<a href='http://192.168.1.7/ciscoServices/dialFromWeb/webDial.html'>New Call</a>";
		
		$response=cisco_voip_phone_xml_push($ipv4,$command);

	}
	
	else {
		echo 'Code wrong!';
		echo "<br>";
	    echo "<a href='http://192.168.1.7/ciscoServices/dialFromWeb/webDial.html'>New Call</a>";
	}
	
	
	function cisco_voip_phone_xml_push($ipv4,$command,$uid="root",$pwd="test2")
  {
     $auth=base64_encode($uid.":".$pwd);
     $xml="XML=".urlencode($command);

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
	
	?>
