<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Celula;
use App\Models\Student;

/**
 * Momento em que Uma Célula Ocupada passa a estar vazia
 * Evento realizado por uma desmarcação de Student
 * Célula que possuía aula passa a não possuir
 * 
 */
class ExitAulaOnCelula
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $celula;
    public $student;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Celula $celula, Student $student)
    {
        $this->celula = $celula;
        $this->student = $student;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
