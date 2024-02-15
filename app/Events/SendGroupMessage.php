<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendGroupMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */

    public $message_text;
    public $sender_id;
    public $sent_date;
    public $group_id;
    public function __construct($message, $sender_id, $group_id,  $sent_date)
    {
        $this->message_text = $message;
        $this->sender_id = $sender_id;
        $this->group_id = $group_id;
        $this->sent_date = $sent_date;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastAs(){
        return 'SendGroupChat';
    }



    public function broadcastOn()
    {
        return  new PresenceChannel('group.' . $this->group_id);
    }
}
