<?php
namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $pendingRequests = LeaveRequest::where('status', 'pending')->count();
            $totalRequests = LeaveRequest::count();
            $recentRequests = LeaveRequest::with('user')->latest()->take(5)->get();
            
            return view('dashboard.admin', compact('pendingRequests', 'totalRequests', 'recentRequests'));
        } else {
            $userRequests = $user->leaveRequests()->latest()->take(5)->get();
            $pendingCount = $user->leaveRequests()->where('status', 'pending')->count();
            $approvedCount = $user->leaveRequests()->where('status', 'approved')->count();
            
            return view('dashboard.employee', compact('userRequests', 'pendingCount', 'approvedCount'));
        }
    }
}