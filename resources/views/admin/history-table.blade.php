<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Archivo') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Estado') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Éxito / Ignorados / Duplicados') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Fecha') }}</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($items as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $log->filename }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($log->status === 'completed') bg-green-100 text-green-800
                                            @elseif($log->status === 'failed') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ strtoupper($log->status) }}
                                        </span>
                                        @if($log->status === 'failed')
                                            <p class="text-[10px] text-red-600 mt-1 max-w-xs truncate" title="{{ $log->error_message }}">
                                                {{ $log->error_message }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">
                                        <span class="text-green-600 font-bold">{{ $log->processed_count }}</span> /
                                        <span class="text-gray-400">{{ $log->skipped_count }}</span> /
                                        <span class="text-orange-600">{{ $log->duplicates_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 italic">
                                        No se han realizado importaciones todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
    </table>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
