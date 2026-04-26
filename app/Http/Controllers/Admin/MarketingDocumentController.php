<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MarketingDocument;
use Illuminate\Http\Request;

class MarketingDocumentController extends Controller
{
    public function index(Request $request)
    {
        // Get all applications for dropdown
        $applications = Application::with('student')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'application_id', 'student_id']);

        $selectedApplication = null;
        $documents = [];

        if ($request->filled('application_id')) {
            $selectedApplication = Application::with('student')->find($request->application_id);

            // Get or create document records for each type
            $docTypes = ['sop', 'cv', 'cl'];
            foreach ($docTypes as $type) {
                $doc = MarketingDocument::firstOrCreate(
                    [
                        'application_id' => $request->application_id,
                        'document_type' => $type,
                    ],
                    [
                        'document_name' => strtoupper($type),
                        'status' => 'pending',
                        'created_by' => auth()->id(),
                    ]
                );
                $documents[$type] = $doc;
            }
        }

        return view('admin.marketing.documents.index', compact('applications', 'selectedApplication', 'documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'documents' => 'required|array',
            'documents.*.status' => 'required|in:pending,received,not_received,ready,submitted',
        ]);

        foreach ($validated['documents'] as $type => $data) {
            MarketingDocument::updateOrCreate(
                [
                    'application_id' => $validated['application_id'],
                    'document_type' => $type,
                ],
                [
                    'document_name' => strtoupper($type),
                    'status' => $data['status'],
                    'created_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.marketing.documents.index', ['application_id' => $validated['application_id']])
            ->with('success', 'Document statuses updated successfully.');
    }
}
