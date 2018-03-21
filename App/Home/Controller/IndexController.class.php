<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2017年11月27日
* 最新修改时间：2017年11月27日
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####首页控制器#####
* @author 代码狮
*/
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController{
    
    
    //主
    public function index(){
        
        //找导航
        $model=M('nav');
        $where=[];
        $nav=$model->where($where)->order('sort asc')->select();
        //遍历找推荐
        $model_up=M('up');
        $model_goods=M('goods');
        $model_carousel=M('carousel');
        
        foreach ($nav as $key => $value) {
            //找到推荐商品
            $where=[];
            $where['nav_id']=$value['nav_id'];
            $up=$model_up->where($where)->select();
            if($up){
                //有推荐数据
                foreach ($up as $key2 => $value2) {
                    $where=[];
                    $where['goods_id']=$value2['goods_id'];
                    $up_goods=$model_goods->where($where)->find();
                    //得判断一下商品有没有上架
                    if($up_goods['is_show']>0){
                        $nav[$key]['goods'][]=$up_goods;
                    }
                }
            }
            //找到推荐商品end
            //开始找轮播图
            $where=[];
            $where['nav_id']=$value['nav_id'];
            $result_carousel=$model_carousel->where($where)->order('sort asc')->select();
            if($result_carousel){
                $nav[$key]['carousel']=$result_carousel;
            }
            
        }
        
        $this->assign('nav',$nav);
        // dump($nav);
        // die;
        
        //显示
        $this->display();
        
    }
    
    public function about(){
        $this->display();
        
    }
    //空操作
    public function _empty(){
        
    }
    
    
}