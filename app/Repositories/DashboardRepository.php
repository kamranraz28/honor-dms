<?php

namespace App\Repositories;

use App\User;
use App\Brand;
use App\Sale;
use App\Purchase;
use App\Preturn;
use App\Smsdetail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    public function getDashboardData($brandId = null, $startDate = null, $endDate = null)
    {
        $data = [];

        $hasBrand = !empty($brandId);
        $hasDateRange = !empty($startDate) && !empty($endDate);
        $useCache = !$hasBrand && !$hasDateRange;

        // helpers for building filtered queries
        $brandFilter = function ($q) use ($hasBrand, $brandId) {
            if ($hasBrand) $q->where('brand_id', $brandId);
        };
        $dateFilter = function ($q, $field = 'created_at') use ($hasDateRange, $startDate, $endDate) {
            if ($hasDateRange) $q->whereBetween(DB::raw("DATE($field)"), [$startDate, $endDate]);
        };

        // Panel Counters (not affected by brand/date filters)
        $_SESSION['requestRetailerCount'] = Cache::remember('requestRetailerCount', 86400, function () {
            return User::where(['active' => 0, 'status' => 0, 'level' => 200])->count();
        });

        $_SESSION['returnCount'] = Cache::remember('returnCount', 86400, function () {
            return Preturn::where('status', '<=', 2)->count();
        });

        // Shared query scope for brand + date range
        $scope = function ($q) use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            if ($hasBrand) $q->where('brand_id', $brandId);
            if ($hasDateRange) $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        };

        // Dashboard Variables
        $data['totalPrimarySale'] = $useCache
            ? Cache::remember('totalPrimarySale', 86400, fn() => Purchase::count())
            : Purchase::where($scope)->count();

        $data['monthlyPrimarySale'] = $useCache
            ? Cache::remember('monthlyPrimarySale', 86400, fn() => Purchase::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count())
            : Purchase::when(!$hasDateRange, fn($q) => $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m")))
                ->where($scope)
                ->count();

        $data['todayPrimarySale'] = Purchase::when(!$hasDateRange, fn($q) => $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d")))
            ->where($scope)
            ->count();

        $data['totalSecondarySale'] = $useCache
            ? Cache::remember('totalSecondarySale', 86400, fn() => Sale::count())
            : Sale::where($scope)->count();

        $data['monthlySecondarySale'] = $useCache
            ? Cache::remember('monthlySecondarySale', 86400, fn() => Sale::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count())
            : Sale::when(!$hasDateRange, fn($q) => $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m")))
                ->where($scope)
                ->count();

        $data['todaySecondarySale'] = Smsdetail::when(!$hasDateRange, fn($q) => $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d")))
            ->where($scope)
            ->count();

        $data['totalTertiarySale'] = $useCache
            ? Cache::remember('totalTertiarySale', 86400, fn() => Smsdetail::count())
            : Smsdetail::where($scope)->count();

        $data['monthlyTertiarySale'] = $useCache
            ? Cache::remember('monthlyTertiarySale', 86400, fn() => Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count())
            : Smsdetail::when(!$hasDateRange, fn($q) => $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m")))
                ->where($scope)
                ->count();

        $data['todayTertiarySale'] = Smsdetail::when(!$hasDateRange, fn($q) => $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d")))
            ->where($scope)
            ->count();

        // Build scope helpers for charts
        $scopeTertiary = function ($q) use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            if ($hasBrand) $q->where('brand_id', $brandId);
            if ($hasDateRange) $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        };

        $scopeSale = function ($q) use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            if ($hasBrand) $q->where('brand_id', $brandId);
            if ($hasDateRange) $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
        };

        // Charts – when date range is provided, skip the "current month"/"today" conditions
        $dayinmonthchartdata = $useCache
            ? Cache::remember('dayinmonthchartdata', 86400, function () {
                $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                $result = [];
                for ($d = 1; $d <= $days; $d++) {
                    $time = mktime(12, 0, 0, date('m'), $d, date('Y'));
                    $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date('Y-m-d', $time))->count();
                    $result[] = ['year' => date('Y', $time), 'month' => date('m', $time), 'day' => date('d', $time), 'sale' => $count];
                }
                return $result;
            })
            : function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
                $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                $result = [];
                for ($d = 1; $d <= $days; $d++) {
                    $time = mktime(12, 0, 0, date('m'), $d, date('Y'));
                    $dayStr = date('Y-m-d', $time);
                    $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), $dayStr)
                        ->when($hasBrand, fn($q) => $q->where('brand_id', $brandId))
                        ->when($hasDateRange, fn($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]))
                        ->count();
                    $result[] = ['year' => date('Y', $time), 'month' => date('m', $time), 'day' => date('d', $time), 'sale' => $count];
                }
                return $result;
            };
        if (is_callable($dayinmonthchartdata)) $dayinmonthchartdata = $dayinmonthchartdata();

        $monthinyearchartdata = $useCache
            ? Cache::remember('monthinyearchartdata', 86400, function () {
                $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
                $result = [];
                foreach ($months as $month) {
                    $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-$month"))->count();
                    $result[] = ['year' => date('Y'), 'month' => $month, 'sale' => $count];
                }
                return $result;
            })
            : function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
                $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
                $result = [];
                foreach ($months as $month) {
                    $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-$month"))
                        ->when($hasBrand, fn($q) => $q->where('brand_id', $brandId))
                        ->when($hasDateRange, fn($q) => $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]))
                        ->count();
                    $result[] = ['year' => date('Y'), 'month' => $month, 'sale' => $count];
                }
                return $result;
            };
        if (is_callable($monthinyearchartdata)) $monthinyearchartdata = $monthinyearchartdata();

        $todaybrandwisesalechart = function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            $brands = Brand::select('id', 'name')->get();
            $result = [];
            foreach ($brands as $brand) {
                $q = Smsdetail::where('brand_id', $brand->id);
                if ($hasDateRange) {
                    $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
                } else {
                    $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"));
                }
                $result[] = ['name' => $brand->name, 'sale' => $q->count()];
            }
            return $result;
        };

        $monthlybrandwisesalechart = function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            $brands = Brand::select('id', 'name')->get();
            $result = [];
            foreach ($brands as $brand) {
                $q = Smsdetail::where('brand_id', $brand->id);
                if ($hasDateRange) {
                    $q->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
                } else {
                    $q->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"));
                }
                $result[] = ['name' => $brand->name, 'sale' => $q->count()];
            }
            return $result;
        };

        $monthlytopproductchart = function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            $q = DB::table('smsdetails as t1')
                ->select(
                    DB::raw('COUNT(t1.product_id) as sale'),
                    DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
                );
            if ($hasDateRange) {
                $q->whereBetween(DB::raw('DATE(t1.created_at)'), [$startDate, $endDate]);
            } else {
                $q->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"));
            }
            return $q->when($hasBrand, fn($q) => $q->where('t1.brand_id', $brandId))
                ->groupBy('product_id')
                ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
                ->take(5)
                ->get();
        };

        $monthlytopretailerchart = function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            $q = DB::table('smsdetails as t1')
                ->select(
                    DB::raw('COUNT(t1.user_id) as sale'),
                    DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,15), "-", officeid) FROM users WHERE id = t1.user_id) as user')
                );
            if ($hasDateRange) {
                $q->whereBetween(DB::raw('DATE(t1.created_at)'), [$startDate, $endDate]);
            } else {
                $q->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"));
            }
            return $q->when($hasBrand, fn($q) => $q->where('t1.brand_id', $brandId))
                ->groupBy('user_id')
                ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
                ->take(5)
                ->get();
        };

        $monthlytopproductsalechart = function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            $q = DB::table('sales as t1')
                ->select(
                    DB::raw('COUNT(t1.product_id) as sale'),
                    DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
                );
            if ($hasDateRange) {
                $q->whereBetween(DB::raw('DATE(t1.created_at)'), [$startDate, $endDate]);
            } else {
                $q->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"));
            }
            return $q->when($hasBrand, fn($q) => $q->where('t1.brand_id', $brandId))
                ->groupBy('product_id')
                ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
                ->take(5)
                ->get();
        };

        $monthlytopdistributorchart = function () use ($hasBrand, $brandId, $hasDateRange, $startDate, $endDate) {
            $q = DB::table('sales as t1')
                ->select(
                    DB::raw('COUNT(t1.user_id) as sale'),
                    DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,18), "-", officeid) FROM users WHERE id = t1.user_id) as user')
                );
            if ($hasDateRange) {
                $q->whereBetween(DB::raw('DATE(t1.created_at)'), [$startDate, $endDate]);
            } else {
                $q->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"));
            }
            return $q->when($hasBrand, fn($q) => $q->where('t1.brand_id', $brandId))
                ->groupBy('user_id')
                ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
                ->take(5)
                ->get();
        };

        // Always call brand-wise charts (not cached)
        $todaybrandwisesalechart = $todaybrandwisesalechart();
        $monthlybrandwisesalechart = $monthlybrandwisesalechart();
        $monthlytopproductchart = $monthlytopproductchart();
        $monthlytopretailerchart = $monthlytopretailerchart();
        $monthlytopproductsalechart = $monthlytopproductsalechart();
        $monthlytopdistributorchart = $monthlytopdistributorchart();

        return compact(
            'data',
            'dayinmonthchartdata',
            'monthinyearchartdata',
            'monthlytopproductchart',
            'monthlytopretailerchart',
            'todaybrandwisesalechart',
            'monthlybrandwisesalechart',
            'monthlytopproductsalechart',
            'monthlytopdistributorchart'
        );
    }
}
