<?php

namespace App\Http\Controllers;

use App\Models\Task; // Task Model eka import karanawa
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // 1. Okkoma tasks pennana function eka (GET Request)
    public function index()
    {
        return Task::orderBy('created_at', 'desc')->get();
    }

    // 2. Aluth task ekak save karana function eka (POST Request)
    public function store(Request $request)
    {
        // Data tika hari da balanawa (Validation)
        $request->validate([
            'title' => 'required|string|max:255',
            'priority' => 'in:low,medium,high',
            'category' => 'nullable|string|max:50',
            'due_date' => 'nullable|date',
        ]);

        // Database eke save karanawa
        $task = Task::create($request->all());

        return response()->json($task, 201);
    }

    // 3. Eka task ekak witharak pennana function eka (GET Request with ID)
    public function show(Task $task)
    {
        return $task;
    }

    // 4. Task ekak update karana function eka (PUT Request)
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'status' => 'in:todo,in_progress,done',
            'priority' => 'in:low,medium,high',
            'category' => 'nullable|string|max:50',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());

        return response()->json($task);
    }

    // 5. Task ekak delete karana function eka (DELETE Request)
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}