<?php
require_once("includes/interface.php");
require_once("includes/smarty.php");

//ȷ����ҳ����Ҫ����Ԫ�أ���ǰ����ҳ��ÿҳ��¼�����ܼ�¼����
$page = _GET('page',"i",1);
$pagesize = 200;

list($recordcount) = $mysqli->query("select count(id) as count from ".$config['tablePre']."keywords ")->fetch_row();
$page_max_no = $recordcount - ($pagesize * ($page - 1));

//��Ŀ�����б�
$list = getArrayFromKeywords("order by hits desc limit ".($page-1)*$pagesize.",".$pagesize);

//���÷�ҳ����
$pages = pages($recordcount,$pagesize,$page,"%d.html");

//�Ҳ�����
$list_hot = getArrayFromPosts("where is_show and dateline<=UNIX_TIMESTAMP() and dateline>".(time()-2592000)." order by hits desc limit 5");

$smarty->assign("list",$list);
$smarty->assign("pages",$pages);
$smarty->assign("list_hot",$list_hot);
$smarty->assign("page_max_no",$page_max_no);
$smarty->display("tags.html");

$mysqli->close();
?>