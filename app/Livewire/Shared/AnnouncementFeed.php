<?php

namespace App\Livewire\Shared;

use App\Models\Announcement;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AnnouncementFeed extends Component
{
    use WithPagination;

    // Track expanded status by announcement ID
    public array $expandedAnnouncements = [];

    public function toggleExpand(int $id): void
    {
        $this->expandedAnnouncements[$id] = !($this->expandedAnnouncements[$id] ?? false);
    }

    public function render()
    {
        $role = Auth::user()->role;

        // Fetch visible announcements targeted to this role (or 'all')
        // Order: Pinned first, then by published_at (most recent first)
        $announcements = Announcement::with('author')
            ->visible($role)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('livewire.shared.announcement-feed', [
            'announcements' => $announcements,
        ]);
    }
}
