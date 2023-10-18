<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CsvImportEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $csvFile;
    public $timeAgo;
    public $timeSet;

    /**
     * Create a new event instance.
     */
    public function __construct($name, $csvFile, $timeAgo, $timeSet)
    {
        $this->name = $name;
        $this->csvFile = $csvFile;
        $this->timeAgo = $timeAgo;
        $this->timeSet = $timeSet;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('csvImport-channel'),
        ];
    }

    public function broadcastAs()
    {
        return 'csvImport-event';
    }
}
