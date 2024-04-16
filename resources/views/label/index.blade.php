@extends('layouts.app')

@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">{{ __('labels.header') }}</h1>

        <div>
            @can('create', App\Models\Label::class)
                <a href="{{ route('labels.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('labels.create_label') }}
                </a>
            @endcan
        </div>

        <table class="mt-4">
            <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>{{ __('labels.id') }}</th>
                <th>{{ __('labels.name') }}</th>
                <th>{{ __('labels.description') }}</th>
                <th>{{ __('labels.created_at') }}</th>
                @can('viewActions', App\Models\Label::class)
                    <th>{{ __('labels.actions') }}</th>
                @endcan
            </tr>
            </thead>
            @foreach($labels as $label)
                <tr class="border-b border-dashed text-left">
                    <td>{{ $label->id }}</td>
                    <td>{{ $label->name }}</td>
                    <td>{{ $label->description }}</td>
                    <td>{{ $label->created_at->format('d.m.Y') }}</td>
                    <td>
                        @can('delete', $label)
                            <a
                                data-confirm="{{ __('labels.delete_confirmation') }}"
                                data-method="delete"
                                class="text-red-600 hover:text-red-900"
                                href="{{ route('labels.destroy', $label) }}"
                            >
                                {{ __('labels.delete') }}
                            </a>
                        @endcan
                        @can('update', $label)
                            <a class="text-blue-600 hover:text-blue-900" href="{{ route('labels.edit', $label) }}">
                                {{ __('labels.edit') }}
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>

        {{ $labels->links() }}
    </div>
@endsection
