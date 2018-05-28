<?php

namespace App\models;
use App\Models\BaseModel;

class User extends BaseModel
{
    protected $table = 'wechat_user';
    public $timestamps = false;
}
