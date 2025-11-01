<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function getCategoryTasks($categoryId)
    {
        $user_id = Auth::user()->id;
        $tasks = Category::findOrFail($categoryId)->tasks;
        if ($tasks[1]->user_id != $user_id)
            return response()->json(
                [
                    'message' => 'Unauthorized'
                ],
                403
            );
        return response()->json($tasks, 200);
    }
}
