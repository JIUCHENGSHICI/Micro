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
* #####分类控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class ClassController extends CommonController{
    
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
        
        
        $model=M('brand');
        $where=[];
        $where['brand_id']=I('get.brand_id');
        $result=$model->where($where)->find();
        
        
        $this->assign('brand',$result);
        
        $this->display();
    }
    
    public function edit(){
        $this->display();
    }
}