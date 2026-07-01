<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cliente - IMAGEN UNIK</title>
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
        .form-card-header .icon {
            font-size: 2.5rem;
            line-height: 1;
        }
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

        .field-group {
            display: grid;
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .fg-2 { grid-template-columns: 1fr 1fr; }
        .fg-3 { grid-template-columns: 1fr 1fr 1fr; }
        .fg-4 { grid-template-columns: 2fr 1fr 1fr 1fr; }

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

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding: 1.5rem 2.5rem;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
        }

        /* Notification toast */
        #toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            color: white;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            display: none;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }
        #toast.success { background: #34a853; }
        #toast.error   { background: #ea4335; }
        @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>
    <header class="pos-header glass">
        <div class="logo-container" style="display: flex; align-items: center; gap: 1rem;">
            <img src="Logo.jpeg" alt="IMAGEN UNIK Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2NjYyIvPjwvc3ZnPg=='">
            <div>
                <h1 style="margin: 0; font-size: 1.4rem; font-weight: 700;">PUNTO DE VENTA IMAGEN UNIK</h1>
                <p style="margin: 0; font-size: 0.85rem; color: #5f6368;">Reynosa, Tamaulipas</p>
            </div>
        </div>
        <div>
            <a href="index.php" class="btn btn-secondary">Volver al POS</a>
        </div>
    </header>

    <div class="page-wrapper">
        <div class="form-card">
            <div class="form-card-header">
                <div class="icon">👤</div>
                <div>
                    <h2>Nuevo Cliente</h2>
                    <p>Ingrese los datos del cliente para su facturación y contacto.</p>
                </div>
            </div>

            <form id="clientForm">
                <div class="form-body">

                    <!-- Datos Personales -->
                    <p class="section-title">Datos Personales</p>
                    <div class="field-group fg-2">
                        <div class="field">
                            <label>Nombre <span class="req">*</span></label>
                            <input type="text" id="nombre" name="nombre" placeholder="Ej: Juan Carlos" required>
                        </div>
                        <div class="field">
                            <label>Apellidos <span class="req">*</span></label>
                            <input type="text" id="apellidos" name="apellidos" placeholder="Ej: García Pérez" required>
                        </div>
                        <div class="field">
                            <label>Teléfono</label>
                            <input type="text" id="telefono" name="telefono" placeholder="Ej: 899 123 4567">
                        </div>
                        <div class="field">
                            <label>Correo Electrónico</label>
                            <input type="email" id="correo_electronico" name="correo_electronico" placeholder="Ej: correo@gmail.com">
                        </div>
                    </div>

                    <!-- Datos Fiscales -->
                    <p class="section-title">Datos Fiscales</p>
                    <div class="field-group fg-3">
                        <div class="field">
                            <label>RFC</label>
                            <input type="text" id="rfc" name="rfc" placeholder="Ej: GAPE900101ABC">
                        </div>
                        <div class="field">
                            <label>CURP</label>
                            <input type="text" id="curp" name="curp" placeholder="Ej: GAPE900101HTNRCR07">
                        </div>
                        <div class="field">
                            <label>Razón Social</label>
                            <input type="text" id="razon_social" name="razon_social" placeholder="Ej: GARCÍA PÉREZ SA DE CV">
                        </div>
                    </div>

                    <!-- Dirección -->
                    <p class="section-title">Dirección</p>
                    <div class="field-group fg-4">
                        <div class="field">
                            <label>Calle</label>
                            <input type="text" id="calle" name="calle" placeholder="Nombre de la calle">
                        </div>
                        <div class="field">
                            <label>Número</label>
                            <input type="text" id="numero_casa" name="numero_casa" placeholder="Ej: 123">
                        </div>
                        <div class="field">
                            <label>Colonia</label>
                            <input type="text" id="colonia" name="colonia" placeholder="Nombre de colonia">
                        </div>
                        <div class="field">
                            <label>Código Postal</label>
                            <input type="text" id="codigo_postal" name="codigo_postal" placeholder="Ej: 88500">
                        </div>
                    </div>

                    <!-- Documentación -->
                    <p class="section-title">Documentación</p>
                    <div class="field-group fg-2">
                        <div class="field">
                            <label>Adjuntar Documento (Opcional)</label>
                            <input type="file" id="documento" name="documento" style="padding: 0.4rem 0; border: none; background: transparent; box-shadow: none;">
                        </div>
                    </div>

                </div>

                <div class="form-footer">
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                    <a href="listado_clientes.php" class="btn btn-secondary">📋 Listado de Clientes</a>
                    <button type="submit" class="btn btn-primary">💾 Guardar Cliente</button>
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

        document.getElementById('clientForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('api/save_client.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    showToast('✅ ' + res.message, 'success');
                    this.reset();
                } else {
                    showToast('❌ Error: ' + res.message, 'error');
                }
            })
            .catch(() => showToast('❌ Error de conexión.', 'error'));
        });
    </script>
</body>
</html>
