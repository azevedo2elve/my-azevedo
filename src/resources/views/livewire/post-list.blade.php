<div>
    <!-- Barra de Filtros e Busca -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Campo de Busca em Tempo Real (wire:model.live.debounce.300ms) -->
        <div class="relative w-full md:w-96">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar artigos por título..."
                class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg pl-10 pr-4 py-2.5 focus:outline-none focus:border-indigo-500 text-sm transition"
            >
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Filtros de Categoria -->
        <div class="flex flex-wrap gap-2">
            <button 
                wire:click="selectCategory(null)"
                class="px-3 py-1.5 rounded-full text-xs font-medium transition {{ is_null($selectedCategory) ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white' }}"
            >
                Todas
            </button>
            @foreach($categories as $category)
                <button 
                    wire:click="selectCategory({{ $category->id }})"
                    class="px-3 py-1.5 rounded-full text-xs font-medium transition {{ $selectedCategory === $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white' }}"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Grid de Posts -->
    @if($posts->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <article class="bg-gray-800/50 border border-gray-700/50 rounded-xl overflow-hidden hover:border-indigo-500/50 transition flex flex-col justify-between p-6">
                    <div>
                        <!-- Categoria e Tags -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                {{ $post->category->name }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $post->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <!-- Título e Resumo -->
                        <h2 class="text-xl font-bold text-white mb-2 line-clamp-2 hover:text-indigo-400 transition">
                            {{ $post->title }}
                        </h2>
                        <p class="text-sm text-gray-400 line-clamp-3 mb-4">
                            {{ Str::limit($post->body, 120) }}
                        </p>
                    </div>

                    <!-- Tags e Link -->
                    <div>
                        <div class="flex flex-wrap gap-1.5 mb-4">
                            @foreach($post->tags as $tag)
                                <span class="text-[10px] bg-gray-900 text-gray-400 px-2 py-0.5 rounded">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>

                        <a href="#" class="inline-flex items-center text-xs font-semibold text-indigo-400 hover:text-indigo-300">
                            Ler artigo completo &rarr;
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-gray-800/30 rounded-xl border border-gray-800">
            <p class="text-gray-400 text-base">Nenhum artigo encontrado para a busca "{{ $search }}".</p>
        </div>
    @endif
</div>
