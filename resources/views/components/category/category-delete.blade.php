<div class="modal animated zoomIn" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class=" mt-3 text-warning">Delete !</h3>
                <p class="mb-3">Once delete, you can't get it back.</p>
                <input class="d-none" id="deleteID"/>
            </div>
            <div class="modal-footer justify-content-end">
                <div>
                    <button type="button" id="delete-modal-close" class="btn bg-gradient-success mx-2" data-bs-dismiss="modal">Cancel</button>
                    <button onclick="itemDelete()" type="button" id="confirmDelete" class="btn bg-gradient-danger" >Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    async function itemDelete() {
        let id = document.getElementById('deleteID').value;
        document.getElementById('delete-modal-close').click();
        try {
            showLoader();
            let response = await axios.post('/delete-category', {
                 id: id
            });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                successToast(response.data.message || 'Category deleted successfully');
                await fetchCategories();
            } else {
                errorToast(response.data.message || 'Failed to delete category');
            }
        } catch (error) {
            hideLoader();
            errorToast('An error occurred while deleting the category');
        }
    }
</script>
