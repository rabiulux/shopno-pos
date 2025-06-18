 <div class="col-md-4 col-lg-4 p-2">
     <div class="shadow-sm h-100 bg-white rounded-3 p-3">
         <table class="table  w-100" id="productTable">
             <thead class="w-100">
                 <tr class="text-xs text-bold">
                     <td>Product</td>
                     <td>Qty</td>
                     <td>Pick</td>
                 </tr>
             </thead>
             <tbody class="w-100" id="productList">

             </tbody>
         </table>
     </div>
 </div>
 <script>
     fetchProducts();
     async function fetchProducts() {
         try {
             let response = await axios.get('/get-products');

             if (response.status === 200 && response.data.status === 'success') {
                 const products = response.data.products;

                 let productList = $('#productList');
                 let productTable = $('#productTable');

                 productList.empty();
                 productTable.DataTable().destroy();

                 products.forEach((product, index) => {
                     productList.append(`
                        <tr class="text-xs">
                            <td> <img class="w-10" src="${product.image}"/> ${product.name} ($ ${product.price})</td>
                            <td>${product.stock?.quantity ?? 0}</td>
                            <td><a data-id="${product.id}" data-name="${product.name}" data-price="${product.price}" data-quantity="${product.quantity}" class="btn addProductBtn btn-sm btn-outline-dark">Add</a></td>
                        </tr>
                    `);
                 });

                 $('.addProductBtn').on('click', async function() {
                     let id = $(this).data('id');
                     let name = $(this).data('name');
                     let price = $(this).data('price');
                     let quantity = $(this).data('quantity');

                     addModal(id, name, price, quantity);

                 });

                 $('#productTable').DataTable({
                     pageLength: 5,
                     lengthMenu: [5, 10, 15, 20],
                     searching: true,
                     ordering: true,
                 });
             } else {
                 errorToast(response.data.message || 'Failed to fetch products');
             }
         } catch (error) {
              errorToast(error.response?.data?.message);
             console.log(error);
         }
     }

     function addModal(id, name, price, quantity) {
         $('#PId').val(id);
         $('#PName').val(name);
         $('#PPrice').val(price);
         $('#PQuantity').text(quantity);
         $('#PStock').val(quantity);
         $('#PQty').val(1);
         $('#create-modal').modal('show');
     }
 </script>
