<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface ConversationInterface
{
    public function listStep();
    public function getOnGoing();
    public function getPast();
    public function saveConversation(Request $request);
    public function updateConversation(Request $request, $id);
    public function addConversation(Request $request);
    public function saveComment(Request $request);
    public function saveStepConversation(Request $request);
    public function saveOkrConversation(Request $request);
    public function findConversationById($id);
    public function deleteConversationTask($id);
    public function deleteConversation($id);
}
