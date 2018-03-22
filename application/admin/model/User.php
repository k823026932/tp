<?php
/**
 * @Author: Marte
 * @Date:   2018-02-05 19:57:39
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-06 22:02:26
 */
namespace app\admin\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class User extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    //查询用户是否存在
    function panduan($name)
    {
        return Db::name('user')->where('username',$name)->find();
    }

    public function userLimit($limit)
    {
        return Db::name('user')->limit($limit)->select();
    }
}