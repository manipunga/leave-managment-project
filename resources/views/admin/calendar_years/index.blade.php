<x-app-layout :title="'Manage Users'">

<div class="container mx-auto p-6" x-data="{ showModal: false, calendarYearId: null }">
    <h1 class="text-3xl font-bold mb-6">Calendar Years</h1>

    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.calendar_years.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition duration-300">
        Add Calendar Year
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="py-3 px-6 text-left font-semibold">Name</th>
                    <th class="py-3 px-6 text-left font-semibold">Start Date</th>
                    <th class="py-3 px-6 text-left font-semibold">End Date</th>
                    <th class="py-3 px-6 text-left font-semibold">Total Leaves</th>
                    <th class="py-3 px-6 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calendarYears as $calendarYear)
                    <tr class="border-b">
                        <td class="py-3 px-6">{{ $calendarYear->name }}</td>
                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($calendarYear->start_date)->format('d M Y') }}</td>
                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($calendarYear->end_date)->format('d M Y') }}</td>
                        <td class="py-3 px-6">{{ $calendarYear->total_leaves }}</td>
                        <td class="py-3 px-6">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.calendar_years.edit', $calendarYear->id) }}" class="bg-yellow-500 text-white font-semibold py-1 px-3 rounded hover:bg-yellow-600">
                                    Edit
                                </a>
                                <button @click="showModal = true; calendarYearId = {{ $calendarYear->id }}" class="bg-red-500 text-white font-semibold py-1 px-3 rounded hover:bg-red-600">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Are you sure?</h2>
            <p class="mb-4">Do you really want to delete this calendar year? This action cannot be undone.</p>
            <div class="flex justify-end space-x-4">
                <button @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Cancel</button>
                <form :action="`{{ url('/admin/calendar_years') }}/${calendarYearId}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
