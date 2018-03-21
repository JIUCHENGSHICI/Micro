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
* #####购物车控制器#####
* @author 代码狮
*/
namespace Home\Controller;
use Think\Controller;
class ShopBagController extends CommonController{
    
    
    //主
    public function shopBag(){
        
        
        $openid=session('openid');
        
        $model=M('bag');
        $where=[];
        $where['openid']=$openid;
        $bag=$model->where($where)
        ->field('t1.*,t2.*')
        ->table('wshop_bag as t1,wshop_goods as t2')
        ->where('t1.goods_id = t2.goods_id')
        ->where($where)
        ->order('t1.add_time desc')
        ->select();
        
        $this->assign('bag',$bag);
        // ========================
        // ==== 代码分割 ====
        // ========================
        
        $arr=[];
        
        foreach ($bag as $key => $value) {
            
            $goods_id=$value['goods_id'];
            $model=M('goods');
            $where=[];
            $where['goods_id']=$goods_id;
            $goods=$model->where($where)->find();
            
            if($goods){
                
                $goods['_color']=$goods['color'] ? explode('|',$goods['color']):[];
                $goods['_size']=$goods['size'] ? explode('|',$goods['size']):[];
                $goods['_size2']=$goods['size2'] ? explode('|',$goods['size2']):[];
                
                $res=[];
                $res['color']= $goods['_color'];
                $res['size']= $goods['_size'];
                $res['size2']= $goods['_size2'];
                
                
                
                $model=M('bag');
                $bag_id=$value['bag_id'];
                $where=[];
                $where['bag_id']=$bag_id;
                $bag_info=$model->where($where)->find();
                
                $res['user_style']= $bag_info['info'];
                $res['_user_style']= $bag_info['info'] ? explode('|',$bag_info['info']):[];
                $arr[$goods_id]=$res;
                
            }
            
            
            
        }
        
        $arr= json_encode($arr);
        $this->assign('goodsInfo',$arr);
        
        // ========================
        // ==== 代码分割 ====
        // ========================
        
        
        
        $openid=session('openid');
        
        $model=M('address');
        $where=[];
        $where['openid']=$openid;
        $address=$model->where($where)->order('add_time desc')->select();
        
        $this->assign('address',$address);
        
        $where=[];
        $where['openid']=$openid;
        $where['is_default']=1;
        $address_is_default=$model->where($where)->find();
        
        $this->assign('address_is_default',$address_is_default['address_id']);
        
        
        
        $this->display();
        
    }
    
    public function saveStyle(){
        
        $post=I('post.');
        $bag_id=$post['bag_id'];
        $info=$post['info'];
        
        
        $info = $info ? implode('|',$info):'';
        
        
        //=========保存数据=========
        $model=M('bag');
        //=========条件区
        $where=[];
        $where['bag_id']=$bag_id;
        //=========保存数据区
        $save=[];
        $save['info']=$info;
        $save['edit_time']=time();
        //=========sql区
        $result=$model->where($where)->save($save);
        //=========保存数据end=========
        
        
        if($result!== false){
            //添加没有出错
            $res['res'] = 1;
            $res['msg'] = $result;
            
        }else{
            //添加失败
            $res['res'] = $result;
            $res['msg'] = $result;
        }
        
        echo json_encode($res);
        
    }
    
    
    public function getStyle(){
        
        $get=I('get.');
        $goods_id=$get['goods_id'];
        $model=M('goods');
        $where=[];
        $where['goods_id']=$goods_id;
        $goods=$model->where($where)->find();
        
        if($goods){
            $goods['_color']=$goods['color'] ? explode('|',$goods['color']):[];
            $goods['_size']=$goods['size'] ? explode('|',$goods['size']):[];
            $goods['_size2']=$goods['size2'] ? explode('|',$goods['size2']):[];
            
            $res=[];
            $res['res']=1;
            $res['msg']['color']= $goods['_color'];
            $res['msg']['size']= $goods['_size'];
            $res['msg']['size2']= $goods['_size2'];
            
            
            
            $model=M('bag');
            $bag_id=$get['bag_id'];
            $where=[];
            $where['bag_id']=$bag_id;
            $bag=$model->where($where)->find();
            
            
            $res['msg']['user_style']= $bag['info'];
            $res['msg']['_user_style']= $bag['info'] ? explode('|',$bag['info']):[];
            
            echo json_encode($res);
            
        }else{
            $res=[];
            $res['res']=0;
            $res['msg']=$model->_sql();
            echo json_encode($res);
            
        }
        
        
        
        
    }
    
    public function add(){
        
        if(IS_POST){
            
            $openid=session('openid');
            $post=I('post.');
            
            
            $goods_id=$post['goods_id'];
            $info=$post['info'];
            
            $info = $info ? implode('|',$info):'';
            
            //=========添加数据=========
            $model=M('bag');
            
            //得先看看，有了就是增加数量而不是添加
            
            $where=[];
            $where['openid']=$openid;
            $where['goods_id']=$goods_id;
            $result=     $model->where($where)->find();
            
            if(!$result){
                //没有数据
                
                //=========添加数据区
                $add=[];
                $add['bag'.'_id']=md5('bag'.$add['add_time'].__KEY__.rand());
                $add['openid']=$openid;
                $add['goods_id']=$goods_id;
                $add['info']=$info;
                // $add['num']=0;
                $add['add_time']=time();
                $add['edit_time']=time();
                //=========sql区
                $result=$model->add($add);
                
                
                if($result!== false){
                    //添加没有出错
                    $res['res'] = $result;
                    $res['msg'] = $add['bag'.'_id'];
                    
                }else{
                    //添加失败
                    $res['res'] = $result;
                    $res['msg'] = $result;
                }
            }else{
                //购物车有了数据
                //=========保存数据区
                $save['edit_time']=time();
                $save['info']=$info;
                //=========sql区
                $result=$model->where($where)->setInc('num',1); // 用户的积分加3
                $result=$model->where($where)->save($save); // 用户的积分加3
                //=========保存数据end=========
                
                if($result!== false){
                    //添加没有出错
                    $res['res'] = 1;
                    $res['msg'] = $result;
                    
                }else{
                    //添加失败
                    $res['res'] = $result;
                    $res['msg'] = $result;
                }
                
            }
            
            echo json_encode($res);
            
        }
        
        
    }
    
    
    public function red(){
        
        if(IS_POST){
            
            $openid=session('openid');
            $post=I('post.');
            
            $model=M('bag');
            
            
            $where=[];
            $where['bag_id']=$post['bag_id'];
            $where['openid']=$openid;
            $result=$model->where($where)->setDec('num',1); //-1
            
            if($result){
                //添加没有出错
                $res['res'] = 1;
                $result=$model->where($where)->find();
                $res['msg'] = $result['num'];
                
            }else{
                //添加失败
                $res['res'] = $result;
                $res['msg'] = $result;
            }
            
            echo json_encode($res);
            
        }
        
    }
    public function del(){
        
        if(IS_POST){
            
            $openid=session('openid');
            $post=I('post.');
            $model=M('bag');
            
            $where=[];
            $where['bag_id']=$post['bag_id'];
            $where['openid']=$openid;
            $result=$model->where($where)->delete();
            
            if($result){
                $res['res'] = $result;
                $res['msg'] = $result;
            }else{
                $res['res'] = $result;
                $res['msg'] = $result;
            }
            
            echo json_encode($res);
            
        }
        
    }
    public function add1(){
        
        if(IS_POST){
            
            $openid=session('openid');
            $post=I('post.');
            
            $model=M('bag');
            
            
            $where=[];
            $where['bag_id']=$post['bag_id'];
            $where['openid']=$openid;
            $result=$model->where($where)->setInc('num',1); //+1
            
            if($result){
                //添加没有出错
                $res['res'] = 1;
                $result=$model->where($where)->find();
                $res['msg'] = $result['num'];
                
            }else{
                //添加失败
                $res['res'] = $result;
                $res['msg'] = $result;
            }
            
            echo json_encode($res);
            
        }
        
    }
    //空操作
    public function _empty(){
        
    }
    
    
}