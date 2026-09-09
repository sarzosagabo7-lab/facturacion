<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <!-- Alertas -->
    <?php if (session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= session()->getFlashdata('mensaje') ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0 mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white m-0">
            <i class="fa-solid fa-cart-shopping text-primary me-2"></i><?= esc($titulo) ?>
        </h2>
    </div>

    <form action="<?= base_url('compras/guardar') ?>" method="POST" id="formCompra">
        <?= csrf_field() ?>

        <div class="row g-4">
            <!-- Columna Izquierda -->
            <div class="col-lg-8">
                
                <!-- Datos de la Compra -->
                <div class="card p-4 mb-4 bg-dark text-white border-secondary rounded-3">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-file-invoice me-2"></i>Datos de la Compra</h5>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted">Proveedor</label>
                            <select class="form-select bg-secondary text-white border-0" id="id_proveedor" name="id_proveedor" required>
                                <option value="">-- Seleccionar Proveedor --</option>
                                <?php if(!empty($proveedores)): ?>
                                    <?php foreach($proveedores as $p): ?>
                                        <option value="<?= $p['id_proveedor'] ?>"><?= esc($p['nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Agregar Productos -->
                <div class="card p-4 mb-4 bg-dark text-white border-secondary rounded-3">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-box-open me-2"></i>Agregar Productos</h5>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label text-muted">Seleccionar Producto</label>
                            <select class="form-select bg-secondary text-white border-0" id="select_producto">
                                <option value="">-- Seleccionar Producto --</option>
                                <?php if(!empty($productos)): ?>
                                    <?php foreach($productos as $prod): ?>
                                        <option value="<?= $prod['id_producto'] ?>" data-precio="<?= $prod['precio_compra'] ?? $prod['precio'] ?? 0 ?>">
                                            <?= esc($prod['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted">Cantidad</label>
                            <input type="number" class="form-control bg-secondary text-white border-0" id="cantidad" value="1" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Precio Costo ($)</label>
                            <input type="number" step="0.01" class="form-control bg-secondary text-white border-0" id="precio_costo" placeholder="0.00">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100 py-2" id="btnAgregar">
                                <i class="fa-solid fa-plus"></i> Añadir
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Productos Agregados -->
                <div class="card p-3 bg-dark text-white border-secondary rounded-3">
                    <div class="table-responsive">
                        <table class="table table-dark align-middle mb-0" id="tablaDetalles">
                            <thead>
                                <tr>
                                    <th class="text-primary">Producto</th>
                                    <th class="text-center text-primary" style="width: 120px;">Cantidad</th>
                                    <th class="text-end text-primary" style="width: 140px;">Precio Costo</th>
                                    <th class="text-end text-primary" style="width: 140px;">Subtotal</th>
                                    <th class="text-center text-primary" style="width: 60px;"><i class="fa-solid fa-trash"></i></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDetalle">
                                <tr id="filaVacia">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-basket-shopping fa-2x mb-2 d-block"></i>
                                        No hay productos agregados a la compra
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-lg-4">
                <div class="card p-4 bg-dark text-white border-secondary rounded-3 sticky-top" style="top: 20px;">
                    <h5 class="text-primary mb-3"><i class="fa-solid fa-calculator me-2"></i>Resumen</h5>
                    
                    <div class="p-3 mb-4 rounded border border-secondary text-end" style="background-color: #1a1a1a;">
                        <span class="text-muted d-block text-uppercase small font-weight-bold">Total a Pagar</span>
                        <h2 class="text-success mb-0 fw-bold">$ <span id="lblTotal">0.00</span></h2>
                        <input type="hidden" name="total" id="txtTotal" value="0.00">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Fecha de Compra</label>
                        <input type="date" class="form-control bg-secondary text-white border-0" name="fecha" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg py-3 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Guardar Compra
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnAgregar = document.getElementById('btnAgregar');
    const selectProducto = document.getElementById('select_producto');
    const inputCantidad = document.getElementById('cantidad');
    const inputPrecio = document.getElementById('precio_costo');
    const tbody = document.getElementById('tbodyDetalle');
    const lblTotal = document.getElementById('lblTotal');
    const txtTotal = document.getElementById('txtTotal');

    selectProducto.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (option && option.dataset.precio) {
            inputPrecio.value = option.dataset.precio;
        }
    });

    btnAgregar.addEventListener('click', function () {
        const idProd = selectProducto.value;
        const nombre = selectProducto.options[selectProducto.selectedIndex]?.text;
        const cantidad = parseFloat(inputCantidad.value);
        const precio = parseFloat(inputPrecio.value);

        if (!idProd || isNaN(cantidad) || cantidad <= 0 || isNaN(precio) || precio <= 0) {
            alert('Por favor selecciona un producto, cantidad y precio válidos.');
            return;
        }

        const subtotal = cantidad * precio;

        const filaVacia = document.getElementById('filaVacia');
        if (filaVacia) filaVacia.remove();

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                ${nombre}
                <input type="hidden" name="producto_ids[]" value="${idProd}">
            </td>
            <td class="text-center">
                ${cantidad}
                <input type="hidden" name="cantidades[]" value="${cantidad}">
            </td>
            <td class="text-end">
                $${precio.toFixed(2)}
                <input type="hidden" name="precios[]" value="${precio}">
            </td>
            <td class="text-end subtotal-val">$${subtotal.toFixed(2)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        calcularTotal();

        selectProducto.value = '';
        inputCantidad.value = '1';
        inputPrecio.value = '';
    });

    tbody.addEventListener('click', function (e) {
        if (e.target.closest('.btn-eliminar')) {
            e.target.closest('tr').remove();
            if (tbody.children.length === 0) {
                tbody.innerHTML = `
                    <tr id="filaVacia">
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fa-solid fa-basket-shopping fa-2x mb-2 d-block"></i>
                            No hay productos agregados a la compra
                        </td>
                    </tr>
                `;
            }
            calcularTotal();
        }
    });

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-val').forEach(td => {
            total += parseFloat(td.textContent.replace('$', ''));
        });
        lblTotal.textContent = total.toFixed(2);
        txtTotal.value = total.toFixed(2);
    }
});
</script>

<?= $this->endSection() ?>