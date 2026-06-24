<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Producto - IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body { background: linear-gradient(135deg, #f4f6f9 0%, #e0e6ed 100%); }

        .page-wrapper {
            max-width: 960px;
            margin: 0 auto;
            padding: 1.5rem 1rem 3rem;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .form-card-header {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white;
            padding: 2rem 2.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .form-card-header .icon { font-size: 2.5rem; line-height: 1; }
        .form-card-header h2 { margin: 0; font-size: 1.5rem; font-weight: 700; }
        .form-card-header p  { margin: 0.3rem 0 0; font-size: 0.9rem; opacity: 0.85; }

        .form-body { padding: 2rem 2.5rem; }

        .section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1a73e8;
            border-bottom: 2px solid #e8f0fe;
            padding-bottom: 0.5rem;
            margin: 0 0 1.5rem;
        }

        .field-group { display: grid; gap: 1.25rem; margin-bottom: 2rem; }
        .fg-2 { grid-template-columns: 1fr 1fr; }
        .fg-3 { grid-template-columns: 1fr 1fr 1fr; }
        .fg-4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
        .fg-full { grid-template-columns: 1fr; }

        .field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5f6368;
            margin-bottom: 0.5rem;
        }
        .field label .req { color: #ea4335; margin-left: 2px; }

        .field input {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            color: #202124;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .field input:focus {
            outline: none;
            border-color: #1a73e8;
            background: white;
            box-shadow: 0 0 0 3px rgba(26,115,232,0.12);
        }
        .field input::placeholder { color: #bbb; }

        /* Special highlight for auto-calculated price */
        .field input.auto-calc {
            background: #f0f7ff;
            border-color: #aecbfa;
            font-weight: 600;
            color: #1a73e8;
        }
        .field .hint {
            font-size: 0.73rem;
            color: #888;
            margin-top: 0.3rem;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1.5rem 2.5rem;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
        }

        /* Toast */
        #toast { position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem; border-radius: 10px; font-weight: 600; font-size: 0.95rem; color: white; box-shadow: 0 8px 24px rgba(0,0,0,0.2); display: none; z-index: 9999; }
        #toast.success { background: #34a853; }
        #toast.error   { background: #ea4335; }
    </style>
</head>
<body>
    <header class="pos-header glass">
        <div class="logo-container" style="display: flex; align-items: center; gap: 1rem;">
            <img src="Logo.jpeg" alt="IMAGEN UNIK Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
            <div>
                <h1 style="margin:0;font-size:1.4rem;font-weight:700;">PUNTO DE VENTA IMAGEN UNIK</h1>
                <p style="margin:0;font-size:0.85rem;color:#5f6368;">Reynosa, Tamaulipas</p>
            </div>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <a href="listado_productos.php" class="btn btn-secondary">📋 Listado de Productos</a>
            <a href="index.php" class="btn btn-secondary">Volver al POS</a>
        </div>
    </header>

    <div class="page-wrapper">
        <div class="form-card">
            <div class="form-card-header">
                <div class="icon">📦</div>
                <div>
                    <h2>Nuevo Producto</h2>
                    <p>Ingrese los detalles del producto para añadirlo al inventario.</p>
                </div>
            </div>

            <form id="productForm">
                <div class="form-body">

                    <!-- Identificación -->
                    <p class="section-title">Identificación del Producto</p>
                    <div class="field-group fg-2">
                        <div class="field">
                            <label>SKU <span class="req">*</span></label>
                            <input type="text" id="sku" name="sku" placeholder="Cargando..." readonly style="background: #f1f3f4; color: #5f6368; cursor: not-allowed; font-weight: 600;" required>
                            <div class="hint">Se genera automáticamente</div>
                        </div>
                        <div class="field">
                            <label>Código de Barras</label>
                            <input type="text" id="codigo_barras" name="codigo_barras" placeholder="Ej: 7501000000001">
                        </div>
                    </div>
                    <div class="field-group fg-full" style="margin-bottom: 2rem;">
                        <div class="field">
                            <label>Descripción <span class="req">*</span></label>
                            <input type="text" id="descripcion" name="descripcion" placeholder="Ej: Bolsa de papel kraft 25x35 cm" required>
                        </div>
                    </div>

                    <!-- Clasificación -->
                    <p class="section-title">Clasificación</p>
                    <div class="field-group fg-2">
                        <div class="field">
                            <label>Categoría</label>
                            <input type="text" id="categoria" name="categoria" placeholder="Ej: Bolsas, Impresos, Sellos…">
                        </div>
                        <div class="field">
                            <label>Proveedor</label>
                            <input type="text" id="proveedor" name="proveedor" placeholder="Ej: Bodega Central">
                        </div>
                    </div>

                    <!-- Precios -->
                    <p class="section-title">Precios e Inventario</p>
                    <div class="field-group fg-4" style="margin-bottom: 1.5rem;">
                        <div class="field">
                            <label>Costo</label>
                            <input type="number" id="costo" name="costo" step="0.01" value="0.00" placeholder="0.00">
                            <div class="hint">Precio de compra</div>
                        </div>
                        <div class="field">
                            <label>Precio Final <span class="req">*</span></label>
                            <input type="number" id="precio" name="precio" step="0.01" value="0.00" placeholder="0.00" required>
                            <div class="hint">Precio de venta</div>
                        </div>
                        <div class="field">
                            <label>Utilidad ($)</label>
                            <input type="number" id="utilidad" name="utilidad" step="0.01" value="0.00" class="auto-calc" readonly>
                            <div class="hint">Ganancia (Precio - Costo)</div>
                        </div>
                        <div class="field">
                            <label>Existencia (Stock)</label>
                            <input type="number" id="existencia" name="existencia" step="1" value="0">
                            <div class="hint">Unidades disponibles</div>
                        </div>
                    </div>

                    <!-- Medidas en Metros -->
                    <div class="field-group fg-full" style="margin-bottom: 1rem;">
                        <div class="field" style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="checkbox" id="venta_por_metros" name="venta_por_metros" style="width: auto; cursor: pointer; margin: 0;">
                            <label for="venta_por_metros" style="margin-bottom: 0; cursor: pointer; font-weight: 700; color: #1a73e8; text-transform: none; letter-spacing: normal;">¿La unidad de medida es en metros?</label>
                        </div>
                    </div>

                    <div id="metros-fields-container" class="field-group fg-3" style="display: none; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem;">
                        <div class="field">
                            <label>Unidad de Medida</label>
                            <input type="text" id="unidad_medida" value="Metros" readonly style="background: #e2e8f0; color: #475569; font-weight: 600; cursor: not-allowed;">
                            <div class="hint">Fijado en metros</div>
                        </div>
                        <div class="field">
                            <label>Costo por M²</label>
                            <input type="number" id="costo_m2" name="costo_m2" step="0.01" value="0.00" placeholder="0.00">
                            <div class="hint">Costo del metro cuadrado</div>
                        </div>
                        <div class="field">
                            <label>Precio Final por M²</label>
                            <input type="number" id="precio_m2" name="precio_m2" step="0.01" value="0.00" placeholder="0.00">
                            <div class="hint">Precio de venta por metro cuadrado</div>
                        </div>
                    </div>

                </div>

                <div class="form-footer">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <a href="listado_productos.php" class="btn btn-secondary">📋 Listado de Productos</a>
                    <button type="submit" class="btn btn-primary">💾 Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast"></div>

    <script>
        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = type;
            t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 3500);
        }

        const costInput  = document.getElementById('costo');
        const utilInput  = document.getElementById('utilidad');
        const priceInput = document.getElementById('precio');

        const ventaMetrosCheckbox = document.getElementById('venta_por_metros');
        const metrosFieldsContainer = document.getElementById('metros-fields-container');
        const costoM2Input = document.getElementById('costo_m2');
        const precioM2Input = document.getElementById('precio_m2');

        ventaMetrosCheckbox.addEventListener('change', function() {
            if (this.checked) {
                metrosFieldsContainer.style.display = 'grid';
                costoM2Input.value = costInput.value;
                precioM2Input.value = priceInput.value;
                costInput.readOnly = true;
                priceInput.readOnly = true;
                costInput.style.background = '#f1f3f4';
                priceInput.style.background = '#f1f3f4';
                costInput.style.cursor = 'not-allowed';
                priceInput.style.cursor = 'not-allowed';
            } else {
                metrosFieldsContainer.style.display = 'none';
                costInput.readOnly = false;
                priceInput.readOnly = false;
                costInput.style.background = '';
                priceInput.style.background = '';
                costInput.style.cursor = '';
                priceInput.style.cursor = '';
            }
            calcUtility();
        });

        costoM2Input.addEventListener('input', function() {
            if (ventaMetrosCheckbox.checked) {
                costInput.value = this.value;
                calcUtility();
            }
        });

        precioM2Input.addEventListener('input', function() {
            if (ventaMetrosCheckbox.checked) {
                priceInput.value = this.value;
                calcUtility();
            }
        });

        function fetchNextSku() {
            fetch('api/get_next_sku.php')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('sku').value = data.sku;
                    }
                })
                .catch(err => console.error("Error al obtener SKU:", err));
        }

        function calcUtility() {
            const c = parseFloat(costInput.value) || 0;
            const p = parseFloat(priceInput.value) || 0;
            utilInput.value = (p - c).toFixed(2);
        }

        costInput.addEventListener('input', calcUtility);
        priceInput.addEventListener('input', calcUtility);

        // Cargar SKU al iniciar
        document.addEventListener('DOMContentLoaded', fetchNextSku);

        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            fetch('api/save_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showToast('✅ ' + res.message, 'success');
                    this.reset();
                    ventaMetrosCheckbox.dispatchEvent(new Event('change'));
                    priceInput.value = '0.00';
                    utilInput.value = '0.00';
                    fetchNextSku();
                } else {
                    showToast('❌ Error: ' + res.message, 'error');
                }
            })
            .catch(() => showToast('❌ Error de conexión.', 'error'));
        });
    </script>
</body>
</html>
