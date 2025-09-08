<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<!-- Tambahkan Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h2 class="text-center mb-4">Ringkasan Pesanan</h2>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="table-responsive mb-4">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_items as $item): ?>
                        <tr>
                            <td><?= esc($item['nama_item']) ?></td>
                            <td class="text-end"><?= $item['quantity'] ?></td>
                            <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td class="text-end">Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Grand Total</td>
                            <td class="text-end">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <hr class="border-secondary">

            <h3 class="text-center my-4">Data Pelanggan</h3>
            <form action="<?= base_url('konsumen/proses-checkout') ?>" method="post" id="checkoutForm">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="customer_id" class="form-label">Pilih Pelanggan (Opsional)</label>
                    <div class="input-group">
                        <select class="form-select" id="customer_id" name="customer_name">
                            <option value="">-- Jalan di Tempat (Umum) --</option>
                            <?php foreach($customers as $customer): ?>
                                <option value="<?= esc($customer['name']) ?>" data-phone="<?= esc($customer['phone']) ?>">
                                    <?= esc($customer['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                            <i class="bi bi-plus-lg"></i> Pelanggan Baru
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="customer_phone" class="form-label">No. Telepon (Opsional)</label>
                    <input type="text" class="form-control" name="customer_phone" id="customer_phone" placeholder="Akan terisi otomatis jika pelanggan dipilih">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Bayar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true" data-bs-theme="dark">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addCustomerModalLabel">Tambah Pelanggan Baru</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addCustomerForm">
            <div id="modal-errors" class="alert alert-danger" style="display:none;"></div>
            <div class="mb-3">
                <label for="modal_customer_name" class="form-label">Nama Pelanggan</label>
                <input type="text" class="form-control" id="modal_customer_name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="modal_customer_phone" class="form-label">No. Telepon (Opsional)</label>
                <input type="text" class="form-control" id="modal_customer_phone" name="phone">
            </div>
            <div class="mb-3">
                <label for="modal_customer_address" class="form-label">Alamat (Opsional)</label>
                <textarea class="form-control" id="modal_customer_address" name="address" rows="2"></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="saveCustomerBtn">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Tambahkan JQuery dan Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('#customer_id').select2({
        theme: 'bootstrap-5'
    });

    // Isi nomor telepon otomatis saat pelanggan dipilih
    $('#customer_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const phone = selectedOption.data('phone');
        $('#customer_phone').val(phone || '');
    });

    // AJAX untuk simpan pelanggan baru
    $('#saveCustomerBtn').on('click', function() {
        const form = $('#addCustomerForm');
        const errorsDiv = $('#modal-errors');
        errorsDiv.hide().html('');

        $.ajax({
            url: "<?= site_url('konsumen/ajax/add-customer') ?>",
            method: "POST",
            data: form.serialize() + '&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const customer = response.customer;
                    // Tambahkan opsi baru ke Select2
                    const newOption = new Option(customer.name, customer.name, true, true);
                    $(newOption).data('phone', customer.phone);
                    $('#customer_id').append(newOption).trigger('change');
                    
                    // Tutup modal
                    $('#addCustomerModal').modal('hide');
                    form[0].reset();
                } else {
                    let errorHtml = '<ul>';
                    $.each(response.errors, function(key, value) {
                        errorHtml += '<li>' + value + '</li>';
                    });
                    errorHtml += '</ul>';
                    errorsDiv.html(errorHtml).show();
                }
            },
            error: function() {
                errorsDiv.html('Terjadi kesalahan. Silakan coba lagi.').show();
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

