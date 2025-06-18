<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create Product</h5>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">

                                <label class="form-label mt-2">Name</label>
                                <input type="text" class="form-control" id="productName">

                                <label class="form-label mt-2">Description</label>
                                <textarea class="form-control" id="productDescription" rows="3"></textarea>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Category</label>
                                        <select type="text" class="form-control form-select" id="productCategory">
                                            <option value="">Select Category</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6"><label class="form-label mt-2">Price</label>
                                        <input type="text" class="form-control" id="productPrice">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6"><label class="form-label mt-2">Unit</label>
                                        <input type="text" class="form-control" id="productUnit">
                                    </div>
                                    <div class="col-md-6"><label class="form-label mt-2">Quantity</label>
                                        <input type="number" class="form-control" id="productQuantity" value="1">
                                    </div>
                                </div>

                                <br />
                                <img class="w-15" id="newImg" src="{{ asset('images/default.jpg') }}" />
                                <br />

                                <label class="form-label">Image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" type="file"
                                    class="form-control" id="productImg">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="modal-close" class="btn bg-gradient-primary mx-2" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="Save()" id="save-btn" class="btn bg-gradient-success">Save</button>
            </div>
        </div>
    </div>
</div>


<script>
    FillCategoryDropDown();

    async function FillCategoryDropDown() {
        let res = await axios.get("/get-categories")
        res.data.categories.forEach(function(item, i) {
            let option = `<option value="${item['id']}">${item['name']}</option>`
            $("#productCategory").append(option);
        })
    }


    async function Save() {

        let productCategory = document.getElementById('productCategory').value;
        let productName = document.getElementById('productName').value;
        let productDescription = document.getElementById('productDescription').value;
        let productPrice = document.getElementById('productPrice').value;
        let productUnit = document.getElementById('productUnit').value;
        let productQuantity = document.getElementById('productQuantity').value;
        let productImg = document.getElementById('productImg').files[0];

        if (productCategory.length === 0) {
            errorToast("Product Category Required !")
        } else if (productName.length === 0) {
            errorToast("Product Name Required !")
        } else if (productDescription.length === 0) {
            errorToast("Product Description Required !")
        } else if (productPrice.length === 0) {
            errorToast("Product Price Required !")
        } else if (productUnit.length === 0) {
            errorToast("Product Unit Required !")
        } else if (productQuantity.length === 0) {
            errorToast("Product Quantity Required !")
        } else if (!productImg) {
            errorToast("Product Image Required !")
        } else {
            document.getElementById('modal-close').click();


            try {
                let formData = new FormData();
                formData.append('image', productImg)
                formData.append('name', productName)
                formData.append('description', productDescription)
                formData.append('price', productPrice)
                formData.append('unit', productUnit)
                formData.append('quantity', productQuantity)
                formData.append('category_id', productCategory)
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                showLoader();
                let res = await axios.post("/create-product", formData, config);
                hideLoader();

                if (res.status === 201) {
                    successToast('Success..!');
                    document.getElementById('save-form').reset();
                    await fetchProducts();
                } else {
                    errorToast("Failed..!")
                }
            } catch (error) {
                console.error("Error in Save function:", error);
            }


        }
    }
</script>
