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
    public function getDashboardData()
    {
        $data = [];

        // Panel Counters
        $_SESSION['requestRetailerCount'] = Cache::remember('requestRetailerCount', 86400, function () {
            return User::where(['active' => 0, 'status' => 0, 'level' => 200])->count();
        });

        $_SESSION['returnCount'] = Cache::remember('returnCount', 86400, function () {
            return Preturn::where('status', '<=', 2)->count();
        });

        // Dashboard Variables
        $data['totalPrimarySale'] = Cache::remember('totalPrimarySale', 86400, function () {
            return Purchase::count();
        });

        $data['monthlyPrimarySale'] = Cache::remember('monthlyPrimarySale', 86400, function () {
            return Purchase::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count();
        });

        $data['todayPrimarySale'] = Purchase::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->count();

        $data['totalSecondarySale'] = Cache::remember('totalSecondarySale', 86400, function () {
            return Sale::count();
        });

        $data['monthlySecondarySale'] = Cache::remember('monthlySecondarySale', 86400, function () {
            return Sale::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count();
        });

        $data['todaySecondarySale'] = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->count();

        $data['totalTertiarySale'] = Cache::remember('totalTertiarySale', 86400, function () {
            return Smsdetail::count();
        });

        $data['monthlyTertiarySale'] = Cache::remember('monthlyTertiarySale', 86400, function () {
            return Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))->count();
        });

        $data['todayTertiarySale'] = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))->count();


        // Charts
        $dayinmonthchartdata = Cache::remember('dayinmonthchartdata', 86400, function () {
            $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $result = [];

            for ($d = 1; $d <= $days; $d++) {
                $time = mktime(12, 0, 0, date('m'), $d, date('Y'));
                $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date('Y-m-d', $time))->count();

                $result[] = [
                    'year' => date('Y', $time),
                    'month' => date('m', $time),
                    'day' => date('d', $time),
                    'sale' => $count
                ];
            }

            return $result;
        });

        $monthinyearchartdata = Cache::remember('monthinyearchartdata', 86400, function () {
            $months = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            $result = [];

            foreach ($months as $month) {
                $count = Smsdetail::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-$month"))->count();
                $result[] = ['year' => date('Y'), 'month' => $month, 'sale' => $count];
            }

            return $result;
        });

        $monthlytopproductchart = Cache::remember('monthlytopproductchart', 86400, function () {
            return DB::table('smsdetails as t1')
                ->select(
                    DB::raw('COUNT(t1.product_id) as sale'),
                    DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
                )
                ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
                ->groupBy('product_id')
                ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
                ->take(5)
                ->get();
        });

        $monthlytopretailerchart = Cache::remember('monthlytopretailerchart', 86400, function () {
            return DB::table('smsdetails as t1')
                ->select(
                    DB::raw('COUNT(t1.user_id) as sale'),
                    DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,15), "-", officeid) FROM users WHERE id = t1.user_id) as user')
                )
                ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
                ->groupBy('user_id')
                ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
                ->take(5)
                ->get();
        });

        $todaybrandwisesalechart = Cache::remember('todaybrandwisesalechart', 86400, function () {
            $brands = Brand::select('id', 'name')->get();
            $result = [];

            foreach ($brands as $brand) {
                $sale = Smsdetail::where('brand_id', $brand->id)
                    ->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), date("Y-m-d"))
                    ->count();

                $result[] = ['name' => $brand->name, 'sale' => $sale];
            }

            return $result;
        });

        $monthlybrandwisesalechart = Cache::remember('monthlybrandwisesalechart', 86400, function () {
            $brands = Brand::select('id', 'name')->get();
            $result = [];

            foreach ($brands as $brand) {
                $sale = Smsdetail::where('brand_id', $brand->id)
                    ->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"), date("Y-m"))
                    ->count();

                $result[] = ['name' => $brand->name, 'sale' => $sale];
            }

            return $result;
        });

        $monthlytopproductsalechart = Cache::remember('monthlytopproductsalechart', 86400, function () {
            return DB::table('sales as t1')
                ->select(
                    DB::raw('COUNT(t1.product_id) as sale'),
                    DB::raw('(SELECT name FROM products WHERE id = t1.product_id) as product')
                )
                ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
                ->groupBy('product_id')
                ->orderBy(DB::raw('COUNT(t1.product_id)'), 'desc')
                ->take(5)
                ->get();
        });

        $monthlytopdistributorchart = Cache::remember('monthlytopdistributorchart', 86400, function () {
            return DB::table('sales as t1')
                ->select(
                    DB::raw('COUNT(t1.user_id) as sale'),
                    DB::raw('(SELECT CONCAT(SUBSTRING(firstname,1,18), "-", officeid) FROM users WHERE id = t1.user_id) as user')
                )
                ->where(DB::raw("DATE_FORMAT(t1.created_at,'%Y-%m')"), date("Y-m"))
                ->groupBy('user_id')
                ->orderBy(DB::raw('COUNT(t1.user_id)'), 'desc')
                ->take(5)
                ->get();
        });

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
