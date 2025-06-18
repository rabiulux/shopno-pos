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
                                <label class="form-label">Supplier Name *</label>
                                <input type="text" class="form-control" id="nameUpdate">

                                <label class="form-label mt-3">Supplier Email *</label>
                                <input type="text" class="form-control" id="emailUpdate">

                                <label class="form-label mt-3">Supplier Mobile *</label>
                                <input type="text" class="form-control" id="mobileUpdate">

                                <input type="text" id="updateID">
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
            let response = await axios.post('/supplier-by-id', { id: $id });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const supplier = response.data.supplier;
                document.getElementById('nameUpdate').value = supplier.name;
                document.getElementById('emailUpdate').value = supplier.email;
                document.getElementById('mobileUpdate').value = supplier.mobile;
            } else {
                errorToast('Failed to fetch supplier details');
            }
        } catch (error) {
            hideLoader();
            console.error(error);
            errorToast('An error occurred while fetching supplier details');
        }
    }

    async function Update() {
        try {
            const nameUpdate = document.getElementById('nameUpdate').value;
            const emailUpdate = document.getElementById('emailUpdate').value;
            const mobileUpdate = document.getElementById('mobileUpdate').value;
            const supplierId = document.getElementById('updateID').value;

            if (!nameUpdate || !emailUpdate || !mobileUpdate || !supplierId) {
                errorToast('All fields are required');

                return;
            }
            showLoader();
            let response = await axios.post('/update-supplier', {
                id: supplierId,
                name: nameUpdate,
                email: emailUpdate,
                mobile: mobileUpdate
            });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                successToast(response.data.message || 'Supplier updated successfully');
                document.getElementById('update-form').reset();
                document.getElementById('update-modal-close').click();
                await fetchSuppliers();
            } else {
                errorToast(response.data.message || 'Failed to update supplier');
            }
        } catch (error) {
            hideLoader();
            errorToast(error.response?.data?.error || 'An error occurred while updating supplier');
        }
    }



</script>
