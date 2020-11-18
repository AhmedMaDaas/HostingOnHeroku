<div class="row">
  <div class="col-xl-3 col-sm-6">
    <div class="card card-mini mb-4">
      <div class="card-body">
        <h2 class="mb-1">{{ $statistics->getProductsLastMonth() }}</h2>
        <p>{{ trans('admin.sold_products_last_month') }}</p>
        <div class="chartjs-wrapper">
          <canvas id="barChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="card card-mini  mb-4">
      <div class="card-body">
        <h2 class="mb-1">{{ $statistics->getProductsThisMonth() }}</h2>
        <p>{{ trans('admin.sold_products_this_month') }}</p>
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
        <p>{{ trans('admin.monthly_sold_products') }}</p>
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