<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\VfsChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VfsChecklistController extends Controller
{
    protected const DEFAULT_CHECKLIST = [
        'VFS Appointment',
        'Visa Application',
        'Photo 35X45',
        'Passport',
        'Academic Certificates (Education Board and ministry attestation)',
        'Academic Transcripts (Education Board and ministry attestation)',
        'English Proficiency (If any)',
        'CV',
        'Motivation Letter',
        'Final Offer Letter and college others documents',
        'Accommodation',
        'Birth Certificate (Notarize and attested)',
        'Insurance',
        'Flight Booking',
        'Student Bank ATM Card',
        'Sponsor NID (Translation and notarize)',
        'Applicant NID (Translation and notarize)',
        'Sponsor Income Source (Trade License or Job Certificate) (Translation and notarize)',
        'TIN certificate',
        'TAX certificate 2 years',
        'Bank Statement',
        'Sponsor Bank ATM Card',
        'Applicants ATM Card',
        'Bank Account Cheque Book copy',
        'Deposit Slip (If possible)',
        'Financial Declaration Affidavit',
    ];

    public function __construct()
    {
        $this->middleware('can:*application');
    }

    public function index(Request $request)
    {
        $query = Application::with(['student', 'university', 'vfsChecklist'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_id', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $applications = $query->paginate(15);

        return view('admin.vfs-checklist.index', compact('applications'));
    }

    public function show(Application $application)
    {
        // Auto-create default checklist items if none exist
        if ($application->vfsChecklist()->count() === 0) {
            $items = collect(self::DEFAULT_CHECKLIST)->map(function ($item) use ($application) {
                return [
                    'application_id' => $application->id,
                    'checklist_item' => $item,
                    'is_checked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            VfsChecklist::insert($items->toArray());
        }

        $checklistItems = $application->vfsChecklist()
            ->with('checkedBy')
            ->orderBy('id')
            ->get();

        return view('admin.vfs-checklist.show', compact('application', 'checklistItems'));
    }

    public function storeItem(Request $request, Application $application)
    {
        $request->validate([
            'checklist_item' => 'required|string|max:255',
        ]);

        VfsChecklist::create([
            'application_id' => $application->id,
            'checklist_item' => $request->checklist_item,
        ]);

        return redirect()->back()->with('success', 'Checklist item added.');
    }

    public function toggleItem(VfsChecklist $item)
    {
        DB::transaction(function () use ($item) {
            if ($item->is_checked) {
                $item->update([
                    'is_checked' => false,
                    'checked_by' => null,
                    'checked_at' => null,
                ]);
            } else {
                $item->update([
                    'is_checked' => true,
                    'checked_by' => Auth::id(),
                    'checked_at' => now(),
                ]);
            }
        });

        return response()->json(['success' => true, 'is_checked' => $item->is_checked]);
    }

    public function updateNotes(Request $request, VfsChecklist $item)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $item->update(['notes' => $request->notes]);

        return response()->json(['success' => true]);
    }

    public function deleteItem(VfsChecklist $item)
    {
        $applicationId = $item->application_id;
        $item->delete();

        return redirect()->route('admin.vfs-checklist.show', $applicationId)
            ->with('success', 'Checklist item deleted.');
    }

    public function bulkCheck(Request $request, Application $application)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:vfs_checklists,id',
        ]);

        // Only update items that actually belong to this application
        VfsChecklist::whereIn('id', $request->item_ids)
            ->where('application_id', $application->id)
            ->update([
                'is_checked' => true,
                'checked_by' => Auth::id(),
                'checked_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    public function bulkUncheck(Request $request, Application $application)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:vfs_checklists,id',
        ]);

        // Only update items that actually belong to this application
        VfsChecklist::whereIn('id', $request->item_ids)
            ->where('application_id', $application->id)
            ->update([
                'is_checked' => false,
                'checked_by' => null,
                'checked_at' => null,
            ]);

        return response()->json(['success' => true]);
    }
}