<?php
namespace app\admin\controller;
use Request;
use Db;
class Attribute extends Common
{
    public function list()
    {   
        $attrc_id=Request::get('id');
        $this->assign('attrc_id',$attrc_id);
    	return $this->fetch();
    }

    // public function show1(){
    //     $arr=Db::query("select * from s_attr_category");
    //     $json=['code'=>'0','status'=>'ok','data'=>$arr];
    //     echo json_encode($json);
    // }
    public function show()
    {   
        $data=Request::post();
        $attrc_id=$data['attrc_id'];
    	$arr=Db::query("select attr.`name` as a_name,attr.id as a_id,attr_cate.id as ac_id,attr_cate.`name` as ac_name from s_attribute as attr join s_attr_category as attr_cate on attr.attrcate_id=attr_cate.id where attrcate_id='$attrc_id'");
        // var_dump($arr);die;
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($json);
    }
    public function addAction(){
        $data=Request::post();
        $name=$data['name'];
        $attrc_id=$data['attrc_id'];
        // var_dump($name);
        // $attr_category_id=$data['attr_category_id'];

        $arr=Db::query("select * from s_attribute where name='$name' and attrcate_id='$attrc_id'");
        
        if (!empty($arr)) {

            $json=['code'=>'1','status'=>'error','data'=>"属性已经存在"];
            echo json_encode($json);
            die;
        }else{
            $arr=Db::query("insert into `s_attribute` (`name`,`attrcate_id`) values ('$name','$attrc_id')");
            $json=['code'=>'0','status'=>'ok','data'=>$arr];
            echo json_encode($json);
            die;
        }           
    }
    public function delete(){
        $data=Request::post();
        $id=$data['id'];
        $arr=Db::query("delete from s_attribute where id='$id'");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        echo json_encode($json);
        die;
    }  
}  