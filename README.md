# 分页
> 对分页生成的统一封装

使用示例:
```
//当不传入渲染对象时,默认使用 SimpleHtml 渲染
// SimpleHtml 渲染结果为 html 代码
/*
//自定义地址,如列表加条件等
$sh=new LSYS\Pagination\Render\SimpleHtml();
$sh->set_tpl_url('./page.php?age=bbb');
*/
$page = Pagination::factory();

//设置总结果数量
$page->set_total(1000);

//当渲染无法解析来源页码时,手动设置页码
//默认 SimpleHtml 渲染将根据渲染规则解析当前页码的页码,无需设置
// $page=1;
// $page->set_page($page);


//判断当前页码设置是否有效
//非必须
if(!$page->valid_page()){
	die("当前页码无效");
}

//获取可能会用到的分页数据

//当前页码
$now_page=$page->get_page();

//偏移位置,一般DB查询会用到
$offset=$page->get_offset();

//总页码
$total_page=$page->get_total_page();

//每页数量
$limit=$page->get_limit();

//总结果数量
$total=$page->get_total();

//渲染分页
echo $page;
```

> 搭配ORM使用 ,非常方便从数据库得到数据 示例 :dome/db.php