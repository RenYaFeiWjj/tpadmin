<?php
namespace app\common\model;

use think\Model;

class Banner extends Model
{
    protected $field = true;
    // 指定表名,不含前缀
    protected $name = 'banner';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
}
