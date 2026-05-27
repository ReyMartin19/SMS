<?php

namespace App\Livewire\Parent\Announcements;

use Livewire\Component;
use App\Models\Announcement;
use Livewire\Attributes\Title;

#[Title('Announcements')]
class AnnouncementFeed extends Component
{
    public array $expandedAnnouncements = [];

    public function toggleAnnouncement(int $id)
    {
        if (in_array($id, $this->expandedAnnouncements)) {
            $this->expandedAnnouncements = array_diff($this->expandedAnnouncements, [$id]);
        } else {
            $this->expandedAnnouncements[] = $id;
        }
    }

    public function render()
    {
        $announcements = Announcement::visible('parent')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->get();

        return view('livewire.parent.announcements.announcement-feed', [
            'announcements' => $announcements,
        ]);
    }
}
