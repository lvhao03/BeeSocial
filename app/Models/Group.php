<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\GroupMember;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'group';
    protected $fillable = [
        'group_name',
    ];

    public function group_members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }
}
