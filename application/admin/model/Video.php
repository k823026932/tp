<?php
namespace app\admin\model;
use think\Model;
use think\Controller;
use think\Db;
use traits\model\SoftDelete;
class Video extends Model
{
    //软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}