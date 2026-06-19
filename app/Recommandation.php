<?php

namespace App;

enum Recommandation: string
{
    case Recommande = 'recommandé';
    case Reserve = 'réservé';
    case NonRetenu = 'non_retenu';

    public function label(): string
    {
        return match ($this) {
            self::Recommande => 'Recommandé',
            self::Reserve => 'Réservé',
            self::NonRetenu => 'Non retenu',
        };
    }
}
