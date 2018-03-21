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
class BrandController extends Controller{
    
    //构造函数
    public function _initialize(){
        
    }
    //主
    public function brand(){
        
        
        $model=M('brand');
        $order='brand_title asc';
        $brand=$model->order($order)->select();
        
        
        $title='';
        $arr=[];
        foreach ($brand as $key => $value) {
            $brand_title_0 = substr( $value['brand_title'], 0, 1 );
            if($title!=$brand_title_0){
                $title=$brand_title_0;
                $brand_title_0= strtoupper($brand_title_0);
                $arr[]=$brand_title_0;
            }
            $arr[]=$value;
            
        }
        $this->assign('brand',$arr);
        
        $this->display();
        
    }
    
    //空操作
    public function _empty(){
        
    }
    
    
}