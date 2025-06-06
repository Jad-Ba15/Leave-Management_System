<?php
namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $leaveRequests = LeaveRequest::with('user')->latest()->paginate(10);
        } else {
            $leaveRequests = Auth::user()->leaveRequests()->latest()->paginate(10);
        }
        
        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('leave-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:sick,vacation,personal,emergency',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_requested' => $daysRequested,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        // Check if user can view this request
        if (!Auth::user()->isAdmin() && $leaveRequest->user_id !== Auth::id()) {
            abort(403);
        }

        return view('leave-requests.show', compact('leaveRequest'));
    }

    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        // Only admins can update status
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_comment' => 'nullable|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => $validated['status'],
            'admin_comment' => $validated['admin_comment'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request ' . $validated['status'] . ' successfully!');
    }
}