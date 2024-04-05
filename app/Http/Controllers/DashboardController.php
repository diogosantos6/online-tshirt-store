<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $filterByYearRevenue = $request->year ?? '';
        $filterByYearOrders = $request->year ?? '';
        $filterByStatus = $request->status ?? '';
        $filterByCustomer = $request->customer ?? '';

        // Vendas de hoje correspondem às encomendas 'paid' e 'closed' de hoje
        $todayOrders = Order::whereRaw('Date(date) = CURDATE()')
            ->whereIn('status', ['closed', 'paid'])
            ->count();
        $todayOrders = number_format($todayOrders, 0, ',', '.');

        // Vendas todais correspondem às encomendas 'paid' e 'closed'
        $totalOrders = Order::whereIn('status', ['closed', 'paid'])->count();
        $totalOrders = number_format($totalOrders, 0, ',', '.');

        // Receita de hoje corresponde às encomendas 'paid' e 'closed' de hoje
        $todayRevenue = Order::whereRaw('Date(date) = CURDATE()')
            ->whereIn('status', ['closed', 'paid'])
            ->sum('total_price');
        $todayRevenue = number_format($todayRevenue, 2, ',', '.');

        // Receita total corresponde às encomendas 'paid' e 'closed'
        $totalRevenue = Order::whereIn('status', ['closed', 'paid'])->sum('total_price');
        $totalRevenue = number_format($totalRevenue, 2, ',', '.');

        //Query para o gráfico de encomendas por mês
        $ordersPerMonthQuery = Order::selectRaw('COUNT(*) as count, MONTH(date) as month')
            ->whereIn('status', ['closed', 'paid'])
            ->groupBy('month')
            ->orderBy('month');

        //Query para o gráfico de receitas por mês
        $revenuePerMonthQuery = Order::selectRaw('SUM(total_price) as count, MONTH(date) as month')
            ->whereIn('status', ['closed', 'paid'])
            ->groupBy('month')
            ->orderBy('month');

        //Query para a tabela de encomendas
        $orderQuery = Order::query();

        //Filtrar por status (tabela)
        if ($filterByStatus != '') {
            $orderQuery->where('status', 'LIKE', $filterByStatus);
        }

        //Filtrar por cliente (tabela)
        if ($filterByCustomer != '') {
            $customerIds = User::where('name', 'like', "%$filterByCustomer%")->pluck('id');
            $orderQuery->whereIntegerInRaw('customer_id', $customerIds);
        }

        //Filtrar por ano (query para o gráfico)
        if ($filterByYearOrders != '') {
            $ordersPerMonthQuery->whereYear('date', $filterByYearOrders);
        }

        //Filtrar por ano (query para o gráfico)
        if ($filterByYearRevenue != '') {
            $revenuePerMonthQuery->whereYear('date', $filterByYearRevenue);
        }

        //Paginação (tabela)
        $orders = $orderQuery->orderBy('date', 'desc')->take(10)->get();

        //Array com o número de encomendas por mês
        $ordersPerMonth = $ordersPerMonthQuery->pluck('count', 'month')->toArray();
        $ordersPerMonth = array_replace(array_fill(1, 12, 0), $ordersPerMonth);
        $ordersPerMonth = array_values($ordersPerMonth);
        $jsonOrdersPerMonth = json_encode($ordersPerMonth); //converter para json (usado no gráfico (js))

        //Array com o número de receita por mês
        $revenuePerMonth = $revenuePerMonthQuery->pluck('count', 'month')->toArray();
        $revenuePerMonth = array_replace(array_fill(1, 12, 0), $revenuePerMonth);
        $revenuePerMonth = array_values($revenuePerMonth);
        $jsonRevenuePerMonth = json_encode($revenuePerMonth); //converter para json (usado no gráfico (js))

        return view('dashboard.index', compact('orders', 'totalOrders', 'todayOrders', 'totalRevenue', 'todayRevenue', 'filterByStatus', 'filterByCustomer', 'filterByYearOrders', 'filterByYearRevenue', 'jsonOrdersPerMonth', 'jsonRevenuePerMonth'));
    }
}
