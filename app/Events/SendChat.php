<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $message_text;
    public $receiver_id;
    public $room_id;
    public $image_url;
    public $sent_date;
    public function __construct($message, $receiver_id, $room_id, $sent_date)
    {
        $this->message_text = $message;
        $this->receiver_id = $receiver_id;
        $this->room_id = $room_id;
        $this->image_url = session('receiver_image');
        $this->sent_date = $sent_date;
    }
    
    public function broadcastAs(){
        return 'SendChat';
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('private.' . $this->room_id);
    }
}
