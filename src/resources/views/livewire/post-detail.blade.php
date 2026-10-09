<div class="max-w-4xl mx-auto">
    <!-- Botão Voltar -->
    <div class="mb-6">
        <a href="/" class="inline-flex items-center text-sm text-gray-400 hover:text-indigo-400 transition">
            &larr; Voltar para todos os artigos
        </a>
    </div>

    <!-- Cabeçalho do Post -->
    <header class="mb-8 border-b border-gray-800 pb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                {{ $post->category->name }}
            </span>
            <span class="text-xs text-gray-500">
                Publicado em {{ $post->created_at->format('d/m/Y') }}
            </span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-4">
            {{ $post->title }}
        </h1>

        <!-- Tags -->
        <div class="flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
                <span class="text-xs bg-gray-800 text-gray-400 px-2.5 py-1 rounded">
                    #{{ $tag->name }}
                </span>
            @endforeach
        </div>
    </header>

    <!-- Conteúdo do Post -->
    <article class="prose prose-invert max-w-none text-gray-300 leading-relaxed text-lg space-y-6">
        {!! nl2br(e($post->body)) !!}
    </article>
</div>