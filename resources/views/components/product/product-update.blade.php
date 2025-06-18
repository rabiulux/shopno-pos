<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
            </div>
            <div class="modal-body">
                <form id="update-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <input type="text" class="d-none" id="updateID">
                                <input type="text" class="d-none" id="file_path">

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productNameUpdate">

                                <label class="form-label mt-2">Description</label>
                                <textarea class="form-control" id="productDescriptionUpdate" rows="3"></textarea>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <select type="text" class="form-control form-select"
                                            id="productCategoryUpdate">
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6"><label class="form-label mt-2">Price</label>
                                        <input type="text" class="form-control" id="productPriceUpdate">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6"><label class="form-label mt-2">Unit</label>
                                        <input type="text" class="form-control" id="productUnitUpdate">
                                    </div>
                                    <div class="col-md-6"><label class="form-label mt-2">Quantity</label>
                                        <input type="number" class="form-control" id="productQuantityUpdate"
                                            value="1">
                                    </div>
                                </div>

                                <br />
                                <img class="w-15" id="oldImg" src="{{ asset('images/default.jpg') }}" />
                                <br />

                                <label class="form-label">Image</label>
                                <input oninput="oldImg.src=window.URL.createObjectURL(this.files[0])" type="file"
                                    class="form-control" id="updateProductImg">

                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="update()" id="update-btn" class="btn bg-gradient-success">Update</button>
            </div>

        </div>
    </div>
</div>

<script>
    async function FillCategoryDropDownUpdate() {
        // Clear the dropdown and add the default option
        $("#productCategoryUpdate").html('<option value="">Select Category</option>');

        try {
            let res = await axios.get("/get-categories");
            res.data.categories.forEach(function(item) {
                let option = `<option value="${item.id}">${item.name}</option>`;
                $("#productCategoryUpdate").append(option);
            });
        } catch (error) {
            console.error("Error fetching categories:", error);
        }
    }


    async function FillUpUpdateForm(id, file_path) {
        document.getElementById('updateID').value = id;
        document.getElementById('file_path').value = file_path;

        try {
            showLoader();
            await FillCategoryDropDownUpdate();
            let response = await axios.post('/product-by-id', {
                id: id
            });
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const product = response.data.product;
                document.getElementById('productNameUpdate').value = product.name;
                document.getElementById('productDescriptionUpdate').value = product.description;
                document.getElementById('productPriceUpdate').value = product.price;
                document.getElementById('productUnitUpdate').value = product.unit;
                document.getElementById('productQuantityUpdate').value = product.quantity;

                // Set image preview
                const imgPreview = document.getElementById('oldImg');
                imgPreview.src = product.image ? product.image : "{{ asset('images/default.jpg') }}";

                // Set the selected category
                $('#productCategoryUpdate').val(product.category_id);
            } else {
                errorToast('Failed to fetch product details');
            }
        } catch (error) {
            hideLoader();
            console.error(error);
            errorToast('An error occurred while fetching product details');
        }
    }

    async function update() {
        try {
            const productId = document.getElementById('updateID').value;
            const file_path = document.getElementById('file_path').value;
            const productName = document.getElementById('productNameUpdate').value;
            const productDescription = document.getElementById('productDescriptionUpdate').value;
            const productPrice = document.getElementById('productPriceUpdate').value;
            const productUnit = document.getElementById('productUnitUpdate').value;
            const productQuantity = document.getElementById('productQuantityUpdate').value;
            const productCategory = document.getElementById('productCategoryUpdate').value;
            const updateProductImg = document.getElementById('updateProductImg').files[0];


            if (!productName || !productPrice || !productUnit || !productQuantity || !productCategory) {
                errorToast('Required fields are missing');
                return;
            }

            let formData = new FormData();
            formData.append('id', productId);
            formData.append('file_path', file_path);
            formData.append('name', productName);
            formData.append('description', productDescription);
            formData.append('price', productPrice);
            formData.append('unit', productUnit);
            formData.append('quantity', productQuantity);
            formData.append('category_id', productCategory);

            if (updateProductImg) {
                formData.append('image', updateProductImg);
            }

            showLoader();
            let response = await axios.post('/update-product', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',

                }
            });
            hideLoader();

            if (response.data.status === 'success') {
                successToast('Product updated successfully!');
                document.getElementById('update-modal-close').click();
                await fetchProducts();
            } else {
                errorToast(response.data.message || "Failed to update product");
            }
        } catch (error) {
            hideLoader();
            console.error('Update error:', error);
            if (error.response) {
                errorToast(error.response.data.message || "Failed to update product");
            } else {
                errorToast("Network error - please try again");
            }
        }
    }

    // Add this to reset the form properly
    document.getElementById('update-modal-close').addEventListener('click', function() {
        document.getElementById('update-form').reset();
        const imgPreview = document.getElementById('oldImg');
        imgPreview.src = "{{ asset('images/default.jpg') }}";
    });
</script>
