<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LabelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Label::class, 'label');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $labels = Label::orderBy('id')->paginate(15);
        return view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $label = new Label();
        return view('label.create', compact('label'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => 'required|unique:labels|max:255',
                'description' => 'nullable|max:1000'
            ],
            [
                'required' => __('labels.validation_required'),
                'name.unique' => __('labels.validation_name_unique'),
                'name.max' => __('labels.validation_name_max'),
                'description.max' => __('labels.validation_description_max')
            ]
        );

        $label = new Label();
        $label->fill($validated)->save();
        flash(__('labels.flash_stored'))->success();
        return redirect()->route('labels.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {
        $validated = $this->validate(
            $request,
            [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('labels', 'name')->ignore($label->id)
                ],
                'description' => 'nullable|max:1000'
            ],
            [
                'required' => __('labels.validation_required'),
                'name.unique' => __('labels.validation_name_unique'),
                'name.max' => __('labels.validation_name_max'),
                'description.max' => __('labels.validation_description_max')
            ]
        );

        $label->fill($validated)->save();
        flash(__('labels.flash_updated'))->success();
        return redirect()->route('labels.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        if ($label->tasks()->exists()) {
            flash(__('labels.flash_error'))->error();
            return back();
        }

        $label->delete();
        flash(__('labels.flash_deleted'))->success();
        return redirect()->route('labels.index');
    }
}
