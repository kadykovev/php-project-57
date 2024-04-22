<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TaskStatus::class, 'task_status');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taskStatuses = TaskStatus::orderBy('id')->paginate(15);
        return view('task_status.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $taskStatus = new TaskStatus();
        return view('task_status.create', compact('taskStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => 'required|unique:task_statuses|max:255'
            ],
            [
                'required' => __('task_statuses.validation_required'),
                'unique' => __('task_statuses.validation_unique'),
                'max' => __('task_statuses.validation_max'),
            ]
        );

        $taskStatus = new TaskStatus();
        $taskStatus->fill($validated)->save();
        flash(__('task_statuses.flash_stored'))->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $taskStatus)
    {
        return view('task_status.edit', compact('taskStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskStatus $taskStatus)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('task_statuses', 'name')->ignore($taskStatus->id)
                ]
            ],
            [
                'required' => __('task_statuses.validation_required'),
                'unique' => __('task_statuses.validation_unique'),
                'max' => __('task_statuses.validation_max'),
            ]
        );

        $taskStatus->fill($validated)->save();
        flash(__('task_statuses.flash_updated'))->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $taskStatus)
    {
        if ($taskStatus->tasks()->exists()) {
            flash(__('task_statuses.flash_error'))->error();
            return back();
        }

        $taskStatus->delete();
        flash(__('task_statuses.flash_deleted'))->success();
        return redirect()->route('task_statuses.index');
    }
}
