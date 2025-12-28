<?php

namespace App\Http\Controllers\Admin;

use App\Models\POSSession;
use App\Models\ShiftType;
use App\Models\CashMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;

class POSSessionController extends Controller
{


    public function dashboard()
    {
        Log::info("1");
        return view('pos.dashboard');
    }

    public function index()
    {
        Log::info("1");
        $sessions = POSSession::with('shiftType', 'cashier')->get();
        return view('pos.sessions.index', compact('sessions'));
    }

    public function create()
    {
        $shiftTypes = ShiftType::all();
        return view('pos.sessions.create', compact('shiftTypes'));
    }

    public function start(Request $request)
    {
        Log::info("1");
        $request->validate([
            'shift_type_id' => 'required|exists:shift_types,id',
            'device_id' => 'required',
            'opening_float' => 'nullable|numeric|min:0',
        ]);

        $session = new POSSession();
        $session->shift_type_id = $request->shift_type_id;
        $session->cashier_id = Auth::id();
        $session->device_id = $request->device_id;
        $session->start_time = Carbon::now();
        $session->opening_float = $request->opening_float ?? 0.00;
        $session->status = 'open';
        $session->save();

        return redirect()->route('pos.active')->with('success', 'POS Session started successfully.');
    }

    public function active()
    {
        Log::info("1");
        $session = POSSession::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$session) {
            return redirect()->route('pos.start')->with('error', 'No active session found. Please start a new session.');
        }

        return view('pos.active', compact('session'));
    }

    public function end(Request $request, $id)
    {
        Log::info("1");
        $session = POSSession::findOrFail($id);

        if ($session->cashier_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to end this session.');
        }


        $session->end_time = Carbon::now();
        $session->cash_actual = $request->cash_actual;
        $session->status = 'pending_approval';
        $session->total_sales = 0.00;
        $session->total_refunds = 0.00;
        $session->cash_expected = $session->opening_float + $session->total_sales - $session->total_refunds;
        $session->save();

        return redirect()->route('pos.summary', $session->id)->with('success', 'POS Session ended. Awaiting manager approval.');
    }

    public function summary($id)
    {
        Log::info("1");
        $session = POSSession::with('shiftType', 'cashier')->findOrFail($id);
        return view('pos.sessions.summary', compact('session'));
    }

    public function cashMovement(Request $request)
    {
        Log::info("1");
        $session = POSSession::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (!$session) {
            return redirect()->route('pos.start')->with('error', 'No active session found. Please start a new session.');
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'type' => 'required|in:in,out',
                'description' => 'nullable|string',
            ]);

            $movement = new CashMovement();
            $movement->pos_session_id = $session->id;
            $movement->amount = $request->amount;
            $movement->type = $request->type;
            $movement->description = $request->description;
            $movement->timestamp = Carbon::now();
            $movement->save();

            return redirect()->route('pos.cash.movement')->with('success', 'Cash movement recorded successfully.');
        }

        $movements = CashMovement::where('pos_session_id', $session->id)->latest()->get();
        return view('pos.cash_movement', compact('movements'));
    }

    public function checkActiveSession()
    {
        $activeSession = POSSession::with('shiftType')
            ->where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->first();

        return response()->json([
            'hasActiveSession' => !!$activeSession,
            'session' => $activeSession
        ]);
    }

    /**
     * API: Start a new POS session (SPA request)
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'shift_type_id' => 'required|exists:shift_types,id',
            'starting_cash' => 'nullable|numeric|min:0',
        ]);

        $existing = POSSession::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->first();
        if ($existing) {
            return response()->json(['error' => 'There is already an active session.'], 409);
        }

        $session = new POSSession();
        $session->shift_type_id = $validated['shift_type_id'];
        $session->cashier_id = Auth::id();
        $session->device_id = $request->device_id ?? request()->header('User-Agent');
        $session->start_time = Carbon::now();
        $session->opening_float = $validated['starting_cash'] ?? 0.00;
        $session->status = 'open';
        $session->save();

        return response()->json(['message' => 'POS Session started successfully', 'data' => $session], 201);
    }

    /**
     * API: List sessions with filters for reporting
     */
    public function apiReport(Request $request)
    {
        $query = POSSession::with(['shiftType', 'cashier']);

        if ($request->filled('cashier_id')) {
            $query->where('cashier_id', $request->cashier_id);
        }
        if ($request->filled('shift_type_id')) {
            $query->where('shift_type_id', $request->shift_type_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('start_time', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('start_time', '<=', $request->to);
        }

        return response()->json($query->latest()->paginate(25));
    }

    /**
     * API: Get all POS sessions (for list view)
     */
    public function apiIndex()
    {
        $sessions = POSSession::with(['shiftType', 'cashier'])
            ->latest()
            ->get();

        return response()->json($sessions);
    }

    /**
     * API: Get dashboard statistics for POS
     */
    public function getDashboardStats()
    {
        $activeSession = POSSession::where('cashier_id', Auth::id())
            ->where('status', 'open')
            ->first();

        $totalSessions = POSSession::count();
        $pendingApprovals = POSSession::where('status', 'pending_approval')->count();
        $activeSessions = POSSession::where('status', 'open')->count();

        return response()->json([
            'activeSession' => $activeSession,
            'stats' => [
                'totalSessions' => $totalSessions,
                'pendingApprovals' => $pendingApprovals,
                'activeSessions' => $activeSessions,
            ]
        ]);
    }

    /**
     * API: Get list of sessions pending approval
     */
    public function apiApprovalsIndex(Request $request)
    {
        $query = POSSession::with(['shiftType', 'cashier'])
            ->where('status', 'pending_approval');

        if ($request->filled('cashier_id')) {
            $query->where('cashier_id', $request->cashier_id);
        }
        if ($request->filled('shift_type_id')) {
            $query->where('shift_type_id', $request->shift_type_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('start_time', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('start_time', '<=', $request->to);
        }

        return response()->json($query->latest()->paginate(25));
    }

    /**
     * API: Record a new cash movement
     */
    public function apiRecordMovement(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:in,out',
            'description' => 'nullable|string|max:255',
        ]);

        $session = POSSession::findOrFail($sessionId);

        if (Auth::id() !== $session->cashier_id && !Auth::user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized access to session data'], 403);
        }

        $movement = new CashMovement();
        $movement->pos_session_id = $session->id;
        $movement->amount = $validated['amount'];
        $movement->type = $validated['type'];
        $movement->description = $validated['description'] ?? null;
        $movement->timestamp = now();
        $movement->save();

        return response()->json([
            'message' => 'Cash movement recorded successfully',
            'data' => $movement
        ], 201);
    }

    /**
     * Get cash movements for a specific session
     *
     * @param int $sessionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiCashMovements($sessionId)
    {
        $session = POSSession::findOrFail($sessionId);

        if (Auth::id() !== $session->cashier_id && !Auth::user()->can('view all pos sessions')) {
            return response()->json(['error' => 'Unauthorized to view movements for this session'], 403);
        }

        $movements = CashMovement::where('pos_session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $movements,
            'session' => [
                'opening_float' => $session->opening_float,
                'total_cash' => $session->total_cash,
                'total_card' => $session->total_card,
                'total_online' => $session->total_online,
                'total_sales' => $session->total_sales,
                'total_expenses' => $session->total_expenses,
                'closing_balance' => $session->closing_balance,
            ]
        ]);
    }

    public function exportCsv(Request $request)
    {
        $sessions = POSSession::with('shiftType', 'cashier')
            ->when($request->filled('start_date'), function ($query) use ($request) {
                return $query->whereDate('start_time', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                return $query->whereDate('start_time', '<=', $request->end_date);
            })
            ->get();

        $filename = 'pos_sessions_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($sessions) {
            $file = fopen('php://output', 'w');


            fputcsv($file, ['ID', 'Cashier', 'Shift Type', 'Start Time', 'End Time', 'Status', 'Opening Float', 'Total Sales', 'Total Refunds', 'Cash Expected', 'Cash Actual', 'Difference']);


            foreach ($sessions as $session) {
                fputcsv($file, [
                    $session->id,
                    $session->cashier->name ?? 'N/A',
                    $session->shiftType->name ?? 'N/A',
                    $session->start_time,
                    $session->end_time,
                    ucfirst($session->status),
                    number_format($session->opening_float, 2),
                    number_format($session->total_sales, 2),
                    number_format($session->total_refunds, 2),
                    number_format($session->cash_expected, 2),
                    $session->cash_actual ? number_format($session->cash_actual, 2) : 'N/A',
                    $session->cash_actual ? number_format($session->cash_expected - $session->cash_actual, 2) : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
