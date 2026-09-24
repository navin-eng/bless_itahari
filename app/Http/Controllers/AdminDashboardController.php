<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\RunReportRequest;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::current();
        
        // Hide dashboard widget completely if disabled
        if (!$settings->enable_analytics) {
            return view('backend.pages.index', [
                'analyticsData' => null,
                'analyticsError' => null,
                'analyticsDisabled' => true,
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

        return view('backend.pages.index', compact('analyticsData', 'analyticsError'));
    }
}
