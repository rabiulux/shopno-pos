<!-- Modal -->
<div class="modal animated zoomIn" id="details-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Purchase</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="purchase" class="modal-body p-3">
                <div class="container-fluid">
                    <br />
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">PURCHASE FROM </span>
                            <p class="text-xs mx-0 my-1">Name: <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email: <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">User ID: <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            {{-- <img class="w-40" src="{{ 'images/logo.png' }}"> --}}
                            <p class="text-bold mx-0 my-1 text-dark">Voucher </p>
                            <p class="text-xs mx-0 my-1">Date: <span id="date"></span></p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="purchaseTable">
                                <thead class="w-100">
                                    <tr class="text-xs text-bold">
                                        <td>Name</td>
                                        <td>Qty</td>
                                        <td>Total</td>
                                    </tr>
                                </thead>
                                <tbody class="w-100" id="purchaseList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary" />
                    <div class="row">
                        <div class="col-12">
                            <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i>
                                <span id="total"></span>
                            </p>
                            <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>
                                <span id="payable"></span>
                            </p>
                            <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>
                                <span id="vat"></span>
                            </p>
                            <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>
                                <span id="discount"></span>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-primary" data-bs-dismiss="modal">Close</button>
                <button onclick="PrintPage()" class="btn bg-gradient-success">Print</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function purchaseDetails(purchase_id, supplier_id) {
        try {
            if (!purchase_id || !supplier_id) {
                errorToast('Invalid purchase or supplier ID');
                return;
            }
            showLoader();
            let response = await axios.post('/purchase-details', {
                purchase_id: purchase_id,
                supplier_id: supplier_id,
            });
            hideLoader();


            if (response.status !== 200) {
                errorToast('Failed to fetch purchase details');
                return;
            }


            document.getElementById('CName').innerText = response.data.supplier.name;
            document.getElementById('CId').innerText = response.data.supplier.user_id;
            document.getElementById('CEmail').innerText = response.data.supplier.email;
            document.getElementById('total').innerText = response.data.purchase.total;
            document.getElementById('payable').innerText = response.data.purchase.payable;
            document.getElementById('vat').innerText = response.data.purchase.vat;
            document.getElementById('discount').innerText = response.data.purchase.discount;

            const rawDate = new Date(response.data.purchase.created_at);

            const formattedDate = rawDate.toLocaleDateString('en-US', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            document.getElementById('date').innerText = formattedDate;


            let purchaseList = $('#purchaseList');

            purchaseList.empty();


            response.data.products.forEach(function(item) {
                const productName = item.products?.[0]?.name || 'N/A';
                purchaseList.append(`
                        <tr class="text-xs">
                            <td>${productName}</td>
                            <td>${item.quantity}</td>
                            <td>${item.purchase_price}</td>
                        </tr>
                    `);
            });

            $("#details-modal").modal('show');

        } catch (error) {
            console.error('Error fetching purchase details:', error);
            errorToast(error.response?.data?.message || 'Something went wrong.');
            hideLoader();
        }
    }

    function PrintPage() {
        const printContents = document.getElementById('purchase').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        setTimeout(() => {
            location.reload();
        }, 1000);

    }
</script>
