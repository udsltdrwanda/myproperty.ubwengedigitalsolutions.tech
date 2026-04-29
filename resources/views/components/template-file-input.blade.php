@props(['name'])

<div x-data="fileInputHandler()" class="px-4 py-4 bg-white rounded shadow">
    <input type="file"
           x-ref="fileInput"
           class="hidden"
           @change="handleFiles"
           name="{{ $name }}"
           accept=".xls,.xlsx" />

    <div class="mb-4">
        <button type="button"
                class="w-full px-4 py-2 text-sm font-medium text-left text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                @click="$refs.fileInput.click()">
            <span><i class="mr-2 fa fa-plus"></i></span>Choose file
        </button>
    </div>

    <template x-if="files.length">
        <ul class="space-y-2">
            <template x-for="(file, index) in files" :key="index">
                <li class="flex items-center justify-between p-2 border border-gray-200 rounded bg-gray-50">
                    <div>
                        <span x-text="file.name" class="block text-sm font-medium text-gray-800"></span>
                        <span class="text-xs text-gray-500" x-text="`(${(file.size / 1024).toFixed(2)} KB)`"></span>
                    </div>
                    <button type="button" class="px-2 text-lg font-bold text-red-500" @click="removeFile(index)">×</button>
                </li>
            </template>
        </ul>
    </template>
</div>

<script>
    function fileInputHandler() {
        return {
            files: [],
            handleFiles(event) {
                const selectedFile = event.target.files[0];
                const validTypes = [
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];

                if (selectedFile && validTypes.includes(selectedFile.type)) {
                    this.files = [selectedFile];
                } else {
                    alert('Only Excel files (.xls, .xlsx) are allowed.');
                    this.$refs.fileInput.value = '';
                }
            },
            removeFile(index) {
                this.files.splice(index, 1);
                this.$refs.fileInput.value = '';
            }
        };
    }
</script>
