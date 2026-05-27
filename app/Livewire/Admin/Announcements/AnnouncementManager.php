<?php

namespace App\Livewire\Admin\Announcements;

use App\Models\ActivityLog;
use App\Models\Announcement;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

#[Title('Announcements')]
class AnnouncementManager extends Component
{
    use WithPagination;

    public function mount(): void
    {
        if (!in_array(auth()->user()->role, ['superadmin', 'admin'])) {
            abort(403, 'Access denied.');
        }
    }

    // Form fields
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $title = '';
    public string $body = '';
    public string $audience = 'all';
    public bool $is_pinned = false;
    public ?string $published_at = null;
    public ?string $expiry_date = null;

    // Search and Filters
    public string $search = '';
    public string $filterAudience = '';
    public string $filterPinned = '';
    public string $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterAudience' => ['except' => ''],
        'filterPinned' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterAudience(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPinned(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
        // Default published_at to today so it's prefilled nicely
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    public function openEdit(int $id): void
    {
        $this->resetForm();
        $announcement = Announcement::findOrFail($id);

        $this->editingId = $announcement->id;
        $this->title = $announcement->title;
        $this->body = $announcement->body;
        $this->audience = $announcement->audience;
        $this->is_pinned = $announcement->is_pinned;
        $this->published_at = $announcement->published_at ? $announcement->published_at->format('Y-m-d\TH:i') : null;
        $this->expiry_date = $announcement->expiry_date ? $announcement->expiry_date->format('Y-m-d') : null;

        $this->showForm = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate([
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'audience'    => 'required|in:all,admin,teacher,student,parent',
            'is_pinned'   => 'boolean',
            'published_at' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:today',
        ]);

        $isNew = ! $this->editingId;

        $announcement = Announcement::updateOrCreate(
            ['id' => $this->editingId],
            [
                'user_id'      => Auth::id(),
                'title'        => $this->title,
                'body'         => $this->body,
                'audience'     => $this->audience,
                'is_pinned'    => $this->is_pinned,
                'published_at' => $this->published_at ?: null,
                'expiry_date'  => $this->expiry_date ?: null,
            ]
        );

        ActivityLog::log(
            $isNew ? 'created_announcement' : 'updated_announcement',
            'announcements',
            ($isNew ? 'Created' : 'Updated') . " announcement: {$this->title}",
            [
                'subject_type' => Announcement::class,
                'subject_id'   => $announcement->id,
            ]
        );

        session()->flash('success', $isNew ? 'Announcement created successfully.' : 'Announcement updated successfully.');

        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $announcement = Announcement::findOrFail($id);

        ActivityLog::log(
            'deleted_announcement', 'announcements',
            "Deleted announcement: {$announcement->title}",
            [
                'subject_type' => Announcement::class,
                'subject_id'   => $id,
            ]
        );

        $announcement->delete();

        session()->flash('success', 'Announcement deleted successfully.');
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->title = '';
        $this->body = '';
        $this->audience = 'all';
        $this->is_pinned = false;
        $this->published_at = null;
        $this->expiry_date = null;
    }

    public function render()
    {
        $query = Announcement::with('author')
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterAudience, function ($q) {
                $q->where('audience', $this->filterAudience);
            })
            ->when($this->filterPinned !== '', function ($q) {
                $q->where('is_pinned', (bool)$this->filterPinned);
            });

        // Status filter: draft, active, expired
        if ($this->filterStatus === 'draft') {
            $query->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '>', now());
            });
        } elseif ($this->filterStatus === 'active') {
            $query->whereNotNull('published_at')
                  ->where('published_at', '<=', now())
                  ->where(function ($q) {
                      $q->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>=', today());
                  });
        } elseif ($this->filterStatus === 'expired') {
            $query->whereNotNull('expiry_date')
                  ->where('expiry_date', '<', today());
        }

        $announcements = $query->orderBy('is_pinned', 'desc')
            ->latest()
            ->paginate(15);

        return view('livewire.admin.announcements.announcement-manager', [
            'announcements' => $announcements,
        ]);
    }
}
