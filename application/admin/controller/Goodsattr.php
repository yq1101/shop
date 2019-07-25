<?php
namespace app\admin\controller;
use Request;
use Db;
class Goodsattr extends Common
{
    public function list()
    {   
        $g_id=Request::get('g_id');
        $this->assign('g_id',$g_id);
    	return $this->fetch();
    }

    public function add()
    {
        return $this->fetch();
    }
    public function show1(){
        $data=Request::post();
        $g_id=$data['g_id'];
        $arr=Db::query("select id,name from s_attr_category");
        $arr1=Db::query("select attr_cate from s_goods where g_id=$g_id");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        if (!empty($arr1)) {
            $json['default']=$arr1;
            // var_dump($json);            
        }
        echo json_encode($json);
        die;
    }
    public function show2(){
        $data=Request::post();
        $g_id=$data['g_id'];
        $attrcate_id=$data['attrcate_id'];
        $arr=Db::query("select attrb.id as ab_id,attrb.name as ab_name,attrd.id as ad_id,attrd.name as ad_name from s_attr_details as attrd join s_attribute as attrb on attrb.id=attrd.attr_id where attrb.attrcate_id='$attrcate_id'");
        $newarr=[];
        foreach ($arr as $key => $value) {
            // $newarr[$value['category_id']]=$value['p_c_name'];//二维数组
            $newarr[$value['ab_name']][]=$value;//三维数组
        }
        $json=['code'=>'0','status'=>'ok','data'=>$newarr];
        $arr1=Db::query("select attr_details_id from s_goods_attr where goods_id='$g_id'");
        $json['default']=$arr1;
        echo json_encode($json);
        die;
    }

    // public function show3(){
    //     $data=Request::post();
    //     $g_id=$data['g_id'];
    //     // echo $g_id;die;
    //     $arr=Db::query("select id,name from s_attr_category");
    //     $arr1=Db::query("select attr_cate from s_goods where g_id=$g_id");
    //     $json=['code'=>'0','status'=>'ok','data'=>$arr];
    //     if (!empty($arr1)) {
    //         $json['default']=$g_id;            
    //     }
    //     echo json_encode($json);
    //     die;
    // }

    public function addAction(){
        $data=Request::post();
        $g_id=$data['g_id'];
        $attrcate_id=$data['attrcate_id'];
        // var_dump($g_id);
        // die;
        $id=$data['id'];
        $id=explode(",", $data['id']);
        // var_dump($id);die;
        // $bb=Db::query("select attr_id from s_attr_details where id='$id'");
        
        Db::startTrans();
        try {
            $arr1=Db::query("update s_goods set `attr_cate`='$attrcate_id' where g_id='$g_id'");
                Db::query("delete from s_goods_attr where goods_id in ($g_id)");
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }


        if ($id!=NULL) {
                foreach ($id as $key => $value) {
                    // var_dump($value);
                    $bb=Db::query("select attr_id from s_attr_details where id='$value'");
                    $attr_id=$bb[0]['attr_id'];
                    $arr=Db::query("insert into s_goods_attr (`goods_id`,`attr_details_id`,`attr_id`) values ('$g_id','$value','$attr_id')");            
                }
                
                $json=['code'=>'0','status'=>'ok','data'=>'添加成功'];
                echo json_encode($json);
                die;    
            }
        }
        
}   
