<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes - IMAGEN UNIK</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .page-wrapper { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem 3rem; }

        .list-card { background: white; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); overflow: hidden; }

        .list-card-header {
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
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

        .clients-table { width: 100%; border-collapse: collapse; }
        .clients-table thead th {
            padding: 0.9rem 1.25rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5f6368;
            background: #f8f9fa;
            border-bottom: 2px solid #e8eaed;
        }
        .clients-table tbody tr { transition: background 0.15s; border-bottom: 1px solid #f0f0f0; }
        .clients-table tbody tr:hover { background: #f0f4ff; }
        .clients-table tbody td { padding: 0.9rem 1.25rem; font-size: 0.92rem; color: #333; }
        .clients-table tbody td.actions { display: flex; gap: 0.5rem; align-items: center; }

        .badge-id {
            display: inline-block;
            background: #e8f0fe;
            color: #1a73e8;
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .btn-icon {
            padding: 0.4rem 0.85rem;
            font-size: 0.82rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.2s;
        }
        .btn-icon.edit  { background: #e8f0fe; color: #1a73e8; }
        .btn-icon.edit:hover  { background: #1a73e8; color: white; }
        .btn-icon.del   { background: #fce8e6; color: #ea4335; }
        .btn-icon.del:hover   { background: #ea4335; color: white; }

        .empty-state { text-align: center; padding: 3rem; color: #aaa; }
        .empty-state span { font-size: 3rem; display: block; margin-bottom: 0.5rem; }

        /* Modal de edición */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; z-index: 2000; }
        .modal-box { background: white; border-radius: 16px; width: 90%; max-width: 700px; box-shadow: 0 24px 60px rgba(0,0,0,0.2); overflow: hidden; display: flex; flex-direction: column; }
        .modal-box-header { background: linear-gradient(135deg, #1a73e8, #0d47a1); color: white; padding: 1.25rem 1.75rem; display: flex; justify-content: space-between; align-items: center; }
        .modal-box-header h3 { margin: 0; font-size: 1.1rem; }
        .modal-body { padding: 1.75rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; max-height: 65vh; overflow-y: auto; }
        .modal-body .full { grid-column: 1 / -1; }
        .btn-doc { display: inline-flex; align-items: center; gap: 0.3rem; background: #e6f4ea; color: #137333; padding: 4px 10px; border-radius: 6px; text-decoration: none; font-size: 0.82rem; font-weight: 600; transition: all 0.2s; }
        .btn-doc:hover { background: #137333; color: white; }
        .mfield label { display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #5f6368; margin-bottom: 0.4rem; }
        .mfield input { width: 100%; padding: 0.6rem 0.9rem; border: 1.5px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 0.92rem; transition: border-color 0.2s; }
        .mfield input:focus { outline: none; border-color: #1a73e8; }
        .modal-footer { padding: 1rem 1.75rem; border-top: 1px solid #f0f0f0; background: #fafafa; display: flex; justify-content: flex-end; gap: 0.75rem; }

        /* Toast */
        #toast { position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem; border-radius: 10px; font-weight: 600; font-size: 0.95rem; color: white; box-shadow: 0 8px 24px rgba(0,0,0,0.2); display: none; z-index: 9999; }
        #toast.success { background: #34a853; }
        #toast.error   { background: #ea4335; }

        /* Confirm dialog */
        .confirm-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; z-index: 3000; }
        .confirm-box { background: white; border-radius: 14px; padding: 2rem; max-width: 380px; width: 90%; text-align: center; box-shadow: 0 24px 60px rgba(0,0,0,0.2); }
        .confirm-box span { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
        .confirm-box h3 { margin: 0 0 0.5rem; font-size: 1.1rem; }
        .confirm-box p  { color: #5f6368; margin: 0 0 1.5rem; font-size: 0.92rem; }
        .confirm-box .actions { display: flex; gap: 0.75rem; justify-content: center; }
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
            <a href="clientes.php" class="btn btn-primary">+ Nuevo Cliente</a>
            <a href="index.php" class="btn btn-secondary">Volver al POS</a>
        </div>
    </header>

    <div class="page-wrapper">
        <div class="list-card">
            <div class="list-card-header">
                <h2>👥 Listado de Clientes</h2>
                <span id="total-badge" style="background:rgba(255,255,255,0.2);padding:4px 14px;border-radius:20px;font-size:0.85rem;font-weight:600;">Cargando...</span>
            </div>

            <div class="search-bar">
                <input type="text" id="search-input" placeholder="🔍 Buscar por nombre, apellidos o teléfono..." oninput="filterClients()">
                <span id="count-label"></span>
            </div>

            <table class="clients-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>RFC</th>
                        <th>Documento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="clients-tbody"></tbody>
            </table>
            <div id="empty-state" class="empty-state" style="display:none;">
                <span>🔍</span>
                <p>No se encontraron clientes con ese criterio.</p>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal-box">
            <div class="modal-box-header">
                <h3>✏️ Editar Cliente</h3>
                <button class="btn-icon" onclick="closeEdit()" style="background:rgba(255,255,255,0.2);color:white;font-size:1rem;">✕</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-id">
                <div class="mfield"><label>Nombre *</label><input type="text" id="edit-nombre" required></div>
                <div class="mfield"><label>Apellidos *</label><input type="text" id="edit-apellidos" required></div>
                <div class="mfield"><label>Teléfono</label><input type="text" id="edit-telefono"></div>
                <div class="mfield"><label>Correo Electrónico</label><input type="email" id="edit-correo"></div>
                <div class="mfield"><label>RFC</label><input type="text" id="edit-rfc"></div>
                <div class="mfield"><label>CURP</label><input type="text" id="edit-curp"></div>
                <div class="mfield full"><label>Razón Social</label><input type="text" id="edit-razon"></div>
                <div class="mfield"><label>Calle</label><input type="text" id="edit-calle"></div>
                <div class="mfield"><label>Número</label><input type="text" id="edit-numero"></div>
                <div class="mfield"><label>Colonia</label><input type="text" id="edit-colonia"></div>
                <div class="mfield"><label>Código Postal</label><input type="text" id="edit-cp"></div>
                <div class="mfield full">
                    <label>Documento Adjunto (Opcional)</label>
                    <div id="edit-doc-status" style="margin-bottom: 0.5rem; font-size: 0.88rem; display: flex; align-items: center; gap: 0.5rem;"></div>
                    <input type="file" id="edit-documento" style="padding: 0.4rem 0;">
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
            <h3>¿Eliminar cliente?</h3>
            <p id="confirm-text">Esta acción no se puede deshacer.</p>
            <div class="actions">
                <button class="btn btn-secondary" onclick="closeConfirm()">Cancelar</button>
                <button class="btn btn-danger" id="confirm-ok-btn">Eliminar</button>
            </div>
        </div>
    </div>

    <div id="toast"></div>

    <script>
        let allClients = [];
        let deleteTarget = null;

        function showToast(msg, type = 'success') {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.className = type;
            t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 3500);
        }

        function loadClients() {
            fetch('api/get_clients.php')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        allClients = data.data;
                        renderTable(allClients);
                    }
                });
        }

        function renderTable(clients) {
            const tbody = document.getElementById('clients-tbody');
            const empty = document.getElementById('empty-state');
            const badge = document.getElementById('total-badge');
            const count = document.getElementById('count-label');

            tbody.innerHTML = '';
            badge.textContent = `${clients.length} cliente${clients.length !== 1 ? 's' : ''}`;
            count.textContent = clients.length > 0 ? `Mostrando ${clients.length} resultado${clients.length !== 1 ? 's' : ''}` : '';

            if (clients.length === 0) { empty.style.display = 'block'; return; }
            empty.style.display = 'none';

            clients.forEach(c => {
                const tr = document.createElement('tr');
                const docLink = c.documento 
                    ? `<a href="${c.documento}" target="_blank" class="btn-doc" title="Ver Documento">📄 Ver Doc</a>` 
                    : '<span style="color:#bbb">—</span>';
                tr.innerHTML = `
                    <td><span class="badge-id">${c.id}</span></td>
                    <td><strong>${c.nombre}</strong> ${c.apellidos}</td>
                    <td>${c.telefono || '<span style="color:#bbb">—</span>'}</td>
                    <td>${c.correo_electronico || '<span style="color:#bbb">—</span>'}</td>
                    <td>${c.rfc || '<span style="color:#bbb">—</span>'}</td>
                    <td>${docLink}</td>
                    <td class="actions">
                        <button class="btn-icon edit" onclick='openEdit(${JSON.stringify(c)})'>✏️ Editar</button>
                        <button class="btn-icon del" onclick='openConfirm(${c.id}, "${c.nombre} ${c.apellidos}")'>🗑️ Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function filterClients() {
            const q = document.getElementById('search-input').value.toLowerCase();
            const filtered = allClients.filter(c =>
                (c.nombre + ' ' + c.apellidos).toLowerCase().includes(q) ||
                (c.telefono || '').toLowerCase().includes(q)
            );
            renderTable(filtered);
        }

        // ---- EDIT ----
        function openEdit(c) {
            document.getElementById('edit-id').value       = c.id;
            document.getElementById('edit-nombre').value   = c.nombre || '';
            document.getElementById('edit-apellidos').value = c.apellidos || '';
            document.getElementById('edit-telefono').value = c.telefono || '';
            document.getElementById('edit-correo').value   = c.correo_electronico || '';
            document.getElementById('edit-rfc').value      = c.rfc || '';
            document.getElementById('edit-curp').value     = c.curp || '';
            document.getElementById('edit-razon').value    = c.razon_social || '';
            document.getElementById('edit-calle').value    = c.calle || '';
            document.getElementById('edit-numero').value   = c.numero_casa || '';
            document.getElementById('edit-colonia').value  = c.colonia || '';
            document.getElementById('edit-cp').value       = c.codigo_postal || '';
            
            const statusEl = document.getElementById('edit-doc-status');
            const fileInput = document.getElementById('edit-documento');
            fileInput.value = '';
            if (c.documento) {
                statusEl.innerHTML = `<span>Actualmente:</span> <a href="${c.documento}" target="_blank" style="color: #1a73e8; font-weight: 600; text-decoration: underline;">📄 Ver Documento</a>`;
            } else {
                statusEl.innerHTML = `<span style="color: #bbb;">Sin documento adjunto</span>`;
            }

            document.getElementById('edit-modal').style.display = 'flex';
        }
        function closeEdit() { document.getElementById('edit-modal').style.display = 'none'; }

        function saveEdit() {
            const formData = new FormData();
            formData.append('id', document.getElementById('edit-id').value);
            formData.append('nombre', document.getElementById('edit-nombre').value);
            formData.append('apellidos', document.getElementById('edit-apellidos').value);
            formData.append('telefono', document.getElementById('edit-telefono').value);
            formData.append('correo_electronico', document.getElementById('edit-correo').value);
            formData.append('rfc', document.getElementById('edit-rfc').value);
            formData.append('curp', document.getElementById('edit-curp').value);
            formData.append('razon_social', document.getElementById('edit-razon').value);
            formData.append('calle', document.getElementById('edit-calle').value);
            formData.append('numero_casa', document.getElementById('edit-numero').value);
            formData.append('colonia', document.getElementById('edit-colonia').value);
            formData.append('codigo_postal', document.getElementById('edit-cp').value);
            
            const fileInput = document.getElementById('edit-documento');
            if (fileInput.files.length > 0) {
                formData.append('documento', fileInput.files[0]);
            }

            fetch('api/update_client.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    closeEdit();
                    showToast('✅ ' + res.message);
                    loadClients();
                } else {
                    showToast('❌ ' + res.message, 'error');
                }
            })
            .catch(() => showToast('❌ Error de conexión.', 'error'));
        }

        // ---- DELETE ----
        function openConfirm(id, name) {
            deleteTarget = id;
            document.getElementById('confirm-text').textContent = `Se eliminará a "${name}". Esta acción no se puede deshacer.`;
            document.getElementById('confirm-modal').style.display = 'flex';
        }
        function closeConfirm() { document.getElementById('confirm-modal').style.display = 'none'; deleteTarget = null; }

        document.getElementById('confirm-ok-btn').addEventListener('click', () => {
            if (!deleteTarget) return;
            fetch('api/delete_client.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: deleteTarget })
            })
            .then(r => r.json())
            .then(res => {
                closeConfirm();
                if (res.success) {
                    showToast('🗑️ ' + res.message);
                    loadClients();
                } else {
                    showToast('❌ ' + res.message, 'error');
                }
            });
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') { closeEdit(); closeConfirm(); }
        });

        loadClients();
    </script>
</body>
</html>
