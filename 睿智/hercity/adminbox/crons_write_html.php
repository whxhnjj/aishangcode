<?php
require_once("includes/config.php");
require_once("includes/rights.php");
require_once("includes/function.php");
require_once("includes/conn.php");
require_once("includes/header.php");

$writePhpToHtml = writePhpToHtml();
echo $writePhpToHtml;
echo "<a href=\"crons_write_index.php\">·µ»Ø</a>";

require_once("includes/footer.php");
?>