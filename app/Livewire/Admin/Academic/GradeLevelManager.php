<?php

namespace App\Livewire\Admin\Academic;

use App\Models\GradeLevel;
use Livewire\Component;

class GradeLevelManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $name = '';
    public int $order = 1;
    public string $type = 'elementary';

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $level = GradeLevel::findOrFail($id);
        $this->editingId = $level->id;
        $this->name      = $level->name;
        $this->order     = $level->order;
        $this->type      = $level->type;
        $this->showForm  = true;
    }

    public function save(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'order' => 'required|integer|min:1',
            'type'  => 'required|in:elementary,junior_high,senior_high',
        ]);

        GradeLevel::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'  => $this->name,
                'order' => $this->order,
                'type'  => $this->type,
            ]
        );

        $this->resetForm();
        session()->flash('success', 'Grade level saved.');
    }

    public function delete(int $id): void
    {
        GradeLevel::findOrFail($id)->delete();
        session()->flash('success', 'Grade level deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm  = false;
        $this->editingId = null;
        $this->name      = '';
        $this->order     = 1;
        $this->type      = 'elementary';
    }

    public function render()
    {
        return view('livewire.admin.academic.grade-level-manager', [
            'gradeLevels' => GradeLevel::orderBy('order')->get(),
        ]);
    }
}