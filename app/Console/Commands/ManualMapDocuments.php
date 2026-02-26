<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class ManualMapDocuments extends Command
{
    protected $signature = 'documents:manual-map';
    protected $description = 'Manually map unmatched document files to students';

    public function handle()
    {
        // Get all unmatched files
        $documentsPath = storage_path('app/public/documents/');
        $allFiles = [];
        
        if (is_dir($documentsPath)) {
            $files = scandir($documentsPath);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                    $allFiles[] = $file;
                }
            }
        }

        // Get files that are already mapped
        $mappedFiles = Student::whereNotNull('doc_path')->pluck('doc_path')->toArray();
        
        // Get unmapped files
        $unmappedFiles = array_diff($allFiles, $mappedFiles);
        
        // Get students without documents
        $studentsWithoutDocs = Student::whereNull('doc_path')->get();

        if (empty($unmappedFiles)) {
            $this->info('No unmapped files found!');
            return;
        }

        if ($studentsWithoutDocs->isEmpty()) {
            $this->info('All students already have documents mapped!');
            return;
        }

        $this->info('Unmapped files: ' . count($unmappedFiles));
        $this->info('Students without documents: ' . $studentsWithoutDocs->count());
        $this->line('');

        foreach ($unmappedFiles as $index => $file) {
            $this->line("File: {$file}");
            
            // Show some student options
            $this->info('Available students (showing first 10):');
            foreach ($studentsWithoutDocs->take(10) as $i => $student) {
                $this->line(($i + 1) . ". {$student->student_number} - {$student->first_name} {$student->last_name}");
            }
            
            $this->line('');
            $this->line('Options:');
            $this->line('- Enter student number to map this file');
            $this->line('- Enter "search [name]" to search students');
            $this->line('- Enter "skip" to skip this file');
            $this->line('- Enter "quit" to exit');
            
            $input = $this->ask('Your choice');
            
            if (strtolower($input) === 'quit') {
                break;
            }
            
            if (strtolower($input) === 'skip') {
                continue;
            }
            
            if (strpos(strtolower($input), 'search ') === 0) {
                $searchTerm = substr($input, 7);
                $this->searchStudents($searchTerm, $studentsWithoutDocs);
                continue;
            }
            
            // Try to find student by number
            $student = $studentsWithoutDocs->where('student_number', $input)->first();
            
            if ($student) {
                $confirm = $this->confirm("Map {$file} to {$student->student_number} - {$student->first_name} {$student->last_name}?");
                
                if ($confirm) {
                    $student->doc_path = $file;
                    $student->save();
                    
                    // Remove from unmapped students
                    $studentsWithoutDocs = $studentsWithoutDocs->reject(function ($s) use ($student) {
                        return $s->id === $student->id;
                    });
                    
                    $this->info("✓ Mapped successfully!");
                } else {
                    $this->line("Skipped.");
                }
            } else {
                $this->error("Student number '{$input}' not found or already has a document.");
            }
            
            $this->line('');
        }
        
        $this->info('Manual mapping session completed.');
    }

    private function searchStudents($searchTerm, $students)
    {
        $results = $students->filter(function ($student) use ($searchTerm) {
            $fullName = strtolower($student->first_name . ' ' . $student->last_name);
            $studentNumber = strtolower($student->student_number);
            $searchTerm = strtolower($searchTerm);
            
            return strpos($fullName, $searchTerm) !== false || 
                   strpos($studentNumber, $searchTerm) !== false;
        });
        
        if ($results->isEmpty()) {
            $this->warn("No students found matching '{$searchTerm}'");
        } else {
            $this->info("Search results for '{$searchTerm}':");
            foreach ($results->take(20) as $student) {
                $this->line("- {$student->student_number} - {$student->first_name} {$student->last_name}");
            }
        }
        
        $this->line('');
    }
}