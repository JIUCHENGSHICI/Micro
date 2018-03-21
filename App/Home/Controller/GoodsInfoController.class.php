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
* #####商品详情控制器#####
* @author 代码狮
*/
namespace Home\Controller;
use Think\Controller;
class GoodsInfoController extends Controller{
    
    //构造函数
    public function _initialize(){
        
    }
    //主
    public function goodsInfo(){
        
        
        $get=I('get.');
        $goods_id=$get['goods_id'];
        $model=M('goods');
        $where=[];
        $where['goods_id']=$goods_id;
        $goods=$model->where($where)->find();
        
        $goods['_color']=$goods['color'] ? explode('|',$goods['color']):[];
        $goods['_size']=$goods['size'] ? explode('|',$goods['size']):[];
        $goods['_size2']=$goods['size2'] ? explode('|',$goods['size2']):[];
        
        
        // dump($goods);
        // die;
        
        $this->assign('goods',$goods);
        
        $this->display();
        
    }
    
    
    //空操作
    public function _empty(){
        
    }
    
    
    
}