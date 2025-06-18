<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h4>Customer</h4>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 bg-gradient-primary">Create</button>
                </div>
            </div>
            <hr class="bg-dark "/>
            <table class="table" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
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
    fetchCustomers();
    async function fetchCustomers() {
        try {
            showLoader();
            let response = await axios.get('/get-customers');
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const customers = response.data.customers;

                let tableList = $('#tableList');

                // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable('#tableData')) {
                    $('#tableData').DataTable().destroy();
                }

                tableList.empty();

                customers.forEach((customer, index) => {
                    tableList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${customer.name}</td>
                            <td>${customer.email}</td>
                            <td>${customer.mobile}</td>
                            <td>
                               <button data-id="${customer['id']}" class="btn editBtn btn-primary">Edit</button>
                                <button data-id="${customer['id']}" class="btn deleteBtn btn-outline-danger">Delete</button>
                            </td>
                        </tr>
                    `);
                });

                  $('.editBtn').on('click', async function() {
                    const id = $(this).data('id');
                    await FillUpUpdateForm(id);
                    $('#update-modal').modal('show');
                });

                $('.deleteBtn').on('click', function() {
                    const id = $(this).data('id');
                    $('#delete-modal').modal('show');
                    $('#deleteID').val(id);
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
        }
    }


</script>
