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
* #####登录控制器#####
* @author 代码狮
*
*/


namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    
    
    //构造函数
    public function _initialize(){
        
    }
    
    //主
    public function login(){
        
        
        if(IS_POST){
            
            $post= I('post.');
            if(empty(I('post.admin_id'))||empty(I('post.admin_pwd'))||empty(I('post.admin_code'))){
                //账户密码啥的没有
                $res['res']=-1;
                $res['msg']='缺少数据！';
            }else{
                //啥参数都有
                $captcha = I('post.admin_code');
                //校验验证码（不需要传参）
                $verify = new \Think\Verify();
                //验证
                $result = $verify -> check($captcha);
                if($result){
                    //验证码正确
                    $pwd=$post['admin_pwd'];
                    $pwd=md5($pwd.__KEY__);
                    $where['admin_id']=$post['admin_id'];
                    $model=M('admin');
                    //先取出用户资料
                    $result= $model->where($where)->find();
                    if($result!==null&&$result!==false){
                        
                        if($result['admin_pwd']===$pwd){
                            //密码正确，可以登录
                            
                            session('admin_id',$result['admin_id']);
                            session('admin_name',$result['admin_name']);
                            $res['res']=0;
                            $res['msg']='验证成功';
                            
                        }else{
                            //密码不正确，不能登录
                            $res['res']=-2;
                            $res['msg']='验证失败';
                        }
                    }else{
                        //没有用户
                        $res['res']=-4;
                        $res['msg']='啥？';
                    }
                    
                }else{
                    //验证码不正确
                    $res['res']=-3;
                    $res['msg']='验证码不正确';
                }
                
            }
            
            echo json_encode($res);
        }else{
            $this->display();
        }
        
    }
    /**
    * 获得验证码
    */
    public function getCode(){
        //验证码配置
        $cfg = array(
        'fontSize' => 12, // 验证码字体大小(px)
        'useCurve' => false, // 是否画混淆曲线
        'useNoise' => false,
        // 是否添加杂点
        'length' => 4, // 验证码位数
        'fontttf' => '4.ttf', // 验证码字体，不设置随机获取
        );
        //实例化验证码类
        $verify = new \Think\Verify($cfg);
        //输出验证码
        ob_clean();
        $verify -> entry();
    }
    /**
    * 退出登录
    */
    public function sinOut(){
        
        session(null);
        $url=U('Login/login');
        echo "<script>top.location.href='$url'</script>";
        
    }
    //空操作
    public function _empty(){
        
    }
    
    /**
    * 添加管理账户
    */
    public function test(){
        
        
        $md5=md5('123'.__KEY__);
        echo $md5;
        dump(session());
        
    }
    
}