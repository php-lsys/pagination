<?php
/**
 * lsys pagination [output]
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS;
use LSYS\Pagination\Render;
class Pagination {
	//page data
	protected $_vars=array(
		'items_per_page'=>10,//每页显示数量
		'total_items'=>0,//总结果数
		'total_pages'=>0,//总页数
		'current_page'=>1,//当前页
		'current_offset'=>0,//当前偏移
	);
	/**
	 * skip page number
	 * @var integer
	 */
	protected $_skip_page=0;
	/**
	 * @var Render
	 */
	protected $_render;
	/**
	 * Creates a new Pagination object.
	 *
	 * @param   array  configuration
	 * @return  void
	 */
	public function __construct(Render $render)
	{
		$this->_render=$render;
		$render->set_pagination($this);
	}
	/**
	 * set limit
	 * @param number $limit
	 * @return static
	 */
	public function set_limit($limit){
		$limit=intval($limit);
		if ($limit<=0)$limit=1;
		$total_page=ceil($this->_vars['total_items']/$limit);
		$this->_vars['total_pages']=$total_page;
		$this->_vars['items_per_page']=$limit;
		return $this;
	}
	/**
	 * set item total
	 * @param number $total
	 * @return static
	 */
	public function set_total($total){
		$total=intval(strval($total));
		if ($total<=0)$total=0;
		$total_page=ceil($total/$this->_vars['items_per_page']);
		$this->_vars['total_pages']=$total_page;
		$this->_vars['total_items']=$total;
		return $this;
	}
	/**
	 * set page number
	 * @param number $page
	 * @return static
	 */
	public function set_page($page){
		$page=intval($page);
		if($page<1) $page=1;
		$this->_vars['current_page']=$page;
		$show=$this->_vars['items_per_page'];
		$this->_vars['current_offset']=$page>0?(($page-1)*$show):0;
		return $this;
	}
	/**
	 * Checks whether the given page number exists.
	 * @param   integer  page number
	 * @return  boolean
	 */
	public function valid_page()
	{
		$page=$this->_vars['current_page'];
		return $page > 0 AND $page <= $this->_vars['total_pages'];
	}
	/**
	 * Fix wrong page number
	 * @return static
	 */
	public function fix_page(){
		$page=$this->_vars['current_page'];
		$total=$this->_vars['total_pages'];
		if ($page<=0){
			$page=$this->_vars['current_page']=1;
		}else if ($page>$total) {
			$page=$this->_vars['current_page']=$total;
		}
		$this->_vars['current_offset']=$page>0?(($page-1)*$show):0;
		return $this;
	}
	/**
	 * set offset
	 * @param int $page
	 * @return static
	 */
	public function set_offset($offset){
		$offset=intval($offset);
		if($offset<=0) $offset=0;
		$this->_vars['current_offset']=$offset;
		$this->_vars['current_page']=floor($offset/$this->_vars['items_per_page'])+1;
		if ($this->_vars['current_page']<1)$this->_vars['current_page']=1;
		return $this;
	}
	/**
	 * set skip page
	 * @param number $page skip page
	 * @return static
	 */
	public function set_skip($skip_page){
		$this->_skip_page=$skip_page;
		return $this;
	}
	/**
	 * get next page number
	 * @return boolean|number
	 */
	public function get_next_page(){
		$page=$this->_vars['current_page'];
		$total=$this->_vars['total_pages'];
		if($this->_vars['current_offset']+$this->_vars['items_per_page']>=$this->_vars['total_items']) return false;
		return $page>=$total?false:$page+1;
	}
	/**
	 * get prev page number
	 * @return number|boolean
	 */
	public function get_prev_page(){
		$page=$this->_vars['current_page'];
		if($page>$this->_vars['total_pages']+1) return false;
		return $page>1?$page-1:false;
	}
	/**
	 * get limit
	 * @return number
	 */
	public function get_limit(){
		return $this->_vars['items_per_page'];
	}
	/**
	 * get total item number
	 * @return number
	 */
	public function get_total(){
		return $this->_vars['total_items'];
	}
	/**
	 * get page number
	 * @param string $fix
	 * @return number
	 */
	public function get_page($fix=false){
		$page=$this->_vars['current_page'];
		if ($fix){
			$total=$this->_vars['total_pages'];
			if ($page<=0){
				$page=1;
			}else if ($page>$total) {
				$page=$total;
			}
		}
		return $page;
	}
	/**
	 * get offset
	 * @return number
	 */
	public function get_offset(){
		return $this->_vars['current_offset'];
	}
	/**
	 * Renders the pagination links.
	 * @return  string  pagination output
	 */
	public function __toString()
	{
		return $this->render();
	}
	/**
	 * proxy to render
	 * @return  string  pagination output
	 */
	public function render(){
		return $this->_render->render();
	}
	/**
	 * get total page
	 * @return number
	 */
	public function get_total_page(){
		return $this->_vars['total_pages'];
	}
	/**
	 * get remain page
	 * @return number
	 */
	public function get_remain_page(){
		$total_page=$this->get_total_page();
		$rpage=$total_page-$this->get_page();
		return $rpage>0?$rpage:0;
	}
	/**
	 * to array
	 * @return array
	 */
	public function as_array(){
		$next_page=$this->get_next_page();
		$prev_page=$this->get_prev_page();
		return array(
			'total'=>$this->get_total(),//总记录数
			'page'=>$this->get_page(),//当前页数
			'offset'=>$this->get_offset(),//当前偏移
			'prev_page'=>$prev_page,//当前页数
			'next_page'=>$next_page,//当前页数
			'page'=>$this->get_page(),//当前页数
			'total_page'=>$this->get_total_page(),//总页数
			'remain_page'=>$this->get_remain_page(),//剩余页数
			'limit'=>$this->get_limit(),//每页数量
			'is_next'=>boolval($next_page),//是否有下一页
			'is_prev'=>boolval($prev_page),//是否有下一页
		);
	}
}