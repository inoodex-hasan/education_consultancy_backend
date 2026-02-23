<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\{Application, OfficeAccount, OfficeTransaction, Payment, Setting, Student, User};
use App\Services\CommissionService;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->middleware('can:*accountant');
        $this->commissionService = $commissionService;
    }

    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Payment::with(['student', 'application', 'collector']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas(
                    'student',
                    function ($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    }
                )->orWhere('receipt_number', 'like', "%{$search}%")
                    ->orWhereHas(
                        'application',
                        function ($aq) use ($search) {
                            $aq->where('application_id', 'like', "%{$search}%");
                        }
                    );
            });
        }

        if ($type = $request->get('payment_type')) {
            $query->where('payment_type', $type);
        }

        if ($status = $request->get('payment_status')) {
            $query->where('payment_status', $status);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $this->authorize('*accountant');

        $applications = Application::with('student')->latest()->get();
        $users = User::orderBy('name')->get(['id', 'name']);
        $selected_application_id = $request->get('application_id');
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.payments.create', compact('applications', 'users', 'selected_application_id', 'accounts'));
    }

    public function store(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $this->validatePayment($request);

        $application = Application::findOrFail($validated['application_id']);
        $validated['student_id'] = $application->student_id;

        $payment = Payment::create($validated);

        // Auto-log as office transaction (income)
        if ($payment->office_account_id) {
            OfficeTransaction::create([
                'from_account_id' => null,
                'to_account_id' => $payment->office_account_id,
                'amount' => $payment->amount,
                'transaction_date' => $payment->payment_date ?? now(),
                'transaction_type' => 'income',
                'reference' => 'Payment: ' . ($payment->receipt_number ?? 'REC-' . $payment->id),
                'notes' => 'Payment for application #' . $application->application_id,
            ]);
        }

        if ($validated['payment_status'] === 'completed') {
            $this->commissionService->calculateCommissions($payment);
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    public function edit(Payment $payment)
    {
        $this->authorize('*accountant');

        $applications = Application::with('student')->latest()->get();
        $users = User::orderBy('name')->get(['id', 'name']);
        $accounts = OfficeAccount::where('status', 'active')->get();

        return view('admin.payments.edit', compact('payment', 'applications', 'users', 'accounts'));
    }

    public function update(Request $request, Payment $payment)
    {
        $this->authorize('*accountant');

        $validated = $this->validatePayment($request);

        $application = Application::findOrFail($validated['application_id']);
        $validated['student_id'] = $application->student_id;

        $payment->update($validated);

        if ($payment->payment_status === 'completed') {
            $this->commissionService->calculateCommissions($payment);
        }

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('*accountant');

        $payment->delete();

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function downloadInvoice(Payment $payment)
    {
        $this->authorize('*accountant');

        $payment->load(['student', 'collector', 'application.university.country', 'application.course', 'application.intake']);
        $settings = Setting::pluck('value', 'key')->all();

        $pdf = Pdf::loadView('admin.payments.invoice', compact('payment', 'settings'));

        $filename = 'Invoice_' . ($payment->receipt_number ?: $payment->id) . '.pdf';

        return $pdf->download($filename);
    }

    private function validatePayment(Request $request): array
    {
        return $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_type' => ['required', Rule::in(['advance', 'partial', 'final'])],
            'payment_date' => ['nullable', 'date'],
            'receipt_number' => ['nullable', 'string', 'max:50'],
            'payment_status' => ['required', Rule::in(['pending', 'completed'])],
            'office_account_id' => ['nullable', 'exists:office_accounts,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    public function getApplicationBalance(Request $request)
    {
        $request->validate(['application_id' => 'required|exists:applications,id']);

        $application = Application::with('payments')->findOrFail($request->application_id);

        $totalPaid = $application->payments()
            ->whereIn('payment_status', ['pending', 'completed'])
            ->sum('amount');

        return response()->json([
            'total_fee' => $application->total_fee,
            'total_paid' => $totalPaid,
            'balance' => $application->total_fee - $totalPaid
        ]);
    }
}
