<?php
header("Content-type:text/html;charset=gbk");

//smarty����Ŀ¼
$config['smartyPath'] = dirname(__FILE__)."/../adminbox/libraries/smarty/";

//��վ��Ϣ
$config['siteName']="�����";

//���ݿ�����
$config['dbHost'] = 'localhost';			// ���ݿ������
$config['dbUser'] = 'hercity_www';			// ���ݿ��û���
$config['dbName'] = 'hercity_www';			// ���ݿ���
$config['dbPwd'] = 'WrS8tAz8Na';				// ���ݿ�����

//����������Ĭ���Ƿ񷢲�
$config['postDefaultStatus']=1;
$config['commentDefaultStatus']=1;

//����ժҪ���Ƴ���
$config['postBriefMaxLen'] = 255;

//�����Զ�����keywords����
$config['autoUpdateKeywords']=1;

//�����в�������ֵ����д�
$config['specialWords']="www.,������,������,���ֹ�,���������й����İ��̲�,������,�ַ�������,���Ĳμ��߻���¼,��ͮ,�μӶ�,���Ӱ�,����,����������,������,�찲��ĸ��,�찲��ĸ���˶�,������,ѧ�������˶�����,�˾ŷ粨,�찲�ŲҰ�,�����¼�,�˾��¼�,��������,������,��������,ȫ��������,��������,����������,�˾�ѧ��,<iframe,<script";

//����ҳģ�����б�ҳģ�塣����Ҫ����1��ģ��,�ҵ�һ����Ĭ��ģ��.
$config['showTemplates'] = array('detail.html','detail_photo.html','detail_shop.html','detail_media.html','detail_clean.html','detail_tbk.html');
$config['listTemplates'] = array('list.html','list_photo.html','list_goods.html','list_shop.html','list_cover.html');

//������������Դ
$config['postAuthors'] = array('����','ľ����','��֬','����','����С��','��ޱ','��İ','��ī','����');
$config['postSources'] = array('�����','�����ԭ������','��ǻ��','�Ѻ�Ů��','����Ů��','����Ů��');

//�������ӷ���
$config['linkCats'] = array('10000'=>'����ƶ���','10001'=>'��ҳ-Ʒ��','10002'=>'��ҳ-Ӱ��','10003'=>'��ҳ-������','10004'=>'��ҳ-������-�¿�','10010'=>'��ҳ-������-������','10005'=>'��ҳ-����','10006'=>'��ҳ-Ʒ��','10007'=>'��ҳ-���Ϲ�','10008'=>'��ҳ-����','101'=>'��ҳ���','102'=>'����ҳ���','103'=>'�б�ҳ���','1'=>'��ҳ����ý��','2'=>'��ҳ��������','17'=>'��ݹ��','18'=>'��ݽ���ͼ','99'=>'��ҳ����','302'=>'���˰���������','401'=>'��ͼ-����','402'=>'��ͼ-��Ӱʦ','403'=>'��ͼ-����ʦ','404'=>'��ͼ-����');

//������༰tags
$config['personCats'] = array('1'=>'�Ŵ���Ů','2'=>'�����Ů','3'=>'�ִ���Ů','4'=>'�����Ů','5'=>'�鹹��Ů');

$config['personTags'][] = array('��Ա','����','����','��ģ','ģ��','��ģ','��ģ','�鶹','Ů����','��Ů������','������Ů','������Ů','�羺����');
$config['personTags'][] = array('У��','�ս�','��Ů��ʦ','������Ů','��̳��Ů','�̽���Ů','��Ů�ϰ�','��Ů����','��Ů��ʿ','ְ����Ů','����');
$config['personTags'][] = array('��͢��Ů','����','��Ů','��Ů','Ůʫ��','����Ӣ��','�Ŵ���Ů','�Ŵ��Ĵ���Ů','�����Ů','����','����','���ʻ�','������Ů','Ů���');
$config['personTags'][] = array('��ѧ��Ʒ�е���Ů','Ӱ����Ʒ�е���Ů','��˵�е���Ů','��¥����Ů','����ʮ����','��ӹ���µ���Ů','��Ů','����');
$config['personTags'][] = array('����С��','���С��','�л�С��','�ǳ�����','��Ů','��Ů','Ѱ���й���','ıŮ��','������Ů','����Ů��');
$config['personTags'][] = array('�Ը���Ů','������Ů','�崿��Ů','֪����Ů','������Ů','�ŵ���Ů','������Ů','��Ů','�й���','��Ů��','С��','С����','��Ů','����','�Ÿ�Ů','�׸���','ħ�����');
$config['personTags'][] = array('��½��Ů','̨����Ů','�����Ů','�¼�����Ů','������Ů','�ձ���Ů','��Ѫ��Ů','������Ů');
$config['personTags'][] = array('�Ϻ���Ů','������Ů','�ɶ���Ů','������','������Ů','������Ů','�պ���Ů','������Ů','��ɳ��Ů','�Ͼ���Ů','������Ů');

//Ʒ��tags
$config['brandTags'][] = array('�Ϻ�','����','���','����','����','����','����','����','�Ͼ�','����','�ɶ�','֣��','�ൺ','����','����','����','�Ϸ�','���','̨��','����');
$config['brandTags'][] = array('���ֺ�','֪��Ʒ��','ʵ��Ʒ��','����Ʒ��','��Ʒ��');
$config['brandTags'][] = array('����','����','�߶�');
$config['brandTags'][] = array('����','��װ','���','����','�����','��̷�','�����');
$config['brandTags'][] = array('��ͳ','����');


//����ΪͶ��ʹ�ú����޸ĵı���

//��ǰ׺
$config['tablePre'] = 'loft_';					// ���ݱ���ǰ׺

//ϵͳĿ¼����
$config['baseUrl'] = "http://www.hercity.com";	//���治��"/"
$config['basePath'] = "/";						//��Ŀ¼����·��,�����"/".��"/"��"/cms/"�ȡ�
$config['adminPath'] = "adminbox/";				//����ƽ̨���·���������"/"���������˴����ã�����Ҫ�ֶ��������ļ������ơ�����·��ͨ��վ�������ļ���������Ŀ¼��
$config['ucPath'] = dirname(__FILE__)."/../uc_client/";				//uc_client����·��������basePath��


//����������ͼ�ϴ�Ŀ¼��������Զ�̸�����������Ŀ¼�ǻ���$config['ftpDir']�ģ����򣬻���$config['basePath']��
$config['attachmentPath'] = "data/upfiles/";
$config['thumbPath'] = "data/upfiles/thumb/";

//����������ͼ����
$config['attachmentAllowExt'] = array('.jpg','.jpeg','.gif','.png','.webp');
$config['attachmentAllowMime'] = array('image/jpeg','image/pjpeg','image/gif','image/png','image/x-png','image/webp');
$config['attachmentAllowSize'] = 1024000;
$config['attachmentThumbWidth'] = 99999;	//����������ͼ��ȣ�����һֱ�����ֵ�����ڲ��޿����߶���ִ����С��
$config['attachmentThumbHeight'] = 80;		//����������ͼ�߶�

$config['thumbAllowExt'] = array('*.jpg','*.jpeg','*.gif','*.png');
$config['thumbAllowMime'] = array('image/jpeg','image/pjpeg','image/gif','image/png','image/x-png');
$config['thumbAllowSize'] = 204800;

//�õ�����ͼ�ı��ֶ�
$config['thumbField'] = array('posts'=>'thumb1,thumb2','poll_options'=>'thumb1');


//���±��ⳤ��
$config['postTitleLen'] = 30;
$config['postSubhead1Len'] = 14;
$config['postSubhead2Len'] = 20;

//����ģ�͡�
$config['specialModel'] = array('0'=>'��ͨ����','-1'=>'����');

//�Ƽ�λ
$config['arrposid'] = array('A'=>'��ҳͷ��','B'=>'��ҳ�Ƽ�','C'=>'Ƶ���Ƽ�','D'=>'��Ŀ�Ƽ�');

//ģ��Ȩ��
$config['rights'] = array('admin'=>'5','attachments'=>'2','comments'=>'2','crons'=>'3','index'=>'1','kinds'=>'4','poll'=>'3','posts'=>'2','links'=>'2','system'=>'5','users'=>'4','file'=>'3','keywords'=>'2','persons'=>'2','brands'=>'2','plugins'=>'3');




//���ɾ�̬

//�Ƿ������Զ�����html�������ã�����ÿ��0���Ժ��һ���˷���ʱ����������档
$config['autoWriteHtml'] = 0;

//��̬showҳ��listҳ·������$config['basePath']·��Ϊ��׼
$config['phpShowPath'] = "engine/show.php";
$config['phpListPath'] = "engine/list.php";

//��̬showҳ��listҳ��Ƶ��ҳ���·������$config['basePath']·��Ϊ��׼�����������ɵ�$config['basePath']��ָ����Ŀ¼�¡�
$config['htmlShowPath'] = "s/";      //���Լ�%s������������Ŀ·�����磺"s/%s/"
$config['htmlListPath'] = "s/";
$config['htmlChannelPath'] = "s/";

//showҳ��listҳ�ļ������򣬱��������%d����show��%d��������id��list��%d����ҳ�롣
$config['htmlShowFileName'] = "%d.html";
$config['htmlListFileName'] = "%d.html";

//showҳ��listҳÿ��д����ļ���
$config['htmlShowCount'] = 20;
$config['htmlListCount'] = 10;

//����Ҫ���ɾ�̬�ļ��ĵ�ҳ��
$config_phpToHtml[] = array('php'=>'engine/index.php','html'=>'s/index.html');
$config_phpToHtml[] = array('php'=>'engine/static.php?file=about','html'=>'s/static/about.html');
$config_phpToHtml[] = array('php'=>'engine/static.php?file=ad','html'=>'s/static/ad.html');
$config_phpToHtml[] = array('php'=>'engine/static.php?file=weixin','html'=>'s/static/weixin.html');
$config_phpToHtml[] = array('php'=>'engine/links.php','html'=>'s/static/links.html');

$config_phpToHtml[] = array('php'=>'engine/photo_top.php?i=new','html'=>'s/photo/top/new.html');
$config_phpToHtml[] = array('php'=>'engine/photo_top.php?i=hits','html'=>'s/photo/top/hits.html');
$config_phpToHtml[] = array('php'=>'engine/photo_top.php?i=comments','html'=>'s/photo/top/comments.html');
$config_phpToHtml[] = array('php'=>'engine/top100.php?i=new','html'=>'s/top/new.html');
$config_phpToHtml[] = array('php'=>'engine/top100.php?i=hits','html'=>'s/top/hits.html');
$config_phpToHtml[] = array('php'=>'engine/top100.php?i=comments','html'=>'s/top/comments.html');
$config_phpToHtml[] = array('php'=>'engine/rss.php','html'=>'s/rss/index.xml');
$config_phpToHtml[] = array('php'=>'engine/rss.php?c=club','html'=>'s/rss/club.xml');
$config_phpToHtml[] = array('php'=>'engine/js_abox.php','html'=>'s/abox.js');

//ʱ������
date_default_timezone_set ("Asia/Shanghai");
?>