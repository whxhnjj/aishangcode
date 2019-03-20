<?php
header("Content-type: text/html; charset=gbk"); 
$m = $_GET['m'];
$m = nl2br(htmlentities($m));

if ($m!='')
{
	$mExt = strtolower(strrchr($m , "."));
	switch ($mExt)
	{
	case ".mp4":
	case ".ogg":
	?>
	document.write('<div style="text-align:center;"><video src="<?=$m?>" controls="controls">Your browser does not support the video tag.</video></div>')
	<?
	break;
	case ".mp3":
	?>
	document.write('<div style="text-align:center;"><audio src="<?=$m?>" controls="controls" autoplay="autoplay">Your browser does not support the audio tag.</audio></div>')
	<?
	break;
	case ".flv":
	?>
	document.write('<div style="text-align:center;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="500" height="400">')
	document.write('<param name="movie" value="/assets/images/flvPlayer.swf" />')
	document.write('<param name="quality" value="high" />')
	document.write('<param name="menu" value="false" />')
	document.write('<param name="allowFullScreen" value="true" />')
	document.write('<param name="FlashVars" value="vcastr_file=<?=$m?>&vcastr_title=&BarColor=0xFF6600&BarPosition=1&LogoText=HerCity.com&IsAutoPlay=1" />')
	document.write('<embed src="/assets/images/flvPlayer.swf" allowFullScreen="true" FlashVars="vcastr_file=<?=$m?>&vcastr_title=&BarColor=0xFF6600&BarPosition=1&LogoText=HerCity.com&IsAutoPlay=1" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="500" height="400" menu="false"></embed>')
	document.write('</object></div>')
	<?
	break;
	case ".rm":
	case ".rmvb":
	case ".ra":
	case ".ram":
	?>
	document.write('<div style="text-align:center;"><object id="vid" classid= "clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" width="500" height="450">')
	document.write('<param name="_ExtentX" value="3016" />')
	document.write('<param name="_ExtentY" value="2646" />')
	document.write('<param name="AUTOSTART" value="-1" />')
	document.write('<param name="SHUFFLE" value="0" />')
	document.write('<param name="PREFETCH" value="0" />')
	document.write('<param name="NOLABELS" value="-1" />')
	document.write('<param name="SRC" value= "<?=$m?>"  />')
	document.write('<param name="CONTROLS" value="Imagewindow,controlpanel" />')
	document.write('<param name="CONSOLE" value="clip1" />')
	document.write('<param name="LOOP" value="0" />')
	document.write('<param name="NUMLOOP" value="0" />')
	document.write('<param name="CENTER" value="0" />')
	document.write('<param name="MAINTAINASPECT" value="1" />')
	document.write('<param name="BACKGROUNDCOLOR" value="#000000" />')
	document.write('</object></div>')
	<?
	break;
	case ".wmv":
	?>

	document.write('<div style="text-align:center;"><object id="NSPlay" width="500" height="450" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">')
	document.write('<param name="AutoRewind" value="1" />')
	document.write('<param name="FileName" value="<?=$m?>" />')
	document.write('<param name="ShowControls" value="1" />')
	document.write('<param name="ShowPositionControls" value="1" />')
	document.write('<param name="ShowAudioControls" value="1" />')
	document.write('<param name="ShowTracker" value="1" />')
	document.write('<param name="ShowDisplay" value="0" />')
	document.write('<param name="ShowStatusBar" value="0" />')
	document.write('<param name="ShowGotoBar" value="0" />')
	document.write('<param name="ShowCaptioning" value="0" />')
	document.write('<param name="AutoStart" value="1" />')
	document.write('<param name="Volume" value="50" />')
	document.write('<param name="AnimationAtStart" value="0" />')
	document.write('<param name="TransparentAtStart" value="0" />')
	document.write('<param name="AllowChangeDisplaySize" value="0" />')
	document.write('<param name="AllowScan" value="0" />')
	document.write('<param name="EnableContextMenu" value="0" />')
	document.write('<param name="ClickToPlay" value="0" />')
	document.write('</object></div>')
	<?
	break;
	case ".mpg":
	case ".mpeg":
	case ".avi":
	case ".asf":
	?>
	document.write('<div style="text-align:center;"><object classid="clsid:05589FA1-C356-11CE-BF01-00AA0055595A" id="ActiveMovie1" width="500" height="450">')
	document.write('<param name="Appearance" value="0" />')
	document.write('<param name="AutoStart" value="-1" />')
	document.write('<param name="AllowChangeDisplayMode" value="-1" />')
	document.write('<param name="AllowHideDisplay" value="0" />') 
	document.write('<param name="AllowHideControls" value="-1" />')
	document.write('<param name="AutoRewind" value="-1" />')
	document.write('<param name="Balance" value="0" />')
	document.write('<param name="CurrentPosition" value="0" />')
	document.write('<param name="DisplayBackColor" value="0" />')
	document.write('<param name="DisplayForeColor" value="16777215" />')
	document.write('<param name="DisplayMode" value="0" />')
	document.write('<param name="Enabled" value="-1" />')
	document.write('<param name="EnableContextMenu" value="-1" />')
	document.write('<param name="EnablePositionControls" value="-1" />')
	document.write('<param name="EnableSelectionControls" value="0" />')
	document.write('<param name="EnableTracker" value="-1" />')
	document.write('<param name="Filename" value="<?=$m?>" valuetype="ref" />')
	document.write('<param name="FullScreenMode" value="0" />')
	document.write('<param name="MovieWindowSize" value="0" />')
	document.write('<param name="PlayCount" value="1" />') 
	document.write('<param name="Rate" value="1" />') 
	document.write('<param name="SelectionStart" value="-1" />') 
	document.write('<param name="SelectionEnd" value="-1" />') 
	document.write('<param name="ShowControls" value="-1" />') 
	document.write('<param name="ShowDisplay" value="0" />') 
	document.write('<param name="ShowPositionControls" value="-1" />') 
	document.write('<param name="ShowTracker" value="-1" />') 
	document.write('<param name="Volume" value="50" />') 
	document.write('</object></div>') 
	<?
	break;
	case ".swf":
	?>
	document.write('<div style="text-align:center;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="500" height="400">')
	document.write('<param name="movie" value="<?=$m?>" />')
	document.write('<param name="quality" value="high" />')
	document.write('<param name="menu" value="false" />')
	document.write('<embed src="<?=$m?>" width="500" height="400" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" menu="false"></embed>')
	document.write('</object></div>')
	<?
	break;
	case ".rar":
	case ".zip":
	case ".doc":
	case ".jpg":
	case ".jpeg":
	case ".png":
	case ".psd":
	?>
	document.write('附件下载：<a href="<?=$m?>" class="red">点击这里开始下载</a><br />')
	<?
	break;
	default:
	echo '输入错误';	
	}
}
?>