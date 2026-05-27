<?php

namespace App\Livewire\Admin\Settings;

use App\Models\ActivityLog;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('System Settings')]
class SystemSettingsManager extends Component
{
    use WithFileUploads;

    public string $activeTab = 'school';

    // Tab 1 — School Information
    public string $school_name = '';
    public string $school_address = '';
    public string $school_phone = '';
    public string $school_email = '';
    public string $school_division = '';
    public string $school_district = '';
    public string $school_id = '';
    public string $principal_name = '';

    // Tab 2 — Logo & Branding
    public $newLogo = null;
    public ?string $currentLogo = null;

    // Tab 3 — Academic Settings
    public string $grading_passing_grade = '75';
    public string $report_card_footer = '';

    // Success messages per tab
    public bool $savedSchool = false;
    public bool $savedLogo = false;
    public bool $savedAcademic = false;

    public function mount(): void
    {
        if (auth()->user()->role !== 'superadmin') {
            abort(403, 'Access denied. Superadmin only.');
        }

        $this->loadSettings();
    }

    protected function loadSettings(): void
    {
        $this->school_name = SystemSetting::get('school_name', '');
        $this->school_address = SystemSetting::get('school_address', '');
        $this->school_phone = SystemSetting::get('school_phone', '');
        $this->school_email = SystemSetting::get('school_email', '');
        $this->school_division = SystemSetting::get('school_division', '');
        $this->school_district = SystemSetting::get('school_district', '');
        $this->school_id = SystemSetting::get('school_id', '');
        $this->principal_name = SystemSetting::get('principal_name', '');
        $this->currentLogo = SystemSetting::get('school_logo');
        $this->grading_passing_grade = SystemSetting::get('grading_passing_grade', '75');
        $this->report_card_footer = SystemSetting::get('report_card_footer', '');
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->savedSchool = false;
        $this->savedLogo = false;
        $this->savedAcademic = false;
    }

    public function saveSchoolInfo(): void
    {
        $this->validate([
            'school_name'     => 'required|string|max:255',
            'school_address'  => 'nullable|string|max:500',
            'school_phone'    => 'nullable|string|max:50',
            'school_email'    => 'nullable|email|max:255',
            'school_division' => 'nullable|string|max:255',
            'school_district' => 'nullable|string|max:255',
            'school_id'       => 'nullable|string|max:100',
            'principal_name'  => 'nullable|string|max:255',
        ]);

        SystemSetting::set('school_name', $this->school_name);
        SystemSetting::set('school_address', $this->school_address);
        SystemSetting::set('school_phone', $this->school_phone);
        SystemSetting::set('school_email', $this->school_email);
        SystemSetting::set('school_division', $this->school_division);
        SystemSetting::set('school_district', $this->school_district);
        SystemSetting::set('school_id', $this->school_id);
        SystemSetting::set('principal_name', $this->principal_name);

        ActivityLog::log(
            'updated_settings', 'settings',
            'Updated system settings — Tab: school',
            [
                'new_values' => [
                    'school_name'     => $this->school_name,
                    'school_address'  => $this->school_address,
                    'school_phone'    => $this->school_phone,
                    'school_email'    => $this->school_email,
                    'school_division' => $this->school_division,
                    'school_district' => $this->school_district,
                    'school_id'       => $this->school_id,
                    'principal_name'  => $this->principal_name,
                ],
            ]
        );

        $this->savedSchool = true;
    }

    public function saveLogo(): void
    {
        $this->validate([
            'newLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($this->newLogo) {
            if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
                Storage::disk('public')->delete($this->currentLogo);
            }
            $path = $this->newLogo->store('logo', 'public');
            SystemSetting::set('school_logo', $path);
            $this->currentLogo = $path;
            $this->newLogo = null;
        }

        ActivityLog::log(
            'updated_settings', 'settings',
            'Updated system settings — Tab: logo'
        );

        $this->savedLogo = true;
    }

    public function removeLogo(): void
    {
        if ($this->currentLogo && Storage::disk('public')->exists($this->currentLogo)) {
            Storage::disk('public')->delete($this->currentLogo);
        }
        SystemSetting::set('school_logo', null);
        $this->currentLogo = null;
        $this->savedLogo = true;
    }

    public function saveAcademic(): void
    {
        $this->validate([
            'grading_passing_grade' => 'required|numeric|min:1|max:100',
            'report_card_footer'    => 'nullable|string|max:1000',
        ]);

        SystemSetting::set('grading_passing_grade', $this->grading_passing_grade);
        SystemSetting::set('report_card_footer', $this->report_card_footer);

        ActivityLog::log(
            'updated_settings', 'settings',
            'Updated system settings — Tab: academic',
            [
                'new_values' => [
                    'grading_passing_grade' => $this->grading_passing_grade,
                    'report_card_footer'    => $this->report_card_footer,
                ],
            ]
        );

        $this->savedAcademic = true;
    }

    public function render()
    {
        return view('livewire.admin.settings.system-settings-manager');
    }
}
