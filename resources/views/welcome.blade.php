<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    </head>
    <body class="container">
        <form method="POST" action="{{ route('index') }}" onsubmit="event.preventDefault(); handleSubmit(this)">
            @csrf
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required id="exampleFormControlInput1">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Quantity In Stock</label>
                    <input type="number" name="quantity" step="0.01" class="form-control" required id="exampleFormControlInput1">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Price Per Item</label>
                    <input type="number" name="price" step="0.01" class="form-control" required id="exampleFormControlInput1">
                </div>
                <button class="btn btn-primary" type="submit">Add</button>
            </div>
        </form>
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Product Name</th>
                        <th scope="col">Quantitiy In Stock</th>
                        <th scope="col">Price per Item</th>
                        <th scope="col">Date Submited</th>
                        <th scope="col">Total Value In Number</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody data-rows></tbody>
            </table>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="EditForm" method="PUT" onsubmit="event.preventDefault(); handleSubmit(this)">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Product Name</label>
                                <input type="text" name="name" class="form-control" required id="exampleFormControlInput1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Quantity In Stock</label>
                                <input type="number" name="quantity" step="0.01" class="form-control" required id="exampleFormControlInput1">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Price Per Item</label>
                                <input type="number" name="price" step="0.01" class="form-control" required id="exampleFormControlInput1">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" form="EditForm" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
        <script>

            let storage = @json($data)

            let myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {});

            function updateTotal() {
                let total = 0;
                let output = storage.reduce((acc, item) => {
                    total += parseInt(item['quantity'] * item['price']);
                    acc += `<tr>
                        <td>${item['name']}</td>
                        <td align="right">${item['quantity']}</td>
                        <td align="right">${item['price']}</td>
                        <td>${item['timestamp']}</td>
                        <td align="right">${item['quantity'] * item['price']}</td>
                        <td><button class="btn btn-secondary btn-sm" onclick="handleModal('${item['id']}')">Edit</button></td>
                    </tr>`;
                    return acc;
                }, '');

                output += `<tr>
                    <th colspan="4">Total</th>
                    <th align="right" style="text-align: right;">${total}</th>
                    </tr>`;

                $('[data-rows]').html(output);
            }

            function handleModal(id) {
                myModal.show();
                $('#EditForm').prop('action', `{{ route('update', '_id') }}`.replace('_id', id));
                const data = storage.find((item) => item.id === id);
                $('#EditForm input[name="name"]').val(data.name);
                $('#EditForm input[name="quantity"]').val(data.quantity);
                $('#EditForm input[name="price"]').val(data.price);
            }

            $(function () {
                updateTotal();
            });

            function handleSubmit(e) {
                const action = e.getAttribute('action');
                const method = e.getAttribute('method');
                var data = new FormData(e);
                data = Object.fromEntries(data);

                $.ajax(action, {
                    method: method,
                    data: data,
                }).then(function (data) {
                    const exist = storage.find((item) => item.id === data.id);
                    if (exist) {
                        storage = storage.map(function (item) {
                            if (item.id === data.id) {
                                return {
                                    item,
                                    ...data,
                                }
                            }

                            return item;
                        });
                        myModal.hide();
                    } else {
                        storage = [data, ...storage];
                    }

                    updateTotal();
                    e.reset();
                });

                return false;
            }
        </script>
    </body>
</html>
