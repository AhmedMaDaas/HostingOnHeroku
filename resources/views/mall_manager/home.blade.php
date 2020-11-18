@extends('mall_manager.index')

@section('content')
	
	@include('mall_manager.shippings.plugins.statistics')
    
    <div class="row">
      <div class="col-xl-8 offset-xl-2 col-md-12">
          <!-- Sales Graph -->
          <div class="card card-default" data-scroll-height="675">
            <div class="card-header">
              <h2>{{ trans('admin.sales_of_the_year') }}</h2>
            </div>
            <div class="card-body">
              <canvas id="linechart" class="chartjs"></canvas>
            </div>
            <div class="card-footer d-flex flex-wrap bg-white p-0">
              <div class="col-6 px-0">
                <div class="text-center p-4">
                  <h4>{{ $statistics->productSoldByYear(\Carbon\Carbon::now()->year) }}</h4>
                  <p class="mt-2">{{ trans('admin.total_products_of_this_year') }}</p>
                </div>
              </div>
              <div class="col-6 px-0">
                <div class="text-center p-4 border-left">
                  <h4>${{ $statistics->productCoastSoldByYear(\Carbon\Carbon::now()->year) }}</h4>
                  <p class="mt-2">{{ trans('admin.total_revenue_of_this_year') }}</p>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>

@push('js')

  <script type="text/javascript">

    var ctx = document.getElementById("linechart");
    if (ctx !== null) {
      var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: "line",

        // The data for our dataset
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec"
          ],
          datasets: [
            {
              label: "",
              backgroundColor: "transparent",
              borderColor: "rgb(82, 136, 255)",
              data: [
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 1) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 2) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 3) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 4) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 5) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 6) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 7) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 8) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 9) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 10) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 11) }},
                {{ $statistics->productCoastSoldByMonth(\Carbon\Carbon::now()->year, 12) }}
              ],
              lineTension: 0.3,
              pointRadius: 5,
              pointBackgroundColor: "rgba(255,255,255,1)",
              pointHoverBackgroundColor: "rgba(255,255,255,1)",
              pointBorderWidth: 2,
              pointHoverRadius: 8,
              pointHoverBorderWidth: 1
            }
          ]
        },

        // Configuration options go here
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            display: false
          },
          layout: {
            padding: {
              right: 10
            }
          },
          scales: {
            xAxes: [
              {
                gridLines: {
                  display: false
                }
              }
            ],
            yAxes: [
              {
                gridLines: {
                  display: true,
                  color: "#eee",
                  zeroLineColor: "#eee",
                },
                ticks: {
                  callback: function(value) {
                    var ranges = [
                      { divider: 1e6, suffix: "M" },
                      { divider: 1e4, suffix: "k" }
                    ];
                    function formatNumber(n) {
                      for (var i = 0; i < ranges.length; i++) {
                        if (n >= ranges[i].divider) {
                          return (
                            (n / ranges[i].divider).toString() + ranges[i].suffix
                          );
                        }
                      }
                      return n;
                    }
                    return formatNumber(value);
                  }
                }
              }
            ]
          },
          tooltips: {
            callbacks: {
              title: function(tooltipItem, data) {
                return data["labels"][tooltipItem[0]["index"]];
              },
              label: function(tooltipItem, data) {
                return "$" + data["datasets"][0]["data"][tooltipItem["index"]];
              }
            },
            responsive: true,
            intersect: false,
            enabled: true,
            titleFontColor: "#888",
            bodyFontColor: "#555",
            titleFontSize: 12,
            bodyFontSize: 18,
            backgroundColor: "rgba(256,256,256,0.95)",
            xPadding: 20,
            yPadding: 10,
            displayColors: false,
            borderColor: "rgba(220, 220, 220, 0.9)",
            borderWidth: 2,
            caretSize: 10,
            caretPadding: 15
          }
        }
      });
    }

  </script>

@endpush
	
@endsection