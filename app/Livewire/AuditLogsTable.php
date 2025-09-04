<?php

namespace App\Livewire;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\{SelectFilter,DateRangeFilter};

class AuditLogsTable extends DataTableComponent
{
    protected $model = AuditLog::class;

    public function columns(): array {
        return [
            Column::make("User", "user")
                ->sortable()
                ->searchable(),
            Column::make("Date & time", "date_time")
                ->sortable(),
            Column::make("Activity type", "activity_type")
                ->sortable()
                ->searchable(),
            Column::make("Username", "username")
                ->sortable()
                ->searchable(),
            Column::make("Full name", "full_name")
                ->sortable()
                ->searchable(),
            Column::make("Student number", "student_number")
                ->sortable()
                ->searchable(),
            Column::make("Filename", "filename")
                ->sortable()
                ->searchable(),
            Column::make("First name", "first_name")
                ->sortable()
                ->searchable(),
            Column::make("Middle name", "middle_name")
                ->sortable()
                ->searchable(),
            Column::make("Last name", "last_name")
                ->sortable()
                ->searchable(),
        ];
    }

    public function configure(): void {
        $this->setPrimaryKey('id')
            ->useComputedPropertiesDisabled()
            ->setDefaultSort('id', 'desc')
            ->setTrimSearchStringEnabled()
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->isField('username') || $column->isField('student_number')) {
                    return ['class' => 'text-blue-400 hover:underline dark:!text-blue-400'];
                }

                if ($column->isField('student_number') || $column->isField('student_number')) {
                    return ['class' => 'text-green-400 hover:underline dark:!text-green-400'];
                }

                if ($column->isField('activity_type')) {
                    $classes = match ($row->activity_type) {
                        'Login' => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-gray3 !bg-gray3/20 !rounded-full dark:!text-gray3',
                        'View'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-primary-500 !bg-primary-500/20 !rounded-full dark:!text-purple-700',
                        'Download'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-secondary !bg-secondary/20 !rounded-full dark:!text-secondary',
                        'Print'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-green-700 !bg-green-500/20 !rounded-full dark:!text-green-400',
                        'Add'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-sky-700 !bg-sky-500/20 !rounded-full dark:!text-sky-400',
                        'Edit'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-blue-700 !bg-blue-500/20 !rounded-full dark:!text-blue-400',
                        'Reset'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-cyan-700 !bg-cyan-500/20 !rounded-full dark:!text-cyan-400',
                        'Delete'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-red-700 !bg-red-500/20 !rounded-full dark:!text-red-400',
                        default => '',
                    };
            
                    return ['class' => $classes];
                }
            
                return [];
            });
    }

    public function getDistinctColumnValues(string $column): array {
        return AuditLog::query()
            ->whereNotNull($column)
            ->distinct()
            ->pluck($column, $column)
            ->prepend('All', '')
            ->toArray();
    }  

    public function filters(): array {
        return [
            SelectFilter::make('User')
                ->options($this->getDistinctColumnValues('user'))
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('user', $value);
                    }
                }),
            DateRangeFilter::make('Date & Time')
                ->config([
                    'allowInput' => true,
                    'altFormat' => 'F j, Y',
                    'ariaDateFormat' => 'F j, Y',
                    'dateFormat' => 'Y-m-d',
                    'earliestDate' => '2020-01-01',
                    'latestDate' => now()->format('Y-m-d'),
                    'placeholder' => 'Enter Date Range',
                    'locale' => 'en',
                ])
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $builder, array $dateRange) { 
                    $builder
                        ->where('date_time', '>=', $dateRange['minDate'] . ' 00:00:00')
                        ->where('date_time', '<=', $dateRange['maxDate'] . ' 23:59:59');
                }),
            SelectFilter::make('Activity Type')
                ->options($this->getDistinctColumnValues('activity_type'))
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('activity_type', $value);
                    }
                }),
        ];
    }
}
