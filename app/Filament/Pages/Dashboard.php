<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string $resource = \App\Filament\Resources\StudentResource::class;

    public function getHeaderWidgets(): array
    {
        // Return different widgets based on user role
        if (auth()->user()->hasRole('health_coordinator')) {
            return [
                // Coordinator-specific widgets
            ];
        }

        // Admin widgets
        return [
            // Admin-specific widgets
        ];
    }
}
