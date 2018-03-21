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
class ClassController extends Controller{
    
    //构造函数
    public function _initialize(){
        
    }
    
    public function show(){
        
        
        
        $brand_id=I('get.brand_id');
        
        $this->assign('brand_id',$brand_id);
        
        $model=M('goods');
        $where=[];
        $where['brand_id']=$brand_id;
        
        if(!empty(I('get.class_id'))){
            $class_id= I('get.class_id');
            $where['class_id_2']=$class_id;
        }
        
        $where['is_show']=1;
        $goods=$model->where($where)->select();
        $this->assign('goods',$goods);
        
        $model=M('Class');
        $where=[];
        $where['brand_id']=$brand_id;
        $where['level']=1;
        $class_1=$model->where($where)->select();
        
        
        //找二级数组
        $model=M('Class');
        $where=[];
        $where['brand_id']=$brand_id;
        $where['level']=2;
        $class_2=$model->where($where)->select();
        $this->assign('class_2',$class_2);
        
        foreach ($class_1 as $key_1 => $value_1) {
            
            $class_1_id=$value_1['class_id'];
            
            foreach ($class_2 as $key_2 => $value_2) {
                
                $super_id=$value_2['super_id'];
                
                if($super_id==$class_1_id){
                    $class_1[$key_1]['class_2'][]=$value_2;
                }
            }
        }
        $this->assign('class_1',$class_1);
        
        $this->display('class');
    }
    //空操作
    public function _empty(){
        
    }
    
    
}