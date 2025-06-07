<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6 text-gray-900">
        <h3 class="text-lg font-medium mb-4">Department Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($departments as $department)
            <div class="border p-4 rounded-lg">
                <h4 class="font-medium">{{ $department->name }}</h4>
                <div class="flex justify-between mt-2">
                    <span>Members:</span>
                    <span class="font-medium">{{ $department->users_count }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Pending Requests:</span>
                    <span class="font-medium">{{ $department->leave_requests_count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
