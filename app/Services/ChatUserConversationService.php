<?php

namespace App\Services;

use App\Models\ChatUserConversation;
use App\Models\ChatMessage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ChatUserConversationService
{
    public function getOrCreateActiveConversation($user)
    {
        $conversation = $this->getActiveConversation($user);

        if (!$conversation) {
            $conversation = $this->createConversation($user);
        }

        $conversation->load('messages');

        return $conversation;
    }

    public function deactivateConversation($user)
    {
        $conversation = $this->getActiveConversation($user);

        if (!$conversation) {
            throw new ModelNotFoundException("Nie znaleziono aktywnej konwersacji dla użytkownika.");
        }

        $conversation->is_active = false;
        $conversation->save();

        return $conversation;
    }

    private function getActiveConversation($user)
    {
        return ChatUserConversation::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('messages')
            ->first();
    }

    private function createConversation($user)
    {
        return ChatUserConversation::create([
            'user_id' => $user->id,
            'session_id' => Str::uuid(),
            'is_active' => true,
        ]);
    }
}
