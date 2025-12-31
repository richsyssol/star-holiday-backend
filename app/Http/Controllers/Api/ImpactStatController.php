<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImpactStat;
use Illuminate\Http\Request;

class ImpactStatController extends Controller
{
    public function index()
    {
        $stats = ImpactStat::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($stat) {
                return [
                    'number' => (float) $stat->number,
                    'label' => $stat->label,
                    'suffix' => $stat->suffix,
                    'decimals' => $stat->decimals,
                ];
            });

        return response()->json($stats);
    }

    /**
     * Get active impact statistics
     * This method might be what you're trying to call
     */
    public function active()
    {
        $stats = ImpactStat::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($stat) {
                return [
                    'number' => (float) $stat->number,
                    'label' => $stat->label,
                    'suffix' => $stat->suffix,
                    'decimals' => $stat->decimals,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Active impact statistics retrieved successfully'
        ]);
    }

    /**
     * Alternative: Get all statistics (both active and inactive)
     */
    public function all()
    {
        $stats = ImpactStat::orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($stat) {
                return [
                    'number' => (float) $stat->number,
                    'label' => $stat->label,
                    'suffix' => $stat->suffix,
                    'decimals' => $stat->decimals,
                    'is_active' => $stat->is_active,
                ];
            });

        return response()->json($stats);
    }
}