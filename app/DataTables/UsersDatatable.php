<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;
use App\User;

class UsersDatatable extends DataTable
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
            ->addColumn('check', 'admin.users.btn.check_box')
            ->addColumn('edit', 'admin.users.btn.edit')
            ->addColumn('delete', 'admin.users.btn.delete')
            ->addColumn('level', 'admin.users.btn.level')
            ->rawColumns([
                'check',
                'edit',
                'delete',
                'level',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\usersDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return User::query()->where(function($q){
            if(request()->has('level')){
                return $q->where('level', request('level'));
            }
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
                    ->setTableId('usersdatatable-table')
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
            Column::make('name')->title(trans('admin.name'))->data('name'),
            Column::make('email')->title(trans('admin.email'))->data('email'),
            Column::make('level')->title(trans('admin.level'))->data('level'),
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
        return 'users_' . date('YmdHis');
    }
}
