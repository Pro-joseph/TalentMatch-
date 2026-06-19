<?php

namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Candidat;
use App\Models\Offre;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $offresCount = Offre::where('user_id', $userId)->count();

        $candidatsCount = Candidat::where('user_id', $userId)->count();

        $analysesCount = Analyse::whereIn('offre_id', Offre::where('user_id', $userId)->select('id'))
            ->count();

        $avgScore = Analyse::whereIn('offre_id', Offre::where('user_id', $userId)->select('id'))
            ->where('status', 'done')
            ->avg('matching_score');

        $recentOffers = Offre::where('user_id', $userId)
            ->withCount(['analyses as candidats_count' => function ($q) {
                $q->select(DB::raw('count(distinct candidat_id)'));
            }])
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'offresCount',
            'candidatsCount',
            'analysesCount',
            'avgScore',
            'recentOffers',
        ));
    }
}
