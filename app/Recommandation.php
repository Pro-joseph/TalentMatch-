<?php

namespace App;

enum Recommandation: string
{
    case Recommande = 'recommandé';
    case Reserve = 'réservé';
    case NonRetenu = 'non_retenu';
}
