<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ServiceProvider;
use App\Models\User;
use App\Models\MainCategory;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $users_count = User::whereDoesntHave('roles')->count();
        $male_count = User::whereDoesntHave('roles')->where('sex', 'm')->count();
        $female_count = User::whereDoesntHave('roles')->where('sex', 'f')->count();
        $banned_count = User::whereDoesntHave('roles')->where('status', 'inactive')->count();
        $main_categories_count = MainCategory::count();
        $sub_categories_count = Category::count();
        $pending_categories = Category::inactive()->count();
        $providers_count = ServiceProvider::count();
        $pending_providers_count = ServiceProvider::inactive()->count();
        $orders_today = Order::whereDay('created_at', '=', today())->count();
        $orders_this_month = Order::whereMonth('created_at', '=', Carbon::now()->month)->count();
        $orders_this_year = Order::whereYear('created_at', '=', Carbon::now()->year)->count();
        $orders_total_sales = Order::where('status','success')->sum('sum');
        $orders_total_sales_today = Order::where('status','success')->whereDay('created_at',today())->sum('sum');
        $orders_total_sales_this_month = Order::where('status','success')->whereMonth('created_at',Carbon::now()->month)->sum('sum');
        $failed_orders = Order::selectRaw('COUNT(*) , STATUS ')->groupBy('STATUS')->get();
        $today_profit = Order::where('status','success')->whereDay('created_at',today())->sum('profit');
        $this_month_profit = Order::where('status','success')->whereMonth('created_at',Carbon::now()->month)->sum('profit');
        $overall_profit = Order::where('status','success')->sum('profit');
        $year = now()->year;

        $successOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->where('status', 'success')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();

        $otherOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->where('status', '!=', 'success')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $successOrderCount = [];
        $othersOrdersCount = [];
        for ($i = 1; $i <= 12; $i++) {
            $successOrderCount[] = $successOrders[$i] ?? 0;
            $othersOrdersCount[] = $otherOrders[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'users_count',
            'male_count',
            'female_count',
            'banned_count',
            'main_categories_count',
            'sub_categories_count',
            'pending_categories',
            'providers_count',
            'pending_providers_count',
            'orders_today',
            'orders_this_month',
            'orders_this_year',
            'successOrderCount',
            'othersOrdersCount',
            'overall_profit',
            'this_month_profit',
            'today_profit',
            'orders_total_sales_this_month',
            'orders_total_sales_today',
            'orders_total_sales',
            'failed_orders',
        ));
    }
}
