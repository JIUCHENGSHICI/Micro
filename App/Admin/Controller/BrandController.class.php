<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2017年11月28日
* 最新修改时间：2017年11月28日
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####品牌控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class BrandController extends CommonController{
    
    //主
    public function index(){
        
    }
    //空操作
    public function _empty(){
        
    }
    
    public function add(){
        $this->display();
        
    }
    
    
    public function showList(){
        $this->display();
    }
}