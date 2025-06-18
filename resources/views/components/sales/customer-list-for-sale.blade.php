<div class="col-md-4 col-lg-4 p-2">
    <div class="shadow-sm h-100 bg-white rounded-3 p-3">
        <table class="table table-sm w-100" id="customerTable">
            <thead class="w-100">
                <tr class="text-xs text-bold">
                    <td>Customer</td>
                    <td>Pick</td>
                </tr>
            </thead>
            <tbody class="w-100" id="customerList">

            </tbody>
        </table>
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

                let customerList = $('#customerList');
                let customerTable = $('#customerTable');

                customerList.empty();
                customerTable.DataTable().destroy();

                customers.forEach((customer, index) => {
                    customerList.append(`
                        <tr class="text-xs">

                            <td><i class="bi bi-person"></i>${customer.name} - ${customer.email}</td>
                            <td><a data-name="${customer.name}" data-email="${customer.email}" data-id ="${customer.id}" class="btn addBtn btn-sm btn-outline-dark">Add</a></td>

                        </tr>
                    `);
                });

                $('.addBtn').on('click', async function() {
                    let name = $(this).data('name');
                    let email = $(this).data('email');
                    let id = $(this).data('id');

                    $('#CName').text(name);
                    $('#CEmail').text(email);
                    $('#CId').text(id);

                });

                $('#customerTable').DataTable({
                    pageLength: 5,
                    lengthMenu: [5, 10, 15, 20],
                    searching: true,
                    ordering: true,
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
