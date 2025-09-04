<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\{ButtonGroupColumn, LinkColumn};
use Rappasoft\LaravelLivewireTables\Views\Filters\{SelectFilter,DateRangeFilter};

class AccessControlAddUserTable extends DataTableComponent
{
    protected $model = User::class;

    public array $selectedUsers = [];

    public function columns(): array {
        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make('Full Name', 'full_name')
                ->view('tables.cells.access-control-full-name')
                ->sortable()
                ->searchable(),
            Column::make("Username", "username")
                ->sortable()->searchable(),
            Column::make("Email", "email")
                ->sortable()->searchable(),
            Column::make("Role", "role")
                ->sortable(),
            Column::make("Access level", "access_level")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make("Date & Time Created", "date_time_created")
                ->sortable(),
            Column::make("Date & Time Updated", "date_time_updated")
                ->sortable(),
        ];
    }

    public function configure(): void {
        $this->setPrimaryKey('id')
            ->useComputedPropertiesDisabled()
            ->setDefaultSort('id', 'desc')
            ->setTrimSearchStringEnabled()
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->isField('username')) {
                    return ['class' => 'text-blue-400 hover:underline dark:!text-blue-400'];
                }

                if ($column->isField('email')) {
                    return ['class' => 'text-green-400 hover:underline dark:!text-green-400'];
                }

                if ($column->isField('role')) {
                    $classes = match ($row->role) {
                        'Admin' => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-green-700 !bg-green-500/20 !rounded-full dark:!text-green-400',
                        'User'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-blue-700 !bg-blue-500/20 !rounded-full dark:!text-blue-400',
                        default => '',
                    };
            
                    return ['class' => $classes];
                }

                
                if ($column->isField('status')) {
                    $classes = match ($row->status) {
                        'Active'   => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-green-700 !bg-green-500/20 !rounded-full dark:!text-green-400',
                        'Inactive' => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-red-700 !bg-red-500/20 !rounded-full dark:!text-red-400',
                        default    => '',
                    };

                    return ['class' => $classes];
                }
            
                return [];
            });
    }

    public function builder(): Builder {
        return User::query()->where('username', '!=', 'superadmin');
    }

    public function getDistinctColumnValues(string $column): array {
        return User::query()
            ->whereNotNull($column)
            ->distinct()
            ->pluck($column, $column)
            ->prepend('All', '')
            ->toArray();
    }  

    public function filters(): array {
        return [
            SelectFilter::make('Role')
                ->options($this->getDistinctColumnValues('role'))
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('role', $value);
                    }
                }),
            SelectFilter::make('Access Level')
                ->options([
                    '' => 'All',
                    'View' => 'View',
                    'Download' => 'Download',
                    'Print' => 'Print',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if (!empty($value)) {
                        $builder->whereRaw("FIND_IN_SET(?, access_level)", [$value]);
                    }
                }),   
            SelectFilter::make('Status')
                ->options($this->getDistinctColumnValues('status'))
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('status', $value);
                    }
                }),         
            DateRangeFilter::make('Date & Time Created')
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
                        ->where('date_time_created', '>=', $dateRange['minDate'] . ' 00:00:00')
                        ->where('date_time_created', '<=', $dateRange['maxDate'] . ' 23:59:59');
                }),
            DateRangeFilter::make('Date & Time Updated')
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
                        ->where('date_time_updated', '>=', $dateRange['minDate'] . ' 00:00:00')
                        ->where('date_time_updated', '<=', $dateRange['maxDate'] . ' 23:59:59');
                }),
        ];
    }
}
