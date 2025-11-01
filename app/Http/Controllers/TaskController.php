<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class TaskController extends Controller
{
    public function getAllTasks()
    {
        $tasks = Task::all();
        return response()->json($tasks, 200);
    }

    public function getTasksByPriority(Request $request)

    {
        $order = $request->query('order', 'desc'); // default 'desc' (تنازلي)

        if ($order === 'asc') {
            $priorityOrder = "'Low', 'Medium', 'High'";
        } else {
            $priorityOrder = "'High', 'Medium', 'Low'";
        }
        $tasks = Auth::user()->tasks()->orderdByRaw("FIELD(priority,$priorityOrder)")->get();
        return response()->json($tasks, 200);
    }


    public function index()
    {
        $tasks = Auth::user()->tasks;
        return response()->json($tasks, 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $user_id = Auth::user()->id;
        $validatedData = $request->validated();
        $validatedData['user_id'] = $user_id;
        $task = Task::create($validatedData);
        return response()->json($task, 201);
    }
    public function update(UpdateTaskRequest $request, $id)
    {
        $user_id = Auth::user()->id;
        $task = Task::findOrFail($id);
        if ($task->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        $task->update($request->validated());
        return response()->json($task, 200);
    }

    public function show($id)
    {
        $user_id = Auth::user()->id;
        $task = Task::findOrFail($id);
        if ($task->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        return response()->json($task, 200);
    }

    public function destroy($id)
    {
        $user_id = Auth::user()->id;
        $task = Task::findOrFail($id);
        if ($task->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        $task->delete();
        return response()->json(null, 204);
    }
    // public function getTaskUser($id)
    // {
    //     // $user_id = Auth::user()->id;
    //     $user = Task::findOrFail($id)->user;
    //     //     if($user->id != $user_id)
    //     //     return response()->json(
    //     // [
    //     //     'message'=>'Un'
    //     // ], 200);
    //     return response()->json($user, 200);
    // }

    public function addCategoriesToTask(Request $request, $taskId)
    {
        $user_id = Auth::user()->id;
        $task = Task::findOrFail($taskId);
        if ($task->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        $task->categories()->attach($request->category_id);
        return response()->json('Category attached successfully', 200);
    }

    public function getTaskCategories($taskId)
    {
        $user_id = Auth::user()->id;
        $task = Task::findOrFail($taskId);
        if ($task->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        $categories = $task->categories;
        return response()->json($categories, 200);
    }

    public function addToFavorites($taskId)
    {
        Task::findOrFail($taskId);
        Auth::user()->favoriteTasks()->syncWithoutDetaching($taskId);
        return response()->json(['message' => 'task add to favorite'], 200);
    }

    public function getFavoriteTasks()
    {
        $favorites = Auth::user()->favoriteTasks;
        return response()->json($favorites, 200);
    }

    public function removeFromFavorites($taskId)
    {
        Task::findOrFail($taskId);
        Auth::user()->favoriteTasks()->detach($taskId);
        return response()->json(['message' => 'task removed from favorite'], 200);
    }
}
