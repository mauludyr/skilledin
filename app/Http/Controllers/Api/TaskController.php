<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\TaskInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskInterface;

    public function __construct(TaskInterface $taskInterface)
    {
        $this->taskInterface = $taskInterface;
    }

    public function showAll() {
        return $this->taskInterface->getAllTask();
    }

    public function showTaskStarred() {
        return $this->taskInterface->getStarred();
    }

    public function showStatus() {
        return $this->taskInterface->getStatusTask();
    }

    public function showLabel() {
        return $this->taskInterface->getLabelTask();
    }

    public function showById($id)
    {
        return $this->taskInterface->findTaskById($id);
    }

    public function store(Request $request)
    {
        return $this->taskInterface->saveTask($request);
    }

    public function storeStatus(Request $request)
    {
        return $this->taskInterface->saveStatusTask($request);
    }

    public function storeLabel(Request $request)
    {
        return $this->taskInterface->saveLabelTask($request);
    }

    public function update(Request $request, $id)
    {
        return $this->taskInterface->updateTask($request, $id);
    }

    public function updateAction(Request $request, $type, $id)
    {
        return $this->taskInterface->updateTaskAction($request, $type, $id);
    }

    public function destroy($id)
    {
        return $this->taskInterface->deleteTask($id);
    }
}
