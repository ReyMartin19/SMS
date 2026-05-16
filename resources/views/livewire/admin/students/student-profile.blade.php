<div>
    {{-- Breadcrumb --}}
    <p class="text-xs text-zinc-400 mb-3">
        Admin / <a href="{{ route('admin.students.index') }}" class="hover:text-zinc-600" wire:navigate>Students</a> /
        <span class="text-zinc-700 dark:text-zinc-200">{{ $student->last_name }}, {{ $student->first_name }}</span>
    </p>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                <span class="text-lg font-medium text-blue-600 dark:text-blue-400">
                    {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                </span>
            </div>
            <div>
                <h1 class="text-xl font-medium text-zinc-800 dark:text-zinc-100">
                    {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }} {{ $student->suffix }}
                </h1>
                <p class="text-sm text-zinc-400 mt-0.5">LRN: {{ $student->lrn ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="flex gap-2">
            @if (!$editing)
                <button wire:click="edit" class="flex items-center gap-2 px-4 py-2 border border-zinc-300 dark:border-zinc-600 text-sm rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700">
                    <i class="ti ti-edit" style="font-size:15px"></i> Edit
                </button>
            @else
                <button wire:click="cancelEdit" class="flex items-center gap-2 px-4 py-2 border border-zinc-300 dark:border-zinc-600 text-sm rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700">
                    Cancel
                </button>
                <button wire:click="save" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                    <i class="ti ti-check" style="font-size:15px"></i> Save changes
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-[1fr_300px] gap-4">

        {{-- Left: Student Info --}}
        <div class="flex flex-col gap-4">

            {{-- Personal Info --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">Personal Information</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">First name</label>
                        @if ($editing)
                            <flux:input wire:model="first_name" />
                            @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->first_name }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Last name</label>
                        @if ($editing)
                            <flux:input wire:model="last_name" />
                            @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->last_name }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Middle name</label>
                        @if ($editing)
                            <flux:input wire:model="middle_name" />
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->middle_name ?? '—' }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Suffix</label>
                        @if ($editing)
                            <flux:input wire:model="suffix" placeholder="Jr., Sr…" />
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->suffix ?? '—' }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Gender</label>
                        @if ($editing)
                            <flux:select wire:model="gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </flux:select>
                            @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ ucfirst($student->gender) }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Birthdate</label>
                        @if ($editing)
                            <flux:input type="date" wire:model="birthdate" />
                            @error('birthdate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->birthdate->format('M d, Y') }}</p>
                        @endif
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs text-zinc-400 mb-1">Birthplace</label>
                        @if ($editing)
                            <flux:input wire:model="birthplace" placeholder="City/Municipality" />
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->birthplace ?? '—' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Contact & Address --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">Contact & Address</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">LRN</label>
                        @if ($editing)
                            <flux:input wire:model="lrn" placeholder="Learner reference number" />
                            @error('lrn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->lrn ?? '—' }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Contact number</label>
                        @if ($editing)
                            <flux:input wire:model="contact_number" placeholder="09xxxxxxxxx" />
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->contact_number ?? '—' }}</p>
                        @endif
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs text-zinc-400 mb-1">Address</label>
                        @if ($editing)
                            <flux:input wire:model="address" />
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->address }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Guardian --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">Guardian Information</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Guardian name</label>
                        @if ($editing)
                            <flux:input wire:model="guardian_name" placeholder="Full name" />
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->guardian_name ?? '—' }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-400 mb-1">Relationship</label>
                        @if ($editing)
                            <flux:input wire:model="guardian_relationship" placeholder="Mother, Father…" />
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->guardian_relationship ?? '—' }}</p>
                        @endif
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs text-zinc-400 mb-1">Guardian contact</label>
                        @if ($editing)
                            <flux:input wire:model="guardian_contact" placeholder="09xxxxxxxxx" />
                        @else
                            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $student->guardian_contact ?? '—' }}</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Status & Enrollment History --}}
        <div class="flex flex-col gap-4">

            {{-- Status --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">Status</p>

                @if ($editing)
                    <flux:select wire:model="status">
                        <option value="active">Active</option>
                        <option value="graduated">Graduated</option>
                        <option value="dropped">Dropped</option>
                        <option value="transferred">Transferred</option>
                    </flux:select>
                @else
                    @php
                        $statusColors = [
                            'active'      => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                            'graduated'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                            'dropped'     => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            'transferred' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                        ];
                        $color = $statusColors[$student->status] ?? 'bg-zinc-100 text-zinc-500';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                        {{ ucfirst($student->status) }}
                    </span>
                @endif
            </div>

            {{-- Enrollment History --}}
            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-5">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-4">Enrollment History</p>

                @if ($student->enrollments->isEmpty())
                    <p class="text-sm text-zinc-400 text-center py-4">No enrollment records.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($student->enrollments->sortByDesc('enrolled_at') as $enrollment)
                            <div class="border border-zinc-100 dark:border-zinc-700 rounded-lg p-3">
                                <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                                    {{ $enrollment->schoolYear->name }}
                                </p>
                                <p class="text-xs text-zinc-400 mt-0.5">
                                    {{ $enrollment->gradeLevel->name }} — {{ $enrollment->section->name }}
                                </p>
                                <p class="text-xs text-zinc-400 mt-0.5">
                                    Enrolled: {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('M d, Y') }}
                                </p>
                                @php
                                    $eColors = [
                                        'enrolled'    => 'bg-green-100 text-green-700',
                                        'dropped'     => 'bg-red-100 text-red-700',
                                        'transferred' => 'bg-yellow-100 text-yellow-700',
                                        'graduated'   => 'bg-blue-100 text-blue-700',
                                    ];
                                    $eColor = $eColors[$enrollment->status] ?? 'bg-zinc-100 text-zinc-500';
                                @endphp
                                <span class="mt-1 inline-block px-2 py-0.5 rounded-full text-xs {{ $eColor }}">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>