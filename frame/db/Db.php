<?php
/**
 * 先写着，看完设计模式再完善
 */
namespace frame\db;

// 装饰模式去判断扩展或者类是否存在
abstract class My_Db
{
    abstract public function search();
}