<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ChatUserConversationService;
use App\Http\Requests\DeactivateConversationRequest;
use App\Http\Resources\ChatUserConversationResource;

class ChatUserConversationController extends Controller
{
    protected $service;

    public function __construct(ChatUserConversationService $service)
    {
        $this->service = $service;
    }

    public function getActiveConversation(Request $request)
    {
        $conversation = $this->service->getOrCreateActiveConversation($request->user());

        return new ChatUserConversationResource($conversation);
    }

    public function deactivateConversation(Request $request)
    {
        $conversation = $this->service->deactivateConversation($request->user());
    
        return new ChatUserConversationResource($conversation);
    }
}
