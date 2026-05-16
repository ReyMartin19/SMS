<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Enroll Student</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow p-6 space-y-6">

        {{-- Student Search --}}
        <div>
            <flux:label>Search Student</flux:label>
            <flux:input
                wire:model.live="search"
                placeholder="Search by name or LRN..."
            />

            {{-- Search Results --}}
            @if (count($searchResults) > 0)
                <div class="mt-1 border rounded-lg shadow dark:border-zinc-700 overflow-hidden">
                    @foreach ($searchResults as $student)
                        <button
                            wire:click="selectStudent({{ $student->id }})"
                            class="w-full text-left px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 text-sm"
                        >
                            {{ $student->last_name }}, {{ $student->first_name }} — LRN: {{ $student->lrn ?? 'N/A' }}
                        </button>
                    @endforeach
                </div>
            @endif

            @error('selectedStudent')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Selected Student --}}
        @if ($selectedStudent)
            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm">
                <p class="font-semibold text-blue-700 dark:text-blue-300">Selected Student</p>
                <p>{{ $selectedStudent->last_name }}, {{ $selectedStudent->first_name }} {{ $selectedStudent->middle_name }}</p>
                <p>LRN: {{ $selectedStudent->lrn ?? 'N/A' }}</p>
                <p>Gender: {{ ucfirst($selectedStudent->gender) }}</p>
                <button wire:click="$set('selectedStudent', null)" class="mt-2 text-red-500 text-xs">Remove</button>
            </div>
        @endif

        {{-- Create New Student Button --}}
        @if (!$selectedStudent && !$showStudentForm)
        <div class="flex justify-end">
            <flux:button variant="ghost" wire:click="showCreateStudent">
                + Create New Student
            </flux:button>
        </div>
        @endif

        {{-- New Student Form --}}
        @if ($showStudentForm)
        <div class="border dark:border-zinc-700 rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-lg">New Student Information</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:label>First Name <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="first_name" placeholder="First name" />
                    @error('first_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label>Last Name <span class="text-red-500">*</span></flux:label>
                    <flux:input wire:model="last_name" placeholder="Last name" />
                    @error('last_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label>Middle Name</flux:label>
                    <flux:input wire:model="middle_name" placeholder="Middle name" />
                </div>
                <div>
                    <flux:label>Suffix</flux:label>
                    <flux:input wire:model="suffix" placeholder="Jr., Sr., III..." />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:label>Gender <span class="text-red-500">*</span></flux:label>
                    <flux:select wire:model="gender">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </flux:select>
                    @error('gender') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label>Birthdate <span class="text-red-500">*</span></flux:label>
                    <flux:input type="date" wire:model="birthdate" />
                    @error('birthdate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:label>Birthplace</flux:label>
                    <flux:input wire:model="birthplace" placeholder="City/Municipality" />
                </div>
                <div>
                    <flux:label>LRN</flux:label>
                    <flux:input wire:model="lrn" placeholder="Learner Reference Number" />
                    @error('lrn') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <flux:label>Address <span class="text-red-500">*</span></flux:label>
                <flux:input wire:model="address" placeholder="Complete address" />
                @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <flux:label>Contact Number</flux:label>
                <flux:input wire:model="contact_number" placeholder="09xxxxxxxxx" />
            </div>

            <div class="border-t dark:border-zinc-700 pt-4 space-y-4">
                <h3 class="font-medium">Guardian Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:label>Guardian Name</flux:label>
                        <flux:input wire:model="guardian_name" placeholder="Full name" />
                    </div>
                    <div>
                        <flux:label>Relationship</flux:label>
                        <flux:input wire:model="guardian_relationship" placeholder="Mother, Father..." />
                    </div>
                </div>
                <div>
                    <flux:label>Guardian Contact</flux:label>
                    <flux:input wire:model="guardian_contact" placeholder="09xxxxxxxxx" />
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <flux:button variant="ghost" wire:click="cancelCreateStudent">
                    Cancel
                </flux:button>
                <flux:button variant="primary" wire:click="saveStudent">
                    Save Student
                </flux:button>
            </div>
        </div>
        @endif

        {{-- School Year --}}
        <div>
            <flux:label>School Year</flux:label>
            <flux:select wire:model="school_year_id">
                <option value="">Select School Year</option>
                @foreach ($schoolYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach
            </flux:select>
            @error('school_year_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Grade Level --}}
        <div>
            <flux:label>Grade Level</flux:label>
            <flux:select wire:model.live="grade_level_id">
                <option value="">Select Grade Level</option>
                @foreach ($gradeLevels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            </flux:select>
            @error('grade_level_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Section --}}
        <div>
            <flux:label>Section</flux:label>
            <flux:select wire:model="section_id" :disabled="!$grade_level_id">
                <option value="">Select Section</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </flux:select>
            @error('section_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Enrolled At --}}
        <div>
            <flux:label>Enrollment Date</flux:label>
            <flux:input type="date" wire:model="enrolled_at" />
            @error('enrolled_at')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <flux:button variant="primary" wire:click="enroll">
                Enroll Student
            </flux:button>
        </div>

    </div>
</div>