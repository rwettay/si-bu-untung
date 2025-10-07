<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'id_staff';
    public $incrementing = false; // karena id_staff bukan auto increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_staff',
        'username',
        'password',
        'role',
    ];
}
