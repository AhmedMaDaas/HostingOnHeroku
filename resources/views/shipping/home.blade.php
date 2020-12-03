@extends('shipping.index')

@section('content')
  <div class="content">
    @include('shipping.plugins.statistics')
    
    <div class="row">
      <div class="col-xl-8 col-md-12">
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
                  <h4>{{ $statistics->totalOrdersPerYear(\Carbon\Carbon::now()->year) }}</h4>
                  <p class="mt-2">{{ trans('admin.total_orders_of_this_year') }}</p>
                </div>
              </div>
              <div class="col-6 px-0">
                <div class="text-center p-4 border-left">
                  <h4>${{ $statistics->SalesPerYear(\Carbon\Carbon::now()->year) }}</h4>
                  <p class="mt-2">{{ trans('admin.total_revenue_of_this_year') }}</p>
                </div>
              </div>
            </div>
          </div>
      </div>

      <div class="col-xl-4 col-md-12">
        <!-- Doughnut Chart -->
        <div class="card card-default" data-scroll-height="675">
          <div class="card-header justify-content-center">
            <h2>{{ trans('admin.orders_over_view') }}</h2>
          </div>
          <div class="card-body" >
            <canvas id="doChart" ></canvas>
          </div>
          <a href="#" class="pb-5 d-block text-center text-muted"><i class="mdi mdi-download mr-2"></i> {{ trans('admin.download_overall_report') }}</a>
          <div class="card-footer d-flex flex-wrap bg-white p-0">
            <div class="col-6">
              <div class="py-4 px-4">
                <ul class="d-flex flex-column justify-content-between">
                  <li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2" style="color: #4c84ff"></i>{{ trans('admin.completed_orders') }}</li>
                </ul>
              </div>
            </div>
            <div class="col-6 border-left">
              <div class="py-4 px-4 ">
                <ul class="d-flex flex-column justify-content-between">
                  <li class="mb-2"><i class="mdi mdi-checkbox-blank-circle-outline mr-2" style="color: #fec402"></i>{{ trans('admin.pending_orders') }}</li>
                  <li><i class="mdi mdi-checkbox-blank-circle-outline mr-2" style="color: #fe5461"></i>{{ trans('admin.cancelled_orders') }}</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col-xl-5">
        <!-- New Customers -->
        <div class="card card-table-border-none"  data-scroll-height="580">
          <div class="card-header justify-content-between ">
            <h2>{{ trans('admin.top_customers') }}</h2>
            <div>
                <button class="text-black-50 mr-2 font-size-20">
                  <i class="mdi mdi-cached"></i>
                </button>
                
            </div>
          </div>
          <div class="card-body pt-0">
            <table class="table ">
              <tbody>
                @foreach($topCustomersBills as $bill)
                  <tr>
                    <td >
                      <div class="media">
                        @if(isset($bill->user->photo))
                        <div class="media-image mr-3 rounded-circle">
                          <img class="customer-img-small rounded-circle w-45" src="{{ url('storage/' . $bill->user->photo) }}" alt="customer image">
                        </div>
                        @endif
                        <div class="media-body align-self-center">
                          <a class="customer-name" id="{{ $bill->user->id }}" href="#"><h6 class="mt-0 text-dark font-weight-medium">{{ $bill->user->name }}</h6></a>
                          <small>{{ $bill->user->email }}</small>
                        </div>
                      </div>
                    </td>
                    <td >{{ $bill->oCount }} {{ trans('admin.orders') }}</td>
                    <td class="text-dark d-none d-md-block">${{ $bill->total_coast }}</td>
                  </tr>
                  <div class="modal customers-modal" id="{{ $bill->user->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                      <div class="modal-content text-center">
                        <div class="modal-header">
                          <h5 class="modal-title">{{ $bill->user->name }}</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="bg-white border rounded">
                            <div class="no-gutters">
                              <div class="profile-content-left pt-5 pb-3 px-3 px-xl-5">
                                <div class="card text-center widget-profile px-0 border-0">
                                  @if(isset($bill->user->photo))
                                  <div class="card-img mx-auto rounded-circle-mid">
                                    <img class="customer-img-small" src="{{ url('storage/' . $bill->user->photo) }}" alt="user image">
                                  </div>
                                  @endif
                                  <div class="card-body">
                                    <h4 class="py-2 text-dark">{{ $bill->user->name }}</h4>
                                    <p>{{ $bill->user->email }}</p>
                                  </div>
                                </div>
                                <div class="d-flex justify-content-between ">
                                  <div class="text-center pb-4">
                                    <h6 class="text-dark pb-2">{{ $bill->user->bills->count() }}</h6>
                                    <p>{{ trans('admin.total_orders') }}</p>
                                  </div>
                                  <div class="text-center pb-4">
                                    <h6 class="text-dark pb-2">{{ $bill->user->billsPerMonth->count() }}</h6>
                                    <p>{{ trans('admin.orders_this_month') }}</p>
                                  </div>
                                  <div class="text-center pb-4">
                                    <h6 class="text-dark pb-2">{{ $bill->user->statusBills('pending')->count() }}</h6>
                                    <p>{{ trans('admin.pending_orders') }}</p>
                                  </div>
                                  <div class="text-center pb-4">
                                    <h6 class="text-dark pb-2">{{ $bill->user->statusBills('completed')->count() }}</h6>
                                    <p>{{ trans('admin.completed_orders') }}</p>
                                  </div>
                                </div>
                                <hr class="w-100">
                                <div class="contact-info pt-4">
                                  <h5 class="text-dark mb-1">{{ trans('admin.contact_info') }}</h5>
                                  <p class="text-dark font-weight-medium pt-4 mb-2">{{ trans('admin.email') }}</p>
                                  <p>{{ $bill->user->email }}</p>
                                  <p class="text-dark font-weight-medium pt-4 mb-2">{{ trans('admin.mobile') }}</p>
                                  <p>{{ $bill->user->phone }}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-xl-7">
        <!-- Top Products -->
        <div class="card card-default" data-scroll-height="580">
          <div class="card-header justify-content-between mb-4">
            <h2>{{ trans('admin.top_stores') }}</h2>
            <div>
                <button class="text-black-50 mr-2 font-size-20"><i class="mdi mdi-cached"></i></button>
            </div>
          </div>

          @foreach($topStoresBills as $bill)
            <div class="card-body py-0">
              <div class="media d-flex mb-5">
                @if(isset($bill->mall->icon))
                <div class="media-image align-self-center mr-3 rounded">
                  <img class="store-img-small" src="{{ url('storage/' . $bill->mall->icon) }}" alt="customer image">
                </div>
                @endif
                <div class="media-body align-self-center">
                  <a class="store-name" id="{{$bill->mall_id}}" href="#"><h6 class="mb-3 text-dark font-weight-medium">{{ $bill->mall->{'name_' . lang()} }}</h6></a>
                  <p class="float-md-right"><span class="text-dark mr-2">{{ $bill->pCount }}</span>{{ trans('admin.sales') }}</p>
                  <p class="mb-0">
                    <span class="text-dark ml-3">${{ $bill->sales }}</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="modal stores-modal" id="{{$bill->mall_id}}" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content text-center">
                  <div class="modal-header">
                    <h5 class="modal-title">{{ $bill->mall->{'name_' . lang()} }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <div class="bg-white border rounded">
                      <div class="no-gutters">
                        <div class="profile-content-left pt-5 pb-3 px-3 px-xl-5">
                          <div class="card text-center widget-profile px-0 border-0">
                            @if(isset($bill->mall->icon))
                            <div class="card-img mx-auto rounded-circle-mid">
                              <img class="customer-img-small" src="{{ url('storage/' . $bill->mall->icon) }}" alt="store image">
                            </div>
                            @endif
                            <div class="card-body">
                              <h4 class="py-2 text-dark">{{ $bill->mall->{'name_' . lang()} }}</h4>
                              <p>{{ $bill->mall->email }}</p>
                            </div>
                          </div>

                          <hr class="w-100">
                          <div class="contact-info pt-4">
                            <h5 class="text-dark mb-1">{{ trans('admin.contact_info') }}</h5>
                            <p class="text-dark font-weight-medium pt-4 mb-2">{{ trans('admin.email') }}</p>
                            <p>{{ $bill->mall->email }}</p>
                            <p class="text-dark font-weight-medium pt-4 mb-2">{{ trans('admin.mobile') }}</p>
                            <p>{{ $bill->mall->mobile }}</p>                
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>

@push('js')

  <script type="text/javascript">

    var lastNotId = 0;
    
    function setCharts(doughnut, ctx, completed, pending, cancelled){
      if (doughnut !== null) {
        var myDoughnutChart = new Chart(doughnut, {
          type: "doughnut",
          data: {
            labels: ["completed", "pending", "cancelled"],
            datasets: [
              {
                label: ["completed", "pending", "cancelled"],
                data: [completed, pending, cancelled],
                backgroundColor: ["#4c84ff", "#fec402", "#fe5461"],
                borderWidth: 1
                // borderColor: ['#4c84ff','#29cc97','#8061ef','#fec402']
                // hoverBorderColor: ['#4c84ff', '#29cc97', '#8061ef', '#fec402']
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
              display: false
            },
            cutoutPercentage: 75,
            tooltips: {
              callbacks: {
                title: function(tooltipItem, data) {
                  return "Order : " + data["labels"][tooltipItem[0]["index"]];
                },
                label: function(tooltipItem, data) {
                  return data["datasets"][0]["data"][tooltipItem["index"]];
                }
              },
              titleFontColor: "#888",
              bodyFontColor: "#555",
              titleFontSize: 12,
              bodyFontSize: 14,
              backgroundColor: "rgba(256,256,256,0.95)",
              displayColors: true,
              borderColor: "rgba(220, 220, 220, 0.9)",
              borderWidth: 2
            }
          }
        });
      }

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
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 1) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 2) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 3) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 4) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 5) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 6) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 7) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 8) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 9) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 10) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 11) }},
                  {{ $statistics->salesPerMonth(\Carbon\Carbon::now()->year, 12) }}
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

    }

    function setLastId(id){
      lastNotId = id;
    }

    function setNewNotifications(data){
      $('.notifications-menu button').removeClass('no-after');
      $('.notifications-menu ul div.others').after(data.html);
      var count = $('.notifications-menu ul').children().length - 2;
      $('.notifications-menu ul').find('span.count').text(count);
      setLastId(data.id);
    }

    function getNewNotification(){
      $.ajax({
        url: "{{ url('/') }}/shipping/new-notifications",
        type: 'post',
        data: {
          id: '{{ auth()->guard("web")->user()->id }}',
          not_id: lastNotId,
          _token: '{{ csrf_token() }}'
        },
        success: function(data){
          var audio = new Audio('{{url("/sounds")}}/piece-of-cake.mp3');
          audio.play();
          setNewNotifications(data);
        }
      });
    }

    function getNewOrder(){
      $.ajax({
        url: "{{ url('/') }}/shipping/home",
        type: 'get',
        data: {},
        success: function(data){
          var doughnut = document.getElementById("doChart");

          var ctx = document.getElementById("linechart");

          var completed = parseInt(data.completed);
          var pending = parseInt(data.pending);
          var cancelled = parseInt(data.cancelled);

          setCharts(doughnut, ctx, completed, pending, cancelled);
        }
      });
    }

    function makeNotificationsOld(){
      $.ajax({
        url: "{{ url('/') }}/shipping/make-notifications-old",
        type: 'post',
        data: {
          id: '{{ auth()->guard("web")->user()->id }}',
          _token: '{{ csrf_token() }}'
        },
        success: function(data){
          console.log(data);
        }
      });
    }

    $(document).ready(function(){

      var doughnut = document.getElementById("doChart");

      var ctx = document.getElementById("linechart");

      var completed = parseInt('{{ $statistics->getCompletedOrders() }}');
      var pending = parseInt('{{ $statistics->getPendingOrders() }}');
      var cancelled = parseInt('{{ $statistics->getCancelledOrders() }}');

      setCharts(doughnut, ctx, completed, pending, cancelled);

      $('.notifications-button').on('click', function(){
        if(!$(this).hasClass('no-after')){
          $(this).addClass('no-after');
          makeNotificationsOld();
        }
      });

      Pusher.logToConsole = true;

      var pusher = new Pusher('9b09910381b9d11e94dd', {
        cluster: 'eu',
        forcaTLS: true
      });

      var channel = pusher.subscribe('orders-channel');
      channel.bind('new-order', function(data) {
        getNewNotification();
        getNewOrder();

      });

    });

  </script>

@endpush

@endsection