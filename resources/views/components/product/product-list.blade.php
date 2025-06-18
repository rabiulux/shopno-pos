<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Product</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0  bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-dark " />
                <table class="table" id="tableData">
                    <thead>
                        <tr class="bg-light">
                            <th>No</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Unit</th>
                            <th>Stock</th>
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
    fetchProducts();

    async function fetchProducts() {
        try {
            showLoader();
            let response = await axios.get('/get-products');
            hideLoader();

            if (response.status === 200 && response.data.status === 'success') {
                const products = response.data.products;

                let tableList = $('#tableList');

                // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable('#tableData')) {
                    $('#tableData').DataTable().destroy();
                }

                tableList.empty();

                products.forEach((product, index) => {
                    tableList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                <img src="${product.image}" alt="${product.name}" class="img-thumbnail" style="width: 50px; height: 50px;">
                            </td>
                            <td>${product.name}</td>
                            <td>${product.price}</td>
                            <td>${product.unit}</td>
                            <td>${product.stock?.quantity ?? 0}</td>
                            <td>
                                <button data-path="${product['image']}" data-id="${product['id']}" class="btn editBtn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#update-modal">Update</button>
                                <button data-path="${product['image']}" data-id="${product['id']}" class="btn deleteBtn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                    `);
                });


                $('.editBtn').on('click', async function() {
                    const id = $(this).data('id');
                    const file_path = $(this).data('path');
                   await FillUpUpdateForm(id, file_path);
                    $('#update-modal').modal('show');
                });

                $('.deleteBtn').on('click', function() {
                    const id = $(this).data('id');
                    const path = $(this).data('path');
                    $('#delete-modal').modal('show');
                    $('#deleteID').val(id);
                    $('#deleteFilePath').val(path);
                });

                // Initialize DataTable
                $('#tableData').DataTable({
                    "order": [
                        [0, "asc"]
                    ],
                    "pageLength": 10,
                    "lengthMenu": [10, 25, 50, 100]
                });
            } else {
                console.error('Failed to fetch products:', response.data.message);
            }
            hideLoader();
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }
</script>
