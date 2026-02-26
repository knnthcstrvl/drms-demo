<?php

namespace App\Livewire;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\{ButtonGroupColumn, LinkColumn};
use Rappasoft\LaravelLivewireTables\Views\Filters\{SelectFilter,DateRangeFilter};

class StudentTable extends DataTableComponent
{
    protected $model = Student::class;
    public $user;
    public string $queryStringCache = '';

    public function mount() {
        $this->user = Auth::user();
        $this->queryStringCache = $_SERVER['QUERY_STRING'] ?? '';
    }

    public function columns(): array {
        return [
            Column::make("ID", "id")
                ->sortable(),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn() => [
                    'class' => 'space-x-2 flex justify-center',
                ])
                ->buttons($this->getActionButtons()),
            Column::make("Last Name", "last_name")
                ->sortable()
                ->searchable(),
            Column::make("First Name", "first_name")
                ->sortable()
                ->searchable(),
            Column::make("Middle Name", "middle_name")
                ->sortable()
                ->searchable(),
            Column::make("Birth Date", "birth_date")
                ->sortable(),
            Column::make("Student Number", "student_number")
                ->sortable()
                ->searchable(),
            Column::make("Filename", "filename")
                ->sortable()
                ->searchable(),
            Column::make("Program", "program")
                ->sortable()
                ->searchable(),
            Column::make("Year Graduated", "year_graduated")
                ->sortable()
                ->searchable(),
        ];
    }

    private function getActionButtons(): array {
        $buttons = [];
        $accessLevels = explode(',', $this->user->access_level);

    if ($this->user->role === 'Admin' || ($this->user->role === 'User' && in_array('View', $accessLevels))) {
            $buttons[] = LinkColumn::make('View')
                ->title(fn() => '<i class="fas fa-eye"></i>')
                ->location(function ($row) {
                    $base = route('document-view', $row->filename);
                    return $this->queryStringCache ? $base . '?' . $this->queryStringCache : $base;
                })
                ->attributes(fn($row) => [
                    'class' => 'text-primary-500 cursor-pointer dark:text-purple-700',
                    'title' => 'View ' . $row->name,
                    'data-tooltip' => 'View ' . $row->name,
                    // 'target' => '_blank',
                ])
                ->html();
        }
    
        if ($this->user->role === 'Admin' || ($this->user->role === 'User' && in_array('Download', $accessLevels))) {
            $buttons[] = LinkColumn::make('Download')
                ->title(fn() => '<i class="fas fa-download"></i>')
                ->location(fn($row) => route('document-download', ['selected' => $row->filename]) . '?t=' . now()->timestamp)
                ->attributes(fn($row) => [
                    'class' => 'text-secondary cursor-pointer',
                    'title' => 'Download ' . $row->name,
                    'data-tooltip' => 'Download ' . $row->name,
                ])
                ->html();
        }
    
        if ($this->user->role === 'Admin' || ($this->user->role === 'User' && in_array('Print', $accessLevels))) {
            $buttons[] = LinkColumn::make('Print')
                ->title(fn() => '<i class="fas fa-print"></i>')
                ->location(fn($row) => 'javascript:void(0)')
                ->attributes(fn($row) => [
                    'class' => 'text-green-500 cursor-pointer',
                    'title' => 'Print ' . $row->name,
                    'data-tooltip' => 'Print ' . $row->name,
                    'onclick' => "openPrintWindow('" . addslashes($row->filename) . "')",
                ])
                ->html();
        }
    
        // if ($this->user->role === 'Admin' || ($this->user->role === 'User' && in_array('Edit', $accessLevels))) {
        //     $buttons[] = LinkColumn::make('Edit')
        //         ->title(fn($row) => '<i class="fas fa-edit text-blue-500 cursor-pointer"></i>')
        //         ->location(fn($row) => route('manage-users-edit', $row->username))
        //         ->attributes(fn($row) => [
        //             'class' => 'text-blue-500 cursor-pointer',
        //             'title' => 'Edit ' . $row->name,
        //             'data-tooltip' => 'Edit ' . $row->name,
        //         ])
        //         ->html();
        // }
    
        return $buttons;
    }    

    public function configure(): void {
        $this->setPrimaryKey('id')
            ->useComputedPropertiesDisabled()
            ->setDefaultSort('id', 'desc')
            ->setTrimSearchStringEnabled()
            ->setBulkActions([
                'downloadSelected' => 'Download',
            ])
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->isField('student_number')) {
                    return ['class' => 'text-blue-400 hover:underline dark:!text-blue-400'];
                }

                if ($column->isField('filename')) {
                    return ['class' => 'text-green-400 hover:underline dark:!text-green-400'];
                }
            
                return [];
            });
    }

    public function downloadSelected() {
        $selectedItems = $this->getSelected();

        if (empty($selectedItems)) {
            session()->flash('error', 'No items selected for download.');
            return;
        }

        return redirect()->route('documents-download-bulk', ['selected' => $selectedItems]);
    }

    public function getDistinctColumnValues(string $column): array {
        return Student::query()
            ->whereNotNull($column)
            ->distinct()
            ->pluck($column, $column)
            ->prepend('All', '')
            ->toArray();
    }

    public function filters(): array {
        return [
            SelectFilter::make('Has Document')
                ->options([
                    '' => 'All',
                    'yes' => 'With Document',
                    'no' => 'Without Document',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value === 'yes') {
                        $builder->whereNotNull('filename')
                                ->where('filename', '!=', '');
                    } elseif ($value === 'no') {
                        $builder->where(function($query) {
                            $query->whereNull('filename')
                                    ->orWhere('filename', '=', '');
                        });
                    }
                }),
            SelectFilter::make('Program')
                ->options($this->getDistinctColumnValues('program'))
                ->filter(function (Builder $builder, string $value) {
                    if ($value !== '') {
                        $builder->where('program', $value);
                    }
                }),
            DateRangeFilter::make('Year Graduated')
                ->config([
                    'allowInput' => true,
                    'altFormat' => 'F j, Y',
                    'ariaDateFormat' => 'F j, Y',
                    'dateFormat' => 'Y-m-d',
                    'earliestDate' => '1904-01-01',
                    'latestDate' => now()->format('Y-m-d'),
                    'placeholder' => 'Enter Date Range',
                    'locale' => 'en',
                ])
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $builder, array $dateRange) { 
                    $builder
                        ->where('year_graduated', '>=', $dateRange['minDate'] . ' 00:00:00')
                        ->where('year_graduated', '<=', $dateRange['maxDate'] . ' 23:59:59');
                }),
        ];
    }
}