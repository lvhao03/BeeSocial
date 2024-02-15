<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'group_members';
    protected $fillable = [
        'group_id',
        'member_id'
    ];
}
