<?php
/**
 * lsys pagination
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
return array(
	'simple_html'=>array(
		'tpls'=>__DIR__."/../tpls/simple_html.php",
		//模式 get 为$_GET 变量传递页码 rule 为自定义传递页码
		'mode'=>"get", 
		//mode 为 get 设置
		'key'=>'page',//页码传递变量键名
		//mode 为 rule 设置 
		// 如重写地址 /cat/1/page/1/ 分解为: before 为 /page/ after 为/ 
		'before'=>'?P', //页码开始前字符
		'after'=>'_', //页码结束后字符
	),
	'link'=>array(
		//模式 get 为$_GET 变量传递页码 rule 为自定义传递页码
		'mode'=>"get",
		//mode 为 get 设置
		'key'=>'page',//页码传递变量键名
		//mode 为 rule 设置
		// 如重写地址 /cat/1/page/1/ 分解为: before 为 /page/ after 为/
		'before'=>'?P', //页码开始前字符
		'after'=>'_', //页码结束后字符
	)
);