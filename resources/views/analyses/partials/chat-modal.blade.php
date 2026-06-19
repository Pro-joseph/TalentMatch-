@props(['candidatNom' => ''])

<div
    x-data="{
        open: false,
        messages: [],
        message: '',
        loading: false,
        conversationId: null,

        async sendMessage() {
            if (!this.message.trim() || this.loading) return;

            const text = this.message;
            this.messages.push({ role: 'user', content: text });
            this.message = '';
            this.loading = true;

            try {
                const url = this.conversationId
                    ? '{{ url('assistant') }}/' + this.conversationId + '/message'
                    : '{{ route('agent-conversations.store') }}';

                const formData = new FormData();
                formData.append('message', text);
                formData.append('title', 'Question à propos de {{ $candidatNom }}');

                const resp = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData,
                });

                if (resp.redirected) {
                    const redirectUrl = resp.url;
                    const match = redirectUrl.match(/\/assistant\/([a-f0-9-]+)/);
                    if (match) this.conversationId = match[1];
                }

                // Fetch the last assistant message
                const historyResp = await fetch('{{ url('assistant') }}/' + this.conversationId);
                const html = await historyResp.text();

                this.messages.push({
                    role: 'assistant',
                    content: 'Réponse enregistrée. Consultez l\'assistant RH pour plus de détails.',
                });
            } catch (e) {
                this.messages.push({ role: 'assistant', content: 'Erreur lors de l\'envoi. Veuillez réessayer.' });
            } finally {
                this.loading = false;
            }
        }
    }"
>
    <!-- Trigger -->
    <button @click="open = true" type="button" class="btn-secondary btn-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        Poser une question à l'assistant
    </button>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div @click="open = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg h-[500px] flex flex-col" @click.stop>
            <!-- Header -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                <h3 class="font-display font-semibold text-slate-950 text-sm">Assistant RH</h3>
                <button @click="open = false" type="button" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3 bg-slate-50/50" x-ref="chatBox">
                <template x-if="!messages.length">
                    <div class="text-center py-8">
                        <p class="text-sm text-slate-400">Posez une question sur <strong>{{ $candidatNom }}</strong></p>
                        <p class="text-xs text-slate-300 mt-1">Ex: « Quels sont les points faibles de ce candidat ? »</p>
                    </div>
                </template>
                <template x-for="(msg, i) in messages" :key="i">
                    <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[80%] px-3 py-2 rounded-xl text-sm"
                            :class="msg.role === 'user'
                                ? 'bg-slate-900 text-white'
                                : 'bg-white border border-slate-200 text-slate-700'">
                            <p x-text="msg.content"></p>
                        </div>
                    </div>
                </template>
                <template x-if="loading">
                    <div class="flex justify-start">
                        <div class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-sm text-slate-400">
                            <span class="animate-pulse">L'assistant écrit...</span>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input -->
            <div class="border-t border-slate-100 p-4">
                <form @submit.prevent="sendMessage" class="flex items-center gap-2">
                    <input x-model="message" type="text" placeholder="Votre question..."
                        class="flex-1 form-input-xl" :disabled="loading" />
                    <button type="submit" class="btn-primary btn-sm" :disabled="loading || !message.trim()">
                        Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
