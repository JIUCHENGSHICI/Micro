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
class OrderController extends CommonController{
    
    public function add(){
        
        if(IS_POST){
            
            $openid=session('openid');
            $post=I('post.');
            $order_id=date('YmdHis').rand(10000,99999);
            $address_id=$post['address_id'];
            
            $model=M('goods');
            $bagInfo=M('bag');
            $goods_info=M('orderInfo');
            
            $money=0.00;
            
            foreach ($post['ids'] as $key => $value) {
                $where=[];
                $where['goods_id']=$value['goods_id'];
                $result=  $model->where($where)->find();
                
                $money += $result['money'];
                
                $order_info_add=$value;
                $order_info_add['order_id']=$order_id;
                
                $where=[];
                $where['openid']=session('openid');
                $where['goods_id']=$value['goods_id'];
                $r=  $bagInfo->where($where)->find();
                
                $order_info_add['info']=$r['info'];
                
                
                
                $goods_info->add($order_info_add);
            }
            
            
            //=========添加数据=========
            $model=M('order');
            
            //=========添加数据区
            $add=[];
            $add['order'.'_id']=$order_id;
            $add['openid']=$openid;
            $add['address_id']=$address_id;
            $add['money']=$money;
            $add['add_time']=time();
            $add['edit_time']=time();
            //=========sql区
            $result=$model->add($add);
            
            
            if($result!== false){
                //添加没有出错
                $res['res'] = $result;
                $res['msg'] = $order_id;
                
            }else{
                //添加失败
                $res['res'] = $result;
                $res['msg'] = $result;
            }
            
            echo json_encode($res);
            
        }
        
        
    }
    
    public function showList(){
        
        
        $openid=session('openid');
        $model=M('order');
        $where=[];
        $where['openid']=$openid;
        $order=$model
        ->field('t1.*,t2.*,t3.*,t2.info as t2_info,t3.money as goods_money,t1.money as order_money,t1.add_time as t1_add_time')
        ->table('wshop_order as t1,wshop_order_info as t2,wshop_goods as t3')
        ->where('t1.order_id = t2.order_id AND t2.goods_id = t3.goods_id')
        ->where($where)
        ->order('t1.state asc,t1.add_time desc')
        ->select();
        
        $arr=[];
        $arr['all']=[];
        $arr[0]=[];
        $arr[1]=[];
        $arr[2]=[];
        $arr[3]=[];
        
        foreach ($order as $key => $value) {
            
            
            $arr['all'][$value['order_id']]['goods_list'][]=$value;
            $arr['all'][$value['order_id']]['order_info']['t1_add_time']=date('Y-m-d H:i:s',$value['t1_add_time']) ;
            // $arr['all'][$value['order_id']]['order_info']['order_id']=$value['order_id'] ;
            // $arr['all'][$value['order_id']]['order_info']['order_money']=$value['order_money'] ;
            // $arr['all'][$value['order_id']]['order_info']['sc_info']=$value['sc_info'] ;
            // $arr['all'][$value['order_id']]['order_info']['sc_type']=$value['sc_type'] ;
            // $arr['all'][$value['order_id']]['order_info']['state']=$value['state'] ;
            $arr['all'][$value['order_id']]['order_info']=$value ;
            
            
            // ====
            
            $arr[$value['state']][$value['order_id']]['goods_list'][]=$value;
            $arr[$value['state']][$value['order_id']]['order_info']['t1_add_time']=date('Y-m-d H:i:s',$value['t1_add_time']) ;
            // $arr[$value['state']][$value['order_id']]['order_info']['order_id']=$value['order_id'] ;
            // $arr[$value['state']][$value['order_id']]['order_info']['order_money']=$value['order_money'] ;
            // $arr[$value['state']][$value['order_id']]['order_info']['sc_info']=$value['sc_info'] ;
            // $arr[$value['state']][$value['order_id']]['order_info']['sc_type']=$value['sc_type'] ;
            // $arr[$value['state']][$value['order_id']]['order_info']['state']=$value['state'] ;
            $arr[$value['state']][$value['order_id']]['order_info']=$value;
        }
        
        // dump($arr);
        // die;
        
        $this->assign('order',$arr);
        
        if (empty(I('get.pages'))){
            $pages=-1;
        }else{
            $pages=I('get.pages');
        }
        
        $this->assign('_pages',$pages);
        
        $this->display();
        
    }
    
    /**
    * 取消
    */
    public function cancel(){
        
        $openid=session('openid');
        $order_id=I('get.order_id');
        
        $model=M('order');
        $where=[];
        $where['order_id']=$order_id;
        $where['openid']=$openid;
        $where['state']=0;
        
        $result=$model->where($where)->find();
        if($result){
            //这个订单是这个用户的，并且状态是0
            $model->where($where)->delete();
            $model=M('orderInfo');
            $where=[];
            $where['order_id']=$order_id;
            $model->where($where)->delete();
            
        }
        
        
        if (empty(I('get.pages'))){
            $pages=-1;
        }else{
            $pages=I('get.pages');
        }
        $url=U('showList',array(
        'pages'=>$pages
        ));
        echo "<script>top.location.href='$url'</script>";
        
    }
    
    /**
    * 提醒发货
    */
    public function remind(){
        
    }
    /**
    * 确认收货
    */
    public function ok(){
        
        
        $openid=session('openid');
        $order_id=I('get.order_id');
        
        $model=M('order');
        $where=[];
        $where['order_id']=$order_id;
        $where['openid']=$openid;
        
        $save['state']=3;
        
        $result=$model->where($where)->save($save);
        
        
        if (empty(I('get.pages'))){
            $pages=-1;
        }else{
            $pages=I('get.pages');
        }
        
        $url=U('showList',array(
        'pages'=>$pages
        ));
        echo "<script>top.location.href='$url'</script>";
        
    }
    /**
    * 售后
    */
    public function sc(){
        
        
        if(IS_POST){
            
            $post=I('post.');
            $order_id=$post['order_id'];
            $openid=session('openid');
            $model=M('order');
            $where=[];
            $where['order_id']=$order_id;
            $where['openid']=$openid;
            $save['state']=4;
            $save['sc_type']= $post['type'];
            $save['sc_info']=$post['info'];
            $result=$model->where($where)->save($save);
            
            if($result){
                //设置成功
                echo 1;
            }else{
                echo -1;
            }
            
        }else{
            
            $openid=session('openid');
            $order_id=I('get.order_id');
            $this->assign('order_id',$order_id);
            $this->display();
        }
        
    }
    
    
    
    //空操作
    public function _empty(){
        
    }
    
    
}