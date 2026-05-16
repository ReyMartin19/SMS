<div>
    {{-- Breadcrumb --}}
    <p class="text-xs text-zinc-400 mb-3">
        Admin / <span class="text-zinc-700 dark:text-zinc-200">Students</span>
    </p>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-medium">Students</h1>
            <p class="text-sm text-zinc-500 mt-1">Manage and view all registered students.</p>
        </div>
        <a href="{{ route('admin.enrollment.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
            <i class="ti ti-plus"></i> Enroll Student
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 mb-4">
        <div class="grid grid-cols-5 gap-3">

            {{-- Search --}}
            <div class="col-span-2 relative">
                <i class="ti ti-search absolute left-3 top-2.5 text-zinc-400" style="font-size:15px"></i>
                <flux:input wire:model.live="search" placeholder="Search by name or LRN…" class="pl-8" />
            </div>

            {{-- School Year --}}
            <div>
                <flux:select wire:model.live="schoolYearFilter">
                    <option value="">All school years</option>
                    @foreach ($schoolYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Grade Level --}}
            <div>
                <flux:select wire:model.live="gradeLevelFilter">
                    <option value="">All grade levels</option>
                    @foreach ($gradeLevels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Section --}}
            <div>
                <flux:select wire:model.live="sectionFilter" :disabled="!$gradeLevelFilter">
                    <option value="">All sections</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        {{-- Status Filter + Clear --}}
        <div class="flex items-center gap-3 mt-3">
            <div class="flex gap-2">
                @foreach (['', 'active', 'graduated', 'dropped', 'transferred'] as $status)
                    <button
                        wire:click="$set('statusFilter', '{{ $status }}')"
                        class="px-3 py-1 text-xs rounded-full border transition
                            {{ $statusFilter === $status
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'border-zinc-300 dark:border-zinc-600 text-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-700' }}"
                    >
                        {{ $status === '' ? 'All' : ucfirst($status) }}
                    </button>
                @endforeach
            </div>
            <button wire:click="clearFilters" class="ml-auto text-xs text-zinc-400 hover:text-zinc-600 flex items-center gap-1">
                <i class="ti ti-x" style="font-size:13px"></i> Clear filters
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100 dark:border-zinc-700 text-xs text-zinc-400 uppercase tracking-wider">
                    <th class="text-left px-5 py-3 font-medium">Student</th>
                    <th class="text-left px-5 py-3 font-medium">LRN</th>
                    <th class="text-left px-5 py-3 font-medium">Grade & Section</th>
                    <th class="text-left px-5 py-3 font-medium">School Year</th>
                    <th class="text-left px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse ($students as $student)
                    @php $enrollment = $student->enrollments->first(); @endphp
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-5 py-3">
                            <div class="font-medium text-zinc-800 dark:text-zinc-100">
                                {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}
                            </div>
                            <div class="text-xs text-zinc-400 mt-0.5">
                                {{ ucfirst($student->gender) }} · {{ \Carbon\Carbon::parse($student->birthdate)->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-5 py-3 text-zinc-500">
                            {{ $student->lrn ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-zinc-500">
                            @if ($enrollment)
                                {{ $enrollment->gradeLevel->name }} — {{ $enrollment->section->name }}
                            @else
                                <span class="text-zinc-300 dark:text-zinc-600">Not enrolled</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-zinc-500">
                            {{ $enrollment?->schoolYear->name ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $statusColors = [
                                    'active'      => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'graduated'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'dropped'     => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'transferred' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                ];
                                $color = $statusColors[$student->status] ?? 'bg-zinc-100 text-zinc-500';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.students.show', $student) }}" class="text-xs text-blue-500 hover:text-blue-700">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-zinc-400">
                            <i class="ti ti-users-off" style="font-size:32px; display:block; margin-bottom:8px"></i>
                            No students found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if ($students->hasPages())
            <div class="px-5 py-3 border-t border-zinc-100 dark:border-zinc-700">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>