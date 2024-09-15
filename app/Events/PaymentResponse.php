<?php

namespace App\Events;

use App\Http\Controllers\AuthFrontEndApplicationController;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentResponse implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    //    public function __construct()
    //    {
    //
    //    }

    public function broadcastWith(): array
    {
        $cobranca = $this->user->getStudent()->asaas_client()->first()->asaas_cobranca()->first();
        if ($cobranca->status == 'RECEIVED') {
            $authFrontEnd = new AuthFrontEndApplicationController();
            return [
                'success' => true,
                'message' => 'Pagamento Recebido',
                'status' => 'RECEIVED',
                "step" => $authFrontEnd->getStepsByUser($this->user)
            ];
        }
        return [
            'success' => false,
            'message' => 'Pagamento Pendente',
            'status' => 'WAITING',
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('payment.' . $this->user->id),
        ];
        //        return [
        //            new PrivateChannel('public'),
        //        ];
    }
}
