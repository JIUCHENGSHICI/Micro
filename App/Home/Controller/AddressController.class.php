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
class AddressController extends Controller{
    
    //构造函数
    public function _initialize(){
        
    }
    //主
    public function add(){
        
        if(IS_POST){
            $post=I('post.');
            
            
            //=========添加数据=========
            $model=M('address');
            //=========添加数据区
            $add=[];
            
            
            $add['people']=$post['people'];
            $add['phone']=$post['phone'];
            $add['openid']=session('openid');
            
            $add['location']=$post['location']['l1'].' '.$post['location']['l2'].' '.$post['location']['l3'];
            
            
            
            $add['add_time']=time();
            $add['edit_time']=time();
            
            
            $add['address_id']=md5('address'.$add['add_time'].__KEY__.rand());
            dump($add);
            
            // die;
            
            
            //=========sql区
            $result=$model->add($add);
            
            
            $url=U('address/showList');
            echo "<script>top.location.href='$url'</script>";
            
            
        }else{
            $this->display();
        }
        
        
        
        
    }
    public function showList(){
        
        $openid=session('openid');
        
        $model=M('address');
        $where=[];
        $where['openid']=$openid;
        $address=$model->where($where)->order('add_time desc')->select();
        
        $this->assign('address',$address);
        
        $this->display();
        
        
    }
    
    
    
    public function setDefault(){
        
        $get=I('get.');
        $openid=session('openid');
        $address_id=$get['address_id'];
        
        //=========保存数据=========
        $model=M('address');
        //=========条件区
        $where=[];
        $where['openid']=$openid;
        //=========保存数据区
        $save=[];
        $save['is_default']=0;
        $save['edit_time']=time();
        //=========sql区
        $result=$model->where($where)->save($save);
        //=========保存数据end=========
        
        
        //=========条件区
        $where=[];
        $where['openid']=$openid;
        $where['address_id']=$address_id;
        //=========保存数据区
        $save=[];
        $save['is_default']=1;
        $save['edit_time']=time();
        //=========sql区
        $result=$model->where($where)->save($save);
        //=========保存数据end=========
        
        
        $url=U('address/showList');
        echo "<script>top.location.href='$url'</script>";
        
        
    }
    
    
    public function del(){
        $get=I('get.');
        $openid=session('openid');
        $address_id=$get['address_id'];
        
        //=========删除数据=========
        $model=M('address');
        //=========条件区
        $where=[];
        $where['openid']=$openid;
        $where['address_id']=$address_id;
        //=========sql区
        $result=$model->where($where)->delete();
        //=========删除数据end=========
        
        $url=U('address/showList');
        echo "<script>top.location.href='$url'</script>";
        
        
    }
    
    
    //空操作
    public function _empty(){
        
    }
    
    
}