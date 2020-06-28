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
		$render->setPagination($this);
	}
	/**
	 * set limit
	 * @param number $limit
	 * @return static
	 */
	public function setLimit(int $limit){
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
	public function setTotal(int $total){
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
	public function setPage(int $page){
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
	public function validPage():bool
	{
		$page=$this->_vars['current_page'];
		return $page > 0 AND $page <= $this->_vars['total_pages'];
	}
	/**
	 * Fix wrong page number
	 * @return static
	 */
	public function fixPage(){
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
	public function setOffset(int $offset){
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
	public function setSkip(int $skip_page){
		$this->_skip_page=$skip_page;
		return $this;
	}
	/**
	 * get next page number
	 * @return boolean|number
	 */
	public function getNextPage(){
		$page=$this->_vars['current_page'];
		$total=$this->_vars['total_pages'];
		if($this->_vars['current_offset']+$this->_vars['items_per_page']>=$this->_vars['total_items']) return false;
		return $page>=$total?false:$page+1;
	}
	/**
	 * get prev page number
	 * @return number|boolean
	 */
	public function getPrevPage(){
		$page=$this->_vars['current_page'];
		if($page>$this->_vars['total_pages']+1) return false;
		return $page>1?$page-1:false;
	}
	/**
	 * get limit
	 * @return number
	 */
	public function getLimit():int{
		return (int)$this->_vars['items_per_page']??0;
	}
	/**
	 * get total item number
	 * @return number
	 */
	public function getTotal():int{
	    return (int)$this->_vars['total_items']??0;
	}
	/**
	 * get page number
	 * @param string $fix
	 * @return number
	 */
	public function getPage(bool $fix=false):int{
		$page=$this->_vars['current_page'];
		if ($fix){
			$total=$this->_vars['total_pages'];
			if ($page<=0){
				$page=1;
			}else if ($page>$total) {
				$page=$total;
			}
		}
		return (int)$page;
	}
	/**
	 * get offset
	 * @return number
	 */
	public function getOffset():int{
		return (int)$this->_vars['current_offset'];
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
	public function render():?string{
		return $this->_render->render();
	}
	/**
	 * get total page
	 * @return number
	 */
	public function getTotalPage():int{
		return (int)$this->_vars['total_pages'];
	}
	/**
	 * get remain page
	 * @return number
	 */
	public function getRemainPage():int{
		$total_page=$this->getTotalPage();
		$rpage=$total_page-$this->getPage();
		return $rpage>0?(int)$rpage:0;
	}
	/**
	 * to array
	 * @return array
	 */
	public function asArray():array{
		$next_page=$this->getNextPage();
		$prev_page=$this->getPrevPage();
		return array(
			'total'=>$this->getTotal(),//总记录数
			'page'=>$this->getPage(),//当前页数
			'offset'=>$this->getOffset(),//当前偏移
			'prev_page'=>$prev_page,//当前页数
			'next_page'=>$next_page,//当前页数
			'page'=>$this->getPage(),//当前页数
			'total_page'=>$this->getTotalPage(),//总页数
			'remain_page'=>$this->getRemainPage(),//剩余页数
			'limit'=>$this->getLimit(),//每页数量
			'is_next'=>boolval($next_page),//是否有下一页
			'is_prev'=>boolval($prev_page),//是否有下一页
		);
	}
}