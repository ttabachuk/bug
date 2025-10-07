<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $id;
    protected $username;
    protected $roleId;
    protected $projectId;
    protected $password;
    protected $name;
}
