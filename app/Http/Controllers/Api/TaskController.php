<?php

namespace App\Http\Controllers\Api;

use App\Filters\TaskFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $query = Task::query();
        TaskFilter::apply($query, $request->only(['status', 'date']));
        $tasks = $query->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): TaskResource
    {
        $task = Task::create($request->all());

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->all());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
