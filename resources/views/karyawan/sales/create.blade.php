@extends('layouts.app')

@section('title', 'Catat Penjualan Toko')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Catat Penjualan Toko</h4>
        <p class="text-muted mb-0">Input barang yang dibeli pelanggan, stok akan otomatis terpotong</p>
    </div>
    <a href="{{ route('karyawan.sales.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Riwayat Penjualan
    </a>
</div>

@if($products->isEmpty())
    <div class="card border shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted opacity-50 mb-3"></i>
            <h6 class="fw-bold text-dark">Belum Ada Produk Tersedia</h6>
            <p class="text-muted small mb-0">Hubungi pemilik toko (Owner) untuk menambahkan daftar produk.</p>
        </div>
    </div>
@else
<form method="POST" action="{{ route('karyawan.sales.store') }}" id="saleForm">
    @csrf

    <div class="row g-4">
        <!-- Kolom Kiri: Pilihan Produk -->
        <div class="col-lg-8">
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-cart-plus text-success me-2"></i>Daftar Barang Belanjaan
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addItemRow()">
                        <i class="fas fa-plus me-1"></i>Tambah Produk
                    </button>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 250px;">Produk</th>
                                    <th style="width: 140px;" class="text-end">Harga Satuan</th>
                                    <th style="width: 130px;" class="text-center">Jumlah</th>
                                    <th style="width: 150px;" class="text-end">Subtotal</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select product-select" name="items[0][product_id]" required onchange="onProductChange(this)">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $p)
                                                <option value="{{ $p->id }}"
                                                        data-price="{{ $p->selling_price }}"
                                                        data-stock="{{ $p->stock }}"
                                                        data-unit="{{ $p->unit }}"
                                                        {{ $p->stock <= 0 ? 'disabled' : '' }}>
                                                    {{ $p->name }} (Sisa: {{ $p->stock }} {{ $p->unit }}) - {{ format_rupiah($p->selling_price) }} {{ $p->stock <= 0 ? '[HABIS]' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="stock-info text-muted small mt-1"></div>
                                    </td>
                                    <td class="text-end">
                                        <span class="unit-price-display fw-semibold text-muted">Rp 0</span>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control text-center quantity-input"
                                                   name="items[0][quantity]" value="1" min="1" required onchange="calculateRow(this)" onkeyup="calculateRow(this)">
                                            <span class="input-group-text unit-label bg-light">pcs</span>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="subtotal-display fw-bold text-dark">Rp 0</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-row-btn" onclick="removeItemRow(this)" title="Hapus Item">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="addItemRow()">
                            <i class="fas fa-plus me-1"></i>+ Tambah Baris Produk Lainnya
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail & Simpan -->
        <div class="col-lg-4">
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-calculator text-primary me-2"></i>Total Pembayaran
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 bg-light rounded-3 mb-4 text-center">
                        <span class="text-muted small fw-semibold text-uppercase">Total Belanja</span>
                        <div class="fs-2 fw-bold text-success" id="grandTotalDisplay">Rp 0</div>
                        <span class="text-muted small" id="totalItemsCount">0 jenis barang</span>
                    </div>

                    <div class="mb-3">
                        <label for="transaction_date" class="form-label fw-semibold small">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="transaction_date" name="transaction_date"
                               value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="payment_method" class="form-label fw-semibold small">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="Tunai" {{ old('payment_method') === 'Tunai' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="Transfer" {{ old('payment_method') === 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="QRIS" {{ old('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS / E-Wallet</option>
                            <option value="Lainnya" {{ old('payment_method') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small">Catatan Pembeli / Keterangan</label>
                        <textarea class="form-control" id="description" name="description" rows="2"
                                  placeholder="Contoh: Pesanan Meja 4, Bawa pulang (opsional)">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold fs-6">
                        <i class="fas fa-check-circle me-1"></i>Selesaikan Penjualan
                    </button>
                    <a href="{{ route('karyawan.sales.index') }}" class="btn btn-outline-secondary w-100 mt-2">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endif

@push('scripts')
<script>
    let rowIndex = 1;
    const productsData = @json($products);

    function formatRp(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    function onProductChange(select) {
        const row = select.closest('tr');
        const selected = select.options[select.selectedIndex];
        const stockInfo = row.querySelector('.stock-info');
        const priceDisplay = row.querySelector('.unit-price-display');
        const unitLabel = row.querySelector('.unit-label');
        const qtyInput = row.querySelector('.quantity-input');

        if (selected && selected.value) {
            const price = parseFloat(selected.getAttribute('data-price')) || 0;
            const stock = parseInt(selected.getAttribute('data-stock')) || 0;
            const unit = selected.getAttribute('data-unit') || 'pcs';

            priceDisplay.textContent = formatRp(price);
            unitLabel.textContent = unit;
            qtyInput.max = stock;

            if (stock <= 0) {
                stockInfo.innerHTML = '<span class="text-danger"><i class="fas fa-times me-1"></i>Stok habis!</span>';
            } else if (stock <= 5) {
                stockInfo.innerHTML = `<span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Sisa ${stock} ${unit}</span>`;
            } else {
                stockInfo.innerHTML = `<span class="text-success"><i class="fas fa-check me-1"></i>Tersedia ${stock} ${unit}</span>`;
            }
        } else {
            priceDisplay.textContent = 'Rp 0';
            stockInfo.innerHTML = '';
        }

        calculateRow(qtyInput);
    }

    function calculateRow(qtyInput) {
        const row = qtyInput.closest('tr');
        const select = row.querySelector('.product-select');
        const selected = select.options[select.selectedIndex];
        const subtotalDisplay = row.querySelector('.subtotal-display');

        const qty = parseInt(qtyInput.value) || 0;
        let subtotal = 0;

        if (selected && selected.value) {
            const price = parseFloat(selected.getAttribute('data-price')) || 0;
            const stock = parseInt(selected.getAttribute('data-stock')) || 0;

            if (qty > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup',
                    text: `Stok hanya tersedia ${stock}. Jumlah disesuaikan ke stok maksimal.`,
                    confirmButtonColor: '#22c55e'
                });
                qtyInput.value = stock;
                subtotal = stock * price;
            } else {
                subtotal = qty * price;
            }
        }

        subtotalDisplay.textContent = formatRp(subtotal);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        let itemCount = 0;

        document.querySelectorAll('#itemsTableBody tr').forEach(row => {
            const select = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.quantity-input');
            const selected = select ? select.options[select.selectedIndex] : null;

            if (selected && selected.value) {
                const price = parseFloat(selected.getAttribute('data-price')) || 0;
                const qty = parseInt(qtyInput.value) || 0;
                grandTotal += (price * qty);
                itemCount++;
            }
        });

        document.getElementById('grandTotalDisplay').textContent = formatRp(grandTotal);
        document.getElementById('totalItemsCount').textContent = itemCount + ' jenis barang';
    }

    function addItemRow() {
        const tbody = document.getElementById('itemsTableBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';

        let optionsHtml = '<option value="">-- Pilih Produk --</option>';
        productsData.forEach(p => {
            const disabled = p.stock <= 0 ? 'disabled' : '';
            const statusLabel = p.stock <= 0 ? '[HABIS]' : '';
            optionsHtml += `<option value="${p.id}" data-price="${p.selling_price}" data-stock="${p.stock}" data-unit="${p.unit}" ${disabled}>
                ${p.name} (Sisa: ${p.stock} ${p.unit}) - ${formatRp(p.selling_price)} ${statusLabel}
            </option>`;
        });

        newRow.innerHTML = `
            <td>
                <select class="form-select product-select" name="items[${rowIndex}][product_id]" required onchange="onProductChange(this)">
                    ${optionsHtml}
                </select>
                <div class="stock-info text-muted small mt-1"></div>
            </td>
            <td class="text-end">
                <span class="unit-price-display fw-semibold text-muted">Rp 0</span>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control text-center quantity-input"
                           name="items[${rowIndex}][quantity]" value="1" min="1" required onchange="calculateRow(this)" onkeyup="calculateRow(this)">
                    <span class="input-group-text unit-label bg-light">pcs</span>
                </div>
            </td>
            <td class="text-end">
                <span class="subtotal-display fw-bold text-dark">Rp 0</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0 remove-row-btn" onclick="removeItemRow(this)" title="Hapus Item">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        tbody.appendChild(newRow);
        rowIndex++;
    }

    function removeItemRow(btn) {
        const rows = document.querySelectorAll('#itemsTableBody tr');
        if (rows.length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Minimal harus ada 1 baris produk untuk dicatat.',
                confirmButtonColor: '#22c55e'
            });
            return;
        }
        btn.closest('tr').remove();
        calculateGrandTotal();
    }
</script>
@endpush
@endsection
