<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Change Password')" :description="__('You must change your password before you can proceed to the portal.')" />

    <form wire:submit="save" class="flex flex-col gap-6">
        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('New Password')"
            type="password"
            required
            viewable
            placeholder="Min. 8 characters"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm Password')"
            type="password"
            required
            viewable
            placeholder="Confirm new password"
        />

        <flux:button variant="primary" type="submit" class="w-full">
            {{ __('Change Password') }}
        </flux:button>
    </form>
</div>
