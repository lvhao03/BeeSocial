<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'message';
    protected $fillable = [
        'message_text',
        'sender_id',
        'receiver_id',
        'sent_date'
    ];

}
