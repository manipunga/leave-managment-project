<x-app-layout :title="'App Settings'">
    <div class="max-w-7xl mx-auto py-10">
        <!-- Page Heading -->
        <h2 class="text-2xl font-bold text-gray-900 mb-6">App Settings</h2>

        <!-- App Settings Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Setting</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Value</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-sm leading-4 font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">Total Available Leaves</td>
                        <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $appSetting->total_leaves }}</td>
                        <td class="px-6 py-4 border-b border-gray-200 text-sm">
                            <a href="{{ route('admin.settings.edit', $appSetting) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">Leave Calendar Year Start Date</td>
                        <td class="px-6 py-4 border-b border-gray-200 text-sm text-gray-900">{{ $appSetting->leave_calendar_start_date }}</td>
                        <td class="px-6 py-4 border-b border-gray-200 text-sm">
                            <a href="{{ route('admin.settings.edit', $appSetting) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Empty State -->
            @if(empty($appSetting))
                <div class="px-6 py-4 text-gray-500">
                    No settings available.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
