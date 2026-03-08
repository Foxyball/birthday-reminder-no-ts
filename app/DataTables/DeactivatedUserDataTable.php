<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DeactivatedUserDataTable extends DataTable
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
            ->editColumn('deleted_at', function (User $user) {
                return $user->deleted_at?->format('d.m.Y');
            })
            ->addColumn('action', function (User $user) {
                return '<form action="'.route('users.restore', $user->id).'" method="POST" class="inline-block">'
                    .csrf_field()
                    .method_field('PATCH')
                    .'<button type="submit" class="inline-flex items-center rounded-lg bg-success-500 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-success-600">'
                        .'Restore'.
                    '</button></form>';
            })
            ->rawColumns(['role', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->onlyTrashed()->select(['id', 'email', 'name', 'role', 'deleted_at']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('deactivated-user-table')
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
            Column::make('deleted_at')->title('Deleted At'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center')
                ->title(__('messages.action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Deactivated_Users_'.date('d.m.Y');
    }
}
