<div class="row">
  <div class="col-xl-3 col-sm-6">
    <div class="card card-mini mb-4">
      <div class="card-body">
        <h2 class="mb-1">{{ $statistics->getBillsLastMonth() }}</h2>
        <p>{{ trans('admin.shipping_orders_last_month') }}</p>
        <div class="chartjs-wrapper">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="card card-mini  mb-4">
      <div class="card-body">
        <h2 class="mb-1">{{ $statistics->getBillsThisMonth() }}</h2>
        <p>{{ trans('admin.shipping_orders_this_month') }}</p>
        <div class="chartjs-wrapper">
          <canvas id="dual-line"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="card card-mini mb-4">
      <div class="card-body">
        <h2 class="mb-1">{{ $statistics->getAvg() }}</h2>
        <p>{{ trans('admin.monthly_total_order') }}</p>
        <div class="chartjs-wrapper">
          <canvas id="area-chart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="card card-mini mb-4">
      <div class="card-body">
        <h2 class="mb-1">{{ $statistics->getRevenueThisMonth() }}</h2>
        <p>{{ trans('admin.revenue_of_shipping_this_month') }}</p>
        <div class="chartjs-wrapper">
          <canvas id="line"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>