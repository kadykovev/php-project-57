<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::exact('status_id'),
                AllowedFilter::exact('created_by_id'),
                AllowedFilter::exact('assigned_to_id'),
            ])
            ->orderBy('id')
            ->paginate(15);

        $taskStatuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $filter = $request->input('filter');
        return view('task.index', compact('tasks', 'taskStatuses', 'users', 'filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task();
        $taskStatuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');
        return view('task.create', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => 'required|unique:tasks|max:255',
                'status_id' => 'required|integer|exists:task_statuses,id',
                'description' => 'string|max:1000',
                'assigned_to_id' => 'nullable|integer',
                'label' => 'nullable|array',
            ],
            [
                'required' => __('tasks.validation_required'),
                'name.max' => __('tasks.validation_name_max'),
                'name.unique' => __('tasks.validation_name_unique'),
                'description.max' => __('tasks.validation_description_max'),
            ]
        );

        $currentUser = Auth::user();
        $task = $currentUser->createdTasks()->create($validated);
        $labels = collect($request->input('labels'))->whereNotNull();

        if ($labels->isNotEmpty()) {
            $task->labels()->attach($labels);
        }

        flash(__('tasks.flash_stored'))->success();
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $taskStatuses = TaskStatus::pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $labels = Label::pluck('name', 'id');
        return view('task.edit', compact('task', 'taskStatuses', 'users', 'labels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('tasks', 'name')->ignore($task->id)
                ],
                'description' => 'nullable|max:1000',
                'assigned_to_id' => 'nullable|integer',
                'status_id' => 'required|integer',
                'label' => 'nullable|array',
            ],
            [
                'required' => __('tasks.validation_required'),
                'name.max' => __('tasks.validation_name_max'),
                'name.unique' => __('tasks.validation_name_unique'),
                'description.max' => __('tasks.validation_description_max')
            ]
        );

        $task->fill($validated)->save();
        $labels = collect($request->input('labels'))->whereNotNull();
        $task->labels()->sync($labels);
        flash(__('tasks.flash_updated'))->success();
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->labels()->detach();
        $task->delete();
        flash(__('tasks.flash_deleted'))->success();
        return redirect()->route('tasks.index');
    }
}
