@extends('layouts.app')

@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">{{ __('tasks.header') }}</h1>
        <div class="w-full flex items-center">
            <div>
                {{ Form::open(['route' => 'tasks.index', 'method' => 'get']) }}
                <div class="flex">
                    <div>
                        {{ Form::select('filter[status_id]', $taskStatuses, $filter['status_id'] ?? null, ['class' => 'rounded border-gray-300', 'placeholder' => __('tasks.filter_placeholder.status')]) }}
                    </div>
                    <div>
                        {{ Form::select('filter[created_by_id]', $users, $filter['created_by_id'] ?? null, ['class' => 'ml-2 rounded border-gray-300', 'placeholder' => __('tasks.filter_placeholder.created_by')]) }}
                    </div>
                    <div>
                        {{ Form::select('filter[assigned_to_id]', $users, $filter['assigned_to_id'] ?? null, ['class' => 'ml-2 rounded border-gray-300', 'placeholder' => __('tasks.filter_placeholder.assigned_to')]) }}
                    </div>
                    <div>
                        {{ Form::submit(__('tasks.filter_button'), ['class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

            <div class="ml-auto">
                @can('create', App\Models\Task::class)
                    <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                        {{ __('tasks.create_task') }}
                    </a>
                @endcan
            </div>
        </div>

        <table class="mt-4">
            <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>{{ __('tasks.id') }}</th>
                <th>{{ __('tasks.status') }}</th>
                <th>{{ __('tasks.name') }}</th>
                <th>{{ __('tasks.created_by') }}</th>
                <th>{{ __('tasks.assigned_to') }}</th>
                <th>{{ __('tasks.created_at') }}</th>
                @can('viewActions', App\Models\Task::class)
                    <th>{{ __('tasks.actions') }}</th>
                @endcan
            </tr>
            </thead>
            @foreach($tasks as $task)
                <tr class="border-b border-dashed text-left">
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->status->name }}</td>
                    <td>
                        <a class="text-blue-600 hover:text-blue-900" href="{{ route('tasks.show', $task) }}">
                            {{ $task->name }}
                        </a>
                    </td>
                    <td>{{ $task->createdBy->name }}</td>
                    <td>{{ $task->assignedTo->name ?? '' }}</td>
                    <td>{{ $task->created_at->format('d.m.Y') }}</td>
                    <td>
                        @can('delete', $task)
                            <a data-confirm="{{ __('tasks.delete_confirmation') }}" data-method="delete" href="{{ route('tasks.destroy', $task) }}" class="text-red-600 hover:text-red-900">
                                {{ __('tasks.delete') }}
                            </a>
                        @endcan
                        @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}" class="text-blue-600 hover:text-blue-900">
                                {{ __('tasks.edit') }}
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    </div>
@endsection
