<x-app-layout :title="isset($calendarYear) ? 'Edit' : 'Create'">

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">{{ isset($calendarYear) ? 'Edit' : 'Create' }} Calendar Year</h1>

    <form action="{{ isset($calendarYear) ? route('admin.calendar_years.update', $calendarYear->id) : route('admin.calendar_years.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($calendarYear))
            @method('PUT')
        @endif

        <!-- Name Field -->
        <div>
            <label for="name" class="block text-gray-700 font-semibold mb-2">Name</label>
            <input type="text" name="name" id="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                   value="{{ isset($calendarYear) ? $calendarYear->name : old('name') }}" required>
        </div>

        <!-- Start Date Field -->
        <div>
            <label for="start_date" class="block text-gray-700 font-semibold mb-2">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                   value="{{ isset($calendarYear) ? $calendarYear->start_date : old('start_date') }}" required>
        </div>

        <!-- End Date Field -->
        <div>
            <label for="end_date" class="block text-gray-700 font-semibold mb-2">End Date</label>
            <input type="date" name="end_date" id="end_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                   value="{{ isset($calendarYear) ? $calendarYear->end_date : old('end_date') }}" required>
        </div>

        <!-- Total Leaves Field -->
        <div>
            <label for="total_leaves" class="block text-gray-700 font-semibold mb-2">Total Leaves</label>
            <input type="number" name="total_leaves" id="total_leaves" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" 
                   value="{{ isset($calendarYear) ? $calendarYear->total_leaves : old('total_leaves') }}" required>
        </div>

        <!-- Buttons -->
          <div class="flex space-x-4">
              <button type="submit" class="w-full bg-green-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-600">
                  {{ isset($calendarYear) ? 'Update' : 'Create' }}
                </button>
                <a href="{{ route('admin.calendar_years.index') }}" class="w-full bg-gray-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600 text-center">
                    Back
                </a>
        </div>
    </form>
</div>
</x-app-layout>