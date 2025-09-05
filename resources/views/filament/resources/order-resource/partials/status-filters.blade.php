<div class="flex flex-wrap gap-4 items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <div class="flex items-center gap-2">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Status:</span>
    </div>
    
    <div class="flex flex-wrap gap-3">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="pending">
            <span class="text-sm text-gray-700 dark:text-gray-300">Pending</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="confirmed">
            <span class="text-sm text-gray-700 dark:text-gray-300">Confirmed</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="processing">
            <span class="text-sm text-gray-700 dark:text-gray-300">Processing</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="shipped">
            <span class="text-sm text-gray-700 dark:text-gray-300">Shipped</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="delivered">
            <span class="text-sm text-gray-700 dark:text-gray-300">Delivered</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="completed">
            <span class="text-sm text-gray-700 dark:text-gray-300">Completed</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="returned">
            <span class="text-sm text-gray-700 dark:text-gray-300">Returned</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="refunded">
            <span class="text-sm text-gray-700 dark:text-gray-300">Refunded</span>
        </label>
        
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                   class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 shadow-sm focus:border-blue-300 dark:focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-800 focus:ring-opacity-50 dark:bg-gray-700"
                   wire:model.live="tableFilters.status.values"
                   value="cancelled">
            <span class="text-sm text-gray-700 dark:text-gray-300">Cancelled</span>
        </label>
    </div>
    
    <div class="flex items-center gap-2">
        <button type="button" 
                class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 underline transition-colors"
                wire:click="$set('tableFilters.status.values', [])">
            Clear All
        </button>
    </div>
</div> 