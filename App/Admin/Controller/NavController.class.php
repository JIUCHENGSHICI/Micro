<?php

/**
* +----------------------------------------------------------------------
* 创建日期：2017年12月4日
* 最新修改时间：2017年12月4日
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####导航栏控制器#####
* @author 代码狮
*
*/


namespace Admin\Controller;
use Think\Controller;
class NavController extends CommonController{
 
    public function index(){
        
    }
    //空操作
    public function _empty(){
        
    }
    
    /**
    * 显示导航列表
    */
    public function showList(){
        $this->display();
        
    }
    
    /**
    * 通用查表，查所有
    */
    public function getList(){
        
        if(!empty(I('get.'))){
            //有get
            //取出所有的nav
            //然后判断传上来的上来的商品id是否被推荐到对应的导航
            //如果被推荐，就设置isup为true
            
            $model=M('nav');
            $where=[];
            // $where['']=$id;
            $nav=$model->where($where)->order('sort asc')->select();
            
            $model=M('up');
            $where=[];
            $where['goods_id']=I('goods_id');
            $goods_up=$model->where($where)->select();
            
            
            
            foreach ($nav as $key1 => $value1) {
                
                $nav[$key1]['is_up']=false;
                
                foreach ($goods_up as $key2 => $value2) {
                    
                    if($value1['nav_id']==$value2['nav_id']){
                        //有
                        $nav[$key1]['is_up']=true;
                    }
                }
            }
            
            
            $res['res'] = count($nav);
            $res['msg'] =$nav;
            
        }else{
            //没有任何get
            $res['res'] = -1;
            $res['msg'] = 'no data';
        }
        
        echo json_encode($res);
        
    }
    
    
    
}