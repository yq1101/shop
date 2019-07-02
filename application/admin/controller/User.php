<?php
namespace app\admin\controller;

class User extends Common
{
    public function list()
    {
    	return $this->fetch();
    }
    
}