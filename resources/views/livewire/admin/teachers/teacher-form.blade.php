<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:breadcrumbs class="mb-2">
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" icon="home" />
                <flux:breadcrumbs.item href="{{ route('admin.teachers.index') }}" wire:navigate>Teachers</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Add Teacher</flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <h1 class="text-xl font-medium">Add Teacher</h1>
            <p class="text-sm text-zinc-500">Create a new teacher profile in the system.</p>
        </div>
        
        <div>
            <flux:button href="{{ route('admin.teachers.index') }}" wire:navigate icon="arrow-left" variant="ghost">Back to List</flux:button>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-800">
        <form wire:submit="save" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            {{-- Personal Information --}}
            <div class="col-span-1 sm:col-span-2">
                <h3 class="text-lg font-medium">Personal Information</h3>
                <hr class="my-3 border-zinc-200 dark:border-zinc-700" />
            </div>

            <flux:input wire:model="first_name" label="First Name" />
            <flux:input wire:model="middle_name" label="Middle Name (Optional)" />
            <flux:input wire:model="last_name" label="Last Name" />
            <flux:input wire:model="suffix" label="Suffix (Optional)" placeholder="e.g. Jr, III" />

            <flux:select wire:model="gender" label="Gender">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </flux:select>

            <flux:input wire:model="birthdate" type="date" label="Birthdate" />

            <div class="col-span-1 sm:col-span-2">
                <flux:textarea wire:model="address" label="Address" />
            </div>

            <flux:input wire:model="contact_number" label="Contact Number" />
            <flux:input wire:model="email" type="email" label="Email Address (Login ID)" />

            {{-- Professional Information --}}
            <div class="col-span-1 sm:col-span-2 mt-4">
                <h3 class="text-lg font-medium">Professional Information</h3>
                <hr class="my-3 border-zinc-200 dark:border-zinc-700" />
            </div>

            <flux:input wire:model="employee_id" label="Employee ID" />
            <flux:input wire:model="specialization" label="Specialization" placeholder="e.g. Mathematics, Science" />
            
            <flux:select wire:model="status" label="Status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </flux:select>

            <div class="col-span-1 mt-4 flex justify-end gap-2 sm:col-span-2">
                <flux:button href="{{ route('admin.teachers.index') }}" wire:navigate variant="ghost">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save Teacher</flux:button>
            </div>
        </form>
    </div>
</div>
