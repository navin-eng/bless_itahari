<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\RunReportRequest;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::current();
        
        // Site Health & Storage Metrics (Cached for 10 minutes)
        $siteHealth = Cache::remember('admin_site_health_metrics', 600, function () {
            return $this->calculateSiteHealth();
        });

        // Hide analytics dashboard widget completely if disabled
        if (!$settings->enable_analytics) {
            return view('backend.pages.index', [
                'analyticsData' => null,
                'analyticsError' => null,
                'analyticsDisabled' => true,
                'siteHealth' => $siteHealth,
            ]);
        }

        $analyticsData = null;
        $analyticsError = null;

        if (!empty($settings->analytics_property_id)) {
            $credentialsPath = storage_path('app/analytics/service-account-credentials.json');
            
            if (file_exists($credentialsPath)) {
                try {
                    $client = new BetaAnalyticsDataClient([
                        'credentials' => $credentialsPath,
                    ]);

                    $request = (new RunReportRequest())
                        ->setProperty('properties/' . $settings->analytics_property_id)
                        ->setDateRanges([
                            new DateRange([
                                'start_date' => '30daysAgo',
                                'end_date' => 'today',
                            ]),
                        ])
                        ->setMetrics([
                            new Metric(['name' => 'activeUsers']),
                            new Metric(['name' => 'screenPageViews']),
                            new Metric(['name' => 'sessions']),
                            new Metric(['name' => 'newUsers']),
                        ]);

                    $response = $client->runReport($request);

                    if (count($response->getRows()) > 0) {
                        $row = $response->getRows()[0];
                        $analyticsData = [
                            'activeUsers' => $row->getMetricValues()[0]->getValue(),
                            'screenPageViews' => $row->getMetricValues()[1]->getValue(),
                            'sessions' => $row->getMetricValues()[2]->getValue(),
                            'newUsers' => $row->getMetricValues()[3]->getValue(),
                        ];
                    } else {
                        $analyticsData = ['activeUsers' => 0, 'screenPageViews' => 0, 'sessions' => 0, 'newUsers' => 0];
                    }
                } catch (\Exception $e) {
                    $analyticsError = $e->getMessage();
                }
            } else {
                $analyticsError = "Credentials file not found at: storage/app/analytics/service-account-credentials.json";
            }
        }

        return view('backend.pages.index', compact('analyticsData', 'analyticsError', 'siteHealth'));
    }

    public function clearCache()
    {
        try {
            Artisan::call('optimize:clear');
            Cache::forget('admin_site_health_metrics');
            return back()->with('success', 'System cache purged and Site Health metrics refreshed!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    private function calculateSiteHealth()
    {
        // Image & Media File Scanning
        $imageCount = 0;
        $imageSize = 0;
        $docCount = 0;
        $docSize = 0;

        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'ico', 'avif'];
        $docExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip'];

        $scanDirectories = [
            public_path('uploads'),
            public_path('frontend'),
            public_path('backend'),
            storage_path('app/public'),
        ];

        foreach ($scanDirectories as $dir) {
            if (is_dir($dir)) {
                try {
                    $iterator = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::SELF_FIRST
                    );

                    foreach ($iterator as $file) {
                        if ($file->isFile()) {
                            $ext = strtolower($file->getExtension());
                            $size = $file->getSize();

                            if (in_array($ext, $imageExtensions)) {
                                $imageCount++;
                                $imageSize += $size;
                            } elseif (in_array($ext, $docExtensions)) {
                                $docCount++;
                                $docSize += $size;
                            }
                        }
                    }
                } catch (\Throwable $t) {
                    // Ignore inaccessible subfolders safely
                }
            }
        }

        // Database Size Calculation
        $dbSizeMB = 0;
        try {
            $dbName = DB::getDatabaseName();
            $result = DB::select("SELECT SUM(data_length + index_length) as db_size FROM information_schema.TABLES WHERE table_schema = ?", [$dbName]);
            if (!empty($result) && isset($result[0]->db_size)) {
                $dbSizeMB = round($result[0]->db_size / (1024 * 1024), 2);
            }
        } catch (\Throwable $e) {
            $dbSizeMB = 0;
        }

        // Server Disk Space
        $freeDisk = @disk_free_space(base_path());
        $totalDisk = @disk_total_space(base_path());
        $usedDiskPercent = 0;
        if ($freeDisk !== false && $totalDisk !== false && $totalDisk > 0) {
            $usedDiskPercent = round((($totalDisk - $freeDisk) / $totalDisk) * 100, 1);
        }

        // Health Checks & Score Calculation
        $checks = [];
        $score = 100;

        // Debug mode check
        $debugMode = config('app.debug');
        if ($debugMode) {
            $checks[] = ['type' => 'warning', 'message' => 'Debug Mode ON (Should be OFF in production)'];
            $score -= 15;
        } else {
            $checks[] = ['type' => 'success', 'message' => 'Debug Mode OFF (Production Secure)'];
        }

        // PHP Version Check
        $phpVersion = PHP_VERSION;
        if (version_compare($phpVersion, '8.1.0', '>=')) {
            $checks[] = ['type' => 'success', 'message' => "PHP v{$phpVersion} active"];
        } else {
            $checks[] = ['type' => 'warning', 'message' => "PHP v{$phpVersion} (Upgrade recommended)"];
            $score -= 10;
        }

        // HTTPS Check
        $isHttps = request()->isSecure() || request()->header('X-Forwarded-Proto') === 'https';
        if ($isHttps) {
            $checks[] = ['type' => 'success', 'message' => 'HTTPS / SSL Encryption Enabled'];
        } else {
            $checks[] = ['type' => 'warning', 'message' => 'HTTPS not detected'];
            $score -= 10;
        }

        // Cache driver check
        $cacheDriver = config('cache.default', 'file');
        $checks[] = ['type' => 'info', 'message' => "Cache Driver: " . ucfirst($cacheDriver)];

        return [
            'image_count' => $imageCount,
            'image_size' => $this->formatBytes($imageSize),
            'image_raw_size' => $imageSize,
            'doc_count' => $docCount,
            'doc_size' => $this->formatBytes($docSize),
            'db_size' => $dbSizeMB > 0 ? $dbSizeMB . ' MB' : 'N/A',
            'disk_free' => $freeDisk !== false ? $this->formatBytes($freeDisk) : 'N/A',
            'disk_total' => $totalDisk !== false ? $this->formatBytes($totalDisk) : 'N/A',
            'disk_used_percent' => $usedDiskPercent,
            'health_score' => max(40, $score),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'checks' => $checks,
            'last_updated' => now()->format('h:i A'),
        ];
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

