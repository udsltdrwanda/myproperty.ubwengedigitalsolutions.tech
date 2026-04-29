<x-app-layout>
    <div class="p-4 bg-gray-100 rounded-md shadow-sm page-breadcrumb">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-700">
                <i class="fa fa-tachometer"></i> WELCOME {{ Auth::user()->name }} {{ Auth::user()->userRole }}
            </h3>
        </div>
    </div>
</x-app-layout>
