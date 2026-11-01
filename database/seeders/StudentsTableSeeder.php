<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use Carbon\Carbon;

class StudentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            'BS Information Technology', 'BS Computer Science', 'BS Computer Engineering', 'BS Information Systems',
            'BS Data Science', 'BS Cybersecurity', 'BS Software Engineering', 'BS Web Development',
            'BS Mobile Development', 'BS Game Development'
        ];

        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Carlos', 'Rosa', 'Miguel', 'Carmen', 'Antonio', 'Isabel'];
        $lastNames = ['Garcia', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Perez', 'Sanchez', 'Ramirez', 'Cruz'];
        $middleNames = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        $startDate = now()->subDays(60);
        $existingStudentNumbers = Student::pluck('student_number')->toArray(); // for checking uniqueness
        $currentIndex = 1;

        // Create 200 students
        for ($i = 1; $i <= 20; $i++) {
            $createdDate = $startDate->copy()->addDays(rand(0, 60));
            $birthDate = Carbon::create(rand(1998, 2005), rand(1, 12), rand(1, 28));

            // Generate a truly unique student number
            do {
                $studentNumber = '2024-' . str_pad($currentIndex++, 5, '0', STR_PAD_LEFT);
            } while (in_array($studentNumber, $existingStudentNumbers));

            $existingStudentNumbers[] = $studentNumber; // prevent reuse in next loop

            $student = Student::firstOrCreate(
                ['student_number' => $studentNumber],
                [
                    'last_name' => $lastNames[array_rand($lastNames)],
                    'first_name' => $firstNames[array_rand($firstNames)],
                    'middle_name' => $middleNames[array_rand($middleNames)],
                    'birth_date' => $birthDate->format('Y-m-d'),
                    'program' => $programs[array_rand($programs)],
                    'filename' => $studentNumber,
                    'year_graduated' => (string) rand(now()->year, now()->year + 4),
                    'date_time_created' => $createdDate,
                    'date_time_updated' => $createdDate,
                ]
            );

            if ($student->filename !== $studentNumber) {
                $student->update(['filename' => $studentNumber]);
            }
        }

        // Add students on specific dates
        $specificDates = [now()->subDays(7), now()->subDays(14), now()->subDays(21), now()->subDays(1), now()];

        foreach ($specificDates as $date) {
            $count = rand(3, 8);
            for ($j = 0; $j < $count; $j++) {
                $birthDate = Carbon::create(rand(1998, 2005), rand(1, 12), rand(1, 28));

                do {
                    $studentNumber = '2024-' . str_pad($currentIndex++, 5, '0', STR_PAD_LEFT);
                } while (in_array($studentNumber, $existingStudentNumbers));

                $existingStudentNumbers[] = $studentNumber;

                $student = Student::firstOrCreate(
                    ['student_number' => $studentNumber],
                    [
                        'last_name' => $lastNames[array_rand($lastNames)],
                        'first_name' => $firstNames[array_rand($firstNames)],
                        'middle_name' => $middleNames[array_rand($middleNames)],
                        'birth_date' => $birthDate->format('Y-m-d'),
                        'program' => $programs[array_rand($programs)],
                        'filename' => $studentNumber,
                        'year_graduated' => (string) rand(now()->year, now()->year - 4),
                        'date_time_created' => $date,
                        'date_time_updated' => $date,
                    ]
                );

                if ($student->filename !== $studentNumber) {
                    $student->update(['filename' => $studentNumber]);
                }
            }
        }

        // Add five final students with stable student numbers and filenames.
        $fixedStudents = [
            '2024-90001',
            '2024-90002',
            '2024-90003',
            '2024-90004',
            '2024-90005',
        ];

        foreach ($fixedStudents as $studentNumber) {
            Student::firstOrCreate(
                ['student_number' => $studentNumber],
                [
                    'last_name' => $lastNames[array_rand($lastNames)],
                    'first_name' => $firstNames[array_rand($firstNames)],
                    'middle_name' => $middleNames[array_rand($middleNames)],
                    'birth_date' => Carbon::create(rand(1998, 2005), rand(1, 12), rand(1, 28))->format('Y-m-d'),
                    'program' => $programs[array_rand($programs)],
                    'filename' => $studentNumber,
                    'year_graduated' => (string) rand(now()->year, now()->year - 4),
                    'date_time_created' => now(),
                    'date_time_updated' => now(),
                ]
            )->update(['filename' => $studentNumber]);
        }

        $this->command->info('Student seeder completed without duplication!');
    }
}
