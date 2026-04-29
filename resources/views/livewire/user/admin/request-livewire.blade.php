<div class=" bg-white rounded-lg shadow">
    <table class="w-full border border-gray-200 rounded-lg">
        <thead>
            <tr class="text-left bg-gray-200">
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Phone</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr class="text-left border-b">
                    <td class="px-4 py-2">{{ $request->name }}</td>
                    <td class="px-4 py-2">{{ $request->email }}</td>
                    <td class="px-4 py-2">{{ $request->phone }}</td>
                    <td class="px-4 py-2">
                        <span
                            class="px-2 py-1 text-white text-xs font-bold rounded-lg
                            {{ $request->status == 'approved' ? 'bg-green-600' : ($request->status == 'rejected' ? 'bg-red-600' : 'bg-gray-600') }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        @if ($request->status != 'approved')
                            <button wire:click="selectRequest({{ $request->id }})"
                                class="px-3 py-1 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                Reply
                            </button>
                        @else
                            <span class="text-sm text-gray-500">Approved</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Modal Dialog -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
            <div class="fixed inset-0 bg-gray-500 opacity-75" aria-hidden="true"></div>
            <div class="relative w-full max-w-2xl mx-auto my-6">
                <div class="relative w-full max-w-md p-6 mx-auto bg-white rounded-lg shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Reply to Request</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Landlord ID Field -->
                    <div>
                        <label for="landlord_id" class="block mb-2 text-sm font-bold text-gray-700">Landlord ID</label>
                        <input wire:model="landlord_id" id="landlord_id" type="text"
                            class="w-full px-3 py-2 border rounded shadow focus:outline-none focus:ring" readonly>
                        @error('landlord_id')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status Dropdown -->
                    <label for="status" class="block mt-4 text-sm font-medium text-gray-700">Status</label>
                    <select wire:model="status" id="status"
                        class="w-full p-2 mt-1 mb-3 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <!-- Reply Textarea (only shown if approved) -->
                    @if ($status === 'approved')
                        <div>
                            <label for="reply" class="block text-sm font-medium text-gray-700">Reply Message</label>
                            <textarea wire:model="reply" id="reply" class="w-full p-2 border rounded-lg" rows="3"
                                placeholder="Type your response..."></textarea>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end mt-4">
                        <button wire:click="closeModal"
                            class="px-4 py-2 mr-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button wire:click="sendReply"
                            class="px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700">
                            Send Reply & Update Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
