<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-apple-text leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-section">
            <div class="card-apple">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-apple-secondary text-sm font-medium">Total Apartments</p>
                        <p class="text-3xl font-semibold text-apple-text mt-2">24</p>
                    </div>
                    <div class="section-icon">
                        <x-icon icon="apartment" size="lg" class="text-apple-blue" />
                    </div>
                </div>
            </div>

            <div class="card-apple">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-apple-secondary text-sm font-medium">Tenants</p>
                        <p class="text-3xl font-semibold text-apple-text mt-2">18</p>
                    </div>
                    <div class="section-icon">
                        <x-icon icon="users" size="lg" class="text-apple-blue" />
                    </div>
                </div>
            </div>

            <div class="card-apple">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-apple-secondary text-sm font-medium">Pending Payments</p>
                        <p class="text-3xl font-semibold text-apple-text mt-2">5</p>
                    </div>
                    <div class="section-icon">
                        <x-icon icon="payment" size="lg" class="text-apple-blue" />
                    </div>
                </div>
            </div>

            <div class="card-apple">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-apple-secondary text-sm font-medium">Monthly Revenue</p>
                        <p class="text-3xl font-semibold text-apple-text mt-2">$4.2K</p>
                    </div>
                    <div class="section-icon">
                        <x-icon icon="receipt" size="lg" class="text-apple-blue" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card-apple">
            <div class="section-header">
                <div class="section-icon">
                    <x-icon icon="dashboard" size="md" class="text-apple-blue" />
                </div>
                <h3 class="text-lg font-semibold text-apple-text">Recent Activity</h3>
            </div>
            <div class="text-apple-secondary text-sm">
                {{ __("Your dashboard is ready to manage apartments and tenants.") }}
            </div>
        </div>
    </div>
</x-app-layout>
