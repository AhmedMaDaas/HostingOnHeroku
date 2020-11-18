<?php

namespace App\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Html\Editor\Editor;

use App\BillProduct;
use DB;

class MallSalesDatatable extends DataTable
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
            ->addColumn('product_coast', $path . '.plugins.coast')
            ->addColumn('total_coast', $path . '.plugins.total_coast')
            ->rawColumns([
                'product_coast',
                'total_coast'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\MallSalesDatatable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BillProduct $model)
    {
        return BillProduct::query()->join('bills', 'bills.id', 'bill_products.bill_id')
                            ->where('bills.status', 'completed')
                            ->where('mall_id', $this->id)
                            ->with('mall')
                            ->with('product')
                            ->with('bill');
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
                    ->lengthMenu([[10, 25, 50, 100],[10, 25, 50, trans('admin.all_records')]])
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
            // Column::computed('orders')
            //       ->exportable(false)
            //       ->printable(false)
            //       ->width(60)
            //       ->addClass('text-center'),
            Column::make('product.id')->title(trans('admin.id'))->data('product.id'),
            Column::make('product.name_ar')->title(trans('admin.name_ar'))->data('product.name_ar'),
            Column::make('product.name_en')->title(trans('admin.name_en'))->data('product.name_en'),
            Column::make('quantity')->title(trans('admin.quantity'))->data('quantity'),
            Column::computed('product_coast')->title(trans('admin.unit_coast'))->addClass('text-center'),
            Column::computed('total_coast')->title(trans('admin.total_coast'))->addClass('text-center'),
            Column::make('bill.id')->title(trans('admin.bill_id'))->data('bill.id'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'MallSalesDatatable_' . date('YmdHis');
    }
}
