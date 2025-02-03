<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <!-- Transactions Table -->
    <div class="row">
      <div class="col-3">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-transparent border-bottom">
            <div class="row">
              <div class="col">
                <h5 class="mt-1 mb-0">Supplier List</h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div>
              <div>Eskina</div>
              <div>PHP</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-9">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-transparent border-bottom">
            <div class="row">
              <div class="col">
                <h5 class="mt-1 mb-0">Transaction Details</h5>
              </div>
            </div>
          </div>
          <div class="card-body">
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<script>
$(document).ready(function () {
    // Toggle custom date fields based on filter selection
    $('#dateFilter').on('change', function () {
        const isCustom = $(this).val() === 'custom';
        $('#dateRangeFields').toggleClass('d-none', !isCustom);
    });
});
</script>