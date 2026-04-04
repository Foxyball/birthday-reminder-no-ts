<?php

namespace App\DataTables;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param  QueryBuilder  $query  Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('image', function ($query) {
                if ($query->image) {
                    $imageUrl = \App\Helpers\ImageHelper::url($query->image);

                    return '<div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700">
                        <img src="'.$imageUrl.'" alt="'.$query->name.'" class="h-full w-full object-cover">
                    </div>';
                } else {
                    $firstLetter = strtoupper(substr($query->name, 0, 1));
                    $bgColors = ['bg-brand-500', 'bg-success-500', 'bg-warning-500', 'bg-error-500', 'bg-info-500'];
                    $bgColor = $bgColors[ord($firstLetter) % count($bgColors)];

                    return '<div class="flex items-center justify-center h-10 w-10 rounded-full border border-gray-300 dark:border-gray-600 '.$bgColor.' text-white text-xs font-semibold">
                        '.$firstLetter.'
                    </div>';
                }
            })
            ->addColumn('action', function ($query) {
                $edit = '<a href="'.route('contact.edit', $query->id).'" class="inline-flex items-center rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-brand-600 transition-colors">'.
                    __('messages.edit').'</a>';
                $delete = '<a href="'.route('contact.destroy', $query->id).'" class="inline-flex items-center rounded-lg bg-error-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-error-600 transition-colors ml-2 delete-item">'.
                    __('messages.delete').'</a>';

                return $edit.' '.$delete;
            })
            ->addColumn('birthday', function ($query) {
                return $query->birthday ? \Carbon\Carbon::parse($query->birthday)->format('Y-m-d') : '-';
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
            ->rawColumns(['image', 'action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Contact $model): QueryBuilder
    {
        return $model->newQuery()->with('category')->where('user_id', auth()->id());
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('contact-table')
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
            Column::make('id')->title(__('messages.id')),
            Column::computed('image')->title(__('messages.image'))->width(50)->addClass('text-center'),
            Column::make('name')->title(__('messages.name')),
            Column::make('email')->title(__('messages.email')),
            Column::make('phone')->title(__('messages.phone')),
            Column::make('birthday')->title(__('messages.birthday')),
            Column::computed('status')->title(__('messages.status')),
            Column::computed('action')->title(__('messages.action'))->exportable(false)->printable(false)->width(150)->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Contact_'.date('YmdHis');
    }
}
