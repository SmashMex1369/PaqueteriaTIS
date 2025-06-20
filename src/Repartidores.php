<?php
$xmlUrl = 'http://localhost:5000/api/repartidores/obtenerTodos';
$xmlContent = file_get_contents($xmlUrl);
$xml = simplexml_load_string($xmlContent);
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="estilos.css">
<head>
    <meta charset="UTF-8">
    <title>Repartidores</title>
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
    <h1>Buscar Repartidores</h1>
    <input type="text" id="busqueda" placeholder="ID Repartidor">
    <div id="resultado"></div>

    <h2 id="tituloTabla">Todos los Repartidores</h2>
    <button onclick="mostrarFormularioRepartidor()">Registrar Repartidor</button>
    <table border="1">
        <thead>
            <tr>
                <th>ID Repartidor</th>
                <th>Nombre</th>
                <th>No. de Guías</th>
            </tr>
        </thead>
        <tbody id="tablaRepartidores">
            <?php foreach ($xml->Repartidor as $repartidor): ?>
                <tr>
                    <td><?php echo $repartidor->IDRepartidor; ?></td>
                    <td><?php echo $repartidor->Nombre; ?></td>
                    <td>
                        <?php
                        $idRepartidor = $repartidor->IDRepartidor;
                        $guiasUrl = "http://localhost:5000/api/paquetes/obtenerGuiasPorIdRepartidor/$idRepartidor";
                        $guiasContent = @file_get_contents($guiasUrl);
                        if ($guiasContent === false) {
                            echo "No hay guías asignadas";
                        } else {
                            $guiasXml = simplexml_load_string($guiasContent);
                            foreach ($guiasXml->Paquete as $paquete) {
                                echo $paquete->NoGuia . '<br>';
                            }
                        }
                        ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="formRepartidorModal">
        <h3>Registrar Repartidor</h3>
        <form id="formRepartidor" onsubmit="registrarRepartidor(event)">
            <label>Nombre: <input type="text" id="formNombreRepartidor" required></label><br>
            <button type="submit">Registrar</button>
            <button type="button" onclick="cerrarFormularioRepartidor()">Cancelar</button>
        </form>
    </div>

    <script>
    function mostrarFormularioRepartidor() {
        document.getElementById('formRepartidorModal').style.display = 'block';
    }
    function cerrarFormularioRepartidor() {
        document.getElementById('formRepartidorModal').style.display = 'none';
        document.getElementById('formRepartidor').reset();
    }

    function registrarRepartidor(event) {
        event.preventDefault();
        const nombre = document.getElementById('formNombreRepartidor').value;

        const xmlBody = `<Repartidor>
            <Nombre>${nombre}</Nombre>
        </Repartidor>`;

        fetch('http://localhost:5000/api/repartidores/registrarRepartidor', {
            method: 'POST',
            headers: { 'Content-Type': 'application/xml' },
            body: xmlBody
        })
        .then(response => response.text())
        .then(msg => {
            alert("Repartidor registrado exitosamente");
            cerrarFormularioRepartidor();
            cargarTodosLosRepartidores();
        })
        .catch(error => {
            alert("Error al registrar el repartidor");
            cerrarFormularioRepartidor();
        });
    }

    const busqueda = document.getElementById('busqueda');
    const tituloTabla = document.getElementById('tituloTabla');
    const tablaRepartidores = document.getElementById('tablaRepartidores');

    function cargarTodosLosRepartidores() {
        tituloTabla.textContent = "Todos los Repartidores";
        fetch('http://localhost:5000/api/repartidores/obtenerTodos', {
            headers: { 'Accept': 'application/xml' }
        })
        .then(response => response.text())
        .then(xmlText => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(xmlText, "application/xml");
            const repartidores = xml.getElementsByTagName('Repartidor');
            let promesas = [];

            for (let repartidor of repartidores) {
                const id = repartidor.getElementsByTagName('IDRepartidor')[0]?.textContent || '';
                const nombre = repartidor.getElementsByTagName('Nombre')[0]?.textContent || '';
                const promesa = fetch(`http://localhost:5000/api/paquetes/obtenerGuiasPorIdRepartidor/${id}`, {
                    headers: { 'Accept': 'application/xml' }
                })
                .then(resp => resp.text())
                .then(guiasXmlText => {
                    const guiaparser = new DOMParser();
                    const guiasXml = guiaparser.parseFromString(guiasXmlText, "application/xml");
                    const guias = guiasXml.getElementsByTagName('Paquete');
                    let guiasHtml = '';
                    if (guias.length === 0) {
                        guiasHtml = 'No hay guías asignadas';
                    } else {
                        for (let guia of guias) {
                            guiasHtml += (guia.getElementsByTagName('NoGuia')[0]?.textContent || '') + '<br>';
                        }
                    }
                    return `<tr>
                        <td>${id}</td>
                        <td>${nombre}</td>
                        <td>${guiasHtml}</td>
                    </tr>`;
                })
                .catch(() => {
                    return `<tr>
                        <td>${id}</td>
                        <td>${nombre}</td>
                        <td>No hay guías asignadas</td>
                    </tr>`;
                });
                promesas.push(promesa);
            }

            Promise.all(promesas).then(filas => {
                tablaRepartidores.innerHTML = filas.join('');
            });
        });
    }

    busqueda.addEventListener('input', function() {
        const idRepartidor = busqueda.value.trim();
        if (idRepartidor.length > 0) {
            fetch(`http://localhost:5000/api/repartidores/buscarRepartidor/${idRepartidor}`, {
                headers: { 'Accept': 'application/xml' }
            })
            .then(response => response.text())
            .then(xmlText => {
                const parser = new DOMParser();
                const xml = parser.parseFromString(xmlText, "application/xml");
                const repartidores = xml.getElementsByTagName('Repartidor');
                let promesas = [];
                if (repartidores.length === 0) {
                    tituloTabla.textContent = "No hay coincidencia";
                    tablaRepartidores.innerHTML = '';
                } else {
                    tituloTabla.textContent = "Repartidor encontrado";
                    for (let repartidor of repartidores) {
                        const id = repartidor.getElementsByTagName('IDRepartidor')[0]?.textContent || '';
                        const nombre = repartidor.getElementsByTagName('Nombre')[0]?.textContent || '';
                        const promesa = fetch(`http://localhost:5000/api/paquetes/obtenerGuiasPorIdRepartidor/${id}`, {
                            headers: { 'Accept': 'application/xml' }
                        })
                        .then(resp => resp.text())
                        .then(guiasXmlText => {
                            const guiaparser = new DOMParser();
                            const guiasXml = guiaparser.parseFromString(guiasXmlText, "application/xml");
                            const guias = guiasXml.getElementsByTagName('Paquete');
                            let guiasHtml = '';
                            if (guias.length === 0) {
                                guiasHtml = 'No hay guías asignadas';
                            } else {
                                for (let guia of guias) {
                                    guiasHtml += (guia.getElementsByTagName('NoGuia')[0]?.textContent || '') + '<br>';
                            }
                        }
                        return `<tr>
                            <td>${id}</td>
                            <td>${nombre}</td>
                            <td>${guiasHtml}</td>
                        </tr>`;
                    })
                    .catch(() => {
                        return `<tr>
                            <td>${id}</td>
                            <td>${nombre}</td>
                            <td>No hay guías asignadas</td>
                        </tr>`;
                    });
                    promesas.push(promesa);
                }
                Promise.all(promesas).then(filas => {
                    tablaRepartidores.innerHTML = filas.join('');
                });
            }
        });
    } else {
        cargarTodosLosRepartidores();
    }
    });
    </script>
</body>
</html>
