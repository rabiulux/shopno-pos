<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h5>Purchases</h5>
                    </div>
                    <div class="align-items-center col">
                        <a href="{{ url('/createPurchasePage') }}" class="float-end btn m-0 bg-gradient-primary">New Purchase</a>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Total</th>
                            <th>Vat</th>
                            <th>Discount</th>
                            <th>Payable</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableList">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    fetchPurchases();

    async function fetchPurchases() {
        try {
            showLoader();
            const response = await axios.get('/get-purchases');
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const purchases = response.data.purchases;

                let tableList = $('#tableList');
                let tableData = $('#tableData');

                 // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable('#tableData')) {
                    $('#tableData').DataTable().destroy();
                }

                tableList.empty();

                purchases.forEach((purchase, index) => {
                    tableList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${purchase.supplier.name}</td>
                            <td>${purchase.supplier.mobile}</td>
                            <td>${purchase.total}</td>
                            <td>${purchase.vat}</td>
                            <td>${purchase.discount}</td>
                            <td>${purchase.payable}</td>
                            <td>
                                <button data-id="${purchase['id']}" data-supplier="${purchase['supplier']['id']}" class="btn btn-sm viewBtn btn-outline-dark"><i class=fas fa-eye></i>View</button>
                                <button data-id="${purchase['id']}" data-supplier="${purchase['supplier']['id']}" class="btn btn-sm deleteBtn btn-outline-danger"><i class=fas fa-trash-alt></i>Delete</button>
                            </td>
                        </tr>
                    `);
                });

                $('.viewBtn').on('click', async function() {
                    let purchase_id = $(this).data('id');
                    let supplier_id = $(this).data('supplier');
                    await purchaseDetails(purchase_id, supplier_id);
                });

                $('.deleteBtn').on('click', async function() {
                    const purchase_id = $(this).data('id');
                    const supplier_id = $(this).data('supplier');
                    document.getElementById('deleteID').value = purchase_id;
                    $("#delete-modal").modal('show');
                });

                $('#tableData').DataTable({
                    pageLength: 5,
                    lengthMenu: [5, 10, 15, 20],
                    searching: true,
                    ordering: true,
                    language: {
                        searchPlaceholder: "Search...",
                        lengthMenu: "Show _MENU_ entries",
                        zeroRecords: "No categories found",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries"
                    }
                });
            } else {
                errorToast(response.data.message || 'Failed to fetch suppliers');
            }
        } catch (error) {
            hideLoader();
            errorToast(error.response?.data?.message || 'An error occurred while fetching suppliers');
            console.log(error);
        }
    }
</script>


