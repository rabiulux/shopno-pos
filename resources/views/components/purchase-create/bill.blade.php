 <div class="col-md-4 col-lg-4 p-2">
     <div class="shadow-sm h-100 bg-white rounded-3 p-3">

         <div class="row">
             <div class="col-8">
                 <span class="text-bold text-dark">Purchase From </span>
                 <p class="text-xs mx-0 my-1">Name: <span id="CName"></span> </p>
                 <p class="text-xs mx-0 my-1">Email: <span id="CEmail"></span></p>
                 <p class="text-xs mx-0 my-1">User ID: <span id="CId"></span> </p>
             </div>
             <div class="col-4">
                 {{-- <img class="w-50" src="{{ 'images/logo.png' }}"> --}}
                 <p class="text-bold mx-0 my-1 text-dark">Voucher </p>
                 <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
             </div>
         </div>
         <hr class="mx-0 my-2 p-0 bg-secondary" />

         <div class="row">
             <div class="col-12">
                 <table class="table w-100" id="invoiceTable">
                     <thead class="w-100">
                         <tr class="text-xs">
                             <td>Name</td>
                             <td>Qty</td>
                             <td>Total</td>
                             <td>Remove</td>
                         </tr>
                     </thead>
                     <tbody class="w-100" id="invoiceList">

                     </tbody>
                 </table>
             </div>
         </div>
         <hr class="mx-0 my-2 p-0 bg-secondary" />

         <div class="row">
             <div class="col-12">
                 <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span
                         id="total"></span></p>
                 <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>
                     <span id="payable"></span>
                 </p>
                 <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>
                     <span id="vat"></span>
                 </p>
                 <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>
                     <span id="discount"></span>
                 </p>
                 <span class="text-xxs">Discount(%):</span>
                 <input onkeydown="return false" value="0" min="0" type="number" step="0.25"
                     onchange="DiscountChange()" class="form-control w-40 " id="discountP" />
                 <p>
                     <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                 </p>
             </div>
             <div class="col-12 p-2">

             </div>

         </div>
     </div>
 </div>


 <script>

     function ShowInvoiceItem() {
         let invoiceList = $('#invoiceList');
         invoiceList.empty();
         let total = 0;
         InvoiceItemList.forEach((item, index) => {
             total += parseFloat(item.sale_price);
             invoiceList.append(`
                    <tr class="text-xs">
                        <td>${item.product_name}</td>
                        <td>${item.product_qty}</td>
                        <td>$ ${item.sale_price}</td>
                        <td><a data-index="${index}" class="btn btn-sm btn-outline-dark removeBtn">Remove</a></td>
                    </tr>
                `);
         });

         CalculateGrandTotal();

     }

     function DiscountChange() {
         CalculateGrandTotal();
     }


     $('#invoiceList').on('click', '.removeBtn', function() {
         let index = $(this).data('index');
         removeItem(index);
     });


     function removeItem(index) {
         InvoiceItemList.splice(index, 1);
         ShowInvoiceItem();
     }


     function CalculateGrandTotal() {

         let Total = 0;
         let Vat = 0;
         let Payable = 0;
         let Discount = 0;
         let DiscountPercentage = parseFloat($('#discountP').val()) || 0;

         InvoiceItemList.forEach(item => {
             Total += parseFloat(item.sale_price);
         });

         if (DiscountPercentage === 0) {
             Vat = ((Total * 5) / 100).toFixed(2);
         } else {
             Discount = (Total * (DiscountPercentage / 100)).toFixed(2);
             Total = (Total - Discount).toFixed(2);
             Vat = ((Total * 5) / 100).toFixed(2);
         }

         Payable = (parseFloat(Total) + parseFloat(Vat)).toFixed(2);

         document.getElementById('total').innerText = Total;
         document.getElementById('payable').innerText = Payable;
         document.getElementById('vat').innerText = Vat;
         document.getElementById('discount').innerText = Discount;

     }


     async function createInvoice() {
         // Validate customer selection
         let customerId = $('#CId').text();
         if (!customerId) {
             errorToast('Please select a customer');
             return;
         }

         // Validate at least one product is added
         if (InvoiceItemList.length === 0) {
             errorToast('Please add at least one product');
             return;
         }

         // Calculate totals (ensure they're up-to-date)
         CalculateGrandTotal();

         // Prepare the invoice data
         let invoiceData = {
             supplier_id: customerId,
             products: InvoiceItemList.map(item => ({
                 product_id: item.product_id,
                 quantity: item.product_qty,
                 purchase_price: item.sale_price
             })),
             total: parseFloat($('#total').text()),
             discount: parseFloat($('#discount').text()),
             vat: parseFloat($('#vat').text()),
             payable: parseFloat($('#payable').text()),
             discount_percentage: parseFloat($('#discountP').val()) || 0
         };

         // Validate financial values
         if (isNaN(invoiceData.payable) || invoiceData.payable <= 0) {
             errorToast('Invalid payable amount');
             return;
         }

         try {
             showLoader();

             // API call to create invoice
             let response = await axios.post('/create-purchase', invoiceData);

             hideLoader();

             if (response.status === 201 && response.data.status === 'success') {
                 window.location.href = '/purchase-page';
                 successToast('Purchased successfully!');

                 // Reset the form
                 InvoiceItemList = [];
                 currentCustomer = null;
                 $('#CName, #CEmail, #CId').text('');
                 ShowInvoiceItem();
                 resetTotals();

             } else {
                 errorToast(response.data.message || 'Purchase failed');
             }
         } catch (error) {
             hideLoader();

             // Handle different error types
             if (error.response) {
                 // Server responded with error status
                 if (error.response.status === 422) {
                     // Validation errors
                     const errors = error.response.data.errors;
                     let errorMessages = Object.values(errors).join('<br>');
                     errorToast(errorMessages);
                 } else {
                     errorToast(error.response.data?.message || 'Server error occurred');
                 }
             } else if (error.request) {
                 // No response received
                 errorToast('Network error - please check your connection');
             } else {
                 // Other errors
                 errorToast('An unexpected error occurred');
                 console.error('Purchase error:', error);
             }
         }
     }

     // Helper function to reset totals
     function resetTotals() {
         $('#total').text('0.00');
         $('#payable').text('0.00');
         $('#vat').text('0.00');
         $('#discount').text('0.00');
         $('#discountP').val('0');
     }
 </script>
