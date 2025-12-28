<?php

namespace App\Http\Controllers\Admin;

use App\Models\POSSession;
use App\Models\SessionApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionApprovalController extends Controller
{
    public function index()
    {
        $approvals = SessionApproval::with('posSession.shiftType', 'posSession.cashier', 'manager')->get();
        return view('pos.approvals.index', compact('approvals'));
    }

    public function create($sessionId)
    {
        $session = POSSession::findOrFail($sessionId);
        if ($session->status !== 'pending_approval') {
            return redirect()->route('pos.sessions.index')->with('error', 'Session is not pending approval.');
        }
        return view('pos.approvals.create', compact('session'));
    }

    public function store(Request $request, $sessionId)
    {
        $session = POSSession::findOrFail($sessionId);
        if ($session->status !== 'pending_approval') {
            return redirect()->route('pos.sessions.index')->with('error', 'Session is not pending approval.');
        }

        $request->validate([
            'delivered_amount' => 'required|numeric|min:0',
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string',
        ]);

        $approval = new SessionApproval();
        $approval->pos_session_id = $session->id;
        $approval->manager_id = Auth::id();
        $approval->delivered_amount = $request->delivered_amount;
        $approval->variance = $request->delivered_amount - $session->cash_expected;
        $approval->status = $request->status;
        $approval->comments = $request->comments;
        $approval->save();

        $session->status = $request->status === 'approved' ? 'closed' : 'rejected';
        $session->save();

        return redirect()->route('pos.approvals.index')->with('success', 'Session approval processed successfully.');
    }
}
