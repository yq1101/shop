<?php
namespace app\admin\controller;
use Request;
use Db;
class Attributecate extends Common
{
	public function list()
    {
    	return $this->fetch();
    }

    public function show()
    {	
    	$arr=Db::query("select id,name from s_attr_category ");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($arr);
    	die;
    }

    public function addAction(){
        $data=Request::post();
        $a_name=$data['a_name'];
        $arr=Db::query("select * from s_attr_category where name='$a_name'");
        if (empty($arr)) {
            $arr=Db::query("insert into s_attr_category (`name`) values ('$a_name')");
            $arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
            echo $json=json_encode($arr);
            die;
        }else{
            $arr=['code'=>'1','status'=>'error','data'=>'已有该分类属性'];
            echo json_encode($arr);
            die;
        }
    }
    public function delete(){
        $data=Request::post();
        $validate = new \app\admin\validate\Permissioncate1;
        if (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $id=$data['id'];       
        $arr=Db::query("select id from s_attribute where attrcate_id=$id");
        // var_dump($arr);
        // die;
        Db::query("delete from s_attr_category where id=$id");
        foreach ($arr as $key => $value) {
            $id1=$value['id'];
            Db::query("delete from s_attr_details where attr_id=$id1");
            Db::query("delete from s_attribute where id=$id1");
        }        
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;

    }
    public function updateaction(){
        $data=Request::post();
        $id=$data['id'];
        $name=$data['name'];
        $arr=Db::query("select * from s_attr_category where id=$id");
        if (empty($arr)) {
            $arr=Db::quer("update set s_attr_category `name`='$name' where id='$id'");
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            echo $json=json_encode($arr);
            die;
        }else{
            if ($arr[0]['id']==$id) {
                $arr=Db::query("update s_attr_category  set `name`='$name' where id='$id'");
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
                die;
            }else{
                $arr=['code'=>'1','status'=>'error','data'=>'修改失败'];
                echo $json=json_encode($arr);
                die;
            }
        }
    }
}