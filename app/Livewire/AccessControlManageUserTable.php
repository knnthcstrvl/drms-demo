<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\{ButtonGroupColumn, LinkColumn};
use Rappasoft\LaravelLivewireTables\Views\Filters\{SelectFilter,DateRangeFilter};

class AccessControlManageUserTable extends DataTableComponent
{
    protected $model = User::class;

    public $userToDelete = null;

    protected $listeners = ['deleteConfirmed'];

    public function columns(): array {
        return [
            Column::make("ID", "id")
                ->sortable(),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn($row) => ['class' => 'space-x-2 flex justify-center'])
                ->buttons([
                    LinkColumn::make('Edit')
                        ->title(fn($row) => '<i class="fas fa-edit text-blue-500 cursor-pointer"></i>')
                        ->location(fn($row) => route('manage-users-edit', $row->username))
                        ->attributes(fn($row) => [
                            'class' => 'text-blue-500 cursor-pointer',
                            'title' => 'Edit ' . $row->name,
                            'data-tooltip' => 'Edit ' . $row->name,
                        ])
                        ->html(),
                    LinkColumn::make('Reset')
                        ->title(fn($row) => '<i class="fas fa-key text-cyan-500 cursor-pointer"></i>')
                        ->location(fn($row) => route('manage-users-reset', $row->email))
                        ->attributes(fn($row) => [
                            'class' => 'text-green-500 cursor-pointer',
                            'title' => 'Reset ' . $row->name,
                            'data-tooltip' => 'Reset ' . $row->name,
                        ])
                        ->html(),
                    LinkColumn::make('Delete')
                        ->title(fn($row) => '<i class="fas fa-trash text-red-500 cursor-pointer"></i>')
                        ->location(fn($row) => 'javascript:void(0)')
                        ->attributes(fn($row) => [
                            'class' => 'text-red-500 cursor-pointer',
                            'title' => 'Delete ' . $row->name,
                            'data-tooltip' => 'Delete ' . $row->name,
                            'wire:click' => "confirmSingleDelete('" . addslashes($row->username) . "')",
                        ])
                        ->html(),             
                ]),
            Column::make('Full Name', 'full_name')
                ->view('tables.cells.access-control-full-name')
                ->sortable()
                ->searchable(),
            Column::make("Username", "username")
                ->sortable()
                ->searchable(),
            Column::make("Email", "email")
                ->sortable()->searchable(),
            Column::make("Role", "role")
                ->sortable(),
            Column::make("Access Level", "access_level")
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
            ->setBulkActions([
                'deleteSelected' => 'Delete',
            ])
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

    public function confirmSingleDelete($username) {
        $this->userToDelete = $username;
        $this->dispatch('showDeleteUserModal');
    }

    public function deleteSelected() {
        if (!empty($this->getSelected())) {
            $this->selectedUsers = $this->getSelected();
            $this->dispatch('showDeleteUserModal');
        }
    }

    public function deleteConfirmed() {
        try {
            // Single user deletion
            if ($this->userToDelete) {
                $user = User::where('username', $this->userToDelete)->first();
                
                if ($user) {
                    // Delete avatar if exists
                    $avatarPath = 'avatars/' . $user->username . '.jpg';
                    if (Storage::disk('public')->exists($avatarPath)) {
                        Storage::disk('public')->delete($avatarPath);
                    }

                    // Delete the user
                    $user->delete();

                    // Audit log
                    AuditLog::create([
                        'user' => auth()->user()->full_name,
                        'date_time' => now(),
                        'activity_type' => 'Delete',
                        'username' => $user->username,
                        'full_name' => $user->full_name,
                    ]);

                    $this->js("showNotification('Success', 'User deleted successfully.', 'success')");
                } else {
                    $this->js("showNotification('Error', 'User not found.', 'error')");
                }

                $this->userToDelete = null;
            }

            // Bulk deletion
            elseif (!empty($this->getSelected())) {
                $selectedUsernames = $this->getSelected();
                $users = User::whereIn('username', $selectedUsernames)->get();

                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        // Delete avatar if exists
                        $avatarPath = 'avatars/' . $user->username . '.jpg';
                        if (Storage::disk('public')->exists($avatarPath)) {
                            Storage::disk('public')->delete($avatarPath);
                        }

                        // Audit log for each
                        AuditLog::create([
                            'user' => auth()->user()->full_name,
                            'date_time' => now(),
                            'activity_type' => 'Delete',
                            'username' => $user->username,
                            'full_name' => $user->full_name,
                        ]);
                    }

                    // Bulk delete users
                    User::whereIn('username', $selectedUsernames)->delete();

                    $this->clearSelected();
                    $this->js("showNotification('Success', 'Selected users deleted successfully.', 'success')");
                } else {
                    $this->js("showNotification('Error', 'No users found to delete.', 'error')");
                }
            } else {
                $this->js("showNotification('Error', 'No users selected for deletion.', 'error')");
            }

        } catch (\Exception $e) {
            $this->js("showNotification('Error', 'Failed to delete user(s).', 'error')");
            $this->userToDelete = null;
            $this->clearSelected();
        }
    }

    // public function deleteConfirmed() {
    //     try {
    //         // Check if single user deletion
    //         if ($this->userToDelete) {
    //             // Get user details before deletion for audit log
    //             $user = User::where('username', $this->userToDelete)->first();
                
    //             if ($user) {
    //                 // Delete the user
    //                 $user->delete();
                    
    //                 // Log the single deletion in AuditLog
    //                 AuditLog::create([
    //                     'user' => auth()->user()->full_name,
    //                     'date_time' => now(),
    //                     'activity_type' => 'Delete',
    //                     'username' => $user->username,
    //                     'full_name' => $user->full_name,
    //                 ]);
                    
    //                 // Show success notification
    //                 $this->js("showNotification('Success', 'User deleted successfully.', 'success')");
    //             } else {
    //                 // Show error notification
    //                 $this->js("showNotification('Error', 'User not found.', 'error')");
    //             }
                
    //             $this->userToDelete = null;
    //         }
    //         // Bulk deletion
    //         elseif (!empty($this->getSelected())) {
    //             $selectedUsernames = $this->getSelected();
    //             $users = User::whereIn('username', $selectedUsernames)->get();
                
    //             if ($users->count() > 0) {
    //                 User::whereIn('username', $selectedUsernames)->delete();
                    
    //                 foreach ($users as $user) {
    //                     AuditLog::create([
    //                         'user' => auth()->user()->full_name,
    //                         'date_time' => now(),
    //                         'activity_type' => 'Delete',
    //                         'username' => $user->username,
    //                         'full_name' => $user->full_name,
    //                     ]);
    //                 }
                    
    //                 $this->clearSelected();
    //                 // Show success notification
    //                 $this->js("showNotification('Success', 'Selected users deleted successfully.', 'success')");
    //             } else {
    //                 // Show error notification
    //                 $this->js("showNotification('Error', 'No users found to delete.', 'error')");
    //             }
    //         } else {
    //             // Show error notification
    //             $this->js("showNotification('Error', 'No users selected for deletion.', 'error')");
    //         }
            
    //     } catch (\Exception $e) {
    //         // Show error notification
    //         $this->js("showNotification('Error', 'Failed to delete user(s).', 'error')");
            
    //         $this->userToDelete = null;
    //         $this->clearSelected();
    //     }
    // }

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
            DateRangeFilter::make('Date Updated')
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