<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
            </div>
            <div class="modal-body">
                <form id="add-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Product ID *</label>
                                <input type="text" class="form-control" id="PId" readonly>
                                <label class="form-label mt-2">Product Name *</label>
                                <input type="text" class="form-control" id="PName" readonly>
                                <label class="form-label mt-2">Product Price *</label>
                                <input type="text" class="form-control" id="PPrice" readonly>
                                <label class="form-label mt-2">Product Qty *</label>

                                <div class="row">

                                    <div class="col-6">
                                <input type="text" class="form-control" id="PQty">
                                    </div>
                                    <div class="col-6"> (Maximum <span id="PQuantity"></span>)
                                        <input type="text" class="d-none" id="PStock">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                    aria-label="Close">Close</button>
                <button onclick="add()" id="save-btn" class="btn bg-gradient-success">Add</button>
            </div>
        </div>
    </div>
</div>
<script>
    InvoiceItemList = [];

    function add() {
        let id = $('#PId').val();
        let name = $('#PName').val();
        let price = $('#PPrice').val();
        let qty = $('#PQty').val();
        let stock = $('#PStock').val();

        let total_price = (parseFloat(price) * parseFloat(qty)).toFixed(2);

        if (id === '' || name === '' || price === '' || qty === '') {
            errorToast('Please fill all fields');
            return;
        }

        if (parseFloat(qty) <= 0) {
            errorToast('Please input product quantity.');
            return;
        }else if (parseFloat(qty) > parseFloat(stock)) {
            errorToast('Product quantity exceeds stock.');
            return;
        }

        let item = {
            product_name: name,
            product_id: id,
            product_price: price,
            product_qty: qty,
            sale_price: total_price
        }

        InvoiceItemList.push(item);
        $('#create-modal').modal('hide');
        ShowInvoiceItem();
    }
</script>
