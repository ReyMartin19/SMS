<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:breadcrumbs class="mb-2">
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
                <flux:breadcrumbs.item href="{{ route('admin.teachers.index') }}" wire:navigate>Teachers</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Teacher Profile</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <h1 class="text-xl font-medium">Teacher Profile</h1>
            <p class="text-sm text-zinc-500">View teacher information and assignments.</p>
        </div>
        
        <div>
            <flux:button href="{{ route('admin.teachers.index') }}" wire:navigate icon="arrow-left" variant="ghost">Back to List</flux:button>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[300px_1fr]">
        {{-- Left Column: Profile Info --}}
        <div class="flex flex-col gap-6">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
                <div class="mb-6 flex flex-col items-center text-center">
                    <div class="mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-zinc-100 text-3xl font-semibold text-zinc-500 dark:bg-zinc-700 dark:text-zinc-400">
                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                    </div>
                    <h2 class="text-lg font-medium">{{ $teacher->first_name }} {{ $teacher->middle_name }} {{ $teacher->last_name }} {{ $teacher->suffix }}</h2>
                    <p class="text-sm text-zinc-500">{{ $teacher->employee_id ?? 'No Employee ID' }}</p>
                    
                    <div class="mt-3">
                        @if($teacher->status === 'active')
                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">Active</span>
                        @else
                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-900/20 dark:text-red-400">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <div>
                        <div class="text-xs text-zinc-500">Specialization</div>
                        <div class="text-sm font-medium">{{ $teacher->specialization ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-zinc-500">Gender</div>
                        <div class="text-sm font-medium capitalize">{{ $teacher->gender }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-zinc-500">Birthdate</div>
                        <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($teacher->birthdate)->format('F d, Y') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-zinc-500">Contact Number</div>
                        <div class="text-sm font-medium">{{ $teacher->contact_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-zinc-500">Address</div>
                        <div class="text-sm font-medium">{{ $teacher->address }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Assignments --}}
        <div class="flex flex-col gap-6">
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-800">
                <div class="border-b border-zinc-200 p-5 dark:border-zinc-700">
                    <h3 class="text-lg font-medium">Teaching Assignments</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-zinc-50 text-xs uppercase tracking-wider text-zinc-400 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-6 py-3 font-medium">School Year</th>
                                <th class="px-6 py-3 font-medium">Subject</th>
                                <th class="px-6 py-3 font-medium">Section</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                            @forelse ($assignments as $assignment)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <td class="whitespace-nowrap px-6 py-4">{{ $assignment->schoolYear->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ $assignment->subject->name ?? 'N/A' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ $assignment->section->gradeLevel->name ?? '' }} - {{ $assignment->section->name ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-zinc-500">
                                        No teaching assignments found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
