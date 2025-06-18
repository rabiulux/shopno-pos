<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h5>Invoices</h5>
                    </div>
                    <div class="align-items-center col">
                        <a href="{{ url('/sale') }}" class="float-end btn m-0 bg-gradient-primary">Create Sale</a>
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
    fetchInvoices();

    async function fetchInvoices() {
        try {
            showLoader();
            const response = await axios.get('/invoices-select');
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const invoices = response.data.invoices;

                let tableList = $('#tableList');
                let tableData = $('#tableData');

                 // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable('#tableData')) {
                    $('#tableData').DataTable().destroy();
                }

                tableList.empty();

                invoices.forEach((invoice, index) => {
                    tableList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${invoice.customer.name}</td>
                            <td>${invoice.customer.mobile}</td>
                            <td>${invoice.total}</td>
                            <td>${invoice.vat}</td>
                            <td>${invoice.discount}</td>
                            <td>${invoice.payable}</td>
                            <td>
                                <button data-id="${invoice['id']}" data-customer="${invoice['customer']['id']}" class="btn btn-sm viewBtn btn-outline-dark"><i class=fas fa-eye></i>View</button>
                                <button data-id="${invoice['id']}" data-customer="${invoice['customer']['id']}" class="btn btn-sm deleteBtn btn-outline-danger"><i class=fas fa-trash-alt></i>Delete</button>
                            </td>
                        </tr>
                    `);
                });

                $('.viewBtn').on('click', async function() {
                    let invoice_id = $(this).data('id');
                    let customer_id = $(this).data('customer');
                    await InvoiceDetails(invoice_id, customer_id);
                });

                $('.deleteBtn').on('click', async function() {
                    const invoiceId = $(this).data('id');
                    const customerId = $(this).data('customer');
                    document.getElementById('deleteID').value = invoiceId;
                    // await InvoiceDelete(invoiceId, customerId);
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
                errorToast(response.data.message || 'Failed to fetch customers');
            }
        } catch (error) {
            hideLoader();
            errorToast(error.response?.data?.message || 'An error occurred while fetching customers');
            console.log(error);
        }
    }
</script>


