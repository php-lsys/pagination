<?php
/**
 * lsys mq
 * @author     Lonely <shan.liu@msn.com>
 * @copyright  (c) 2017 Lonely <shan.liu@msn.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 */
namespace LSYS\Pagination;
/**
 * @method \LSYS\Pagination pagination($render=null)
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->pagination)&&$di->pagination(new \LSYS\DI\MethodCallback(function($render=null){
            if ($render==null)$render=new \LSYS\Pagination\Render\SimpleHtml(\LSYS\Config\DI::get()->config("pagination.simple_html"));
            return new \LSYS\Pagination($render);
        }));
        return $di;
    }
}