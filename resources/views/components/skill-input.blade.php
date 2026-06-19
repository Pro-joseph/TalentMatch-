@props(['name' => 'competences_requises', 'existing' => []])

@php
    $existing = is_array($existing) ? $existing : [];
@endphp

<div
    x-data="{
        tags: {{ json_encode($existing) }},
        input: '',
        addTag() {
            const tag = this.input.trim().toLowerCase();
            if (tag && !this.tags.includes(tag)) {
                this.tags.push(tag);
            }
            this.input = '';
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        handleKeydown(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                this.addTag();
            }
            if (e.key === 'Backspace' && !this.input && this.tags.length) {
                this.tags.pop();
            }
        }
    }"
    class="flex flex-wrap items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm focus-within:border-slate-400 focus-within:ring-2 focus-within:ring-slate-300/50 transition-all duration-200"
    role="listbox"
    aria-label="Compétences requises"
>
    <template x-for="(tag, index) in tags" :key="index">
        <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 border border-slate-200">
            <span x-text="tag"></span>
            <button @click="removeTag(index)" type="button" class="text-slate-400 hover:text-slate-600" :aria-label="'Supprimer ' + tag">&times;</button>
        </span>
    </template>
    <input
        x-model="input"
        @keydown="handleKeydown"
        type="text"
        class="min-w-[120px] flex-1 border-0 bg-transparent p-0 text-sm outline-none placeholder:text-slate-400"
        placeholder="Saisir une compétence…"
        aria-label="Ajouter une compétence"
    />
    <template x-for="(tag, index) in tags" :key="index">
        <input type="hidden" :name="'{{ $name }}[]'" :value="tag" />
    </template>
</div>
