<?php

namespace App\models;

class User extends BaseModel
{
    protected $table = 'wechat_user';
    public $timestamps = false;
    public $fillable = [];
}
