<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'competences_requises',
        'experience_min',
    ];

    protected function casts(): array
    {
        return [
            'competences_requises' => 'array',
            'experience_min' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(Analyse::class);
    }
}
