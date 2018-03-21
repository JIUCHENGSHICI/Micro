<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller{
    
    //构造函数
    public function _initialize(){
        
    }
    
    public function getopenid(){
        
        $info= baseAuth('http://120.78.162.200:12138/wShop/index.php/Home/login/getopenid');
        $openid= $info['openid'];
        $access_token= $info['access_token'];
        session('openid',$openid);
        session('access_token',$access_token);
        
    }
    
    
    public function login(){
        
        $openid= I('post.openid');
        $access_token= I('post.access_token');
        session('openid',$openid);
        session('access_token',$access_token);
        
        //=========添加数据=========
        $model=M('user');
        //=========添加数据区
        $add=[];
        $add['openid']=$openid;
        $add['access_token']=$access_token;
        $add['add_time']=time();
        $add['edit_time']=time();
        //=========sql区
        $result=$model->add($add,null,true);
    }
    
    public function openid(){
        
        
        $code=I('get.code');
        $nsukey=I('get.nsukey');
        // $ip='192.168.1.196:12138';
        // $ip='127.0.0.1:12138';
        $ip='120.78.162.200:12138';
        echo "<script>window.location.href='http://$ip/wShop/index.php/home/login/setOpenid/code/$code/nsukey/$nsukey'</script>";
        
        
    }
    
    public function setOpenid(){
        
        
        
        
        $this->assign('openid',$openid);
        $this->assign('openid',$openid);
        
        
        $this->display();
        
        
        
    }
    
    
    //空操作
    public function _empty(){
        
    }
    
}