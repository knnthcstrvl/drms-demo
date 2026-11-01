<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Student;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    private function resolveDocumentFilePath(Student $student): ?string
    {
        // Older imports may have no document row/path, while the PDF is still
        // named after the student's filename in storage/app/public/documents.
        $candidates = [
            $student->document?->doc_path,
            $student->filename,
        ];

        foreach ($candidates as $candidate) {
            if (!$candidate) {
                continue;
            }

            $filename = basename(str_replace('\\', '/', trim($candidate)));
            if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'pdf') {
                $filename .= '.pdf';
            }

            $filePath = storage_path("app/public/documents/{$filename}");
            if (is_file($filePath)) {
                return $filePath;
            }
        }

        return null;
    }

    private function buildDownloadFilename(Student $student): string
    {
        $filename = $student->filename ?: 'document';

        if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'pdf') {
            $filename .= '.pdf';
        }

        return $filename;
    }

    public function index ()
    {
        return view('pages.document.document', ['students' => null]);
    }

    public function view($selected = null)
    {
        if (!$selected) {
            session()->flash('error_notification', "There's no filename linked to this record.");
            return redirect()->route('document');
        }

        $student = Student::where('filename', $selected)->first();

        if (!$student) {
            session()->flash('error_notification', "The file '{$selected}' is currently not available.");
            return redirect()->route('document');
        }

        $filePath = $this->resolveDocumentFilePath($student);

        if (!$filePath || !file_exists($filePath)) {
            session()->flash('error_notification', "The document '{$selected}' is not available right now. It may have been moved or deleted.");
            return redirect()->route('document');
        }

        if (request()->has('file') && request()->get('file') === 'true') {
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $this->buildDownloadFilename($student) . '"'
            ]);
        }

        AuditLog::create([
            'user' => auth()->user()->full_name,
            'date_time' => now(),
            'activity_type' => 'View',
            'student_number' => $student->student_number,
            'filename' => $student->filename,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
        ]);

        return view('pages.document.document', [
            'students' => $student,
            'openModal' => 'view-modal',
        ]);
    }

    public function download($selected = null)
    {
        if (empty($selected)) {
            return redirect()->route('document')->with('error_notification', "There's no filename linked to this record.");
        }

        $user = auth()->user();
        $accessLevels = explode(',', $user->access_level);

        if (!in_array('Download', $accessLevels)) {
            return redirect()->route('document')->with('error_notification', "You don't have permission to download this file. Contact your administrator for access.");
        }

        $student = Student::where('filename', $selected)->first();

        if (!$student) {
            return redirect()->route('document')->with('error_notification', "The file '{$selected}' is currently not available.");
        }

        $filePath = $this->resolveDocumentFilePath($student);

        if (!$filePath || !file_exists($filePath)) {
            return redirect()->route('document')->with('error_notification', "The document '{$selected}' is not available right now. It may have been moved or deleted.");
        }

        AuditLog::create([
            'user' => $user->full_name,
            'date_time' => now(),
            'activity_type' => 'Download',
            'student_number' => $student->student_number,
            'filename' => $student->filename,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
        ]);

        return response()->download($filePath, $this->buildDownloadFilename($student), [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    // Fix this: table component and script is not updated
    public function downloadBulk(Request $request)
    {
        $selected = $request->input('selected');

        if (!$selected || !is_array($selected)) {
            return redirect()->route('document')->with('error_notification', 'No files selected for download.');
        }

        $user = auth()->user();
        $accessLevels = explode(',', $user->access_level);

        if (!in_array('Download', $accessLevels)) {
            return redirect()->route('document')->with('error_notification', "You don't have permission to download files. Contact your administrator for access.");
        }

        $zipFileName = 'drms_documents.zip';
        $zipPath = storage_path($zipFileName);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $addedFiles = 0;
            
            foreach ($selected as $filename) {
                $student = Student::where('filename', $filename)->first();
                
                if (!$student) {
                    continue; // Skip if student not found
                }

                $filePath = $this->resolveDocumentFilePath($student);
                
                if ($filePath && file_exists($filePath)) {
                    $zip->addFile($filePath, $this->buildDownloadFilename($student));
                    $addedFiles++;

                    // Log each download in audit
                    AuditLog::create([
                        'user' => $user->full_name,
                        'date_time' => now(),
                        'activity_type' => 'Bulk Download',
                        'student_number' => $student->student_number,
                        'filename' => $student->filename,
                        'first_name' => $student->first_name,
                        'middle_name' => $student->middle_name,
                        'last_name' => $student->last_name,
                    ]);
                }
            }
            
            $zip->close();

            if ($addedFiles === 0) {
                // Clean up empty zip file
                if (file_exists($zipPath)) {
                    unlink($zipPath);
                }
                return redirect()->route('document')->with('error_notification', 'No valid documents found for the selected files.');
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return redirect()->route('document')->with('error_notification', 'Failed to create ZIP file.');
    }

    public function print($selected = null)
    {
        // Handle AJAX pre-check request
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            if (empty($selected)) {
                return response()->json(['error' => "There's no filename linked to this record."], 400);
            }

            $user = auth()->user();
            $accessLevels = explode(',', $user->access_level);
            if (!in_array('Print', $accessLevels)) {
                return response()->json(['error' => "You don't have permission to print this file. Contact your administrator for access."], 403);
            }

            $student = Student::where('filename', $selected)->first();
            if (!$student) {
                return response()->json(['error' => "The file '{$selected}' is currently not available."], 404);
            }

            $filePath = $this->resolveDocumentFilePath($student);
            if (!$filePath || !file_exists($filePath)) {
                return response()->json(['error' => "The document '{$selected}' is not available right now. It may have been moved or deleted."], 404);
            }

            return response()->json(['success' => true]);
        }

        // Normal full redirect mode
        if (empty($selected)) {
            return redirect()->route('document')->with('error_notification', "There's no filename linked to this record.");
        }

        $user = auth()->user();
        $accessLevels = explode(',', $user->access_level);
        if (!in_array('Print', $accessLevels)) {
            return redirect()->route('document')->with('error_notification', "You don't have permission to print this file. Contact your administrator for access.");
        }

        $student = Student::where('filename', $selected)->first();
        if (!$student) {
            return redirect()->route('document')->with('error_notification', "The file '{$selected}' is currently not available.");
        }

        $filePath = $this->resolveDocumentFilePath($student);
        if (!$filePath || !file_exists($filePath)) {
            return redirect()->route('document')->with('error_notification', "The document '{$selected}' is not available right now. It may have been moved or deleted.");
        }

        AuditLog::create([
            'user' => $user->full_name,
            'date_time' => now(),
            'activity_type' => 'Print',
            'student_number' => $student->student_number,
            'filename' => $student->filename,
            'first_name' => $student->first_name,
            'middle_name' => $student->middle_name,
            'last_name' => $student->last_name,
        ]);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
