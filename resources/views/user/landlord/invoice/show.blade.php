<x-app-layout>
    <div class="p-4 bg-gray-100 border-b rounded-md page-breadcrumb">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-700">
                Invoice detail
            </h3>
        </div>
    </div>
    <div class="container px-4 py-6 my-6 bg-white rounded-md shadow-md x-auto p">
        <div class="content">
            @livewire('user.land-lord.invoice.invoice-detail-livewire', ['invoice' => $invoiceId])
        </div>
    </div>
</x-app-layout>
