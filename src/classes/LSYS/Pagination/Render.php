<?php
/**
 * lsys pagination
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Pagination;
use LSYS\Pagination;

interface Render{
	/**
	 * set Pagination object
	 * @param Pagination $page
	 */
	public function setPagination(Pagination $page);
	/**
	 * render Pagination
	 * @return string
	 */
	public function render():?string;
}