<div>
    {{-- Breadcrumb --}}
    <p class="text-xs text-zinc-400 mb-3">
        Admin / Enrollment / <span class="text-zinc-700 dark:text-zinc-200">New Enrollment</span>
    </p>

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-medium">Enroll a Student</h1>
        <p class="text-sm text-zinc-500 mt-1">Search for an existing student or register a new one, then complete the enrollment details.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-[320px_1fr] gap-6 items-start">

        {{-- LEFT: Student Panel --}}
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
            <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="ti ti-user-search" aria-hidden="true"></i>Student
            </p>

            {{-- Selected Student Card --}}
            @if ($selectedStudent)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3 mb-4">
                    <div class="flex justify-between items-start gap-2">
                        <p class="font-medium text-sm text-blue-700 dark:text-blue-300">
                            {{ $selectedStudent->last_name }}, {{ $selectedStudent->first_name }} {{ $selectedStudent->middle_name }}
                        </p>
                        <button wire:click="clearStudent" class="flex items-center justify-center w-6 h-6 rounded-full bg-red-100 dark:bg-red-900/30 text-red-400 hover:text-red-600 hover:bg-red-200 dark:hover:bg-red-900/50 flex-shrink-0">
                            <i class="ti ti-x" style="font-size:12px"></i>
                        </button>
                    </div>
                    <div class="text-xs text-zinc-500 space-y-1">
                        <p><span class="text-zinc-400">LRN:</span> {{ $selectedStudent->lrn ?? 'N/A' }}</p>
                        <p><span class="text-zinc-400">Gender:</span> {{ ucfirst($selectedStudent->gender) }}</p>
                        <p><span class="text-zinc-400">Birthdate:</span> {{ \Carbon\Carbon::parse($selectedStudent->birthdate)->format('M d, Y') }}</p>
                        <p><span class="text-zinc-400">Address:</span> {{ $selectedStudent->address }}</p>
                        @if ($selectedStudent->guardian_name)
                            <p><span class="text-zinc-400">Guardian:</span> {{ $selectedStudent->guardian_name }} ({{ $selectedStudent->guardian_relationship }})</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Search --}}
            <div class="mb-3">
                <label class="block text-xs text-zinc-500 mb-1">Search by name or LRN</label>
                <div class="relative">
                    <i class="ti ti-search"></i>
                    <flux:input wire:model.live="search" placeholder="e.g. Juan Dela Cruz…" class="pl-8" />
                </div>

                @if (count($searchResults) > 0)
                    <div class="mt-1 border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden shadow-sm">
                        @foreach ($searchResults as $student)
                            <button
                                wire:click="selectStudent({{ $student->id }})"
                                class="w-full text-left px-3 py-2 hover:bg-zinc-50 dark:hover:bg-zinc-700 text-sm border-b border-zinc-100 dark:border-zinc-700 last:border-0"
                            >
                                <span class="font-medium">{{ $student->last_name }}, {{ $student->first_name }}</span>
                                <span class="text-xs text-zinc-400 ml-2">LRN: {{ $student->lrn ?? 'N/A' }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                @error('selectedStudent')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-zinc-100 dark:border-zinc-700 my-4">

            {{-- Create New --}}
            @if (!$showStudentForm)
                <button
                    wire:click="showCreateStudent"
                    class="w-full py-2 border border-dashed border-zinc-300 dark:border-zinc-600 rounded-lg text-sm text-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-700 flex items-center justify-center gap-2"
                >
                <i class="ti ti-plus"></i> Register new student
                </button>
            @endif
        </div>

        {{-- RIGHT: Forms --}}
        <div class="flex flex-col gap-4">

            {{-- Enrollment Details --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="ti ti-calendar"></i> Enrollment details
                </p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-zinc-500 mb-1">School year</label>
                        <flux:select wire:model="school_year_id">
                            <option value="">Select school year</option>
                            @foreach ($schoolYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </flux:select>
                        @error('school_year_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 mb-1">Enrollment date</label>
                        <flux:input type="date" wire:model="enrolled_at" />
                        @error('enrolled_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 mb-1">Grade level</label>
                        <flux:select wire:model.live="grade_level_id">
                            <option value="">Select grade level</option>
                            @foreach ($gradeLevels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </flux:select>
                        @error('grade_level_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 mb-1">Section</label>
                        <flux:select wire:model="section_id" :disabled="!$grade_level_id">
                            <option value="">Select section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </flux:select>
                        @error('section_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- New Student Form --}}
            @if ($showStudentForm)
                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="ti ti-user-plus"></i> Student information
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">First name <span class="text-red-400">*</span></label>
                            <flux:input wire:model="first_name" placeholder="First name" />
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Last name <span class="text-red-400">*</span></label>
                            <flux:input wire:model="last_name" placeholder="Last name" />
                            @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Middle name</label>
                            <flux:input wire:model="middle_name" placeholder="Middle name" />
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Suffix</label>
                            <flux:input wire:model="suffix" placeholder="Jr., Sr., III…" />
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Gender <span class="text-red-400">*</span></label>
                            <flux:select wire:model="gender">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </flux:select>
                            @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Birthdate <span class="text-red-400">*</span></label>
                            <flux:input type="date" wire:model="birthdate" />
                            @error('birthdate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mt-5 mb-3">Contact & address</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">LRN</label>
                            <flux:input wire:model="lrn" placeholder="Learner reference number" />
                            @error('lrn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Contact number</label>
                            <flux:input wire:model="contact_number" placeholder="09xxxxxxxxx" />
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Birthplace</label>
                            <flux:input wire:model="birthplace" placeholder="City/Municipality" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs text-zinc-500 mb-1">Address <span class="text-red-400">*</span></label>
                        <flux:input wire:model="address" placeholder="Complete address" />
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mt-5 mb-3">Guardian</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Guardian name</label>
                            <flux:input wire:model="guardian_name" placeholder="Full name" />
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 mb-1">Relationship</label>
                            <flux:input wire:model="guardian_relationship" placeholder="Mother, Father…" />
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-zinc-500 mb-1">Guardian contact</label>
                            <flux:input wire:model="guardian_contact" placeholder="09xxxxxxxxx" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-5">
                        <flux:button variant="ghost" wire:click="cancelCreateStudent">Cancel</flux:button>
                        <flux:button variant="primary" wire:click="saveStudent">Save student</flux:button>
                    </div>
                </div>
            @endif

            {{-- Submit --}}
            <flux:button variant="primary" wire:click="enroll" class="w-full justify-center py-2.5">
                Confirm enrollment
            </flux:button>
        </div>
    </div>
</div>