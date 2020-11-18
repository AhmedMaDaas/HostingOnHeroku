<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use App\Mall;

class MallsDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('check', 'admin.malls.btn.check_box')
            ->addColumn('edit', 'admin.malls.btn.edit')
            ->addColumn('delete', 'admin.malls.btn.delete')
            ->rawColumns([
                'check',
                'edit',
                'delete'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\adminDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Mall $model)
    {
        return Mall::query()->with('country');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('mallsdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->lengthChange(true)
                    ->lengthMenu([[10, 25, 50, 100],[10, 25, 50, trans('all_records')]])
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create')->className('btn btn-primary')->text(trans('admin.create')),
                        Button::make('export')->className('btn btn-info')->text(trans('admin.export')),
                        Button::make('print')->className('btn btn-info')->text(trans('admin.print')),
                        Button::make('reset')->className('btn btn-info')->text(trans('admin.reset')),
                        Button::make('reload')->className('btn btn-info')->text(trans('admin.reload')),
                        Button::make('')->className('btn btn-danger confirm')->text('<i class="fa fa-trash"></i>')
                    )
                    ->parameters([
                        "language" => [ 'url' => url('lang/' . lang() . '/dataTable.json')]
                    ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('edit')
                  ->exportable(false)
                  ->printable(false)
                  ->orderable(false)
                  ->searchable(false)
                  ->width(60)
                  ->addClass('text-center')
                  ->title(trans('admin.edit')),
            Column::computed(trans('delete'))
                  ->exportable(false)
                  ->printable(false)
                  ->orderable(false)
                  ->searchable(false)
                  ->width(60)
                  ->addClass('text-center')
                  ->title(trans('admin.delete')),
            Column::computed('check')
                  ->title(trans('admin.all') . ' <input type="checkbox" class="check_all" onClick="check_all()"></input>')
                  ->exportable(false)
                  ->printable(false)
                  ->orderable(false)
                  ->searchable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id')->title(trans('admin.id'))->data('id'),
            Column::make('name_ar')->title(trans('admin.name_ar'))->data('name_ar'),
            Column::make('name_en')->title(trans('admin.name_en'))->data('name_en'),
            Column::make('contact_name')->title(trans('admin.contact_name'))->data('contact_name'),
            Column::make('address')->title(trans('admin.address'))->data('address'),
            Column::make('mobile')->title(trans('admin.mobile'))->data('mobile'),
            Column::make('country')->title(trans('admin.country'))->data('country.name_' . lang()),
            Column::make('created_at')->title(trans('admin.created_at'))->data('created_at'),
            Column::make('updated_at')->title(trans('admin.updated_at'))->data('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'malls_' . date('YmdHis');
    }
}
