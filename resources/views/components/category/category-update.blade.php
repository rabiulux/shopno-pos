<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="categoryNameUpdate">
                                <input class="d-none" id="updateID">
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
    async function FillUpUpdateForm($id){
        document.getElementById('updateID').value = $id;
        try {
            showLoader();
            let response = await axios.post('/category-by-id', { id: $id });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const category = response.data.category;
                document.getElementById('categoryNameUpdate').value = category.name;
            } else {
                errorToast('Failed to fetch category details');
            }
        } catch (error) {
            hideLoader();
            console.error(error);
            errorToast('An error occurred while fetching category details');
        }
    }

    async function Update() {
        try {
            showLoader();
            const categoryName = document.getElementById('categoryNameUpdate').value;
            const categoryId = document.getElementById('updateID').value;

            if (!categoryName || !categoryId) {
                errorToast('Category name and ID are required');
                hideLoader();
                return;
            }

            let response = await axios.post('/update-category', { id: categoryId, name: categoryName });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                successToast(response.data.message || 'Category updated successfully');
                document.getElementById('update-form').reset();
                document.getElementById('update-modal-close').click();
                await fetchCategories();
            } else {
                errorToast(response.data.message || 'Failed to update category');
            }

        } catch (error) {
            hideLoader();
            console.error(error);
            errorToast('An error occurred while updating the category');
        }
    }
</script>
