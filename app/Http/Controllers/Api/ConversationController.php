<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\ConversationInterface;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    protected $conversationInterface;

    public function __construct(ConversationInterface $conversationInterface)
    {
        $this->conversationInterface = $conversationInterface;
    }

    public function getStep() {
        return $this->conversationInterface->listStep();
    }

    public function ongoing() {
        return $this->conversationInterface->getOnGoing();
    }

    public function past() {
        return $this->conversationInterface->getPast();
    }

    public function store(Request $request)
    {
        return $this->conversationInterface->saveConversation($request);
    }

    public function update(Request $request, $id)
    {
        return $this->conversationInterface->updateConversation($request, $id);
    }

    public function add(Request $request)
    {
        return $this->conversationInterface->addConversation($request);
    }

    public function addComment(Request $request)
    {
        return $this->conversationInterface->saveComment($request);
    }

    public function addStep(Request $request)
    {
        return $this->conversationInterface->saveStepConversation($request);
    }

    public function addOkr(Request $request)
    {
        return $this->conversationInterface->saveOkrConversation($request);
    }

    public function showById($id)
    {
        return $this->conversationInterface->findConversationById($id);
    }

    public function destroyConversationTask($id)
    {
        return $this->conversationInterface->deleteConversationTask($id);
    }

    public function destroyConversation($id)
    {
        return $this->conversationInterface->deleteConversation($id);
    }
}
