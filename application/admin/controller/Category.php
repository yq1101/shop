<?php
namespace app\admin\controller;
use Request;
use Db;
class Category extends Common
{
	public function list()
    {
    	return $this->fetch();
    }

    private function getTree($array, $pid =0, $level = 0){
    //声明静态数组,避免递归调用时,多次声明导致数组覆盖
    // static $list = [];
    echo "<ul id=ul1>";
    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){

            $value['name'] = $value['name'];
            $m_id=$value['id'];
            // 输出 名称
            echo "<li value='$m_id' id=li1>".$value['name']."&nbsp"."<a style='text-decoration:none' title='删除' onclick='dele(".$value['id'].")'><i class='Hui-iconfont'>&#xe706;</i></a>&nbsp|&nbsp<a style='text-decoration:none' title='编辑' onclick='modaldemo(".$value['id'].")'><i class='Hui-iconfont'>&#xe6df;</i></a></li>";
            //把数组放到list中
            // $list[] = $value;
            //把这个节点从数组中移除,减少后续递归消耗
            //unset($array[$key]);
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            $this->getTree($array, $value['id'], $level+1);
        }
        
    }
    echo "</ul>";
}


    public function show()
    {	
    	$arr=Db::query("select * from s_category");
        $this->getTree($arr);


    }

    
    public function addaction(){
        $data=Request::post();
        $name=Request::post('name');
        $pid=Request::post('pid');
        $validate = new \app\admin\validate\Category;
            if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $arr=Db::query("select id from s_category where name='$name'");
        if (!empty($arr)) {
            $json=['code'=>'1','status'=>'error','data'=>'重名了'];
            return json($json);
        }else{
            $arr=Db::query("insert into `s_category` (`name`,`pid`) values ('$name','$pid')");
                $json=['code'=>'0','status'=>'ok','data'=>$arr];
                return json($json);
        }
    }

    public function delete(){
        $data=Request::post();
        $id=$data['id'];
        $validate = new \app\admin\validate\Permissioncate1;
        if (!$validate->check($data)) {
            $json=['code'=>'302','status'=>'error','data'=>$validate->getError()];
           echo json_encode($json); 
        }
        Db::query("delete from s_category where id='$id'");
        $this->del($id);
        $json=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo json_encode($json);   
    }
    function del($id){
        $arr=Db::query("select * from s_category where pid='$id'");
        if (empty($arr)) {
            
        }else{
            foreach ($arr as $key => $value) {
                $id1=$value['id'];
                $arr=Db::query("delete from s_category where id=$id1");
                $this->del($value['id']);
               
            }
        }
    }

    public function updateAction(){
        $data=Request::post();
        $id=$data['id'];
        $name=$data['name'];
        $bb=Db::query("select * from s_category where name='$name'");
        if (empty($bb)) {
            $arr=Db::query("update s_category set name='$name' where id='$id'");
            $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            return $json;
        }else{
            if($bb[0]['id']!=$id){
                $json=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
                return $json;
            }else{
                $arr=Db::query("update s_category set name='$name' where id='$id'");
                $json=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                return $json;              
            }
        }
    }
}