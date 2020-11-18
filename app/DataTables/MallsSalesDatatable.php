<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

use App\BillProduct;
use DB;

class MallsSalesDatatable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $path = isManager() ? 'mall_manager.sales' : 'admin.malls';
        return datatables()
            ->eloquent($query)
            ->addColumn('orders', $path . '.btn.orders')
            ->addColumn('sales', $path . '.plugins.sales')
            ->rawColumns([
                'orders',
                'sales'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\MallsSalesDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BillProduct $model)
    {
        return BillProduct::query()->join('bills', 'bills.id', 'bill_products.bill_id')
                            ->where('bills.status', 'completed')
                            ->when(isManager(), function($query){
                                return $query->whereIn('mall_id', $this->mallsIds);
                            })
                            ->select('mall_id', DB::raw('sum(quantity) as pCount'), DB::raw('sum(product_coast * quantity) as sales'))
                            ->with('mall')
                            ->groupBy('mall_id')
                            ->orderBy('pCount', 'desc');
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
                        Button::make('export')->className('btn btn-info')->text(trans('admin.export')),
                        Button::make('print')->className('btn btn-info')->text(trans('admin.print')),
                        Button::make('reset')->className('btn btn-info')->text(trans('admin.reset')),
                        Button::make('reload')->className('btn btn-info')->text(trans('admin.reload')),
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
            Column::computed('orders')
                  ->exportable(false)
                  ->printable(false)
                  ->orderable(false)
                  ->searchable(false)
                  ->width(60)
                  ->addClass('text-center')
                  ->title(trans('admin.orders')),
            Column::make('mall_id')->title(trans('admin.id'))->data('mall_id'),
            Column::make('mall.name_ar')->title(trans('admin.name_ar'))->data('mall.name_ar'),
            Column::make('mall.name_en')->title(trans('admin.name_en'))->data('mall.name_en'),
            Column::make('mall.mobile')->title(trans('admin.mobile'))->data('mall.mobile'),
            Column::make('pCount')->title(trans('admin.sales_number'))->data('pCount'),
            Column::computed('sales')->title(trans('admin.sales'))->addClass('text-center'),
            Column::make('mall.created_at')->title(trans('admin.created_at'))->data('mall.created_at'),
            Column::make('mall.updated_at')->title(trans('admin.updated_at'))->data('mall.updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'MallsSalesDatatable_' . date('YmdHis');
    }
}
