<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2017年11月19日
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####商品控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends CommonController {
    public function showList() {
        $this->display();
    }
    
    
    /**
    * 获得
    */
    public function getList(){
        
        $get = I('get.');
        $table = strtolower($get['table']);
        //创建模型
        $model = M($table);
        
        //定制分页-start
        $page=$get['page'];
        $limit=$get['limit'];
        $page=($page-1)* $limit;
        //定制分页-end
        
        if(!empty($get['key'])){
            
            $key=$get['key'];
            $type=$get['type'];
            
            if($type=='reload_brand'){
                $brand_model=M('brand');
                $where=[];
                $where['brand_title']=$key;
                $brand_info= $brand_model->where($where)->find();
                $where['brand_id']=$brand_info['brand_id'];
            }
            
            if($type=='reload_class'){
                $class_model=M('class');
                $where=[];
                $where['class_title']=$key;
                $class_info= $class_model->where($where)->find();
                $where['class_id_1']=$class_info['class_id'];
                $where['class_id_2']=$class_info['class_id'];
            }
            
            if($type=='reload'){
                $key_where= $get['key_where'] ? $get['key_where']: $table.'_id';
                $where[$key_where] = array(
                'like',
                "%".$key."%",
                'OR'
                );
            }
            
            $where['_logic'] = 'OR';
            
            $result= $model->limit("$page,$limit")->order('add_time desc')->where($where)->select();
            // $res['sql']=$model->_sql();
            $res['count']=$model->where($where)->count();
            
        }else{
            
            $count= $model->count();
            $res['count']=$count;
            $result= $model->limit("$page,$limit")->order('add_time desc')->select();
            $res['sql']=$model->_sql();
            
        }
        
        //转换时间戳
        $result=   toTime($result);
        
        foreach ($result as $key => $value) {
            //转换品牌名和类名
            
            
            //找一级分类
            $model=M('class');
            $where=[];
            $where['class_id']=$value['class_id_1'];
            $r=$model->where($where)->find();
            $result[$key]['class_title_1']=$r['class_title'];
            //找二级分类
            $model=M('class');
            $where=[];
            $where['class_id']=$value['class_id_2'];
            $r=$model->where($where)->find();
            $result[$key]['class_title_2']=$r['class_title'];
            //找品牌
            $model=M('brand');
            $where=[];
            $where['brand_id']=$value['brand_id'];
            $r=$model->where($where)->find();
            $result[$key]['brand_title']=$r['brand_title'];
            
        }
        
        
        
        
        if($result){
            $res['res']= $res['count'];
            $res['code']=1;
            $res['data']= $result;
            $res['msg']= $result;
        }else{
            $res['code']=-1;
            $res['msg']='没有数据！';
        }
        echo json_encode($res);
        
    }
    /**
    * 添加
    */
    public function add(){
        $this->display();
    }
    /**
    * 编辑
    */
    public function edit(){
        
        $model=M('goods');
        $where=[];
        $where['goods_id']=I('get.goods_id');
        $result=$model->where($where)->find();
        
        
        $result['color']=$result['color']?explode('|',$result['color']):[];
        $result['color']=json_encode($result['color']);
        
        
        $result['size']=$result['size']?explode('|',$result['size']):[];
        $result['size']=json_encode($result['size']);
        
        $result['size2']=$result['size2']?explode('|',$result['size2']):[];
        $result['size2']=json_encode($result['size2']);
        
        $this->assign('goods',$result);
        $this->display();
    }
    
    public function batchUpload(){
        
        $this->display();
        
    }
    
    public function getBatchUploadList(){
        
        $model=M('class');
        $where=[];
        $class=$model->where($where)->select();
        
        
        $model=M('brand');
        $where=[];
        $brand=$model->where($where)->select();
        
        $list=[];
        
        foreach ($brand as $brand_key => $brand_value) {
            # code...
            $list[$brand_value['brand_title']]['brand_info']=$brand_value;
            foreach ($class as $class_key => $class_value) {
                
                if($class_value['level']==1){
                    
                    if($class_value['brand_id']==$brand_value['brand_id']){
                        $list[$brand_value['brand_title']]['class_1'][$class_value['class_title']]=$class_value;
                        
                        foreach ($class as $class_key_2 => $class_value_2) {
                            if($class_value_2['level']==2){
                                if($class_value_2['super_id']==$class_value['class_id']){
                                    $list[$brand_value['brand_title']]['class_1'][$class_value['class_title']]['class_2'][$class_value_2['class_title']]=$class_value_2;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        echo json_encode($list);
        
    }
    
}