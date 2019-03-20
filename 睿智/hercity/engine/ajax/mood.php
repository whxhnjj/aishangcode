<?php
require_once("../includes/interface.php");

$id = _POST('id','i');
$x = _POST('x','i',-1);
$status = -1;

session_start();
$session_tag = isset($_SESSION["postmood".$id]) ? $_SESSION["postmood".$id] : 0;

if ($x != -1)
{
	$x = "x".$x;
	//¸ÕÍ¶¹ýÆ±
	if (time() - $session_tag < 5)
	{
		$status = 9999;
	}
	else
	{
		$_SESSION["postmood".$id] = time();
		$sql = "update ".$config['tablePre']."mood set ".$x."=".$x."+1,lastupdate=UNIX_TIMESTAMP() where post_id=".$id;
		$mysqli->query($sql);

		if ($mysqli->affected_rows == 0)
		{
		$sql = "insert into ".$config['tablePre']."mood (post_id,".$x.",lastupdate) values (".$id.",1,UNIX_TIMESTAMP())";
		$mysqli->query($sql);
		if ($mysqli->affected_rows == 0)
			{
			$status = 0;
			}
			else
			{
			$status = 1;
			}
		}
		else
		{
		$status = 1;
		}
	}
}

$json['status'] = $status;


$sql = "select x0,x1,x2,x3,x4,x5,x6,x7,x8,x9 from ".$config['tablePre']."mood where post_id = ".$id;
$result = $mysqli->query($sql);
if ($x = $result->fetch_row())
{
	$x_max = max($x);
	for ($i = 0; $i <= 9; $i++ )
	{
	$json['x'.$i] = $x[$i];
	$json['p'.$i] = ceil(($x[$i]/$x_max)*50)+1;
	}
}

echo json_encode($json);
?>