<?php

namespace App\Jobs\Message;

use App\Events\Message\NotificationStatusEvent;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class NotificationStatusJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Collection $users,
        private int $chatId,
        private Message $message
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->users as $user)
        {
            DB::table('message_status')->insert([
                'chat_id' => $this->chatId,
                'user_id' => $user->id,
                'message_id' => $this->message->id,
            ]);

            $count = MessageStatus::query()
                ->where('user_id', $user->id)
                ->where('is_read', false)
                ->count();

            broadcast(new NotificationStatusEvent($count, $user, $this->chatId, $this->message))->toOthers();
        }
    }
}
