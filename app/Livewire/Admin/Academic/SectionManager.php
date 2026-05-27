<?php

namespace App\Livewire\Admin\Academic;

use App\Models\GradeLevel;
use App\Models\Section;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sections')]
class SectionManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public ?int $gradeLevelFilter = null;

    public int $grade_level_id = 0;
    public string $name = '';
    public string $room_number = '';
    public ?int $capacity = null;

    public $gradeLevels = [];

    public function mount(): void
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Access denied.');
        }

        $this->gradeLevels = GradeLevel::orderBy('order')->get();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $section = Section::findOrFail($id);
        $this->editingId       = $section->id;
        $this->grade_level_id  = $section->grade_level_id;
        $this->name            = $section->name;
        $this->room_number     = $section->room_number ?? '';
        $this->capacity        = $section->capacity;
        $this->showForm        = true;
    }

    public function save(): void
    {
        $this->validate([
            'grade_level_id' => 'required|exists:grade_levels,id',
            'name'           => 'required|string|max:255',
            'room_number'    => 'nullable|string|max:255',
            'capacity'       => 'nullable|integer|min:1',
        ]);

        Section::updateOrCreate(
            ['id' => $this->editingId],
            [
                'grade_level_id' => $this->grade_level_id,
                'name'           => $this->name,
                'room_number'    => $this->room_number ?: null,
                'capacity'       => $this->capacity,
            ]
        );

        $this->resetForm();
        session()->flash('success', 'Section saved.');
    }

    public function delete(int $id): void
    {
        Section::findOrFail($id)->delete();
        session()->flash('success', 'Section deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm       = false;
        $this->editingId      = null;
        $this->grade_level_id = 0;
        $this->name           = '';
        $this->room_number    = '';
        $this->capacity       = null;
    }

    public function render()
    {
        $sections = Section::with('gradeLevel')
            ->when($this->gradeLevelFilter, fn ($q) => $q->where('grade_level_id', $this->gradeLevelFilter))
            ->orderBy('grade_level_id')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.academic.section-manager', compact('sections'));
    }
}