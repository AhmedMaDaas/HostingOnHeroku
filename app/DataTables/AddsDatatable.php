<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use App\Ad;

class AddsDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $path = isManager() ? 'mall_manager' : 'admin';
        return datatables()
            ->eloquent($query)
            ->addColumn('check', $path . '.adds.btn.check_box')
            ->addColumn('edit', $path . '.adds.btn.edit')
            ->addColumn('delete', $path . '.adds.btn.delete')
            ->addColumn('photo', $path . '.adds.plugins.photo')
            ->rawColumns([
                'check',
                'edit',
                'delete',
                'photo',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\adminDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Ad $model)
    {
        return Ad::query()->when(isManager(), function($query){
            return $query->whereIn('mall_id', getMallsIds());
        });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('addsdatatable-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->lengthChange(true)
                    ->lengthMenu([[10, 25, 50, 100],[10, 25, 50, trans('admin.all_records')]])
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
                  ->addClass('text-center')
                  ->title(trans('admin.edit')),
            Column::computed(trans('delete'))
                  ->exportable(false)
                  ->printable(false)
                  ->orderable(false)
                  ->searchable(false)
                  ->addClass('text-center')
                  ->title(trans('admin.delete')),
            Column::computed('check')
                  ->title(trans('admin.all') . ' <input type="checkbox" class="check_all" onClick="check_all()"></input>')
                  ->exportable(false)
                  ->printable(false)
                  ->orderable(false)
                  ->searchable(false)
                  ->addClass('text-center'),
            Column::make('id')->title(trans('admin.id'))->data('id'),
            Column::computed('photo')->title(trans('admin.photo'))->orderable(false)->searchable(false),
            Column::make('title')->title(trans('admin.title_' . lang()))->data('title_' . lang()),
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
        return 'adds_' . date('YmdHis');
    }
}
