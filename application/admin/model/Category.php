<?php
/**
 * @Author: Marte
 * @Date:   2018-02-06 09:02:54
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-07 19:36:14
 */
namespace app\admin\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class Category extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    //查询所有板块
    public function sel()
    {
        return Db::name('category')->select();
    }

    //分页偏移量查询
    public function catLimit($limit)
    {
        return Db::name('category')->limit($limit)->select();
    }
    //大板块查询
    public function big($where)
    {
        return Db::name('category')->where($where)->select();
    }

    //添加板块
    public function addBord($name,$sel)
    {
        return Db::name('category')->insert(['bkname'=>$name, 'parentid'=>$sel]);
    }
    //根据小板块id查询大板块名
    public function selda($cid)
    {
        return Db::name('category')->where('cid','=',$cid)->select();
    }
}