<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos - IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .page-wrapper { max-width: 1200px; margin: 0 auto; padding: 1.5rem 1rem 3rem; }

        .list-card { background: white; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); overflow: hidden; }

        .list-card-header {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .list-card-header h2 { margin: 0; font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; gap: 0.6rem; }

        .search-bar {
            display: flex;
            gap: 1rem;
            padding: 1.25rem 2rem;
            border-bottom: 1px solid #f0f0f0;
            background: #fafafa;
            align-items: center;
        }
        .search-bar input {
            flex: 1;
            padding: 0.65rem 1rem;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-bar input:focus { outline: none; border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,0.12); }
        .search-bar span { color: #5f6368; font-size: 0.9rem; white-space: nowrap; }

        .products-table { width: 100%; border-collapse: collapse; }
        .products-table thead th {
            padding: 0.9rem 1rem;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5f6368;
            background: #f8f9fa;
            border-bottom: 2px solid #e8eaed;
        }
        .products-table tbody tr { transition: background 0.15s; border-bottom: 1px solid #f0f0f0; }
        .products-table tbody tr:hover { background: #f0f4ff; }
        .products-table tbody td { padding: 0.85rem 1rem; font-size: 0.9rem; color: #333; }

        .badge-sku {
            display: inline-block;
            background: #e8f0fe;
            color: #1a73e8;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-stock {
            display: inline-block;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .badge-stock.ok  { background: #e6f4ea; color: #34a853; }
        .badge-stock.low { background: #fce8e6; color: #ea4335; }

        .btn-icon { padding: 0.4rem 0.85rem; font-size: 0.82rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-family: inherit; display: inline-flex; align-items: center; gap: 0.3rem; transition: all 0.2s; }
        .btn-icon.edit  { background: #e8f0fe; color: #1a73e8; }
        .btn-icon.edit:hover  { background: #1a73e8; color: white; }
        .btn-icon.del   { background: #fce8e6; color: #ea4335; }
        .btn-icon.del:hover   { background: #ea4335; color: white; }

        .td-actions { display: flex; gap: 0.5rem; align-items: center; }
        .empty-state { text-align: center; padding: 3rem; color: #aaa; }
        .empty-state span { font-size: 3rem; display: block; margin-bottom: 0.5rem; }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; z-index: 2000; }
        .modal-box { background: white; border-radius: 16px; width: 90%; max-width: 720px; box-shadow: 0 24px 60px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column; }
        .modal-box-header { background: linear-gradient(135deg, #1a73e8, #0d47a1); color: white; padding: 1.25rem 1.75rem; display: flex; justify-content: space-between; align-items: center; }
        .modal-box-header h3 { margin: 0; font-size: 1.1rem; }
        .modal-body { padding: 1.75rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; max-height: 65vh; overflow-y: auto; }
        .modal-body .full { grid-column: 1 / -1; }
        .modal-body .cols3 { grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; }
        .mfield label { display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #5f6368; margin-bottom: 0.4rem; }
        .mfield input { width: 100%; padding: 0.6rem 0.9rem; border: 1.5px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 0.92rem; transition: border-color 0.2s; }
        .mfield input:focus { outline: none; border-color: #1a73e8; }
        .mfield input.auto-calc { background: #f0f7ff; border-color: #aecbfa; font-weight: 600; color: #1a73e8; }
        .modal-footer { padding: 1rem 1.75rem; border-top: 1px solid #f0f0f0; background: #fafafa; display: flex; justify-content: flex-end; gap: 0.75rem; }

        /* Confirm */
        .confirm-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; z-index: 3000; }
        .confirm-box { background: white; border-radius: 14px; padding: 2rem; max-width: 380px; width: 90%; text-align: center; box-shadow: 0 24px 60px rgba(0,0,0,0.2); }
        .confirm-box span { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
        .confirm-box h3 { margin: 0 0 0.5rem; font-size: 1.1rem; }
        .confirm-box p  { color: #5f6368; margin: 0 0 1.5rem; font-size: 0.92rem; }
        .confirm-box .actions { display: flex; gap: 0.75rem; justify-content: center; }

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
            <a href="productos.php" class="btn btn-primary">+ Nuevo Producto</a>
            <a href="index.php" class="btn btn-secondary">Volver al POS</a>
        </div>
    </header>

    <div class="page-wrapper">
        <div class="list-card">
            <div class="list-card-header">
                <h2>📦 Listado de Productos</h2>
                <span id="total-badge" style="background:rgba(255,255,255,0.2);padding:4px 14px;border-radius:20px;font-size:0.85rem;font-weight:600;">Cargando...</span>
            </div>

            <div class="search-bar">
                <input type="text" id="search-input" placeholder="🔍 Buscar por descripción, SKU o categoría..." oninput="filterProducts()">
                <span id="count-label"></span>
            </div>

            <table class="products-table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Proveedor</th>
                        <th>Costo</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="products-tbody"></tbody>
            </table>
            <div id="empty-state" class="empty-state" style="display:none;">
                <span>🔍</span>
                <p>No se encontraron productos con ese criterio.</p>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal-box">
            <div class="modal-box-header">
                <h3>✏️ Editar Producto</h3>
                <button class="btn-icon" onclick="closeEdit()" style="background:rgba(255,255,255,0.2);color:white;font-size:1rem;">✕</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-id">
                <div class="mfield"><label>SKU *</label><input type="text" id="edit-sku" readonly style="background: #f1f3f4; color: #5f6368; cursor: not-allowed; font-weight: 600;"></div>
                <div class="mfield"><label>Código de Barras</label><input type="text" id="edit-barras"></div>
                <div class="mfield full"><label>Descripción *</label><input type="text" id="edit-descripcion"></div>
                <div class="mfield"><label>Categoría</label><input type="text" id="edit-categoria"></div>
                <div class="mfield"><label>Proveedor</label><input type="text" id="edit-proveedor"></div>
                <div class="cols3">
                    <div class="mfield"><label>Costo</label><input type="number" id="edit-costo" step="0.01" oninput="calcEditUtility()"></div>
                    <div class="mfield"><label>Precio Final *</label><input type="number" id="edit-precio" step="0.01" oninput="calcEditUtility()"></div>
                    <div class="mfield"><label>Utilidad ($)</label><input type="number" id="edit-utilidad" step="0.01" class="auto-calc" readonly></div>
                    <div class="mfield"><label>Stock</label><input type="number" id="edit-existencia" step="1"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeEdit()">Cancelar</button>
                <button class="btn btn-primary" onclick="saveEdit()">💾 Guardar Cambios</button>
            </div>
        </div>
    </div>

    <!-- Confirm Delete -->
    <div class="confirm-overlay" id="confirm-modal">
        <div class="confirm-box">
            <span>🗑️</span>
            <h3>¿Eliminar producto?</h3>
            <p id="confirm-text">Esta acción no se puede deshacer.</p>
            <div class="actions">
                <button class="btn btn-secondary" onclick="closeConfirm()">Cancelar</button>
                <button class="btn btn-danger" id="confirm-ok-btn">Eliminar</button>
            </div>
        </div>
    </div>

    <div id="toast"></div>

    <script>
        let allProducts = [];
        let deleteTarget = null;

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = type;
            t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 3500);
        }

        function loadProducts() {
            fetch('api/get_products.php')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        allProducts = data.data;
                        renderTable(allProducts);
                    }
                });
        }

        function renderTable(products) {
            const tbody = document.getElementById('products-tbody');
            const empty = document.getElementById('empty-state');
            const badge = document.getElementById('total-badge');
            const count = document.getElementById('count-label');

            tbody.innerHTML = '';
            badge.textContent = `${products.length} producto${products.length !== 1 ? 's' : ''}`;
            count.textContent = products.length > 0 ? `Mostrando ${products.length} resultado${products.length !== 1 ? 's' : ''}` : '';

            if (products.length === 0) { empty.style.display = 'block'; return; }
            empty.style.display = 'none';

            products.forEach(p => {
                const stock = parseInt(p.existencia) || 0;
                const stockClass = stock > 0 ? 'ok' : 'low';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="badge-sku">${p.sku}</span></td>
                    <td><strong>${p.descripcion}</strong></td>
                    <td>${p.categoria || '<span style="color:#bbb">—</span>'}</td>
                    <td>${p.proveedor || '<span style="color:#bbb">—</span>'}</td>
                    <td>$${parseFloat(p.costo).toFixed(2)}</td>
                    <td style="font-weight:600;color:#1a73e8;">$${parseFloat(p.precio).toFixed(2)}</td>
                    <td><span class="badge-stock ${stockClass}">${stock}</span></td>
                    <td class="td-actions">
                        <button class="btn-icon edit" onclick='openEdit(${JSON.stringify(p)})'>✏️ Editar</button>
                        <button class="btn-icon del" onclick='openConfirm(${p.id}, "${p.descripcion.replace(/"/g,"&quot;")}")'>🗑️ Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function filterProducts() {
            const q = document.getElementById('search-input').value.toLowerCase();
            const filtered = allProducts.filter(p =>
                (p.descripcion || '').toLowerCase().includes(q) ||
                (p.sku || '').toLowerCase().includes(q) ||
                (p.categoria || '').toLowerCase().includes(q)
            );
            renderTable(filtered);
        }

        // ---- EDIT ----
        function openEdit(p) {
            document.getElementById('edit-id').value          = p.id;
            document.getElementById('edit-sku').value         = p.sku || '';
            document.getElementById('edit-barras').value      = p.codigo_barras || '';
            document.getElementById('edit-descripcion').value = p.descripcion || '';
            document.getElementById('edit-categoria').value   = p.categoria || '';
            document.getElementById('edit-proveedor').value   = p.proveedor || '';
            document.getElementById('edit-costo').value       = parseFloat(p.costo).toFixed(2);
            document.getElementById('edit-utilidad').value    = parseFloat(p.utilidad).toFixed(2);
            document.getElementById('edit-precio').value      = parseFloat(p.precio).toFixed(2);
            document.getElementById('edit-existencia').value  = p.existencia || 0;
            document.getElementById('edit-modal').style.display = 'flex';
        }
        function closeEdit() { document.getElementById('edit-modal').style.display = 'none'; }

        function calcEditUtility() {
            const c = parseFloat(document.getElementById('edit-costo').value) || 0;
            const p = parseFloat(document.getElementById('edit-precio').value) || 0;
            document.getElementById('edit-utilidad').value = (p - c).toFixed(2);
        }

        function saveEdit() {
            const payload = {
                id:            document.getElementById('edit-id').value,
                sku:           document.getElementById('edit-sku').value,
                codigo_barras: document.getElementById('edit-barras').value,
                descripcion:   document.getElementById('edit-descripcion').value,
                categoria:     document.getElementById('edit-categoria').value,
                proveedor:     document.getElementById('edit-proveedor').value,
                costo:         document.getElementById('edit-costo').value,
                utilidad:      document.getElementById('edit-utilidad').value,
                precio:        document.getElementById('edit-precio').value,
                existencia:    document.getElementById('edit-existencia').value
            };
            fetch('api/update_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) { closeEdit(); showToast('✅ ' + res.message); loadProducts(); }
                else { showToast('❌ ' + res.message, 'error'); }
            });
        }

        // ---- DELETE ----
        function openConfirm(id, name) {
            deleteTarget = id;
            document.getElementById('confirm-text').textContent = `Se eliminará "${name}". Esta acción no se puede deshacer.`;
            document.getElementById('confirm-modal').style.display = 'flex';
        }
        function closeConfirm() { document.getElementById('confirm-modal').style.display = 'none'; deleteTarget = null; }

        document.getElementById('confirm-ok-btn').addEventListener('click', () => {
            if (!deleteTarget) return;
            fetch('api/delete_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: deleteTarget })
            })
            .then(r => r.json())
            .then(res => {
                closeConfirm();
                if (res.success) { showToast('🗑️ ' + res.message); loadProducts(); }
                else { showToast('❌ ' + res.message, 'error'); }
            });
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeEdit(); closeConfirm(); }
        });

        loadProducts();
    </script>
</body>
</html>
