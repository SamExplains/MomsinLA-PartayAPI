<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardSignup extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'zip', 'name', 'password', 'uuid', 'qrcode_src', 'profile_image_src', 'token', 'role', 'phone'];
    protected $hidden = ['password'];
}