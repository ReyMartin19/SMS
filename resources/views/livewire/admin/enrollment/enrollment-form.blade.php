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