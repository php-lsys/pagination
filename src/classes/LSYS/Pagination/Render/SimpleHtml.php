<?php
/**
 * lsys pagination
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
/**
 * HTML 代码分页渲染
 */
namespace LSYS\Pagination\Render;
use LSYS\Pagination\Render;
use LSYS\Config;
use LSYS\Pagination;
class SimpleHtml implements Render{
	// Merged configuration settings
	protected $_vars = array(
		'count_out'    			=> 2,
		'count_in'    			=> 2,
		'auto_hide'         	=> TRUE,
	);
	protected $_tpls;
	/**
	 * @var Config
	 */
	protected $_config;
	protected $_tpl_url;
	protected $_current_page=false;
	/**
	 * @var Pagination
	 */
	protected $_page;
	public function __construct(\LSYS\Config $config){
		$this->_tpls=$config->get("tpls",__DIR__."/../../../../tpls/simple_html.php");
		$this->_config=$config;
	}
	public function setTplUrl($url){
		$this->_tpl_url=$url;
		return $this;
	}
	/**
	 * 设置链接模板
	 * @param string $url
	 * @return \LSYS\Pagination\Render\SimpleHtml
	 */
	protected function _initTplUrl(){
		$this->_get_url=null;
		$this->_rule_page=null;
		switch ($this->_config->get("mode")){
			case 'get':
				$key=$this->_config->get("key","page");
				if (isset($_GET[$key])&&$_GET[$key]>0){
					$this->_page->setPage(intval($_GET[$key]));
				}
				break;
			case 'rule':
				$before=$this->_config->get("before");
				$pos=strpos($this->_tpl_url, $before);
				$len=strlen($before);
				if($pos!==false&&$len>0){
					$page=intval(substr($this->_tpl_url, $pos+strlen($before)));
					if ($page>0)$this->_page->setPage($page);
				}
				break;
		}
		return $this;
	}
	/**
	 * 获得分页链接地址
	 * @param string $page
	 * @return string
	 */
	protected function _url($page){
		switch ($this->_config->get("mode")){
			case 'get': return $this->_modelGet($page);
			case 'rule': return $this->_modelRule($page);
		}
		return $page;
	}
	//规则模式
	private $_rule_page;
	private function _modelRule($page){
		static $before,$after,$before_len,$after_len,$before_pos,$after_pos;
		if ($this->_rule_page===null){
			$before=$this->_config->get("before");
			$after=$this->_config->get("after");
			$before_len=strlen($before);
			if ($before===null||$before_len==0)$before_pos=false;
			else{
				$before_pos=strpos($this->_tpl_url, $before);
				if($before_pos!==false)$before_pos+=$before_len;
			}
			$after_len=strlen($after);
			if ($after_len>0){
				$after_pos=strpos($this->_tpl_url, $after,$before_pos);
			}else $after_pos=false;
			$this->_rule_page=intval(substr($this->_tpl_url, $before_pos));
		}
		if ($before_pos===false){
			return $this->_tpl_url.$before.$page.$after;
		}else{
			if ($after_len>0&&$after_pos!==false){
				return substr_replace($this->_tpl_url,$page,$before_pos,$after_pos-$before_pos);
			}
			if ($this->_rule_page>0){
				return str_replace($this->_rule_page, $page,$this->_tpl_url);
			}else{
				if ($before_pos>0){
					return substr_replace($this->_tpl_url,$page.$after,$before_pos,0);
				}
				return $this->_tpl_url.$before.$page.$after;
			}
		}
	}
	//$_GET 模式
	private $_get_url=null;
	private function _modelGet($page){
		$key=$this->_config->get("key","page");
		if ($this->_get_url===null){
			$url=$this->_tpl_url;
			$url=preg_replace("/([\?&]){$key}=\d+[&]?/","$1",$url);
			$url=trim($url,'&');
			if(strpos($url, "?")===false)$url.='?';
			else{
			    if(substr($url, -1,1)!='?')$url.='&';
			}
			$this->_get_url=$url;
		}
		return $this->_get_url.$key."=".$page;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Pagination\Render::setPagination()
	 */
	public function setPagination(Pagination $page){
		$this->_page=$page;
		if ($this->_tpl_url===null&&isset($_SERVER['REQUEST_URI'])){
			$this->setTplUrl($_SERVER['REQUEST_URI']);
		}
		if ($this->_tpl_url!==null)$this->_initTplUrl();
		return $this;
	}
	/**
	 * {@inheritDoc}
	 * @see \LSYS\Pagination\Render::render()
	 */
	public function render()
	{
		extract($this->_vars);
		$page=$this->_page;
		if ($page->getTotalPage()<=1&&$auto_hide) return '';
		ob_start();
		require $this->_tpls;
		$data=ob_get_contents();
		ob_end_clean();
		return $data;
	}
}