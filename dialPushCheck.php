<?php

session_start();

// Report runtime errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Report all errors
error_reporting(E_ALL);

session_start();

$ipv4=$_POST["phoneip"];
$number2dial=$_POST["number"];

$_SESSION['phoneip'] = $ipv4;
$_SESSION['number2dial'] = $number2dial;

//Generate random code
$code = mt_rand(1000,9999);


$_SESSION['code'] = $code;

//Create xml element


$xml = new SimpleXMLElement('<CiscoIPPhoneText/>');
	$title = $xml->addChild('Title', "Authentication Code: $code");
    $text = $xml->addChild('Text', "Enter code above into browser to dial $number2dial");
    
    //remove version tag
    $dom = dom_import_simplexml($xml);
	$xmlOut = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);

//Write to file

$myfile = fopen($ipv4."-code.php", "w") or die("Unable to open file!");
fwrite($myfile, '<?header("Content-type: text/xml");');
fwrite($myfile, 'header("Connection: close");');
fwrite($myfile, 'header("Expires: -1");?>');

fwrite($myfile, $xmlOut);
fclose($myfile);

//echo "<a href='http://192.168.1.7/ciscoServices/dialFromWeb/".$ipv4."-code.php'>file</a>";

// Push file to phone

//$url="http://192.168.1.7/ciscoServices/dialFromWeb/".$ipv4."-code.xml"
$command='<CiscoIPPhoneExecute><ExecuteItem Priority="0" URL="http://192.168.1.7/ciscoServices/dialFromWeb/'.$ipv4.'-code.php"/></CiscoIPPhoneExecute>';

//  echo "$command";
//  echo "<br>";
//	echo "Command sent to phone.";
//	echo "<br>";
//	echo "<a href='http://192.168.1.7/ciscoServices/dialFromWeb/webDial.html'>New Call</a>";
  $response=cisco_voip_phone_xml_push($ipv4,$command);



  // push xml-like packet to cisco voip phone...
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
     //echo $response;
  }
//echo $response;
//echo 'here';

//Check if code is set, if so display form.

if (isset($code)){
?>
<html>
<title>Phone Dial Tool</title>
<body>
<h2>Phone number dial tool</h2>
<h3>To dial <? echo $number2dial; ?>,
enter the code from the phone screen</h3>
<form action="dialPushCheckCode.php" method="post">
Code: <input type="number" name="enteredCode" required><br>
<br>
<input type="submit">
</form>
<h5>Sam Turner 28/5/16</h5>
</body>
</html>
<?php
}
else{ ?>
<html>
<h1>Error</h1>
</html>
<?php
}
return;
?>