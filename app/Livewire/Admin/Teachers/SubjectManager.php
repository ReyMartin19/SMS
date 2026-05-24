<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectManager extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';

    public $showForm = false;
    public $subjectId;
    public $name;
    public $code;
    public $type;
    public $grade_level_id;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:subjects,code,' . $this->subjectId,
            'type' => 'required|in:elementary,junior_high,senior_high',
            'grade_level_id' => 'nullable|exists:grade_levels,id',
        ];
    }

    public function create()
    {
        $this->reset(['subjectId', 'name', 'code', 'type', 'grade_level_id']);
        $this->resetValidation();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $this->subjectId = $subject->id;
        $this->name = $subject->name;
        $this->code = $subject->code;
        $this->type = $subject->type;
        $this->grade_level_id = $subject->grade_level_id;
        $this->resetValidation();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        Subject::updateOrCreate(
            ['id' => $this->subjectId],
            [
                'name' => $this->name,
                'code' => $this->code,
                'type' => $this->type,
                'grade_level_id' => $this->grade_level_id ?: null,
            ]
        );

        $this->showForm = false;
        session()->flash('success', 'Subject saved successfully.');
    }

    public function delete($id)
    {
        Subject::findOrFail($id)->delete();
        session()->flash('success', 'Subject deleted successfully.');
    }

    public function render()
    {
        $subjects = Subject::with('gradeLevel')
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.teachers.subject-manager', [
            'subjects' => $subjects,
            'gradeLevels' => \App\Models\GradeLevel::orderBy('order')->get(),
        ]);
    }
}
