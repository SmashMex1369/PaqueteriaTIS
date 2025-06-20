<?php
$xmlUrl = 'http://localhost:5000/api/clientes/obtenerTodos';
$xmlContent = file_get_contents($xmlUrl);
$xml = simplexml_load_string($xmlContent);
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="estilos.css">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
</head>
<body>
    <header>
        <nav>
            <ul class="nav-horizontal">
                <li><a href="Repartidores.php">Repartidores</a></li>
                <li><a href="Clientes.php">Clientes</a></li>
                <li><a href="Paquetes.php">Paquetes</a></li>
            </ul>
        </nav>
    </header>
    <h1>Buscar Clientes</h1>
    <input type="text" id="busqueda" placeholder="ID Cliente">
    <div id="resultado"></div>

    <h2 id="tituloTabla">Todos los Clientes</h2>
    <button id="botonCliente" onclick="mostrarFormulario('0',event)">Registrar Cliente</button>
    <table border="1">
        <thead>
            <tr>
                <th>ID Cliente</th>
                <th>Nombre</th>
                <th>RFC</th>
                <th>Telefono</th>
                <th>C.P.</th>
                <th>Colonia</th>
                <th>Calle</th>
                <th>Numero</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaClientes">
            <?php foreach ($xml->Cliente as $cliente): ?>
                <tr>
                    <td><?php echo $cliente->IDCliente; ?></td>
                    <td><?php echo $cliente->Nombre; ?></td>
                    <td><?php echo $cliente->RFC; ?></td>
                    <td><?php echo $cliente->Telefono; ?></td>
                    <td><?php echo $cliente->Direccion->{'C.P.'}; ?></td>
                    <td><?php echo $cliente->Direccion->Colonia; ?></td>
                    <td><?php echo $cliente->Direccion->Calle; ?></td>
                    <td><?php echo $cliente->Direccion->Numero; ?></td>
                    <td>
                        <button id="botonDireccion" onclick="mostrarFormulario('<?php echo $cliente->IDCliente; ?>',event)">Cambiar dirección</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="formDireccionModal">
        <h3>Actualizar dirección</h3>
        <form id="formDireccion" onsubmit="enviarDireccion(event)">
            <input type="hidden" id="formIdCliente">
            <label>C.P.: <input type="text" id="formCP" required></label><br>
            <label>Colonia: <input type="text" id="formColonia" required></label><br>
            <label>Calle: <input type="text" id="formCalle" required></label><br>
            <label>Numero: <input type="text" id="formNumero" required></label><br>
            <button type="submit">Actualizar</button>
            <button id="cerrarDireccion" type="button" onclick="cerrarFormulario(event)">Cancelar</button>
        </form>
    </div>

    <div id="formClienteModal">
        <h3>Registrar cliente</h3>
        <form id="formCliente" onsubmit="registrarCliente(event)">
            <label>Nombre: <input type="text" id="formNombre" required></label><br>
            <label>RFC: <input type="text" id="formRFC" required></label><br>
            <label>Teléfono: <input type="text" id="formTelefono" required></label><br>
            <fieldset>
                <legend>Dirección</legend>
                <label>C.P.: <input type="text" id="formCPCliente" required></label><br>
                <label>Colonia: <input type="text" id="formColoniaCliente" required></label><br>
                <label>Calle: <input type="text" id="formCalleCliente" required></label><br>
                <label>Numero: <input type="text" id="formNumeroCliente" required></label><br>
            </fieldset>
            <button type="submit">Registrar</button>
            <button type="button" id="cerrarCliente" onclick="cerrarFormulario(event)">Cancelar</button>
        </form>
    </div>

    <script>
    const busqueda = document.getElementById('busqueda');
    const tituloTabla = document.getElementById('tituloTabla');
    const tablaClientes = document.getElementById('tablaClientes');

    function mostrarFormulario(idCliente, event) {
        if (event.target.id === "botonDireccion") {
            document.getElementById('formIdCliente').value = idCliente;
            document.getElementById('formDireccionModal').style.display = 'block';
            document.getElementById('formClienteModal').style.display = 'none';
            document.getElementById('formCliente').reset();
        }else if (event&&event.target&&event.target.id === "botonCliente")  {
            document.getElementById('formClienteModal').style.display = 'block';
            document.getElementById('formDireccionModal').style.display = 'none';
            document.getElementById('formDireccion').reset();
        }
    }

    function cerrarFormulario(event) {
        if(event.target.id === "cerrarDireccion") {
            document.getElementById('formDireccionModal').style.display = 'none';
            document.getElementById('formDireccion').reset();
        }else if(event.target.id === "cerrarCliente") {
            document.getElementById('formClienteModal').style.display = 'none';
            document.getElementById('formCliente').reset();
        }
    }

    function registrarCliente(event) {
        event.preventDefault();

        const nombre = document.getElementById('formNombre').value;
        const rfc = document.getElementById('formRFC').value;
        const telefono = document.getElementById('formTelefono').value;
        const cp = document.getElementById('formCPCliente').value;
        const colonia = document.getElementById('formColoniaCliente').value;
        const calle = document.getElementById('formCalleCliente').value;
        const numero = document.getElementById('formNumeroCliente').value;

        const xmlCliente = `<Cliente>
            <Nombre>${nombre}</Nombre>
            <RFC>${rfc}</RFC>
            <Telefono>${telefono}</Telefono>
        </Cliente>`;

        fetch('http://localhost:5000/api/clientes/registrarCliente', {
            method: 'POST',
            headers: { 'Content-Type': 'application/xml' },
            body: xmlCliente
        })
        .then(response => response.text())
        .then(() => {
            return fetch('http://localhost:5000/api/clientes/obtenerTodosSinDestinos', {
                headers: { 'Accept': 'application/xml' }
            });
        })
        .then(response => response.text())
        .then(xmlText => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(xmlText, "application/xml");
            const clientes = xml.getElementsByTagName('Cliente');
            let idCliente = null;
            for (let i = clientes.length - 1; i >= 0; i--) {
                const c = clientes[i];
                if (
                    (c.getElementsByTagName('Nombre')[0]?.textContent || '') === nombre &&
                    (c.getElementsByTagName('RFC')[0]?.textContent || '') === rfc &&
                    (c.getElementsByTagName('Telefono')[0]?.textContent || '') === telefono
                ) {
                    idCliente = c.getElementsByTagName('IDCliente')[0]?.textContent || null;
                    break;
                }
            }
            if (!idCliente) {
                alert("No se pudo obtener el ID del cliente registrado.");
                return;
            }

            const xmlDestino = `<Destino>
                <IDCliente>${idCliente}</IDCliente>
                <Direccion>
                    <C.P.>${cp}</C.P.>
                    <Colonia>${colonia}</Colonia>
                    <Calle>${calle}</Calle>
                    <Numero>${numero}</Numero>
                </Direccion>
            </Destino>`;

            return fetch('http://localhost:5000/api/clientes/registrarDestino', {
                method: 'POST',
                headers: { 'Content-Type': 'application/xml' },
                body: xmlDestino
            });
        })
        .then(response => {
            if (response && response.ok) {
                alert("Cliente registrado exitosamente");
                cerrarFormulario({target: {id: "cerrarCliente"}});
                cargarTodosLosClientes();
            }
        })
        .catch(error => {
            alert("Error al registrar el cliente");
            cerrarFormulario({target: {id: "cerrarCliente"}});
        });
    }

    function enviarDireccion(event) {
        event.preventDefault();
        const idCliente = document.getElementById('formIdCliente').value;
        const cp = document.getElementById('formCP').value;
        const colonia = document.getElementById('formColonia').value;
        const calle = document.getElementById('formCalle').value;
        const numero = document.getElementById('formNumero').value;

        fetch(`http://localhost:5000/api/clientes/actualizarDestino/${idCliente}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/xml' },
            body: `<Destino>
                <Direccion>
                    <C.P.>${cp}</C.P.>
                    <Colonia>${colonia}</Colonia>
                    <Calle>${calle}</Calle>
                    <Numero>${numero}</Numero>
                </Direccion>
            </Destino>`
        })
        .then(response => response.text())
        .then(xmlText => {
            alert("Destino actualizado exitosamente");
            cerrarFormulario({target: {id: "cerrarDireccion"}});
            cargarTodosLosClientes();
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error al actualizar la dirección");
        });
    }



    function cargarTodosLosClientes() {
        tituloTabla.textContent = "Todos los Clientes";
        fetch('http://localhost:5000/api/clientes/obtenerTodos', {
            headers: { 'Accept': 'application/xml' }
        })
        .then(response => response.text())
        .then(xmlText => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(xmlText, "application/xml");
            const clientes = xml.getElementsByTagName('Cliente');
            let html = '';
            for (let cliente of clientes) {
                html += `<tr>
                    <td>${cliente.getElementsByTagName('IDCliente')[0]?.textContent || ''}</td>
                    <td>${cliente.getElementsByTagName('Nombre')[0]?.textContent || ''}</td>
                    <td>${cliente.getElementsByTagName('RFC')[0]?.textContent || ''}</td>
                    <td>${cliente.getElementsByTagName('Telefono')[0]?.textContent || ''}</td>
                    <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('C.P.')[0]?.textContent || ''}</td>
                    <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('Colonia')[0]?.textContent || ''}</td>
                    <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('Calle')[0]?.textContent || ''}</td>
                    <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('Numero')[0]?.textContent || ''}</td>
                    <td>
                        <button id="botonDireccion" onclick="mostrarFormulario('${cliente.getElementsByTagName('IDCliente')[0]?.textContent || ''}', event)">Cambiar dirección</button>
                    </td>
                </tr>`;
            }
            tablaClientes.innerHTML = html;
        });
    }

    busqueda.addEventListener('input', function() {
        const idCliente = busqueda.value.trim();
        if (idCliente.length > 0) {
            fetch(`http://localhost:5000/api/clientes/buscarCliente/${idCliente}`, {
                headers: { 'Accept': 'application/xml' }
            })
            .then(response => response.text())
            .then(xmlText => {
                const parser = new DOMParser();
                const xml = parser.parseFromString(xmlText, "application/xml");
                const clientes = xml.getElementsByTagName('Cliente');
                let html = '';
                if (clientes.length === 0) {
                    tituloTabla.textContent = "No hay coincidencia";
                    tablaClientes.innerHTML = '';
                } else {
                    tituloTabla.textContent = "Cliente encontrado";
                    for (let cliente of clientes) {
                        html += `<tr>
                            <td>${cliente.getElementsByTagName('IDCliente')[0]?.textContent || ''}</td>
                            <td>${cliente.getElementsByTagName('Nombre')[0]?.textContent || ''}</td>
                            <td>${cliente.getElementsByTagName('RFC')[0]?.textContent || ''}</td>
                            <td>${cliente.getElementsByTagName('Telefono')[0]?.textContent || ''}</td>
                            <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('C.P.')[0]?.textContent || ''}</td>
                            <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('Colonia')[0]?.textContent || ''}</td>
                            <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('Calle')[0]?.textContent || ''}</td>
                            <td>${cliente.getElementsByTagName('Direccion')[0]?.getElementsByTagName('Numero')[0]?.textContent || ''}</td>
                            <td>
                                <button id="botonDireccion" onclick="mostrarFormulario('${cliente.getElementsByTagName('IDCliente')[0]?.textContent || ''}',event)">Cambiar dirección</button>
                            </td>
                        </tr>`;
                    }
                    tablaClientes.innerHTML = html;
                }
            });
        } else {
            cargarTodosLosClientes();
        }
    });
    </script>
</body>
</html>
