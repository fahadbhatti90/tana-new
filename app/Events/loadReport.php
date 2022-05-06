<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class loadReport implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $title;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param $title
     * @param $message
     */
    public function __construct($title, $message)
    {
        $this->title  = $title;
        $this->message  = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notification');
    }

    /**
     * Set event that is broadcast as.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'load-report';
    }
}
