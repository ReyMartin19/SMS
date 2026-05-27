<?php

namespace App\Livewire\Admin\Academic;

use App\Models\SchoolYear;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('School Years')]
class SchoolYearManager extends Component
{
    public function mount(): void
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Access denied.');
        }
    }

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $start_date = '';
    public string $end_date = '';
    public bool $is_active = false;

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $year = SchoolYear::findOrFail($id);
        $this->editingId  = $year->id;
        $this->name       = $year->name;
        $this->start_date = $year->start_date->format('Y-m-d');
        $this->end_date   = $year->end_date->format('Y-m-d');
        $this->is_active  = $year->is_active;
        $this->showForm   = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'       => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'is_active'  => 'boolean',
        ]);

        if ($this->is_active) {
            SchoolYear::query()->update(['is_active' => false]);
        }

        SchoolYear::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'       => $this->name,
                'start_date' => $this->start_date,
                'end_date'   => $this->end_date,
                'is_active'  => $this->is_active,
            ]
        );

        $this->resetForm();
        session()->flash('success', 'School year saved.');
    }

    public function setActive(int $id): void
    {
        SchoolYear::query()->update(['is_active' => false]);
        SchoolYear::findOrFail($id)->update(['is_active' => true]);
        session()->flash('success', 'Active school year updated.');
    }

    public function delete(int $id): void
    {
        SchoolYear::findOrFail($id)->delete();
        session()->flash('success', 'School year deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm   = false;
        $this->editingId  = null;
        $this->name       = '';
        $this->start_date = '';
        $this->end_date   = '';
        $this->is_active  = false;
    }

    public function render()
    {
        return view('livewire.admin.academic.school-year-manager', [
            'schoolYears' => SchoolYear::orderByDesc('is_active')->orderByDesc('start_date')->get(),
        ]);
    }
}