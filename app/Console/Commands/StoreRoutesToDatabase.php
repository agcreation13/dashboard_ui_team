<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class StoreRoutesToDatabase extends Command
{
    // php artisan route:store
    protected $signature = 'routes:store';

    protected $description = 'Store all route URLs into the database';

    public function handle()
    {
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            $uri = $route->uri(); 

        // Skip unwanted routes like debugbar or _ignition
    if (str_starts_with($uri, '_') || str_contains($uri, 'debugbar')) {
        continue;
    }

       $routeName = $route->getName();

    // Check if the route has a name (it should be in the format 'leadsSheet.Index')
    if ($routeName) {
        // Split the route name by '.' to separate the resource and action
        $nameParts = explode('.', $routeName);

        // Create the title by joining the resource and action with a dash
        $title = implode('-', $nameParts);
    } else {
        // If no name, use the URI as a fallback title (this is optional)
        $segments = collect(explode('/', $uri))
            ->filter(fn($segment) => !in_array($segment, ['dashboard', 'admin'])) // Remove base segments
            ->values(); // Re-index the array after filtering
        $title = $segments->implode('-');
    }

    // Remove repetitive segments
    $titleParts = explode('-', $title);
    $titleParts = array_unique($titleParts); // Remove duplicates
    $title = implode('-', $titleParts); // Rebuild the title from unique segments

            // Insert or ignore if already exists
            DB::table('route_u_r_l_lists')->updateOrInsert(
                ['url_name' => $uri],
                ['title'      => $title, 
                  'updated_at' => now(), 
                  'created_at' => now()
                ]
            );
        }

        $this->info('All routes have been stored in the database.');
    }
}
