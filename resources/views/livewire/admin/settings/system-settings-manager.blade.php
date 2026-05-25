<div class="space-y-6">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
        <flux:breadcrumbs.item>System Settings</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Page Header --}}
    <div class="flex items-start justify-between border-b border-zinc-200 dark:border-zinc-700 pb-5">
        <div>
            <h1 class="text-xl font-semibold text-zinc-900 dark:text-white flex items-center gap-2">
                <i class="ti ti-settings text-zinc-500 dark:text-zinc-400"></i>
                System Settings
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                Configure global school information, branding, and academic defaults.
            </p>
        </div>
    </div>

    {{-- Tabbed Settings Card --}}
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden shadow-sm">

        {{-- Tab Navigation --}}
        <div class="flex border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50">
            <button
                wire:click="setTab('school')"
                class="px-5 py-3.5 text-sm font-medium transition-all duration-150 border-b-2 focus:outline-none
                    {{ $activeTab === 'school'
                        ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400'
                        : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
            >
                <span class="flex items-center gap-1.5">
                    <i class="ti ti-building-community text-base"></i>
                    School Information
                </span>
            </button>
            <button
                wire:click="setTab('logo')"
                class="px-5 py-3.5 text-sm font-medium transition-all duration-150 border-b-2 focus:outline-none
                    {{ $activeTab === 'logo'
                        ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400'
                        : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
            >
                <span class="flex items-center gap-1.5">
                    <i class="ti ti-photo text-base"></i>
                    Logo & Branding
                </span>
            </button>
            <button
                wire:click="setTab('academic')"
                class="px-5 py-3.5 text-sm font-medium transition-all duration-150 border-b-2 focus:outline-none
                    {{ $activeTab === 'academic'
                        ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400'
                        : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:border-zinc-300 dark:hover:border-zinc-600' }}"
            >
                <span class="flex items-center gap-1.5">
                    <i class="ti ti-school text-base"></i>
                    Academic Settings
                </span>
            </button>
        </div>

        <div class="p-6">

            {{-- ===================== TAB 1: SCHOOL INFORMATION ===================== --}}
            @if($activeTab === 'school')
                @if($savedSchool)
                    <div class="mb-5 flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 text-green-700 dark:text-green-400 text-sm rounded-xl px-4 py-3">
                        <i class="ti ti-circle-check text-lg"></i>
                        School information saved successfully.
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 flex items-start gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 text-red-700 dark:text-red-400 text-sm rounded-xl px-4 py-3">
                        <i class="ti ti-alert-circle text-lg mt-0.5"></i>
                        <ul class="space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-700">
                            Basic School Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <flux:input
                                    wire:model="school_name"
                                    label="School Name *"
                                    placeholder="e.g. Mabini National High School"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <flux:input
                                    wire:model="school_address"
                                    label="School Address"
                                    placeholder="e.g. Brgy. San Pedro, Taguig City, Metro Manila"
                                />
                            </div>
                            <div>
                                <flux:input
                                    wire:model="school_phone"
                                    label="Phone Number"
                                    placeholder="e.g. (02) 8123-4567"
                                />
                            </div>
                            <div>
                                <flux:input
                                    type="email"
                                    wire:model="school_email"
                                    label="Email Address"
                                    placeholder="e.g. info@school.edu.ph"
                                />
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-700">
                            DepEd Classification
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <flux:input
                                    wire:model="school_id"
                                    label="DepEd School ID"
                                    placeholder="e.g. 123456"
                                />
                            </div>
                            <div>
                                <flux:input
                                    wire:model="school_division"
                                    label="School Division"
                                    placeholder="e.g. Division of Taguig-Pateros"
                                />
                            </div>
                            <div>
                                <flux:input
                                    wire:model="school_district"
                                    label="School District"
                                    placeholder="e.g. District IV"
                                />
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-700">
                            School Leadership
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <flux:input
                                    wire:model="principal_name"
                                    label="Principal / School Head Name"
                                    placeholder="e.g. Juan Dela Cruz, PhD"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button
                            wire:click="saveSchoolInfo"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-medium rounded-lg px-5 py-2 transition-colors shadow-sm cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="saveSchoolInfo">
                                <i class="ti ti-device-floppy text-base"></i>
                            </span>
                            <span wire:loading wire:target="saveSchoolInfo">
                                <i class="ti ti-loader-2 animate-spin text-base"></i>
                            </span>
                            Save School Information
                        </button>
                    </div>
                </div>
            @endif

            {{-- ===================== TAB 2: LOGO & BRANDING ===================== --}}
            @if($activeTab === 'logo')
                @if($savedLogo)
                    <div class="mb-5 flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 text-green-700 dark:text-green-400 text-sm rounded-xl px-4 py-3">
                        <i class="ti ti-circle-check text-lg"></i>
                        Logo settings saved successfully.
                    </div>
                @endif

                @if($errors->has('newLogo'))
                    <div class="mb-5 flex items-center gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 text-red-700 dark:text-red-400 text-sm rounded-xl px-4 py-3">
                        <i class="ti ti-alert-circle text-lg"></i>
                        {{ $errors->first('newLogo') }}
                    </div>
                @endif

                <div class="space-y-6 max-w-xl">
                    {{-- Current Logo Preview --}}
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-700">
                            Current School Logo
                        </h3>

                        @if($currentLogo)
                            <div class="flex items-center gap-4">
                                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-3 bg-zinc-50 dark:bg-zinc-900">
                                    <img
                                        src="{{ Storage::url($currentLogo) }}"
                                        alt="School Logo"
                                        class="max-h-32 max-w-xs object-contain rounded-lg"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Current logo in use</p>
                                    <button
                                        wire:click="removeLogo"
                                        wire:confirm="Are you sure you want to remove the current logo?"
                                        class="inline-flex items-center gap-1.5 text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium transition-colors cursor-pointer"
                                    >
                                        <i class="ti ti-trash text-sm"></i>
                                        Remove Logo
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-center border-2 border-dashed border-zinc-300 dark:border-zinc-600 rounded-xl p-8 text-center bg-zinc-50 dark:bg-zinc-900/50">
                                <div class="space-y-2">
                                    <i class="ti ti-photo-off text-3xl text-zinc-400 dark:text-zinc-500 block"></i>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No logo uploaded yet.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Upload New Logo --}}
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-700">
                            Upload New Logo
                        </h3>

                        <div class="space-y-4">
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                    Choose image file (JPEG, PNG, GIF, WEBP — max 2MB)
                                </label>
                                <input
                                    type="file"
                                    wire:model="newLogo"
                                    accept="image/*"
                                    class="block w-full text-sm text-zinc-700 dark:text-zinc-300
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-lg file:border-0
                                           file:text-sm file:font-medium
                                           file:bg-blue-50 file:text-blue-700
                                           dark:file:bg-blue-900/30 dark:file:text-blue-400
                                           hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50
                                           file:cursor-pointer file:transition-colors"
                                />
                            </div>

                            {{-- Preview newly selected logo before saving --}}
                            @if($newLogo)
                                <div class="flex items-center gap-3">
                                    <div class="border border-blue-200 dark:border-blue-800/50 rounded-xl p-2 bg-blue-50 dark:bg-blue-900/20">
                                        <img
                                            src="{{ $newLogo->temporaryUrl() }}"
                                            alt="Logo Preview"
                                            class="max-h-24 max-w-xs object-contain rounded"
                                        />
                                    </div>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">Preview — not yet saved</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button
                            wire:click="saveLogo"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-medium rounded-lg px-5 py-2 transition-colors shadow-sm cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="saveLogo">
                                <i class="ti ti-upload text-base"></i>
                            </span>
                            <span wire:loading wire:target="saveLogo">
                                <i class="ti ti-loader-2 animate-spin text-base"></i>
                            </span>
                            Save Logo
                        </button>
                    </div>
                </div>
            @endif

            {{-- ===================== TAB 3: ACADEMIC SETTINGS ===================== --}}
            @if($activeTab === 'academic')
                @if($savedAcademic)
                    <div class="mb-5 flex items-center gap-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 text-green-700 dark:text-green-400 text-sm rounded-xl px-4 py-3">
                        <i class="ti ti-circle-check text-lg"></i>
                        Academic settings saved successfully.
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-5 flex items-start gap-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 text-red-700 dark:text-red-400 text-sm rounded-xl px-4 py-3">
                        <i class="ti ti-alert-circle text-lg mt-0.5"></i>
                        <ul class="space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6 max-w-xl">
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-700">
                            Grading Configuration
                        </h3>
                        <div class="space-y-5">
                            <div class="max-w-xs">
                                <flux:input
                                    type="number"
                                    wire:model="grading_passing_grade"
                                    label="Passing Grade (%)"
                                    min="1"
                                    max="100"
                                    placeholder="75"
                                />
                                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1.5">
                                    Students scoring at or above this percentage are marked as Passed.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mb-4 pb-2 border-b border-zinc-100 dark:border-zinc-700">
                            Report Card
                        </h3>
                        <div class="space-y-2">
                            <label class="block text-xs font-medium text-zinc-600 dark:text-zinc-400">
                                Report Card Footer Note
                            </label>
                            <flux:textarea
                                wire:model="report_card_footer"
                                placeholder="e.g. This is an official document of the school."
                                rows="3"
                            />
                            <p class="text-xs text-zinc-400 dark:text-zinc-500">
                                This text will appear at the bottom of every printed report card PDF.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button
                            wire:click="saveAcademic"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 disabled:opacity-60 text-white text-sm font-medium rounded-lg px-5 py-2 transition-colors shadow-sm cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="saveAcademic">
                                <i class="ti ti-device-floppy text-base"></i>
                            </span>
                            <span wire:loading wire:target="saveAcademic">
                                <i class="ti ti-loader-2 animate-spin text-base"></i>
                            </span>
                            Save Academic Settings
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
