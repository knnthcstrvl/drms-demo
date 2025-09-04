<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportStudentController extends Controller
{
    public function import(Request $request)
    {
        try {
            $request->validate([
                'import_student' => [
                    'required',
                    'file',
                    'mimes:xls,xlsx,csv',
                ],
            ]);

            if (!$request->hasFile('import_student')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file uploaded.',
                ], 400);
            }

            $inserted = 0;
            $updated = 0;

            Excel::import(new StudentImport($inserted, $updated), $request->file('import_student'));

            session()->flash('import_message', [
                'inserted' => $inserted,
                'updated' => $updated,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Imported Successfully",
                'inserted' => $inserted,
                'updated' => $updated,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import students.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}