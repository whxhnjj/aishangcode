<?php
require_once("../includes/interface.php");

$id = _GET('id','i');
$sql = "update ".$config['tablePre']."posts set hits=hits+1 where id=".$id;
$mysqli->query($sql);

$sql = "select linkto from ".$config['tablePre']."posts where id=".$id;
if ($result = $mysqli->query($sql))
{
list($linkto) = $result->fetch_row();
if ($linkto == '') $linkto = "/404.html";

?>
<script type="text/javascript">
window.location.href = "<?=$linkto?>";
</script>
<?php
}
?>