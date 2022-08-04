<?php
namespace App\Interfaces;

use Illuminate\Http\Request;

interface TaskInterface
{
    public function getAllTask();
    public function getStarred();
    public function getStatusTask();
    public function getLabelTask();
    public function findTaskById($id);
    public function saveTask(Request $request);
    public function saveStatusTask(Request $request);
    public function saveLabelTask(Request $request);
    public function updateTask(Request $request, $id);
    public function updateTaskAction(Request $request, $action, $id);
    public function deleteTask($id);
}
