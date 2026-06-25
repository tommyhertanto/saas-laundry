{{-- resources/views/test.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry MVP</title>

    {{-- Bootstrap 5 CDN --}}
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet"
    href="{{ asset('assets/bootstrap-icons/bootstrap-icons.css') }}"> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">LaundryApp</a>

        <div class="ms-auto d-flex align-items-center gap-2">

            <a href="#dashboard" class="btn btn-light btn-sm">
                Dashboard
            </a>

            <a href="#order" class="btn btn-light btn-sm">
                Order
            </a>

            <a href="#tambah-order" class="btn btn-warning btn-sm">
                + Tambah Order
            </a>

            {{-- Profile Dropdown --}}
            <div class="dropdown">

                <button class="btn btn-light btn-sm dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown">

                    <i class="bi bi-person me-1"></i>
                    {{ auth()->user()->name }}

                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                    <li>
                        <span class="dropdown-item-text text-muted small">
                            {{ auth()->user()->email }}
                        </span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="dropdown-item text-danger">

                                Logout

                            </button>
                        </form>
                    </li>

                </ul>

            </div>

        </div>
    </div>
</nav>

<div class="container py-4">

    {{-- DASHBOARD --}}
    <section id="dashboard" class="mb-5">
        <h3 class="fw-bold mb-3">Dashboard</h3>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Order Hari Ini</h6>
                        <h2 class="fw-bold text-primary">
                            {{ $today }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Sedang Diproses</h6>
                        <h2 class="fw-bold text-warning">
                            {{ $process }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Selesai</h6>
                        <h2 class="fw-bold text-success">
                            {{ $done }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ORDER --}}
    <section id="order" class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Daftar Order</h3>
            <div class="d-flex gap-2">
                <input type="text" id="searchInput"
                       class="form-control"
                       placeholder="🔍 Cari pelanggan...">

                <select id="statusFilter" class="form-select w-auto">
                    <option value="">Semua</option>
                    <option value="process">Diproses</option>
                    <option value="done">Selesai</option>
                </select>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table id="orderTable" class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No HP</th>
                            <th>Kg</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tr id="noDataRow" style="display: none;">
                        <td colspan="7" class="text-center text-muted py-3">
                            Data tidak ditemukan
                        </td>
                    </tr>
                    <tbody>
                        <!--<tr>
                            <td>Budi</td>
                            <td>08123456789</td>
                            <td>3</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    Proses
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm">
                                    Selesai
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>Ani</td>
                            <td>08129876543</td>
                            <td>2</td>
                            <td>
                                <span class="badge bg-success">
                                    Selesai
                                </span>
                            </td>
                            <td>-</td>
                        </tr>

                        <tr>
                            <td>Rina</td>
                            <td>08131111222</td>
                            <td>5</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    Proses
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-success btn-sm">
                                    Selesai
                                </button>
                            </td>
                        </tr>-->

                        @foreach($orders as $order)
                            <tr data-status="{{ $order->status }}">
                        <td class="row-number"></td>
                        <td>{{ $order->customer->name }}</td>
                        <td>{{ $order->customer->phone }}</td>
                        <td>{{ $order->weight }}</td>
                        <td>Rp {{ number_format($order->price,0,',','.') }}</td>
                        <td>{{ $order->status }}</td>

                        <td>
                            @if($order->status == 'process')

                            <form action="{{ route('orders.done', $order->id) }}"
                                  method="POST"
                                  class="form-selesai">

                                @csrf

                                <button type="submit" class="btn btn-success btn-sm">
                                    Selesai
                                </button>

                            </form>

                            @else

                            <span class="badge bg-secondary">
                                Selesai
                            </span>

                            @endif
                        </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-3">

                    <div id="paginationInfo" class="text-muted small"></div>

                    <div>
                        <button id="prevPage" class="btn btn-sm btn-outline-secondary">Prev</button>
                        <span id="pageNumbers" class="mx-2"></span>
                        <button id="nextPage" class="btn btn-sm btn-outline-secondary">Next</button>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- TAMBAH ORDER --}}
    <section id="tambah-order">
        <h3 class="fw-bold mb-3">Tambah Order</h3>

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <form method="POST" action="/store">
                @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No HP</label>
                        <input type="text" class="form-control" placeholder="08xxxxxxxxxx" name="phone" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Berat (Kg)</label>
                        <input type="number" class="form-control" placeholder="Contoh: 3" name="weight" required>
                    </div>

                    <!--<div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="text" class="form-control" name="price" readonly>
                    </div>-->

                    <button type="submit" class="btn btn-primary">
                        Simpan Order
                    </button>
                </form>

                <!--<form method="POST" action="/store">
                @csrf

                <input type="text" name="name" required>
                <input type="text" name="phone" required>
                <input type="number" name="weight" required>

                <button type="submit">
                Simpan Order
                </button>

                </form>-->

            </div>
        </div>
    </section>

</div>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
document.querySelectorAll('.form-selesai').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Yakin?',
            text: 'Tandai order ini selesai?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const noDataRow = document.getElementById('noDataRow');

    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const pageNumbers = document.getElementById('pageNumbers');
    const info = document.getElementById('paginationInfo');

    let rows = Array.from(document.querySelectorAll("#orderTable tbody tr:not(#noDataRow)"));

    let currentPage = 1;
    const perPage = 5;

    function filterRows() {
        let searchValue = searchInput.value.toLowerCase();
        let statusValue = statusFilter.value;

        return rows.filter(row => {
            let text = row.innerText.toLowerCase();
            let status = row.getAttribute("data-status");

            let matchSearch = text.includes(searchValue);
            let matchStatus = statusValue === "" || status === statusValue;

            return matchSearch && matchStatus;
        });
    }

    function renderTable() {
        let filtered = filterRows();
        let total = filtered.length;

        let start = (currentPage - 1) * perPage;
        let end = start + perPage;

        rows.forEach(row => row.style.display = 'none');

        if (total === 0) {
            noDataRow.style.display = '';
        } else {
            noDataRow.style.display = 'none';

            filtered.slice(start, end).forEach((row, index) => {

                row.style.display = '';

                row.querySelector('.row-number').innerText = start + index + 1;

            });
        }

        renderPagination(total);
        updateInfo(start, end, total);
    }

    function renderPagination(total) {
        let totalPages = Math.ceil(total / perPage);

        pageNumbers.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            let btn = document.createElement('button');
            btn.className = 'btn btn-sm ' + (i === currentPage ? 'btn-primary' : 'btn-outline-secondary');
            btn.innerText = i;

            btn.addEventListener('click', () => {
                currentPage = i;
                renderTable();
            });

            pageNumbers.appendChild(btn);
        }

        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    }

    function updateInfo(start, end, total) {
        if (total === 0) {
            info.innerText = "Tidak ada data";
        } else {
            info.innerText = `Menampilkan ${start + 1} - ${Math.min(end, total)} dari ${total} data`;
        }
    }

    prevBtn.addEventListener('click', () => {
        currentPage--;
        renderTable();
    });

    nextBtn.addEventListener('click', () => {
        currentPage++;
        renderTable();
    });

    searchInput.addEventListener('keyup', () => {
        currentPage = 1;
        renderTable();
    });

    statusFilter.addEventListener('change', () => {
        currentPage = 1;
        renderTable();
    });

    renderTable();
});
</script>
</body>
</html>
