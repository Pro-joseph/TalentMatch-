<div
    x-data="{
        open: false,
        nom: '',
        cvTexte: '',
        analyseId: null,
        polling: null,
        status: 'idle',
        error: null,

        async submitForm() {
            if (!this.nom.trim() || !this.cvTexte.trim()) return;

            this.status = 'loading';
            this.error = null;

            const formData = new FormData();
            formData.append('nom', this.nom);
            formData.append('cv_texte', this.cvTexte);

            try {
                const response = await fetch('{{ route('offres.submit-cv', $offre) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData,
                });

                if (!response.ok) throw new Error('Erreur lors de la soumission');

                const html = await response.text();

                // Extract analyse_id from the redirect URL or flash message
                this.analyseId = '{{ session('analyse_id') }}';
                this.status = 'polling';
                this.startPolling();
            } catch (e) {
                this.error = e.message;
                this.status = 'error';
            }
        },

        startPolling() {
            this.polling = setInterval(async () => {
                try {
                    const resp = await fetch('/analyses/' + this.analyseId + '/status');
                    const data = await resp.json();

                    if (data.status === 'done') {
                        clearInterval(this.polling);
                        window.location.href = '/analyses/' + this.analyseId;
                    } else if (data.status === 'failed') {
                        clearInterval(this.polling);
                        this.status = 'error';
                        this.error = 'L\'analyse a échoué. Veuillez réessayer.';
                    }
                } catch {
                    clearInterval(this.polling);
                    this.status = 'error';
                    this.error = 'Erreur de connexion. Veuillez rafraîchir la page.';
                }
            }, 2000);
        }
    }"
>
    <!-- Trigger button -->
    <button @click="open = true" type="button" class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Soumettre un CV
    </button>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div @click="open = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 sm:p-8" @click.stop>
            <h3 class="font-display font-semibold text-slate-950 text-lg mb-1">Soumettre un CV</h3>
            <p class="text-sm text-slate-500 mb-6">Saisissez les informations du candidat pour lancer l'analyse IA.</p>

            <!-- Loading state -->
            <template x-if="status === 'loading' || status === 'polling'">
                <div class="text-center py-10">
                    <svg class="animate-spin w-10 h-10 mx-auto text-slate-400 mb-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <p class="text-sm font-medium text-slate-700" x-text="status === 'loading' ? 'Soumission en cours…' : 'Analyse en cours…'"></p>
                    <p class="text-xs text-slate-400 mt-1" x-show="status === 'polling'">L'IA analyse le CV. Cela peut prendre quelques instants.</p>
                </div>
            </template>

            <!-- Error state -->
            <template x-if="status === 'error'">
                <div class="text-center py-6">
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-red-700" x-text="error"></p>
                    <button @click="status = 'idle'; error = null" type="button" class="btn-ghost mt-4 text-sm">Réessayer</button>
                </div>
            </template>

            <!-- Form -->
            <template x-if="status === 'idle'">
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label for="cv-nom" class="form-label">Nom du candidat</label>
                        <input id="cv-nom" x-model="nom" type="text" required
                            class="form-input-xl" placeholder="Ex: Omar Benali" />
                    </div>
                    <div>
                        <label for="cv-texte" class="form-label">CV (texte)</label>
                        <textarea id="cv-texte" x-model="cvTexte" rows="8" required
                            class="form-input-xl resize-y" placeholder="Collez le contenu du CV ici…"></textarea>
                    </div>
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn-primary">Analyser</button>
                        <button @click="open = false" type="button" class="btn-ghost">Annuler</button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</div>
