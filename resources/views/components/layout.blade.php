<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SIPUS-Del' }} — SIPUS-Del</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --del-purple: #4C1D95;
            --del-purple-dark: #3B0764;
            --del-purple-soft: #EDE9FE;
            --bs-link-color-rgb: 76, 29, 149;
            --bs-link-hover-color-rgb: 59, 7, 100;
        }
        .navbar-del { background-color: var(--del-purple); }
        .navbar-del .nav-link.active { font-weight: 600; border-bottom: 2px solid #fff; }
        .btn-del { background-color: var(--del-purple); border-color: var(--del-purple); color: #fff; }
        .btn-del:hover { background-color: var(--del-purple-dark); border-color: var(--del-purple-dark); color: #fff; }
        .badge-del { background-color: var(--del-purple-soft); color: var(--del-purple); }
        .pagination {
            --bs-pagination-color: var(--del-purple);
            --bs-pagination-hover-color: var(--del-purple-dark);
            --bs-pagination-active-bg: var(--del-purple);
            --bs-pagination-active-border-color: var(--del-purple);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--del-purple);
            box-shadow: 0 0 0 .25rem rgba(76, 29, 149, .25);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-del shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('buku.index') }}">SIPUS-Del</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#menuUtama" aria-controls="menuUtama"
                    aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuUtama">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}"
                           href="{{ route('buku.index') }}">Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                           href="{{ route('kategori.index') }}">Kategori</a>
                    </li>
                </ul>
                <span class="navbar-text small">Perpustakaan Kampus Del</span>
            </div>
        </div>
    </nav>

    <main class="container pb-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="text-center text-muted small py-4">
        &copy; {{ date('Y') }} SIPUS-Del &middot; Institut Teknologi Del
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>