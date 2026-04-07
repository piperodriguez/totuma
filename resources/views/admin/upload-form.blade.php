<form action="{{ route('loggro.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
    <div class="flex flex-col sm:flex-row items-center gap-4">
        <x-input-label for="loggro_file" :value="__('Cargar archivo de facturación (Loggro)')" />
        <input type="file"
               name="loggro_file"
               id="loggro_file"
               class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-green-600 file:text-white
                            hover:file:bg-green-700 cursor-pointer"
               accept=".csv,.xlsx"
               required>
        <x-input-error :messages="$errors->get('loggro_file')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button  class="w-full sm:w-auto justify-center">{{ __('Subir e Importar') }}</x-primary-button>
        <br>
        <span class="text-xs text-gray-500 italic">{{ __('Formatos admitidos: CSV, XLSX') }}</span>
    </div>
</form>
