<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between ">
                    <div class="align-items-center col">
                        <h4>Category</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal"
                            class="float-end btn m-0 bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-secondary" />
                <div class="table-responsive">
                    <table class="table" id="tableData">
                        <thead>
                            <tr class="bg-light">
                                <th>No</th>
                                <th>Category</th>
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
</div>

<script>
    fetchCategories();

    async function fetchCategories() {
        try {
            showLoader();
            let response = await axios.get('/get-categories');
            hideLoader();


            if (response.status === 403) {
                errorToast('You are not authorized to access this resource.');
                // return;
            }

            if (response.status === 200 && response.data.status === 'success') {
                const categories = response.data.categories;

                let tableList = $('#tableList');

                // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable('#tableData')) {
                    $('#tableData').DataTable().destroy();
                }

                tableList.empty();

                categories.forEach((category, index) => {
                    tableList.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${category.name}</td>
                            <td>
                                <button data-id="${category['id']}" class="btn editBtn btn-primary">Edit</button>
                                <button data-id="${category['id']}" class="btn deleteBtn btn-outline-danger">Delete</button>
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
                console.error('Failed to fetch categories:', response.data.message);
            }
        } catch (error) {
            hideLoader();
            console.error('Error fetching categories:', error);
        }
    }
</script>

