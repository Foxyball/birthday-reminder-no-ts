<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $edit = '<a href="'.route('category.edit', $query->id).'" class="inline-flex items-center rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600 transition-colors">'.
                    __('messages.edit').'</a>';
                $delete = '<a href="'.route('category.destroy', $query->id).'" class="inline-flex items-center rounded-lg bg-error-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-error-600 transition-colors ml-2 delete-item">'.
                    __('messages.delete').'</a>';

                return $edit.' '.$delete;
            })
            ->addColumn('status', function ($query) {
                $checked = $query->status == 1 ? 'true' : 'false';
                $checkedAttr = $query->status == 1 ? 'checked' : '';

                return '<div x-data="{ switcherToggle: '.$checked.' }">
                    <label class="flex cursor-pointer select-none items-center">
                        <div class="relative">
                            <input type="checkbox" '.$checkedAttr.' data-id="'.$query->id.'" class="sr-only change-status" @change="switcherToggle = !switcherToggle">
                            <div class="block h-6 w-11 rounded-full transition-colors" :class="switcherToggle ? \'bg-brand-500\' : \'bg-gray-200 dark:bg-white/10\'"></div>
                            <div class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear" :class="switcherToggle ? \'translate-x-full\' : \'translate-x-0\'"></div>
                        </div>
                    </label>
                </div>';
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('category-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->dom('Bfrtip')
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
            Column::make('name')->width(500)->title(__('messages.name')),
            Column::make('status')->width(100)->title(__('messages.status')),
            Column::computed('action')
                ->width(200)
                ->addClass('text-center')
                ->title(__('messages.action')),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Category_'.date('YmdHis');
    }
}
