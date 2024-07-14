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
                    </tr>
                </thead>
                <tbody data-rows>
                    @foreach ($data as $key => $value)
                        <tr>
                            <td>{{ $value['name'] }}</td>
                            <td>{{ $value['quantity'] }}</td>
                            <td>{{ $value['price'] }}</td>
                            <td>{{ $value['timestamp'] }}</td>
                            <td data-value>{{ $value['quantity'] * $value['price'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="4">Total</th>
                        <th data-total>harga</th>
                    </tr>
                </tbody>
            </table>
        </div>
        <script>

            function updateTotal() {
                let val = 0;
                $('[data-value]').each(function (key, value) {
                    val = val + parseInt(value.innerHTML);
                });

                $('[data-total]').html(val);
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
                    $('[data-rows]').prepend(`<tr>
                            <td>${data['name']}</td>
                            <td>${data['quantity']}</td>
                            <td>${data['price']}</td>
                            <td>${data['timestamp']}</td>
                            <td>${data['quantity'] * data['price']}</td>
                        </tr>`);

                    let total = $('[data-total]').html();
                    $('[data-total]').html(
                        parseInt(total) + (parseInt(data['quantity']) * parseInt(data['price']))
                    );

                    e.reset();
                });

                return false;
            }
        </script>
    </body>
</html>
