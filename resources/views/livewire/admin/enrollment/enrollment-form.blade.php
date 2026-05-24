<div>
    {{-- Breadcrumb --}}
    <nav class="flex mb-4 text-xs text-zinc-500 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center hover:text-blue-600">
                    <i class="ti ti-smart-home mr-1"></i> Admin
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-zinc-400 text-[10px]"></i>
                    <a href="#" class="ml-1 hover:text-blue-600 md:ml-2">Enrollment</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="ti ti-chevron-right text-zinc-400 text-[10px]"></i>
                    <span class="ml-1 text-zinc-800 dark:text-zinc-200 md:ml-2">New Enrollment</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                <div class="bg-blue-100 dark:bg-blue-900/40 p-2 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-school text-xl"></i>
                </div>
                Enroll a Student
            </h1>
            <p class="text-sm text-zinc-500 mt-2">Find an existing record or create a new student profile to proceed with enrollment.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-start gap-3 shadow-sm">
            <i class="ti ti-circle-check text-lg mt-0.5"></i>
            <div>
                <p class="font-medium">Success!</p>
                <p class="text-sm opacity-90">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-[360px_1fr] gap-6 items-start">

        {{-- LEFT COLUMN: Student Context --}}
        <div class="flex flex-col gap-6">
            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
                    <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                        <i class="ti ti-user-search text-blue-500 text-lg"></i> Student Selection
                    </h2>
                    @if($selectedStudent)
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-[10px] font-bold rounded-full uppercase tracking-wide border border-green-200 dark:border-green-800/50">Selected</span>
                    @endif
                </div>
                
                <div class="p-5">
                    @if ($selectedStudent)
                        {{-- Selected Student Profile Card --}}
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-800 border border-blue-100 dark:border-zinc-700 rounded-xl p-4 relative overflow-hidden group shadow-sm transition-all">
                            <div class="absolute top-0 right-0 p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="clearStudent" class="bg-white/80 dark:bg-zinc-900/80 hover:bg-red-50 dark:hover:bg-red-900/30 text-red-500 hover:text-red-600 rounded-full p-1.5 shadow-sm transition-colors border border-zinc-100 dark:border-zinc-700" title="Change Student">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                            
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-blue-200 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 flex items-center justify-center font-bold text-lg shadow-inner border border-blue-300 dark:border-blue-700">
                                    {{ strtoupper(substr($selectedStudent->first_name, 0, 1)) }}{{ strtoupper(substr($selectedStudent->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-zinc-900 dark:text-white leading-tight">
                                        {{ $selectedStudent->last_name }}, {{ $selectedStudent->first_name }} {{ $selectedStudent->middle_name }} {{ $selectedStudent->suffix }}
                                    </h3>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium mt-0.5 flex items-center gap-1">
                                        <i class="ti ti-id-badge"></i> LRN: {{ $selectedStudent->lrn ?? 'Not Assigned' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-y-3 gap-x-4 text-xs bg-white/60 dark:bg-zinc-900/60 rounded-lg p-3 border border-white/40 dark:border-zinc-700/50">
                                <div>
                                    <span class="block text-zinc-400 mb-0.5">Gender</span>
                                    <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ ucfirst($selectedStudent->gender) }}</span>
                                </div>
                                <div>
                                    <span class="block text-zinc-400 mb-0.5">Birthdate</span>
                                    <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ \Carbon\Carbon::parse($selectedStudent->birthdate)->format('M d, Y') }}</span>
                                </div>
                                <div class="col-span-2">
                                    <span class="block text-zinc-400 mb-0.5">Address</span>
                                    <span class="font-medium text-zinc-700 dark:text-zinc-300 truncate block" title="{{ $selectedStudent->address }}">{{ $selectedStudent->address }}</span>
                                </div>
                                @if ($selectedStudent->guardian_name)
                                    <div class="col-span-2 pt-2 border-t border-zinc-200/50 dark:border-zinc-700/50">
                                        <span class="block text-zinc-400 mb-0.5">Guardian</span>
                                        <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $selectedStudent->guardian_name }} ({{ $selectedStudent->guardian_relationship }})</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Search State --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Search Database</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ti ti-search text-zinc-400"></i>
                                    </div>
                                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Enter student name or LRN..." class="pl-9 bg-zinc-50 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700 focus:bg-white dark:focus:bg-zinc-900 w-full" />
                                </div>
                                
                                @if(strlen($search) > 0 && count($searchResults) == 0)
                                    <div class="mt-2 text-xs text-zinc-500 flex items-center gap-1.5 p-2 bg-zinc-50 dark:bg-zinc-800/50 rounded-md border border-zinc-100 dark:border-zinc-700">
                                        <i class="ti ti-info-circle text-zinc-400"></i> No records found for "{{ $search }}"
                                    </div>
                                @endif
                            </div>

                            @if (count($searchResults) > 0)
                                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden shadow-sm bg-white dark:bg-zinc-800 max-h-60 overflow-y-auto custom-scrollbar">
                                    <ul class="divide-y divide-zinc-100 dark:divide-zinc-700/50">
                                        @foreach ($searchResults as $student)
                                            <li>
                                                <button
                                                    wire:click="selectStudent({{ $student->id }})"
                                                    class="w-full text-left px-4 py-3 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors flex items-center justify-between group"
                                                >
                                                    <div>
                                                        <p class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                            {{ $student->last_name }}, {{ $student->first_name }}
                                                        </p>
                                                        <p class="text-xs text-zinc-500 mt-0.5">LRN: {{ $student->lrn ?? 'N/A' }}</p>
                                                    </div>
                                                    <i class="ti ti-chevron-right text-zinc-300 group-hover:text-blue-500 transform transition-transform group-hover:translate-x-1"></i>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    @error('selectedStudent')
                        <div class="mt-3 text-red-500 text-xs flex items-center gap-1 bg-red-50 dark:bg-red-900/20 p-2 rounded-md border border-red-100 dark:border-red-900/30">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                
                @if (!$showStudentForm && !$selectedStudent)
                    <div class="px-5 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-100 dark:border-zinc-800 text-center">
                        <p class="text-xs text-zinc-500 mb-3">Student not in the system yet?</p>
                        <button
                            wire:click="showCreateStudent"
                            class="w-full py-2.5 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-blue-600 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-sm transition-all flex items-center justify-center gap-2 group"
                        >
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-1 rounded-md text-blue-600 dark:text-blue-400 group-hover:bg-blue-100 transition-colors">
                                <i class="ti ti-user-plus text-base"></i>
                            </div>
                            Register New Student
                        </button>
                    </div>
                @endif
            </div>

            {{-- Enrollment Status Guide (Optional UX enhancement) --}}
            <div class="hidden xl:block bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl p-5 text-white shadow-md relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                    <i class="ti ti-books text-9xl"></i>
                </div>
                <h3 class="font-semibold mb-2 flex items-center gap-2 relative z-10"><i class="ti ti-bulb text-xl text-yellow-300"></i> Helpful Tip</h3>
                <p class="text-xs text-blue-100 leading-relaxed relative z-10">
                    Ensure all student details are up to date before confirming the enrollment. Assigning the correct grade level will automatically filter the available sections.
                </p>
            </div>
            
        </div>

        {{-- RIGHT COLUMN: Forms --}}
        <div class="flex flex-col gap-6">

            {{-- New Student Registration Form --}}
            @if ($showStudentForm)
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden transition-all">
                    <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
                        <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                            <i class="ti ti-id text-blue-500 text-lg"></i> New Student Registration
                        </h2>
                        <button wire:click="cancelCreateStudent" class="text-zinc-500 hover:text-zinc-800 dark:hover:text-zinc-200 text-xs font-medium flex items-center gap-1 px-2 py-1 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-md transition-colors">
                            <i class="ti ti-arrow-left"></i> Back to search
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="h-6 w-6 rounded-md bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs font-bold border border-blue-200 dark:border-blue-800/50">1</div>
                                <h3 class="text-sm font-medium text-zinc-800 dark:text-zinc-200 uppercase tracking-wider">Personal Information</h3>
                                <div class="h-px bg-zinc-100 dark:bg-zinc-800 flex-1 ml-2"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-4">
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                    <flux:input wire:model="first_name" placeholder="e.g. Juan" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                    @error('first_name') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                    <flux:input wire:model="last_name" placeholder="e.g. Dela Cruz" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                    @error('last_name') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Middle Name</label>
                                    <flux:input wire:model="middle_name" placeholder="Optional" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Suffix</label>
                                    <flux:input wire:model="suffix" placeholder="e.g. Jr., III" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Gender <span class="text-red-500">*</span></label>
                                    <flux:select wire:model="gender" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900">
                                        <option value="">Select...</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </flux:select>
                                    @error('gender') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Birthdate <span class="text-red-500">*</span></label>
                                    <flux:input type="date" wire:model="birthdate" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                    @error('birthdate') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div class="lg:col-span-2">
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Birthplace</label>
                                    <flux:input wire:model="birthplace" placeholder="City/Municipality" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="h-6 w-6 rounded-md bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs font-bold border border-blue-200 dark:border-blue-800/50">2</div>
                                <h3 class="text-sm font-medium text-zinc-800 dark:text-zinc-200 uppercase tracking-wider">Contact & Address</h3>
                                <div class="h-px bg-zinc-100 dark:bg-zinc-800 flex-1 ml-2"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Learner Reference Number (LRN)</label>
                                    <flux:input wire:model="lrn" placeholder="12-digit number" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                    @error('lrn') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Contact Number</label>
                                    <flux:input wire:model="contact_number" placeholder="09xxxxxxxxx" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Complete Address <span class="text-red-500">*</span></label>
                                    <flux:input wire:model="address" placeholder="House/Block/Lot No., Street, Barangay, City/Municipality, Province" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                    @error('address') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="h-6 w-6 rounded-md bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xs font-bold border border-blue-200 dark:border-blue-800/50">3</div>
                                <h3 class="text-sm font-medium text-zinc-800 dark:text-zinc-200 uppercase tracking-wider">Guardian Information</h3>
                                <div class="h-px bg-zinc-100 dark:bg-zinc-800 flex-1 ml-2"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-5 gap-y-4">
                                <div class="md:col-span-1 lg:col-span-1">
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Guardian Name</label>
                                    <flux:input wire:model="guardian_name" placeholder="Full name" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Relationship</label>
                                    <flux:input wire:model="guardian_relationship" placeholder="e.g. Mother, Father, Aunt" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Contact Number</label>
                                    <flux:input wire:model="guardian_contact" placeholder="09xxxxxxxxx" class="bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                            <flux:button variant="ghost" wire:click="cancelCreateStudent" class="px-5">Cancel</flux:button>
                            <flux:button variant="primary" wire:click="saveStudent" class="px-6 shadow-sm bg-blue-600 hover:bg-blue-700">
                                <i class="ti ti-device-floppy mr-2"></i> Save Profile & Proceed
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Enrollment Settings Form --}}
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden relative transition-all duration-300 {{ !$selectedStudent ? 'opacity-60 grayscale-[30%]' : '' }}">
                @if(!$selectedStudent)
                    <div class="absolute inset-0 z-10 bg-zinc-50/50 dark:bg-zinc-900/50 backdrop-blur-[1px] flex items-center justify-center rounded-2xl">
                        <div class="bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-200 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-3 shadow-lg border border-zinc-200 dark:border-zinc-700">
                            <div class="p-1.5 bg-blue-100 dark:bg-blue-900/50 rounded-lg text-blue-600 dark:text-blue-400">
                                <i class="ti ti-lock text-lg"></i>
                            </div>
                            Select a student first to proceed with enrollment
                        </div>
                    </div>
                @endif

                <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/50 flex justify-between items-center">
                    <h2 class="text-sm font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-2">
                        <i class="ti ti-calendar-event text-blue-500 text-lg"></i> Academic Placement
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">School Year <span class="text-red-500">*</span></label>
                                <flux:select wire:model="school_year_id" class="w-full bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900">
                                    <option value="">Select Academic Year</option>
                                    @foreach ($schoolYears as $year)
                                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                                    @endforeach
                                </flux:select>
                                @error('school_year_id') <p class="text-red-500 text-[11px] mt-1 flex items-center gap-1"><i class="ti ti-alert-circle"></i> {{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Enrollment Date <span class="text-red-500">*</span></label>
                                <flux:input type="date" wire:model="enrolled_at" class="w-full bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900" />
                                @error('enrolled_at') <p class="text-red-500 text-[11px] mt-1 flex items-center gap-1"><i class="ti ti-alert-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                        
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Grade Level <span class="text-red-500">*</span></label>
                                <flux:select wire:model.live="grade_level_id" class="w-full bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900">
                                    <option value="">Select Grade Level</option>
                                    @foreach ($gradeLevels as $level)
                                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </flux:select>
                                @error('grade_level_id') <p class="text-red-500 text-[11px] mt-1 flex items-center gap-1"><i class="ti ti-alert-circle"></i> {{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1.5">Section <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <flux:select wire:model="section_id" :disabled="!$grade_level_id" class="w-full {{ !$grade_level_id ? 'bg-zinc-100 dark:bg-zinc-800/80 opacity-70 cursor-not-allowed' : 'bg-zinc-50 dark:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-900' }}">
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                @error('section_id') <p class="text-red-500 text-[11px] mt-1 flex items-center gap-1"><i class="ti ti-alert-circle"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Final Submit Banner --}}
                <div class="px-6 py-5 bg-zinc-50 dark:bg-zinc-800/80 border-t border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="text-sm text-zinc-500 flex items-start sm:items-center gap-2">
                        <i class="ti ti-info-square rounded text-blue-500 text-lg"></i> 
                        <span>Please review all details before confirming.</span>
                    </div>
                    <flux:button variant="primary" wire:click="enroll" class="w-full sm:w-auto px-8 py-2.5 shadow-md hover:shadow-lg transition-all bg-blue-600 hover:bg-blue-700 flex justify-center items-center gap-2" :disabled="!$selectedStudent">
                        Confirm Enrollment <i class="ti ti-check text-lg"></i>
                    </flux:button>
                </div>
            </div>

        </div>
    </div>
</div>