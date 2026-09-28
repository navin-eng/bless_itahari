<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$router = app('router');
$routes = $router->getRoutes()->getRoutesByMethod()['GET'];

$baseUrl = 'http://localhost'; // We can use the test client

echo "Starting route tests...\n";

$failedRoutes = [];
$testedCount = 0;

foreach ($routes as $route) {
    // Skip routes with parameters like {id} for now, or provide dummy values
    $uri = $route->uri();

    // Skip telescope, ignition, etc.
    if (strpos($uri, '_ignition') !== false || strpos($uri, 'sanctum') !== false || strpos($uri, 'api/') === 0) {
        continue;
    }

    // Skip routes with required parameters that we don't know
    if (preg_match('/\{[^\}]+\}/', $uri) && !preg_match('/\{[^\}]+\?\}/', $uri)) {
        // Has required param, skip for basic crawler unless we want to inject dummy IDs
        continue;
    }

    $url = preg_replace('/\{[^\}]+\?\}/', '', $uri); // Remove optional params

    try {
        $request = Illuminate\Http\Request::create($url, 'GET');
        $response = app()->handle($request);
        $status = $response->getStatusCode();

        $testedCount++;

        if ($status >= 500) {
            echo "[500 ERROR] /" . $url . "\n";
            $failedRoutes[] = $url;
        } else {
            // echo "[SUCCESS] " . $status . " /" . $url . "\n";
        }
    } catch (\Throwable $e) {
        echo "[FATAL ERROR] /" . $url . " - " . $e->getMessage() . "\n";
        $failedRoutes[] = $url;
    }
}

echo "\nTested $testedCount parameterless GET routes.\n";
if (empty($failedRoutes)) {
    echo "Result: ALL PASSED (No 500 errors found in standard GET routes).\n";
} else {
    echo "Result: FOUND ERRORS in " . count($failedRoutes) . " routes.\n";
}
