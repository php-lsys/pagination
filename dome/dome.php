<?php
use LSYS\Pagination;

include __DIR__."/Bootstarp.php";

//当不传入渲染对象时,默认使用 SimpleHtml 渲染
// SimpleHtml 渲染结果为 html 代码
//自定义地址,如列表加条件等
$sh=new LSYS\Pagination\Render\SimpleHtml(\LSYS\Config\DI::get()->config("pagination.simple_html"));
$sh->setTplUrl('./dome.php?age=bbb');
$page =new Pagination($sh);

//设置总结果数量
$page->setTotal(1000);

//当渲染无法解析来源页码时,手动设置页码
//默认 SimpleHtml 渲染将根据渲染规则解析当前页码的页码,无需设置
// $page=1;
// $page->setPage($page);


//判断当前页码设置是否有效
//非必须
if(!$page->validPage()){
	die("当前页码无效");
}

//获取可能会用到的分页数据

//当前页码
$now_page=$page->getPage();

//偏移位置,一般DB查询会用到
$offset=$page->getOffset();

//总页码
$total_page=$page->getTotalPage();

//每页数量
$limit=$page->getLimit();

//总结果数量
$total=$page->getTotal();

//渲染分页
echo $page;

