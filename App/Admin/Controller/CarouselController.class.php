<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2017年12月5日
* 最新修改时间：2017年12月5日
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####轮播图控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class CarouselController extends CommonController{
    
    
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
        
        $nav_id=   I('get.nav_id');
        
        $model=M('nav');
        $where=[];
        $where['nav_id']=$nav_id;
        $nav=$model->where($where)->find();
        $this->assign('nav',$nav);
        
        
        $this->display();
    }
}