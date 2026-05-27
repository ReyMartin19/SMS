<?php

namespace App\Livewire\Admin\Logs;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Activity Logs')]
class ActivityLogViewer extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterModule = '';
    public ?int $filterUser = null;
    public string $dateFrom = '';
    public string $dateTo = '';

    public array $expandedRows = [];

    protected $queryString = [
        'search'       => ['except' => ''],
        'filterModule' => ['except' => ''],
        'filterUser'   => ['except' => ''],
        'dateFrom'     => ['except' => ''],
        'dateTo'       => ['except' => ''],
    ];

    public function mount(): void
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Access denied. Superadmin only.');
        }
    }

    public function updatingSearch(): void    { $this->resetPage(); }
    public function updatingFilterModule(): void { $this->resetPage(); }
    public function updatingFilterUser(): void   { $this->resetPage(); }
    public function updatingDateFrom(): void     { $this->resetPage(); }
    public function updatingDateTo(): void       { $this->resetPage(); }

    public function toggleRow(int $id): void
    {
        if (in_array($id, $this->expandedRows)) {
            $this->expandedRows = array_values(array_filter($this->expandedRows, fn ($r) => $r !== $id));
        } else {
            $this->expandedRows[] = $id;
        }
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->filterModule = '';
        $this->filterUser = null;
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, fn ($q) =>
                $q->where('description', 'like', "%{$this->search}%")
                  ->orWhere('action', 'like', "%{$this->search}%")
            )
            ->when($this->filterModule, fn ($q) =>
                $q->where('module', $this->filterModule)
            )
            ->when($this->filterUser, fn ($q) =>
                $q->where('user_id', $this->filterUser)
            )
            ->when($this->dateFrom, fn ($q) =>
                $q->whereDate('created_at', '>=', $this->dateFrom)
            )
            ->when($this->dateTo, fn ($q) =>
                $q->whereDate('created_at', '<=', $this->dateTo)
            )
            ->orderByDesc('created_at')
            ->paginate(20);

        $users = User::orderBy('name')->get();

        return view('livewire.admin.logs.activity-log-viewer', [
            'logs'  => $logs,
            'users' => $users,
        ]);
    }
}
