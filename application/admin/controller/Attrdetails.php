<?php
namespace app\admin\controller;
use Request;
use Db;
class Attrdetails extends Common
{
    public function list()
    {   
        $attr_id=Request::get();
        $this->assign('id',$attr_id);
    	return $this->fetch();
    }

    public function show()
    {   
        $data=Request::post();
        $aid=$data['aid'];
    	$arr=Db::query("select deta.id as d_id,attr.id as a_id,attr.name as a_name,deta.name as d_name from s_attribute as attr join s_attr_details as deta on attr.id=deta.attr_id where attr_id='$aid'");
        // var_dump($arr);die;
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($json);
        die;
    }
    // public function show1(){
    //     $data=Request::post();
    //     $id=$data['aid'];
    //     $arr=Db::query("select * from s_attribute where attrcate_id=$id");
    //     $json=['code'=>'0','status'=>'ok','data'=>$arr];
    //     echo json_encode($json);
    //     die;
    // }
    public function addaction(){
        $data=Request::post();
        // var_dump($data);
        // die;
        $attr_name=$data['attr_name'];
        $aid=$data['aid'];
        $arr=Db::query("select * from s_attr_details where attr_id='$aid' and name='$attr_name'");
        // var_dump(empty($arr));
        // die;
        if (empty($arr)) {
            $arr=Db::query("insert into s_attr_details (`attr_id`,`name`) value ('$aid','$attr_name')");
            $json=['code'=>'0','status'=>'ok','data'=>$arr];
            echo json_encode($json);
            die;
        }else{
            $json=['code'=>'1','status'=>'error','data'=>"该属性下的详情已经存在"];
            echo json_encode($json);
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
        $arr=Db::query("delete from s_attr_details where id=$id");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        echo json_encode($json);
        die;
    }

}