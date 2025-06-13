<?php
$xmlUrl = 'http://localhost:5000/api/paquetes/obtenerTodos';
$xmlContent = file_get_contents($xmlUrl);
$xml = simplexml_load_string($xmlContent);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Paquetería</title>
</head>
<body>
    <h1>Buscar Paquetes</h1>
    <input type="text" id="busqueda" placeholder="No. de guía">
    <div id="resultado"></div>

    <h2 id="tituloTabla">Todos los Paquetes</h2>
    <table border="1">
        <thead>
            <tr>
                <th>No. de Guía</th>
                <th>Descripción</th>
                <th>Peso</th>
                <th>Alto</th>
                <th>Ancho</th>
                <th>Largo</th>
                <th>ID Repartidor</th>
                <th>ID Destino</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaPaquetes">
            <?php foreach ($xml->Paquete as $paquete): ?>
                <tr>
                    <td><?php echo $paquete->NoGuia; ?></td>
                    <td><?php echo $paquete->Descripcion; ?></td>
                    <td><?php echo $paquete->Peso; ?></td>
                    <td><?php echo $paquete->Dimensiones->Alto; ?></td>
                    <td><?php echo $paquete->Dimensiones->Ancho; ?></td>
                    <td><?php echo $paquete->Dimensiones->Largo; ?></td>
                    <td><?php echo $paquete->IDRepartidor; ?></td>
                    <td><?php echo $paquete->IDDestino; ?></td>
                    <td>
                        <button onclick="completarPaquete('<?php echo $paquete->NoGuia; ?>')">Completar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
    const busqueda = document.getElementById('busqueda');
    const tituloTabla = document.getElementById('tituloTabla');
    const tablaPaquetes = document.getElementById('tablaPaquetes');

    function completarPaquete(noGuia) {
        if (confirm("¿Desea marcar el paquete como Completado?")) {
            fetch('http://localhost:5000/api/paquetes/completarEnvio', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/xml' },
                body: `<Paquete><NoGuia>${noGuia}</NoGuia></Paquete>`
            })
            .then(response => response.text())
            .then(msg => {
                alert("Paquete completado.");
                // Refresca la tabla después de completar
                busqueda.value = '';
                cargarTodosLosPaquetes();
            });
        }
    }

    function cargarTodosLosPaquetes() {
        tituloTabla.textContent = "Todos los Paquetes";
        fetch('http://localhost:5000/api/paquetes/obtenerTodos', {
            headers: { 'Accept': 'application/xml' }
        })
        .then(response => response.text())
        .then(xmlText => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(xmlText, "application/xml");
            const paquetes = xml.getElementsByTagName('Paquete');
            let html = '';
            for (let paquete of paquetes) {
                html += `<tr>
                    <td>${paquete.getElementsByTagName('NoGuia')[0]?.textContent || ''}</td>
                    <td>${paquete.getElementsByTagName('Descripcion')[0]?.textContent || ''}</td>
                    <td>${paquete.getElementsByTagName('Peso')[0]?.textContent || ''}</td>
                    <td>${paquete.getElementsByTagName('Dimensiones')[0]?.getElementsByTagName('Alto')[0]?.textContent || ''}</td>
                    <td>${paquete.getElementsByTagName('Dimensiones')[0]?.getElementsByTagName('Ancho')[0]?.textContent || ''}</td>
                    <td>${paquete.getElementsByTagName('Dimensiones')[0]?.getElementsByTagName('Largo')[0]?.textContent || ''}</td>
                    <td>${paquete.getElementsByTagName('IDRepartidor')[0]?.textContent || ''}</td>
                    <td>${paquete.getElementsByTagName('IDDestino')[0]?.textContent || ''}</td>
                    <td>
                        <button onclick="completarPaquete('${paquete.getElementsByTagName('NoGuia')[0]?.textContent || ''}')">Completar</button>
                    </td>
                </tr>`;
            }
            tablaPaquetes.innerHTML = html;
        });
    }

    busqueda.addEventListener('input', function() {
        const guia = busqueda.value.trim();
        if (guia.length > 0) {
            fetch(`http://localhost:5000/api/paquetes/buscarPaquetes/${guia}`, {
                headers: { 'Accept': 'application/xml' }
            })
            .then(response => response.text())
            .then(xmlText => {
                const parser = new DOMParser();
                const xml = parser.parseFromString(xmlText, "application/xml");
                const paquetes = xml.getElementsByTagName('Paquete');
                let html = '';
                if (paquetes.length === 0) {
                    tituloTabla.textContent = "No hay coincidencias";
                    tablaPaquetes.innerHTML = '';
                } else {
                    tituloTabla.textContent = "Paquetes encontrados";
                    for (let paquete of paquetes) {
                        html += `<tr>
                            <td>${paquete.getElementsByTagName('NoGuia')[0]?.textContent || ''}</td>
                            <td>${paquete.getElementsByTagName('Descripcion')[0]?.textContent || ''}</td>
                            <td>${paquete.getElementsByTagName('Peso')[0]?.textContent || ''}</td>
                            <td>${paquete.getElementsByTagName('Dimensiones')[0]?.getElementsByTagName('Alto')[0]?.textContent || ''}</td>
                            <td>${paquete.getElementsByTagName('Dimensiones')[0]?.getElementsByTagName('Ancho')[0]?.textContent || ''}</td>
                            <td>${paquete.getElementsByTagName('Dimensiones')[0]?.getElementsByTagName('Largo')[0]?.textContent || ''}</td>
                            <td>${paquete.getElementsByTagName('IDRepartidor')[0]?.textContent || ''}</td>
                            <td>${paquete.getElementsByTagName('IDDestino')[0]?.textContent || ''}</td>
                            <td>
                                <button onclick="completarPaquete('${paquete.getElementsByTagName('NoGuia')[0]?.textContent || ''}')">Completar</button>
                            </td>
                        </tr>`;
                    }
                    tablaPaquetes.innerHTML = html;
                }
            });
        } else {
            cargarTodosLosPaquetes();
        }
    });
    </script>
</body>
</html>
