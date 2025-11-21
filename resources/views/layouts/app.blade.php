<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- BOOTSTRAP 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- BOOTSTRAP ICONS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- SELECT2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    {{-- DATATABLES CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


    {{-- //fontawsome --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">





    {{-- Vite --}}
    @vite(['././resources/js/app.js'])

    <style>
        body {
            min-height: 100vh;
        }

        /* Sidebar fix */
        .sidebar {
            width: 250px;
        }

        .content {
            margin-left: 250px;
        }

        /* Font tabel lebih kecil */
        table.dataTable {
            font-size: 13pt;
            /* ubah sesuai kebutuhan */
        }

        /* Teks satu baris dan potong jika panjang */
        td,
        th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
            /* atur lebar maksimal kolom */
        }

        /* Optional: tooltip untuk menampilkan full text saat hover */
        td[title] {
            cursor: help;
        }
    </style>
</head>

<body>
    <div class="d-flex">

        @include('layouts.navigation')

        {{-- Konten utama --}}
        <div class="content flex-grow-1">

            @include('layouts.topnav')

            {{-- Konten Halaman --}}
            <div class="p-4">
                @yield('content')
            </div>

        </div>
    </div>

    {{-- ================= JAVASCRIPT ================= --}}
    {{-- JQUERY --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- BOOTSTRAP JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SELECT2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- DATATABLES JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    @yield('scripts') <!-- di sinilah JS halaman create/edit masuk -->

    {{-- AJAX Setup CSRF --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // SELECT2 Init
            $('.select2').select2();

            // DATATABLES Init
            $('.datatable').DataTable({
                pageLength: 10,
                ordering: true
            });
        });


        // menghilangkan otomatis alert
        $(document).ready(function() {
            // Alert otomatis hilang setelah 2 detik
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 2000);
        });
    </script>

</body>

</html>
