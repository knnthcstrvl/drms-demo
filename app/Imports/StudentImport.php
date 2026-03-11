<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Document;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\{ToCollection, WithHeadingRow};

class StudentImport implements ToCollection, WithHeadingRow
{
    protected $inserted;
    protected $updated;

    public function __construct(&$inserted, &$updated) {
        $this->inserted = &$inserted;
        $this->updated = &$updated;
    }

    private function normalizeFilename(?string $filename): ?string
    {
        if ($filename === null) {
            return null;
        }

        $filename = trim($filename);

        if ($filename === '') {
            return null;
        }

        return preg_replace('/\.pdf$/i', '', $filename);
    }

    public function collection(Collection $rows) {
        foreach ($rows as $row) {
            $studentNumber = $row['studentnumberformatted'] ?? $row['studentnumber'] ?? null;
            $filename = $this->normalizeFilename($row['filenameformatted'] ?? $row['filename'] ?? null);
            $docPath = $filename ? "storage/app/public/documents/{$filename}" : null;

            $document = null;
            $docId = null;

            if ($docPath) {
                $document = Document::firstOrCreate(
                    ['doc_path' => $docPath],
                    [
                        'date_time_created' => now(),
                        'date_time_updated' => now(),
                    ]
                );

                $docId = $document->id;
            }

            if (!empty($studentNumber)) {
                $student = Student::where('student_number', $studentNumber)->first();

                if ($student) {
                    $student->update([
                        'last_name' => $row['lname'],
                        'first_name' => $row['fname'],
                        'middle_name' => $row['mname'],
                        'birth_date' => $row['birthdateformatted'],
                        'filename' => $filename,
                        'program' => $row['program'],
                        'year_graduated' => $row['yeargraduatedformatted'],
                        'doc_id' => $docId,
                        'date_time_updated' => now(),
                    ]);

                    $this->updated++;
                } else {
                    $studentFullName = Student::where('last_name', $row['lname'])
                        ->where('first_name', $row['fname'])
                        ->where('middle_name', $row['mname'])
                        ->first();

                    if ($studentFullName) {
                        Student::create([
                            'last_name' => $row['lname'],
                            'first_name' => $row['fname'],
                            'middle_name' => $row['mname'],
                            'birth_date' => $row['birthdateformatted'],
                            'student_number' => $studentNumber,
                            'filename' => $filename,
                            'program' => $row['program'],
                            'year_graduated' => $row['yeargraduatedformatted'],
                            'doc_id' => $docId,
                            'date_time_created' => now(),
                        ]);

                        $this->inserted++;
                    } else {
                        Student::create([
                            'last_name' => $row['lname'],
                            'first_name' => $row['fname'],
                            'middle_name' => $row['mname'],
                            'birth_date' => $row['birthdateformatted'],
                            'student_number' => $studentNumber,
                            'filename' => $filename,
                            'program' => $row['program'],
                            'year_graduated' => $row['yeargraduatedformatted'],
                            'doc_id' => $docId,
                            'date_time_created' => now(),
                        ]);

                        $this->inserted++;
                    }
                }
            } else {
                if (!empty($row['last_name'])) {
                    $studentFullName = Student::where('last_name', $row['lname'])
                        ->where('first_name', $row['fname'])
                        ->where('middle_name', $row['mname'])
                        ->first();

                    if ($studentFullName) {
                        $studentFullName->update([
                            'birth_date' => $row['birthdateformatted'],
                            'student_number' => $studentNumber,
                            'filename' => $filename,
                            'program' => $row['program'],
                            'year_graduated' => $row['yeargraduatedformatted'],
                            'doc_id' => $docId,
                            'date_time_updated' => now(),
                        ]);

                        $this->updated++;
                    } else {
                        Student::create([
                            'last_name' => $row['lname'],
                            'first_name' => $row['fname'],
                            'middle_name' => $row['mname'],
                            'birth_date' => $row['birthdateformatted'],
                            'student_number' => $studentNumber,
                            'filename' => $filename,
                            'program' => $row['program'],
                            'year_graduated' => $row['yeargraduatedformatted'],
                            'doc_id' => $docId,
                            'date_time_created' => now(),
                        ]);

                        $this->inserted++;
                    }
                } else {
                    Student::create([
                        'last_name' => $row['lname'],
                        'first_name' => $row['fname'],
                        'middle_name' => $row['mname'],
                        'birth_date' => $row['birthdateformatted'],
                        'student_number' => $studentNumber,
                        'filename' => $filename,
                        'program' => $row['program'],
                        'year_graduated' => $row['yeargraduatedformatted'],
                        'doc_id' => $docId,
                        'date_time_created' => now(),
                    ]);

                    $this->inserted++;
                }
            }
        }
    }
}
