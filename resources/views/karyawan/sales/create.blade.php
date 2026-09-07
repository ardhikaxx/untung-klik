@extends('layouts.app')

@section('title', 'Kasir & Penjualan Toko')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1">Kasir & Penjualan Toko</h4>
        <p class="text-muted mb-0">Pilih barang belanjaan pembeli dengan cepat, stok terpotong otomatis dan bukti nota siap dicetak.</p>
    </div>
    <a href="{{ route('karyawan.sales.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-history me-1"></i>Riwayat Kasir Saya
    </a>
</div>

@if($products->isEmpty())
    <div class="card border shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted opacity-50 mb-3"></i>
            <h6 class="fw-bold text-dark">Belum Ada Produk Tersedia</h6>
            <p class="text-muted small mb-0">Hubungi pemilik usaha (Owner) untuk menambahkan daftar produk ke sistem.</p>
        </div>
    </div>
@else
<form method="POST" action="{{ route('karyawan.sales.store') }}" id="saleForm">
    @csrf

    <div class="row g-4">
        <!-- Kolom Kiri: Quick Pick Grid & Daftar Keranjang -->
        <div class="col-xl-8 col-lg-7">

            <!-- Panel Pemilih Cepat Produk (POS Quick Pick) -->
            <div class="card border shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-th-large text-success me-2"></i>Pilih Cepat Produk (Klik untuk Menambahkan)
                            </h6>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control border-start-0" id="quickSearchInput"
                                       placeholder="Cari nama barang atau SKU..." onkeyup="filterQuickProducts()">
                            </div>
                        </div>
                    </div>
                    @if($categories->isNotEmpty())
                    <div class="d-flex gap-1 flex-wrap mt-2 pt-2 border-top">
                        <button type="button" class="btn btn-xs btn-success category-filter-btn active py-1 px-2 small"
                                onclick="filterByCategory('all', this)" style="font-size: 0.75rem;">Semua Kategori</button>
                        @foreach($categories as $c)
                            <button type="button" class="btn btn-xs btn-outline-secondary category-filter-btn py-1 px-2 small"
                                    onclick="filterByCategory('{{ $c->id }}', this)" style="font-size: 0.75rem;">
                                {{ $c->name }}
                            </button>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 overflow-auto" id="quickProductsGrid" style="max-height: 280px;">
                        @foreach($products as $p)
                            <div class="col-6 col-sm-4 col-md-3 quick-product-card"
                                 data-id="{{ $p->id }}"
                                 data-name="{{ strtolower($p->name) }}"
                                 data-sku="{{ strtolower($p->sku ?? '') }}"
                                 data-category="{{ $p->category_id }}"
                                 data-stock="{{ $p->stock }}"
                                 data-price="{{ $p->selling_price }}"
                                 data-unit="{{ $p->unit }}">
                                <div class="card h-100 border text-start p-2 {{ $p->stock <= 0 ? 'bg-light opacity-50' : 'cursor-pointer hover-shadow product-card-btn' }}"
                                     onclick="{{ $p->stock > 0 ? "addFromQuickGrid({$p->id})" : '' }}"
                                     style="transition: all 0.15s ease-in-out; {{ $p->stock > 0 ? 'cursor: pointer;' : 'cursor: not-allowed;' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge {{ $p->stock <= 0 ? 'bg-danger' : ($p->stock <= $p->min_stock ? 'bg-warning text-dark' : 'bg-light text-secondary border') }}" style="font-size: 0.65rem;">
                                            {{ $p->stock <= 0 ? 'Habis' : 'Stok: ' . $p->stock }}
                                        </span>
                                        @if($p->sku)
                                            <span class="text-muted" style="font-size: 0.65rem;">{{ $p->sku }}</span>
                                        @endif
                                    </div>
                                    <div class="fw-semibold text-dark text-truncate small mb-1" title="{{ $p->name }}">{{ $p->name }}</div>
                                    <div class="fw-bold text-success small">{{ format_rupiah($p->selling_price) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tabel Item Terjual (Keranjang) -->
            <div class="card border shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-shopping-cart text-primary me-2"></i>Daftar Item Keranjang
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addItemRow()">
                        <i class="fas fa-plus me-1"></i>Tambah Baris Dropdown
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="min-width: 230px;">Produk</th>
                                    <th style="width: 130px;" class="text-end">Harga Satuan</th>
                                    <th style="width: 140px;" class="text-center">Jumlah</th>
                                    <th style="width: 140px;" class="text-end">Subtotal</th>
                                    <th style="width: 50px;" class="text-center pe-3"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Dynamic Rows Generated via JS -->
                            </tbody>
                        </table>
                    </div>

                    <div id="emptyCartNotice" class="text-center py-5 text-muted">
                        <i class="fas fa-cart-arrow-down fa-3x text-secondary opacity-50 mb-2"></i>
                        <p class="mb-1 fw-semibold text-dark">Keranjang Masih Kosong</p>
                        <small>Pilih produk pada kotak di atas atau klik "Tambah Baris Dropdown".</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Pelanggan & Pembayaran -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border shadow-sm sticky-top" style="top: 20px; z-index: 10;">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-cash-register text-success me-2"></i>Pembayaran & Pelanggan
                    </h6>
                </div>
                <div class="card-body p-3 p-sm-4">

                    <!-- Info Pelanggan -->
                    <div class="mb-3">
                        <label for="customer_name" class="form-label small fw-semibold">Nama Pembeli (Opsional)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                   placeholder="Contoh: Bu Siti / Umum" value="{{ old('customer_name', 'Pelanggan Umum') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone" class="form-label small fw-semibold">No. WhatsApp / Telepon (Opsional)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fab fa-whatsapp text-muted"></i></span>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                                   placeholder="0812xxxx (untuk kirim nota via WA)" value="{{ old('customer_phone') }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-sm-6">
                            <label for="transaction_date" class="form-label small fw-semibold">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-sm" id="transaction_date" name="transaction_date"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="payment_method" class="form-label small fw-semibold">Metode Bayar <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" id="payment_method" name="payment_method" required onchange="toggleCashInput()">
                                <option value="Tunai" {{ old('payment_method') === 'Tunai' ? 'selected' : '' }}>Tunai (Cash)</option>
                                <option value="Transfer" {{ old('payment_method') === 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="QRIS" {{ old('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS / E-Wallet</option>
                                <option value="Lainnya" {{ old('payment_method') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-3 text-muted">

                    <!-- Perhitungan Total & Diskon -->
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-muted">Subtotal Belanja:</span>
                        <span class="fw-semibold text-dark" id="subtotalDisplay">Rp 0</span>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1 small">
                            <label for="discount" class="form-label mb-0 text-muted">Potongan / Diskon (Rp):</label>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" class="form-control text-end" id="discount" name="discount"
                                   value="{{ old('discount', 0) }}" min="0" onkeyup="calculateTotals()" onchange="calculateTotals()">
                        </div>
                    </div>

                    <!-- Grand Total Banner -->
                    <div class="p-3 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 mb-3 text-center">
                        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Total yang Harus Dibayar</span>
                        <div class="fs-3 fw-bold text-success" id="grandTotalDisplay">Rp 0</div>
                        <span class="text-muted small" id="itemsCountBadge">0 item dipilih</span>
                    </div>

                    <!-- Input Pembayaran Tunai & Kembalian -->
                    <div id="cashCalculationArea">
                        <div class="mb-2">
                            <label for="cash_received" class="form-label small fw-semibold">Uang Diterima (Rp)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" class="form-control text-end fs-6 fw-bold" id="cash_received" name="cash_received"
                                       placeholder="0" value="{{ old('cash_received') }}" onkeyup="calculateChange()" onchange="calculateChange()">
                            </div>
                        </div>

                        <!-- Quick Cash Shortcuts -->
                        <div class="d-flex gap-1 flex-wrap mb-3">
                            <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small" onclick="setExactCash()">Uang Pas</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small" onclick="addCashShortcut(10000)">+10rb</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small" onclick="addCashShortcut(20000)">+20rb</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small" onclick="addCashShortcut(50000)">+50rb</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 small" onclick="addCashShortcut(100000)">+100rb</button>
                        </div>

                        <!-- Kembalian Display -->
                        <div class="p-2 rounded-2 mb-3 d-flex justify-content-between align-items-center" id="changeBox" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                            <span class="small fw-semibold text-muted">Kembalian:</span>
                            <span class="fw-bold fs-6 text-dark" id="cashChangeDisplay">Rp 0</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label small fw-semibold">Catatan Transaksi (Opsional)</label>
                        <textarea class="form-control form-control-sm" id="description" name="description" rows="2"
                                  placeholder="Keterangan tambahan untuk struk/nota">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold fs-6 shadow-sm" id="submitBtn">
                        <i class="fas fa-check-circle me-1"></i>Selesaikan & Cetak Struk
                    </button>
                    <a href="{{ route('karyawan.sales.index') }}" class="btn btn-outline-secondary w-100 mt-2 btn-sm">Batal</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endif

@push('styles')
<style>
    .cursor-pointer { cursor: pointer; }
    .product-card-btn:hover {
        border-color: #22c55e !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.08);
    }
</style>
@endpush

@push('scripts')
<script>
    const productsData = @json($products);
    let cart = [];
    let rawGrandTotal = 0;

    function formatRp(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (productsData.length > 0) {
            const firstAvailable = productsData.find(p => p.stock > 0);
            if (firstAvailable) {
                addFromQuickGrid(firstAvailable.id);
            } else {
                addItemRow();
            }
        }
        toggleCashInput();
    });

    function filterQuickProducts() {
        const query = document.getElementById('quickSearchInput').value.toLowerCase().trim();
        document.querySelectorAll('.quick-product-card').forEach(card => {
            const name = card.getAttribute('data-name');
            const sku = card.getAttribute('data-sku');
            if (!query || name.includes(query) || (sku && sku.includes(query))) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Barcode scanner & quick search Enter key support
    document.getElementById('quickSearchInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const query = this.value.trim().toLowerCase();
            if (!query) return;

            // Check exact SKU or Name match
            let matchedProduct = productsData.find(p => (p.sku && p.sku.toLowerCase() === query) || p.name.toLowerCase() === query);

            // If no exact match, check single visible product in grid
            if (!matchedProduct) {
                const visibleCards = Array.from(document.querySelectorAll('.quick-product-card')).filter(c => c.style.display !== 'none');
                if (visibleCards.length === 1) {
                    const id = parseInt(visibleCards[0].getAttribute('data-id'));
                    matchedProduct = productsData.find(p => p.id === id);
                }
            }

            if (matchedProduct) {
                if (matchedProduct.stock > 0) {
                    addFromQuickGrid(matchedProduct.id);
                    this.value = '';
                    filterQuickProducts();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Habis',
                        text: `Produk ${matchedProduct.name} sedang habis (0 ${matchedProduct.unit}).`,
                        confirmButtonColor: '#22c55e'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Produk Tidak Ditemukan',
                    text: `Tidak ada produk yang cocok dengan "${this.value}".`,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }
    });

    function filterByCategory(catId, btn) {
        document.querySelectorAll('.category-filter-btn').forEach(b => {
            b.classList.remove('btn-success', 'active');
            b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success', 'active');

        document.querySelectorAll('.quick-product-card').forEach(card => {
            const cardCat = card.getAttribute('data-category');
            if (catId === 'all' || cardCat === catId) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function addFromQuickGrid(productId) {
        const product = productsData.find(p => p.id === productId);
        if (!product || product.stock <= 0) return;

        const existingItem = cart.find(item => item.productId === productId);
        if (existingItem) {
            if (existingItem.qty < product.stock) {
                existingItem.qty += 1;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Stok Tercapai',
                    text: `Stok ${product.name} hanya tersedia ${product.stock} ${product.unit}.`,
                    confirmButtonColor: '#22c55e'
                });
            }
        } else {
            cart.push({ productId: productId, qty: 1 });
        }

        renderCart();
    }

    function addItemRow() {
        const unused = productsData.find(p => p.stock > 0 && !cart.some(c => c.productId === p.id));
        const toAdd = unused || productsData.find(p => p.stock > 0) || productsData[0];
        if (toAdd) {
            cart.push({ productId: toAdd.id, qty: 1 });
            renderCart();
        }
    }

    function removeCartItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function updateCartQty(index, delta) {
        if (!cart[index]) return;
        const product = productsData.find(p => p.id === cart[index].productId);
        if (!product) return;

        let newQty = cart[index].qty + delta;
        if (newQty < 1) newQty = 1;
        if (newQty > product.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: `Stok hanya tersedia ${product.stock} ${product.unit}.`,
                confirmButtonColor: '#22c55e'
            });
            newQty = product.stock;
        }

        cart[index].qty = newQty;
        renderCart();
    }

    function onCartProductSelect(index, selectElem) {
        const newProductId = parseInt(selectElem.value);
        if (!newProductId) return;

        const isDuplicate = cart.some((c, i) => i !== index && c.productId === newProductId);
        if (isDuplicate) {
            Swal.fire({
                icon: 'info',
                title: 'Produk Sudah Ada di Keranjang',
                text: 'Jumlah produk disatukan di baris yang sudah ada.',
                confirmButtonColor: '#22c55e'
            });
            const existing = cart.find(c => c.productId === newProductId);
            existing.qty += cart[index].qty;
            cart.splice(index, 1);
        } else {
            cart[index].productId = newProductId;
            cart[index].qty = 1;
        }

        renderCart();
    }

    function onManualQtyInput(index, inputElem) {
        if (!cart[index]) return;
        const product = productsData.find(p => p.id === cart[index].productId);
        if (!product) return;

        let val = parseInt(inputElem.value) || 1;
        if (val < 1) val = 1;
        if (val > product.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: `Stok hanya tersedia ${product.stock} ${product.unit}.`,
                confirmButtonColor: '#22c55e'
            });
            val = product.stock;
            inputElem.value = val;
        }

        cart[index].qty = val;
        renderCart(false);
    }

    function renderCart(redrawInputs = true) {
        const tbody = document.getElementById('itemsTableBody');
        const emptyNotice = document.getElementById('emptyCartNotice');

        if (cart.length === 0) {
            tbody.innerHTML = '';
            emptyNotice.style.display = 'block';
            calculateTotals();
            return;
        }

        emptyNotice.style.display = 'none';

        if (redrawInputs) {
            tbody.innerHTML = '';
            cart.forEach((item, index) => {
                const product = productsData.find(p => p.id === item.productId) || {};
                const unitPrice = parseFloat(product.selling_price) || 0;
                const subtotal = item.qty * unitPrice;

                let optionsHtml = '';
                productsData.forEach(p => {
                    const isSelected = p.id === item.productId ? 'selected' : '';
                    const isDisabled = p.stock <= 0 ? 'disabled' : '';
                    optionsHtml += `<option value="${p.id}" ${isSelected} ${isDisabled}>
                        ${p.name} (Sisa: ${p.stock} ${p.unit}) - ${formatRp(p.selling_price)}
                    </option>`;
                });

                const tr = document.createElement('tr');
                tr.className = 'cart-item-row';
                tr.innerHTML = `
                    <td class="ps-3">
                        <select class="form-select form-select-sm" name="items[${index}][product_id]" required onchange="onCartProductSelect(${index}, this)">
                            ${optionsHtml}
                        </select>
                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                            <i class="fas fa-cubes me-1"></i>Tersedia: <strong>${product.stock || 0} ${product.unit || 'pcs'}</strong>
                        </div>
                    </td>
                    <td class="text-end small fw-semibold text-muted">
                        ${formatRp(unitPrice)}
                    </td>
                    <td>
                        <div class="input-group input-group-sm justify-content-center">
                            <button type="button" class="btn btn-outline-secondary px-2" onclick="updateCartQty(${index}, -1)">-</button>
                            <input type="number" class="form-control text-center px-1" name="items[${index}][quantity]"
                                   value="${item.qty}" min="1" max="${product.stock || 1}" required
                                   style="max-width: 55px;" onchange="onManualQtyInput(${index}, this)">
                            <button type="button" class="btn btn-outline-secondary px-2" onclick="updateCartQty(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-dark fs-6" id="row-subtotal-${index}">
                        ${formatRp(subtotal)}
                    </td>
                    <td class="text-center pe-3">
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeCartItem(${index})" title="Hapus Item">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        } else {
            cart.forEach((item, index) => {
                const product = productsData.find(p => p.id === item.productId) || {};
                const unitPrice = parseFloat(product.selling_price) || 0;
                const subtotal = item.qty * unitPrice;
                const label = document.getElementById(`row-subtotal-${index}`);
                if (label) label.textContent = formatRp(subtotal);
            });
        }

        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        let totalItems = 0;

        cart.forEach(item => {
            const product = productsData.find(p => p.id === item.productId);
            if (product) {
                subtotal += (item.qty * parseFloat(product.selling_price));
                totalItems += item.qty;
            }
        });

        const discountInput = document.getElementById('discount');
        let discount = parseFloat(discountInput.value) || 0;
        if (discount < 0) {
            discount = 0;
            discountInput.value = 0;
        }
        if (discount > subtotal && subtotal > 0) {
            discount = subtotal;
            discountInput.value = discount;
        }

        rawGrandTotal = Math.max(0, subtotal - discount);

        document.getElementById('subtotalDisplay').textContent = formatRp(subtotal);
        document.getElementById('grandTotalDisplay').textContent = formatRp(rawGrandTotal);
        document.getElementById('itemsCountBadge').textContent = `${cart.length} jenis (${totalItems} unit barang)`;

        calculateChange();
    }

    function toggleCashInput() {
        const method = document.getElementById('payment_method').value;
        const cashArea = document.getElementById('cashCalculationArea');
        if (method === 'Tunai') {
            cashArea.style.display = 'block';
        } else {
            cashArea.style.display = 'none';
        }
    }

    function calculateChange() {
        const cashInput = document.getElementById('cash_received');
        const changeDisplay = document.getElementById('cashChangeDisplay');
        const changeBox = document.getElementById('changeBox');

        if (!cashInput.value) {
            changeDisplay.textContent = 'Rp 0';
            changeBox.style.backgroundColor = '#f8fafc';
            changeBox.style.borderColor = '#cbd5e1';
            return;
        }

        const received = parseFloat(cashInput.value) || 0;
        const diff = received - rawGrandTotal;

        if (diff >= 0) {
            changeDisplay.textContent = formatRp(diff);
            changeDisplay.className = 'fw-bold fs-6 text-success';
            changeBox.style.backgroundColor = '#ecfdf5';
            changeBox.style.borderColor = '#22c55e';
        } else {
            changeDisplay.textContent = 'Kurang ' + formatRp(Math.abs(diff));
            changeDisplay.className = 'fw-bold fs-6 text-danger';
            changeBox.style.backgroundColor = '#fef2f2';
            changeBox.style.borderColor = '#ef4444';
        }
    }

    function setExactCash() {
        document.getElementById('cash_received').value = rawGrandTotal;
        calculateChange();
    }

    function addCashShortcut(amount) {
        const cashInput = document.getElementById('cash_received');
        const current = parseFloat(cashInput.value) || 0;
        cashInput.value = current + amount;
        calculateChange();
    }

    document.getElementById('saleForm').addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Keranjang Kosong',
                text: 'Pilih minimal satu produk untuk dicatat penjualannya.',
                confirmButtonColor: '#22c55e'
            });
            return false;
        }

        const paymentMethod = document.getElementById('payment_method').value;
        const cashInput = document.getElementById('cash_received');
        if (paymentMethod === 'Tunai' && cashInput.value) {
            const received = parseFloat(cashInput.value) || 0;
            if (received < rawGrandTotal) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Uang Diterima Kurang',
                    text: `Total belanja adalah ${formatRp(rawGrandTotal)}, sedangkan uang yang dimasukkan hanya ${formatRp(received)}.`,
                    confirmButtonColor: '#22c55e'
                });
                cashInput.focus();
                return false;
            }
        }
    });
</script>
@endpush
@endsection
