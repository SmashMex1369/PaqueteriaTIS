<?php
$xmlUrl = 'http://localhost:5000/api/clientes/obtenerTodos';
$xmlContent = file_get_contents($xmlUrl);
$xml = simplexml_load_string($xmlContent);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
</head>
<body>
    <h1>Buscar Clientes</h1>
    <input type="text" id="busqueda" placeholder="ID Cliente">
    <div id="resultado"></div>

    <h2 id="tituloTabla">Todos los Clientes</h2>
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
                        <button onclick="mostrarFormularioDireccion('<?php echo $cliente->IDCliente; ?>')">Cambiar dirección</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="formDireccionModal" style="display:none; position:fixed; top:20%; left:50%; transform:translate(-50%, 0); background:#fff; border:1px solid #ccc; padding:20px; z-index:1000;">
        <h3>Actualizar dirección</h3>
        <form id="formDireccion" onsubmit="enviarDireccion(event)">
            <input type="hidden" id="formIdCliente">
            <label>C.P.: <input type="text" id="formCP" required></label><br>
            <label>Colonia: <input type="text" id="formColonia" required></label><br>
            <label>Calle: <input type="text" id="formCalle" required></label><br>
            <label>Numero: <input type="text" id="formNumero" required></label><br>
            <button type="submit">Actualizar</button>
            <button type="button" onclick="cerrarFormularioDireccion()">Cancelar</button>
        </form>
    </div>

    <script>
    const busqueda = document.getElementById('busqueda');
    const tituloTabla = document.getElementById('tituloTabla');
    const tablaClientes = document.getElementById('tablaClientes');

    function mostrarFormularioDireccion(idCliente) {
        document.getElementById('formIdCliente').value = idCliente;
        document.getElementById('formDireccionModal').style.display = 'block';
    }
    function cerrarFormularioDireccion() {
        document.getElementById('formDireccionModal').style.display = 'none';
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
            cerrarFormularioDireccion();
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
                        <button onclick="mostrarFormularioDireccion('${cliente.getElementsByTagName('IDCliente')[0]?.textContent || ''}')">Cambiar dirección</button>
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
                                <button onclick="mostrarFormularioDireccion('${cliente.getElementsByTagName('IDCliente')[0]?.textContent || ''}')">Cambiar dirección</button>
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
