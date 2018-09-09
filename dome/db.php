<?php
use LSYS\Pagination;
use LSYS\Pagination\Render\SimpleHtml;
use LSYS\ORM\Active;
include __DIR__."/Bootstarp.php";
//配合ORM使用分页
// ORM 实现 参考 lorm
// 运行此示例前先加载 lorm
$sh=new SimpleHtml(\LSYS\Config\DI::get()->config("pagination.simple_html"));
$sh->set_tpl_url('./page.php?age=bbb');//自定义地址
$page = new Pagination($sh);

$table = new Active("order");

//添加过滤条件等等...

$total=$table->count_all();
$page->set_total($total);

//输出分页数据
//默认只有一页时隐藏分页
echo $page;

if($page->valid_page()){
	//乱传页面不取数据...
	$res=$table->offset($page->get_offset())
	->limit($page->get_limit())
	->find_all();
	echo "<pre>";
	print_r($res->as_array());
	echo "</pre>";

}

//输出分页数据
echo $page;

