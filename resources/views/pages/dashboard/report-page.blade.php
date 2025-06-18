@extends('layouts.sidenav-layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Report</h4>

                        @if (session('error'))
                            <div class="alert alert-warning text-light alert-dismissible fade show mb-4" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row g-3">
                            <!-- Date Inputs -->
                            <div class="col-md-6">
                                <label class="form-label">Date From</label>
                                <input id="FormDate" type="date" class="form-control" />
                                <label class="form-label">Date To</label>
                                <input id="ToDate" type="date" class="form-control" />
                            </div>


                            <!-- Action Buttons -->
                            <div class="col-md-6">
                                <button onclick="PurchaseReport()" class="btn w-100 bg-gradient-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Purchases
                                </button>
                                <button onclick="SalesReport()" class="btn w-100 bg-gradient-primary">
                                    <i class="fas fa-chart-line me-2"></i>Sales
                                </button>
                                <button onclick="ProfitReport()" class="btn w-100 bg-gradient-primary">
                                    <i class="fas fa-money-bill-trend-up me-2"></i>Profit/Loss
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Column (optional content) -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Report Summary</h5>
                        <p class="text-muted">Select date range and report type to generate data</p>
                        <div class="mt-4 p-3 bg-light rounded">
                            <p class="mb-2"><i class="fas fa-info-circle me-2"></i>Instructions:</p>
                            <ul class="small">
                                <li>Select date range for your report</li>
                                <li>Click the appropriate report button</li>
                                <li>Reports will show below or in a new page</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    function SalesReport() {
        const fromDate = document.getElementById('FormDate').value;
        const toDate = document.getElementById('ToDate').value;

        if (!fromDate || !toDate) {
            errorToast('Please select both dates.');
            return;
        }

        if (new Date(fromDate) > new Date(toDate)) {
            errorToast('The "From" date cannot be later than the "To" date.');
            return;
        }
        if (new Date(toDate) > new Date()) {
            errorToast('The "To" date cannot be in the future.');
            return;
        }

        window.location.href = `/sales-report/${fromDate}/${toDate}`;
    }

    function PurchaseReport() {
        const fromDate = document.getElementById('FormDate').value;
        const toDate = document.getElementById('ToDate').value;

        if (!fromDate || !toDate) {
            errorToast('Please select both dates.');
            return;
        }

        if (new Date(fromDate) > new Date(toDate)) {
            errorToast('The "From" date cannot be later than the "To" date.');
            return;
        }
        if (new Date(toDate) > new Date()) {
            errorToast('The "To" date cannot be in the future.');
            return;
        }

        window.location.href = `/purchase-report/${fromDate}/${toDate}`;
    }

    function ProfitReport() {
        const fromDate = document.getElementById('FormDate').value;
        const toDate = document.getElementById('ToDate').value;

        if (!fromDate || !toDate) {
            errorToast('Please select both dates.');
            return;
        }

        if (new Date(fromDate) > new Date(toDate)) {
            errorToast('The "From" date cannot be later than the "To" date.');
            return;
        }
        if (new Date(toDate) > new Date()) {
            errorToast('The "To" date cannot be in the future.');
            return;
        }

        window.location.href = `/profit-report/${fromDate}/${toDate}`;
    }
</script>
