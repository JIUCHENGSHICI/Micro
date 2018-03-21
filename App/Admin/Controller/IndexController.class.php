<?php
/**
* +----------------------------------------------------------------------
* 创建日期：2017年11月17日
* +----------------------------------------------------------------------
* https：//github.com/ALNY-AC
* +----------------------------------------------------------------------
* 微信：AJS0314
* +----------------------------------------------------------------------
* QQ:1173197065
* +----------------------------------------------------------------------
* #####主页控制器#####
* @author 代码狮
*
*/
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index() {
        
        
        
        
        if (IS_POST) {
            $url = I('post.url');
            session('admin_url', $url);
            echo U($url);
        } else {
            
            $admin_url = session('?admin_url');
            
            if ($admin_url) {
                
                $this -> assign('admin_url', U(session('admin_url')));
                $this -> display();
                
            } else {
                $this -> display();
            }
            
        }
        
    }
    public function home(){
        $this -> display();
    }
    
}