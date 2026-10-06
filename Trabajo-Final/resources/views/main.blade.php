<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <!-- DataTable responsivo-->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">




    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script src="{{ asset('js/select2.js') }}"></script>




    

    @yield('css')
    @yield('head')
    <title>@yield('title')</title>
    
</head>


<body>

<!-- Loading Overlay -->
    <div id="loadingOverlay"
        class="position-fixed top-0 start-0 w-100 h-100 d-none
                bg-white bg-opacity-75
                d-flex align-items-center justify-content-center"
        style="z-index: 2000;">

        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status"></div>
            <div class="fw-semibold" id="loadingText">Cargando...</div>
        </div>
    </div>


    <button class="btn btn-dark d-md-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

   <div class="d-flex">
        @include('sidebar')

        <div class="flex-grow-1" style="min-width: 0;">
            <div class="container-fluid mt-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
                    @if (Breadcrumbs::exists())
                        {{ Breadcrumbs::render() }}
                    @else
                        <h4 class="mb-0">@yield('title')</h4>
                    @endif

                    <span class="text-muted"> Fecha: {{ now()->format('d/m/Y') }}</span>
                </div>
                @yield('body')
                @yield('js')
            </div>
        </div>
    </div>
</body>
</html>