<div class="space-y-6">
    <!-- Breadcrumbs -->
    <flux:breadcrumbs class="mb-4">
        <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
        <flux:breadcrumbs.item>Reports</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Report Center</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-zinc-200 dark:border-zinc-700 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                <i class="ti ti-chart-bar text-zinc-700 dark:text-zinc-300"></i> Report Center
            </h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                Generate official student registries, academic records, and progress reports.
            </p>
        </div>
    </div>

    <!-- Cards Layout -->
    <div class="grid grid-cols-1 gap-6">
        
        <!-- 1. Enrollment Report Card -->
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                    <i class="ti ti-chart-pie text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Enrollment Report</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Export historical and active student enrollment lists.
                    </p>

                    <!-- Filters Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">School Year</label>
                            <select wire:model.live="enrollmentSchoolYearId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All School Years</option>
                                @foreach($schoolYears as $sy)
                                    <option value="{{ $sy->id }}">{{ $sy->name }} {{ $sy->is_active ? '(Active)' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Grade Level</label>
                            <select wire:model.live="enrollmentGradeLevelId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Grade Levels</option>
                                @foreach($gradeLevels as $gl)
                                    <option value="{{ $gl->id }}">{{ $gl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Section</label>
                            <select wire:model.live="enrollmentSectionId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Sections</option>
                                @foreach($enrollmentSections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Status</label>
                            <select wire:model.live="enrollmentStatus" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Statuses</option>
                                <option value="enrolled">Enrolled</option>
                                <option value="dropped">Dropped</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 mt-6">
                        <a href="{{ route('admin.reports.excel.enrollment', [
                            'school_year_id' => $enrollmentSchoolYearId,
                            'grade_level_id' => $enrollmentGradeLevelId,
                            'section_id' => $enrollmentSectionId,
                            'status' => $enrollmentStatus
                        ]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-sm transition-colors shadow-sm">
                            <i class="ti ti-file-spreadsheet text-base"></i> Export Excel (CSV)
                        </a>
                        <a href="{{ route('admin.reports.pdf.enrollment', [
                            'school_year_id' => $enrollmentSchoolYearId,
                            'grade_level_id' => $enrollmentGradeLevelId,
                            'section_id' => $enrollmentSectionId,
                            'status' => $enrollmentStatus
                        ]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition-colors shadow-sm">
                            <i class="ti ti-file-type-pdf text-base"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Student List Card -->
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-violet-50 dark:bg-violet-900/30 rounded-lg text-violet-600 dark:text-violet-400">
                    <i class="ti ti-users text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Student Directory</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Download official directory registries for active, suspended, or inactive students.
                    </p>

                    <!-- Filters Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-5">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Enrollment Status</label>
                            <select wire:model.live="studentStatus" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Gender</label>
                            <select wire:model.live="studentGender" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Active Grade Level</label>
                            <select wire:model.live="studentGradeLevelId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Grade Levels</option>
                                @foreach($gradeLevels as $gl)
                                    <option value="{{ $gl->id }}">{{ $gl->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 mt-6">
                        <a href="{{ route('admin.reports.excel.students', [
                            'status' => $studentStatus,
                            'gender' => $studentGender,
                            'grade_level_id' => $studentGradeLevelId
                        ]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-sm transition-colors shadow-sm">
                            <i class="ti ti-file-spreadsheet text-base"></i> Export Excel (CSV)
                        </a>
                        <a href="{{ route('admin.reports.pdf.students', [
                            'status' => $studentStatus,
                            'gender' => $studentGender,
                            'grade_level_id' => $studentGradeLevelId
                        ]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium text-sm transition-colors shadow-sm">
                            <i class="ti ti-file-type-pdf text-base"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Grades Report Card -->
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                    <i class="ti ti-notebook text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Grades & Marks Registry</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Download academic scores, quarterly grades, and remarks.
                    </p>

                    <!-- Filters Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">School Year</label>
                            <select wire:model.live="gradesSchoolYearId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All School Years</option>
                                @foreach($schoolYears as $sy)
                                    <option value="{{ $sy->id }}">{{ $sy->name }} {{ $sy->is_active ? '(Active)' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Section</label>
                            <select wire:model.live="gradesSectionId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Sections</option>
                                @foreach($gradesSections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Subject</label>
                            <select wire:model.live="gradesSubjectId" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 dark:text-zinc-400 mb-1.5">Quarter</label>
                            <select wire:model.live="gradesQuarter" class="w-full text-sm rounded-lg border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Quarters</option>
                                <option value="1">Quarter 1</option>
                                <option value="2">Quarter 2</option>
                                <option value="3">Quarter 3</option>
                                <option value="4">Quarter 4</option>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 mt-6">
                        <a href="{{ route('admin.reports.excel.grades', [
                            'school_year_id' => $gradesSchoolYearId,
                            'section_id' => $gradesSectionId,
                            'subject_id' => $gradesSubjectId,
                            'quarter' => $gradesQuarter
                        ]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium text-sm transition-colors shadow-sm">
                            <i class="ti ti-file-spreadsheet text-base"></i> Export Excel (CSV)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Report Card Visual Search Card -->
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-rose-50 dark:bg-rose-900/30 rounded-lg text-rose-600 dark:text-rose-400">
                    <i class="ti ti-id-badge text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Student Report Cards</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Lookup academic scores, print preview report card transcripts, or generate official PDFs.
                    </p>

                    <!-- Student Search Dropdown -->
                    <div class="relative max-w-lg mt-5">
                        <flux:input 
                            wire:model.live.debounce.300ms="studentSearch" 
                            placeholder="Search by student name or LRN..." 
                            icon="magnifying-glass" 
                            class="w-full bg-zinc-50 dark:bg-zinc-800 border-zinc-200 dark:border-zinc-700" 
                        />
                        
                        <!-- Search Results Overlay -->
                        @if(!empty($studentSearch))
                            <div class="absolute z-10 w-full mt-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                @if(count($searchedStudents) > 0)
                                    @foreach($searchedStudents as $student)
                                        <a href="{{ route('admin.reports.report-card', ['student_id' => $student->id]) }}" class="block px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-700 border-b border-zinc-100 dark:border-zinc-700 last:border-0 transition-colors">
                                            <div class="font-semibold text-sm text-zinc-900 dark:text-white">
                                                {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }}
                                            </div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5 flex justify-between">
                                                <span>LRN: {{ $student->lrn }}</span>
                                                <span class="capitalize text-blue-600 dark:text-blue-400 font-medium">View Report Card &rarr;</span>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400 text-center">
                                        No students found matching "{{ $studentSearch }}"
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
