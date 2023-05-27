<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>{{ App\Models\Settings::first()->name }} - Login</title>

    <link rel="shortcut icon" href="{{ url('storage/' . App\Models\Settings::first()->icons) }}" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css">
</head>
<body class="border-top-wide border-primary d-flex flex-column">

    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="{{ url('') }}" class="navbar-brand navbar-brand-autodark">
                    <img src="{{ url('storage/' . App\Models\Settings::first()->icons) }}" height="36" alt="" />
                </a>
            </div>

            @yield('content')

        </div>
    </div>

    @vite('resources/js/app.jsx')
</body>
</html>