<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <!-- Header -->
    <header class="pos-header glass">
        <div class="logo-container">
            <img src="Logo.jpeg" alt="Imagen Unik Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
        </div>
        <div class="header-info">
            <h1>PUNTO DE VENTA IMAGEN UNIK</h1>
            <div id="clock">Cargando fecha...</div>
            <div>LUGAR: Reynosa, Tamaulipas</div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="pos-container">
        
        <!-- Left Panel: Client & Table -->
        <div class="main-panel">
            <div class="client-section glass">
                <div class="input-group">
                    <label>CLIENTE:</label>
                    <!-- Podría ser dinámico desde BD, se pone default para la vista -->
                    <input type="text" class="input-control" value="Público General" readonly>
                </div>
                <div class="input-group" style="max-width: 100px;">
                    <label>ID</label>
                    <input type="text" class="input-control center" value="1" readonly>
                </div>
            </div>

            <div class="table-container glass">
                <table id="pos-table">
                    <thead>
                        <tr>
                            <th class="center">Líneas</th>
                            <th>Producto</th>
                            <th class="center">Cantidad</th>
                            <th class="right">Costo Unitario</th>
                            <th class="center">Desc %</th>
                            <th class="right">Desc (MXN)</th>
                            <th class="right">Total Línea</th>
                            <th class="center">X</th>
                        </tr>
                    </thead>
                    <tbody id="pos-tbody">
                        <!-- JS Dynamically adds rows here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Panel: Actions & Totals -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <div class="actions-panel glass">
                <a href="productos.php" class="btn btn-primary">REGISTRAR PRODUCTO</a>
                <a href="clientes.php" class="btn btn-secondary">REGISTRAR CLIENTE</a>
                <button class="btn btn-secondary">CORTE DE CAJA</button>
                <a href="cotizaciones.php" class="btn btn-secondary">COTIZACIONES</a>
                <button class="btn btn-secondary">HISTORIALES</button>
            </div>

            <!-- Totals Panel -->
            <div class="totals-panel glass">
                <div class="total-row">
                    <span>SUBTOTAL (Sin IVA)</span>
                    <span id="lbl-subtotal">$0.00</span>
                </div>
                <div class="total-row">
                    <span>DESCUENTO TOTAL</span>
                    <span id="lbl-desc-total">$0.00</span>
                </div>
                <div class="total-row">
                    <span>SUBTOTAL DESP. DESCUENTO</span>
                    <span id="lbl-subtotal-desc">$0.00</span>
                </div>
                <div class="total-row">
                    <span>IVA (16%)</span>
                    <span id="lbl-iva">$0.00</span>
                </div>
                <hr>
                <div class="total-row grand-total">
                    <span>TOTAL A PAGAR</span>
                    <span id="lbl-total">$0.00</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Actions Panel -->
    <div class="bottom-actions" style="display: flex; gap: 1rem; padding: 0 1rem 1rem; max-width: 600px;">
        <button class="btn btn-primary" style="flex:1;" id="btn-cotizacion">COTIZACION</button>
        <button class="btn btn-danger" style="flex:1;" onclick="window.location.reload()">Cancelar</button>
        <button class="btn btn-success" style="flex:1;" id="btn-confirm">Confirmar</button>
    </div>

    <script src="js/pos.js"></script>
</body>
</html>
