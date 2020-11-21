          <div class="page-wrapper">
            <footer class="footer mt-auto">
              <div class="copyright bg-white">
                <p>
                  &copy; <span id="copy-year">2019</span> Copyright Bazar Al-Seeb
                </p>
              </div>
              <script>
                  var d = new Date();
                  var year = d.getFullYear();
                  document.getElementById("copy-year").innerHTML = year;
              </script>
            </footer>
          </div>
        </div>

            
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDCn8TFXGg17HAUcNpkwtxxyT9Io9B_NcM" defer></script> -->
    <script src="{{ url('shipping_design') }}/assets/plugins/jquery/jquery.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/toaster/toastr.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/slimscrollbar/jquery.slimscroll.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/charts/Chart.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/ladda/spin.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/ladda/ladda.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/jquery-mask-input/jquery.mask.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/select2/js/select2.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/jvectormap/jquery-jvectormap-world-mill.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/daterangepicker/moment.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="{{ url('shipping_design') }}/assets/plugins/jekyll-search.min.js"></script>
    <script src="{{ url('shipping_design') }}/assets/js/sleek.js"></script>
    <script src="{{ url('shipping_design') }}/assets/js/chart.js"></script>
    <script src="{{ url('shipping_design') }}/assets/js/date-range.js"></script>
    <script src="{{ url('shipping_design') }}/assets/js/map.js"></script>
    <script src="{{ url('shipping_design') }}/assets/js/custom.js"></script>
    <script src="{{ url('shipping_design') }}/assets/js/dashboard.js"></script>
    <script src="{{ url('admin_design') }}/assets/js/plugins/pusher.min.js"></script>
    @stack('js')
  </body>
</html>
