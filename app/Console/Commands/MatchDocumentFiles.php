<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;

class MatchDocumentFiles extends Command
{
    protected $signature = 'documents:match {--csv-file=} {--dry-run}';
    protected $description = 'Match document files to student records';

    public function handle()
    {
        $csvFile = $this->option('csv-file');
        $dryRun = $this->option('dry-run');

        if ($csvFile && file_exists($csvFile)) {
            $this->matchFromCsv($csvFile, $dryRun);
        } else {
            $this->matchFromDirectory($dryRun);
        }
    }

    private function matchFromCsv($csvFile, $dryRun = false)
    {
        $this->info('📥 Matching files from CSV...');
        $filenames = [];

        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                foreach ($data as $filename) {
                    $filename = trim($filename);
                    if (!empty($filename) && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'pdf') {
                        $filenames[] = $filename;
                    }
                }
            }
            fclose($handle);
        } else {
            $this->error("Could not open the CSV file: $csvFile");
            return;
        }

        $this->matchFiles($filenames, $dryRun);
    }

    private function matchFromDirectory($dryRun = false)
    {
        $this->info('📁 Scanning documents directory...');

        $documentsPath = storage_path('app/public/documents/test');
        $filenames = [];

        if (is_dir($documentsPath)) {
            $files = scandir($documentsPath);
            foreach ($files as $file) {
                if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf') {
                    $filenames[] = $file;
                }
            }
        } else {
            $this->error("Documents directory does not exist: $documentsPath");
            return;
        }

        $this->matchFiles($filenames, $dryRun);
    }

    private function matchFiles(array $filenames, $dryRun = false)
    {
        $matched = 0;
        $unmatched = [];
        $matchedList = [];

        $this->info("📄 Total files to process: " . count($filenames));

        foreach ($filenames as $filename) {
            $this->line("🔍 Processing: $filename");

            $student = $this->findMatchingStudentDirect($filename);

            if ($student) {
                if (!$dryRun) {
                    $student->doc_path = $filename;
                    $student->save();
                }

                $matchedList[] = [
                    'filename' => $filename,
                    'student_number' => $student->student_number,
                    'name' => "{$student->last_name}, {$student->first_name}",
                ];

                $matched++;
            } else {
                // Leave blank if no match found
                $matchedList[] = [
                    'filename' => $filename,
                    'student_number' => '',
                    'name' => '',
                ];
                $unmatched[] = $filename;
            }
        }

        $this->info("\n🎯 MATCHING COMPLETE!");
        $this->info("✔ Total matched: $matched");
        $this->info("✖ Total unmatched: " . count($unmatched));

        if (!empty($matchedList)) {
            $this->line("\n📋 Matched files:");
            foreach ($matchedList as $match) {
                $this->line("✓ {$match['filename']} -> {$match['student_number']} - {$match['name']}");
            }
        }

        if ($dryRun) {
            $this->warn("\n💡 DRY RUN: No changes were saved to the database.");
        }
    }

    private function findMatchingStudentDirect($filename)
    {
        $baseFilename = pathinfo($filename, PATHINFO_FILENAME);
        // Clean input: remove non-alphanumeric and uppercase
        $cleaned = strtoupper(preg_replace('/[^A-Z0-9]/', '', $baseFilename));

        // Step 1: Match by exact student number after removing dashes
        // We expect student_number like '202010208C'
        $students = Student::select('id', 'first_name', 'last_name', 'student_number')->get();

        foreach ($students as $student) {
            $cleanStudentNumber = strtoupper(str_replace('-', '', $student->student_number));

            if ($cleaned === $cleanStudentNumber) {
                return $student; // Exact student number match
            }
        }

        // Step 2: Match by lastname and first initial, stricter matching
        // Parse the cleaned filename into words (letters only)
        $words = preg_split('/[^A-Z]+/', $cleaned, -1, PREG_SPLIT_NO_EMPTY);

        if (count($words) >= 2) {
            foreach ($students as $student) {
                $cleanLast = strtoupper(preg_replace('/[^A-Z]/', '', $student->last_name));
                $cleanFirst = strtoupper(preg_replace('/[^A-Z]/', '', $student->first_name));

                // Match last name and first initial strictly and in order, entire last name must be substring, and first initial next
                // To avoid mixing Imelda with Miriam matching same last name only

                // Check last name present as full substring
                if (strpos($cleaned, $cleanLast) !== false) {
                    // Check first initial exists after last name somewhere: find position of last name, then check character(s) following
                    $posLast = strpos($cleaned, $cleanLast);
                    $posFirstInitial = strpos($cleaned, $cleanFirst[0], $posLast + strlen($cleanLast));
                    if ($posFirstInitial !== false) {
                        // Extra check: ensure first initial appears logically after last name (posFirstInitial > posLast)
                        return $student;
                    }
                }
            }
        }

        // No match found
        return null;
    }
}