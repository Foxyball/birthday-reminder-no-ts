<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder<User>  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('role', function (User $user) {
                $label = $user->isAdmin() ? 'Admin' : 'User';
                $classes = $user->isAdmin()
                    ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-300'
                    : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';

                return '<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium '.$classes.'">'.$label.'</span>';
            })
            ->addColumn('status', function (User $user) {
                $checked = $user->is_locked ? 'true' : 'false';
                $checkedAttr = $user->is_locked ? 'checked' : '';

                return '<div x-data="{ switcherToggle: '.$checked.' }">
                    <label class="flex cursor-pointer select-none items-center">
                        <div class="relative">
                            <input type="checkbox" '.$checkedAttr.' data-id="'.$user->id.'" class="sr-only change-status" @change="switcherToggle = !switcherToggle">
                            <div class="block h-6 w-11 rounded-full transition-colors" :class="switcherToggle ? \'bg-error-500\' : \'bg-success-500\'"></div>
                            <div class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear" :class="switcherToggle ? \'translate-x-full\' : \'translate-x-0\'"></div>
                        </div>
                    </label>
                </div>';
            })
            ->addColumn('action', function (User $user) {
                $edit = '<a href="'.route('users.edit', $user).'" class="inline-flex items-center rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-brand-600">'.
                    __('messages.edit').'</a>';
                $delete = '<a href="'.route('users.destroy', $user).'" class="ml-2 inline-flex items-center rounded-lg bg-error-500 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-error-600 delete-item">'.
                    __('messages.delete').'</a>';

                return $edit.' '.$delete;
            })
            ->rawColumns(['role', 'status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()->select(['id', 'email', 'name', 'role', 'is_locked']);

        if (Auth::check()) {
            $query->where('id', '!=', Auth::id());
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->width(60)->title(__('messages.id')),
            Column::make('email')->title('Email'),
            Column::make('name')->title(__('messages.name')),
            Column::make('role')->title('Role'),
            Column::computed('status')->title(__('messages.status')),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(180)
                ->addClass('text-center')
                ->title(__('messages.action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_'.date('YmdHis');
    }
}
