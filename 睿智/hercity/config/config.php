<?php
header("Content-type:text/html;charset=gbk");

//smarty所在目录
$config['smartyPath'] = dirname(__FILE__)."/../adminbox/libraries/smarty/";

//网站信息
$config['siteName']="倾城网";

//数据库配置
$config['dbHost'] = 'localhost';			// 数据库服务器
$config['dbUser'] = 'hercity_www';			// 数据库用户名
$config['dbName'] = 'hercity_www';			// 数据库名
$config['dbPwd'] = 'WrS8tAz8Na';				// 数据库密码

//文章与评论默认是否发布
$config['postDefaultStatus']=1;
$config['commentDefaultStatus']=1;

//文章摘要限制长度
$config['postBriefMaxLen'] = 255;

//启用自动更新keywords功能
$config['autoUpdateKeywords']=1;

//评论中不允许出现的敏感词
$config['specialWords']="www.,操你妈,日你妈,法轮功,中宣部是中国社会的艾滋病,刘晓竹,讨伐中宣部,六四参加者回忆录,鲍彤,何加栋,丁加班,梓霖,生者与死者,蒋彦勇,天安门母亲,天安门母亲运动,丁子霖,学生爱国运动正名,八九风波,天安门惨案,六四事件,八九事件,六四正名,蒋彦永,六四真相,全球公审江泽民,公审江泽民,六四死难者,八九学潮,<iframe,<script";

//文章页模板与列表页模板。至少要设置1个模板,且第一个是默认模板.
$config['showTemplates'] = array('detail.html','detail_photo.html','detail_shop.html','detail_media.html','detail_clean.html','detail_tbk.html');
$config['listTemplates'] = array('list.html','list_photo.html','list_goods.html','list_shop.html','list_cover.html');

//文章作者与来源
$config['postAuthors'] = array('良久','木子李','胭脂','儿歌','快乐小姐','雨薇','夏陌','非墨','若曦');
$config['postSources'] = array('倾城网','倾城网原创内容','倾城会馆','搜狐女人','网易女人','新浪女性');

//友情链接分类
$config['linkCats'] = array('10000'=>'倾城移动版','10001'=>'首页-品牌','10002'=>'首页-影像','10003'=>'首页-发布会','10004'=>'首页-发布会-新款','10010'=>'首页-发布会-文字链','10005'=>'首页-人物','10006'=>'首页-品鉴','10007'=>'首页-资料馆','10008'=>'首页-机构','101'=>'首页广告','102'=>'内容页广告','103'=>'列表页广告','1'=>'首页合作媒体','2'=>'首页友情链接','17'=>'会馆广告','18'=>'会馆焦点图','99'=>'内页链接','302'=>'美人榜友情链接','401'=>'美图-明星','402'=>'美图-摄影师','403'=>'美图-造型师','404'=>'美图-其它');

//人物分类及tags
$config['personCats'] = array('1'=>'古代美女','2'=>'民国美女','3'=>'现代美女','4'=>'倾城美女','5'=>'虚构美女');

$config['personTags'][] = array('演员','歌星','歌手','名模','模特','裸模','胸模','麻豆','女主播','美女主持人','网络美女','网游美女','电竞主播');
$config['personTags'][] = array('校花','空姐','美女教师','政界美女','体坛美女','商界美女','美女老板','美女作家','美女博士','职场美女','警花');
$config['personTags'][] = array('宫廷美女','名妓','才女','侠女','女诗人','巾帼英雄','古代美女','古代四大美女','民国美女','名媛','名伶','交际花','三国美女','女间谍');
$config['personTags'][] = array('文学作品中的美女','影视作品中的美女','传说中的美女','红楼梦美女','金陵十二钗','金庸笔下的美女','仙女','妖精');
$config['personTags'][] = array('世界小姐','香港小姐','中华小姐','非诚勿扰','超女','快女','寻找中国美','谋女郎','春晚美女','邵氏女星');
$config['personTags'][] = array('性感美女','气质美女','清纯美女','知性美女','中性美女','古典美女','风情美女','熟女','中国美','萌女郎','小资','小清新','嗲女','萝莉','炫富女','白富美','魔鬼身材');
$config['personTags'][] = array('大陆美女','台湾美女','香港美女','新加坡美女','韩国美女','日本美女','混血美女','海外美女');
$config['personTags'][] = array('上海美女','北京美女','成都美女','川妹子','重庆美女','西安美女','苏杭美女','大连美女','长沙美女','南京美女','广州美女');

//品牌tags
$config['brandTags'][] = array('上海','北京','天津','重庆','杭州','苏州','深圳','广州','南京','西安','成都','郑州','青岛','济南','昆明','长春','合肥','香港','台湾','海外');
$config['brandTags'][] = array('老字号','知名品牌','实体品牌','网络品牌','淘品牌');
$config['brandTags'][] = array('成衣','定制','高定');
$config['brandTags'][] = array('旗袍','唐装','礼服','嫁衣','禅茶服','秀禾服','民族风');
$config['brandTags'][] = array('传统','改良');


//以下为投入使用后不能修改的变量

//表前缀
$config['tablePre'] = 'loft_';					// 数据表名前缀

//系统目录设置
$config['baseUrl'] = "http://www.hercity.com";	//后面不加"/"
$config['basePath'] = "/";						//根目录绝对路径,后面加"/".如"/"、"/cms/"等。
$config['adminPath'] = "adminbox/";				//管理平台相对路径。后面加"/"。若更改了此设置，您需要手动重命名文件夹名称。访问路径通过站点配置文件建立虚拟目录。
$config['ucPath'] = dirname(__FILE__)."/../uc_client/";				//uc_client所在路径。基于basePath。


//附件与缩略图上传目录。若开启远程附件，则以下目录是基于$config['ftpDir']的；否则，基于$config['basePath']。
$config['attachmentPath'] = "data/upfiles/";
$config['thumbPath'] = "data/upfiles/thumb/";

//附件与缩略图限制
$config['attachmentAllowExt'] = array('.jpg','.jpeg','.gif','.png','.webp');
$config['attachmentAllowMime'] = array('image/jpeg','image/pjpeg','image/gif','image/png','image/x-png','image/webp');
$config['attachmentAllowSize'] = 1024000;
$config['attachmentThumbWidth'] = 99999;	//附件的缩略图宽度，设置一直超大的值，等于不限宽，按高度来执行缩小。
$config['attachmentThumbHeight'] = 80;		//附件的缩略图高度

$config['thumbAllowExt'] = array('*.jpg','*.jpeg','*.gif','*.png');
$config['thumbAllowMime'] = array('image/jpeg','image/pjpeg','image/gif','image/png','image/x-png');
$config['thumbAllowSize'] = 204800;

//用到缩略图的表及字段
$config['thumbField'] = array('posts'=>'thumb1,thumb2','poll_options'=>'thumb1');


//文章标题长度
$config['postTitleLen'] = 30;
$config['postSubhead1Len'] = 14;
$config['postSubhead2Len'] = 20;

//内容模型。
$config['specialModel'] = array('0'=>'普通文章','-1'=>'链接');

//推荐位
$config['arrposid'] = array('A'=>'首页头条','B'=>'首页推荐','C'=>'频道推荐','D'=>'栏目推荐');

//模块权限
$config['rights'] = array('admin'=>'5','attachments'=>'2','comments'=>'2','crons'=>'3','index'=>'1','kinds'=>'4','poll'=>'3','posts'=>'2','links'=>'2','system'=>'5','users'=>'4','file'=>'3','keywords'=>'2','persons'=>'2','brands'=>'2','plugins'=>'3');




//生成静态

//是否启用自动生成html，若启用，会在每天0点以后第一个人访问时激活更新引擎。
$config['autoWriteHtml'] = 0;

//动态show页与list页路径，以$config['basePath']路径为基准
$config['phpShowPath'] = "engine/show.php";
$config['phpListPath'] = "engine/list.php";

//静态show页与list页和频道页存放路径，以$config['basePath']路径为基准，留空则生成到$config['basePath']所指定的目录下。
$config['htmlShowPath'] = "s/";      //可以加%s，代表文章栏目路径。如："s/%s/"
$config['htmlListPath'] = "s/";
$config['htmlChannelPath'] = "s/";

//show页与list页文件名规则，必须包含“%d”，show的%d代码文章id，list的%d代表页码。
$config['htmlShowFileName'] = "%d.html";
$config['htmlListFileName'] = "%d.html";

//show页与list页每批写入的文件数
$config['htmlShowCount'] = 20;
$config['htmlListCount'] = 10;

//其它要生成静态文件的单页面
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

//时差设置
date_default_timezone_set ("Asia/Shanghai");
?>