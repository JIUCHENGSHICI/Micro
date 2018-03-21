<?php
namespace Admin\Controller;
use Think\Controller;
class UseController extends CommonController {
    
    public function index(){
        
        
        $this->display();
    }
    
    public function saveField(){
        
        if(IS_POST){
            
            $info = I('post.');
            $id = $info['id'];
            
            if(!empty($id)){
                
                $table =   strtolower($info['table']);
                $save = $info['save'];
                $save['edit_time'] = time();
                
                $model = M($table);
                $where[$table.'_id'] = $id;
                
                $result =   $model->where($where)->save($save);
                
                
                if($result!== false){
                    $res['res'] = 1;
                    $res['msg'] = $result;
                }else{
                    $res['res'] = -1;
                    $res['msg'] = $result.'【'.$id.'】【'.json_encode($save).'】【'.$table.'】';
                }
            }else{
                $res['res'] = -2;
                $res['msg'] = $table.'_id is null id:'.$id;
            }
            
        }else{
            $res['res'] = -2;
            $res['msg'] = 'is no post';
        }
        
        echo json_encode($res);
        
    }
    /*
    查一条
    */
    public function getOne(){
        
        
        $info = I('get.');
        $table =   strtolower($info['table']);
        $id = $info['id'];
        
        
        if(!empty($id)){
            $model = M($table);
            $where[$table.'_id'] = $id;
            
            $result =   $model->field($info['field'])->where($where)->find();
            
            if($result!== false){
                $res['res'] = 1;
                if(empty($info['field'])){
                    $res['msg'] = $result;
                }else{
                    $res['msg'] = $result;
                }
            }else{
                $res['res'] = -1;
                $res['msg'] = 'no';
            }
        }else{
            $res['res'] = -2;
            $res['msg'] = 'id is null id:'.$id;
        }
        
        
        
        echo json_encode($res);
        
        
    }
    
    /**
    * 通用查表，查所有
    */
    public function getList(){
        
        if(!empty(I('get.'))){
            //有get
            $get = I('get.');
            
            $table =$get['table'];
            
            $type=gettype($table);
            
            if($type=='array'){
                //查多个表
                
                foreach ($table as $key => $value) {
                    $table = strtolower($value);
                    
                    //判断有没有别名
                    $tableAS=strpos($table ,' as ');
                    
                    if($tableAS!==false){
                        $tableAS = explode(' as ',$table);
                        $table=trim($tableAS[0]);//真名
                        $tableASname=trim($tableAS[1]);//别名
                    }else{
                        $tableASname=$table;//别名
                    }
                    
                    $model = M($table);
                    //查询语句组件区
                    $where=$get['where'][$tableASname];
                    $order=$get['order'][$tableASname];
                    //查询语句组件区end
                    $result  =  $model->where($where)->order($order)->select();
                    $res['sql'] = $model->_sql();
                    //转换时间戳
                    $result=   toTime($result);
                    if($result!== false){
                        //查询没有出错
                        $res['res'][$tableASname] = count($result);
                        $res['msg'][$tableASname] = $result;
                    }else{
                        //查询失败
                        $res['code'][$tableASname] = -1;
                        $res['msg'][$tableASname] = $result;
                        $res['res'][$tableASname] =-1;
                    }
                    
                }
            }
            
            if($type=='string') {
                //查一个表
                $table = strtolower($table);
                $model = M($table);
                //查询语句组件区
                $where=$get['where'];
                $order=$get['order'];
                //查询语句组件区end
                $result  =  $model->where($where)->order($order)->select();
                $res['sql'] = $model->_sql();
                //转换时间戳
                $result=   toTime($result);
                if($result!== false){
                    //查询没有出错
                    $res['code'] = 1;
                    $res['count'] = count($result);
                    $res['data'] = $result;
                    //
                    $res['msg'] = $result;
                    $res['res'] = count($result);
                }else{
                    //查询失败
                    $res['code'] = -1;
                    $res['msg'] = $result;
                    $res['res'] =-1;
                }
                
            }
            
            
        }else{
            //没有任何get
            $res['res'] = -1;
            $res['msg'] = 'no data';
        }
        
        echo json_encode($res);
        
    }
    
    /**
    * 带条件的查询
    */
    public function getListWhere(){
        
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
            
            $key_where= $get['key_where'] ? $get['key_where']: $table.'_id';
            
            $where[$key_where] = array(
            'like',
            "%".$key."%",
            'OR'
            );
            
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
    
    public function add(){
        
        if(!empty(I('post.'))){
            //有get
            $post = I('post.');
            $table =   strtolower($post['table']);
            
            $add = $post['add'];
            
            $add['add_time']=time();
            $add['edit_time']=time();
            
            $add[$table.'_id']=md5($table.$add['add_time'].__KEY__.rand());
            
            
            $model = M($table);
            
            $result  =  $model->add($add);
            
            if($result!== false){
                //添加没有出错
                $res['res'] = $result;
                $res['msg'] = $add[$table.'_id'];
                
            }else{
                //添加失败
                $res['res'] = $result;
                $res['msg'] = $result;
            }
        }else{
            //没有任何post
            $res['res'] = -1;
            $res['msg'] = 'no data';
        }
        
        echo json_encode($res);
        
    }
    
    /**
    * 添加多个，一次传入多个值
    */
    public function addAll(){
        if(!empty(I('post.'))){
            //有get
            $post = I('post.');
            $table =   strtolower($post['table']);
            
            $add = $post['add'];
            
            foreach ($add as $key => $value) {
                
                $add[$key]['add_time']=time();
                $add[$key]['edit_time']=time();
                $add[$key][$table.'_id']=md5($table.time().__KEY__.rand());
                
            }
            
            $model = M($table);
            
            $result  =  $model->addAll($add);
            
            if($result!== false){
                //添加没有出错
                $res['res'] = $result;
                $res['msg'] = $result;
                
            }else{
                //添加失败
                $res['res'] = $result;
                $res['msg'] = $result;
            }
        }else{
            //没有任何post
            $res['res'] = -1;
            $res['msg'] = 'no data';
        }
        echo json_encode($res);
        
    }
    
    
    public function del(){
        
        if(!empty(I('post.'))){
            $post = I('post.');
            $table = strtolower($post['table']);
            
            if(!empty($post['where'])){
                //有where
                $where=$post['where'];
            }else{
                
                $where[$table.'_id']= $post['id'];
            }
            
            $model = M($table);
            $result=$model->where($where)->delete();
            
            if($result){
                //没有出错
                $res['res'] = $result;
                $res['msg'] = $result;
                
                if(!empty($post['link_del'])){
                    //如果有关联删除的条件
                    $link_del=$post['link_del'];
                    foreach ($link_del as $key => $value) {
                        $table = strtolower($value['table']);
                        $model = M($table);
                        $where = $value['where'];
                        $result=$model->where($where)->delete();
                    }
                    
                }
                
                
            }else{
                //失败
                $res['res'] = -1;
                $res['msg'] = 'delete false';
            }
            
            
        }else{
            //没有post
            $res['res'] = -1;
            $res['msg'] = 'no data';
        }
        echo json_encode($res);
        
        
    }
    /*
    删除选中
    */
    public function delAll(){
        
        
        if(!empty(I('post.'))){
            $post = I('post.');
            $table = strtolower($post['table']);
            
            
            $id= $post['id'];//这个id必须是数组
            $id= implode("','",$id);//用逗号分隔
            $where = $table."_id in('".$id."')";
            $model = M($table);
            $result=$model->where($where)->delete();
            
            if($result){
                //没有出错
                
                $res['res'] = $result;
                $res['msg'] = $result;
                
            }else{
                //失败
                $res['res'] = $result;
                $res['msg'] = $result;
                $res['sql'] = $model->_sql();
            }
            
            
        }else{
            //没有post
            $res['res'] = -1;
            $res['msg'] = 'no data';
        }
        echo json_encode($res);
        
        
    }
    /*
    批量修改
    */
    public function saveAll(){
        
        
        if(!empty(I('post.'))){
            $post = I('post.');
            $table = strtolower($post['table']);
            $save= $post['save'];
            
            $id= $post['id'];//这个id必须是数组
            $id= implode("','",$id);//用逗号分隔
            $where = $table."_id in('".$id."')";
            $model = M($table);
            $result=$model->where($where)->save($save);
            
            if($result!==false){
                //没有出错
                
                $res['res'] = $result;
                $res['msg'] = $result;
                
            }else{
                //失败
                $res['res'] = $result;
                $res['msg'] = $result;
                $res['sql'] = $model->_sql();
            }
            
            
        }else{
            //没有post
            $res['res'] = -1;
            $res['msg'] = 'no data';
        }
        echo json_encode($res);
        
        
    }
    
    
    
    /**
    * 统一上传接口
    * 上传单个文件
    */
    
    public function upFile(){
        
        if (IS_POST) {
            
            
            $file = $_FILES['file'];
            
            if (!$file['error']) {
                //定义配置
                
                $cfg = [];
                //默认是管理上传路径
                //如果传了路径就使用传来的路径
                if(empty(I('post.src'))){
                    //默认路径
                    $cfg['rootPath']=WORKING_PATH . __UPLOAD__ADMIN__;
                }else{
                    //传来的路径
                    $cfg['rootPath']=WORKING_PATH .'/Public/Upload/'.I('post.src') ;
                    // $cfg['autoSub']=false;
                    // $cfg['hash']=false;
                    // $cfg['saveName']='';
                }
                
                //实例化上传类
                $upload = new \Think\Upload($cfg);
                //开始上传
                $info = $upload -> uploadOne($file);
                //判断是否上传成功
                if ($info) {
                    //图片地址
                    if(empty(I('post.src'))){
                        //默认路径
                        $img_url = __ROOT__ . __UPLOAD__ADMIN__. $info['savepath'] . $info['savename'];
                    }else{
                        //传来的路径
                        $img_url = UPLOAD_ROOT_PATH . I('post.src') . $info['savepath'] . $info['savename'];
                    }
                    
                    
                    
                    $result['code'] = 0;
                    $result['msg'] = '成功';
                    $result['data'] = array();
                    $result['data']['src'] = $img_url;
                    
                } else {
                    $result['code'] = 'error';
                    $result['msg'] = '失败，上传错误';
                    $result['cfg'] = $cfg;
                }
                
            } else {
                $result['code'] = 'error';
                $result['msg'] = '失败，图片错误';
            }
            echo json_encode($result);
        } else {
            $url = U('Index/index');
            echo "<script>top.location.href='$url'</script>";
        }
        
        
    }
    
    
}