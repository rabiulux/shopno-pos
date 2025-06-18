<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="customerNameUpdate">

                                <label class="form-label mt-3">Customer Email *</label>
                                <input type="text" class="form-control" id="customerEmailUpdate">

                                <label class="form-label mt-3">Customer Mobile *</label>
                                <input type="text" class="form-control" id="customerMobileUpdate">

                                <input type="text" class="d-none" id="updateID">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="Update()" id="update-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>


<script>
    async function FillUpUpdateForm($id) {
        document.getElementById('updateID').value = $id;
        try {
            showLoader();
            let response = await axios.post('/customer-by-id', { id: $id });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const customer = response.data.customer;
                document.getElementById('customerNameUpdate').value = customer.name;
                document.getElementById('customerEmailUpdate').value = customer.email;
                document.getElementById('customerMobileUpdate').value = customer.mobile;
            } else {
                errorToast('Failed to fetch customer details');
            }
        } catch (error) {
            hideLoader();
            console.error(error);
            errorToast('An error occurred while fetching customer details');
        }
    }

    async function Update() {
        try {
            const customerName = document.getElementById('customerNameUpdate').value;
            const customerEmail = document.getElementById('customerEmailUpdate').value;
            const customerMobile = document.getElementById('customerMobileUpdate').value;
            const customerId = document.getElementById('updateID').value;

            if (!customerName || !customerEmail || !customerMobile || !customerId) {
                errorToast('All fields are required');
                hideLoader();
                return;
            }
            showLoader();
            let response = await axios.post('/update-customer', {
                id: customerId,
                name: customerName,
                email: customerEmail,
                mobile: customerMobile
            });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                successToast(response.data.message || 'Customer updated successfully');
                document.getElementById('update-form').reset();
                document.getElementById('update-modal-close').click();
                await fetchCustomers();
            } else {
                errorToast(response.data.message || 'Failed to update customer');
            }
        } catch (error) {
            hideLoader();
            errorToast(error.response?.data?.message || 'An error occurred while updating customer');
        }
    }



</script>
