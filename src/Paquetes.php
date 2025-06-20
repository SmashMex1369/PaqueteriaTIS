<?php
$xmlUrl = 'http://localhost:5000/api/paquetes/obtenerTodos';
$xmlContent = file_get_contents($xmlUrl);
$xml = simplexml_load_string($xmlContent);
?>
<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="estilos.css">
<head>
    <meta charset="UTF-8">
    <title>Paquetería</title>
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
    <h1>Buscar Paquetes</h1>
    <input type="text" id="busqueda" placeholder="No. de guía">
    <div id="resultado"></div>

    <h2 id="tituloTabla">Todos los Paquetes</h2>
    <button onclick="mostrarFormularioPaquete()">Crear Paquete</button>
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

    <div id="formPaqueteModal">
    <h3>Agregar Paquete</h3>
    <form id="formPaquete" onsubmit="enviarPaquete(event)">
        <label>Descripción: <input type="text" id="formDescripcion" required></label><br>
        <label>Peso (kg): <input type="number" id="formPeso" step="0.01" required></label><br>
        <label>Alto (cm): <input type="number" id="formAlto" step="0.01" required></label><br>
        <label>Ancho (cm): <input type="number" id="formAncho" step="0.01" required></label><br>
        <label>Largo (cm): <input type="number" id="formLargo" step="0.01" required></label><br>
        <label>ID Repartidor: <input type="number" id="formIDRepartidor" required></label><br>
        <label>ID Destino: <input type="number" id="formIDDestino" required></label><br>
        <button type="submit">Agregar</button>
        <button type="button" onclick="cerrarFormularioPaquete()">Cancelar</button>
    </form>
</div>

    <script>
    const busqueda = document.getElementById('busqueda');
    const tituloTabla = document.getElementById('tituloTabla');
    const tablaPaquetes = document.getElementById('tablaPaquetes');

    function mostrarFormularioPaquete() {
        document.getElementById('formPaqueteModal').style.display = 'block';
    }
    function cerrarFormularioPaquete() {
        document.getElementById('formPaqueteModal').style.display = 'none';
        document.getElementById('formPaquete').reset();
    }
    function generarNoGuia() {
        let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let nums = '0123456789';
        let guia = '';
        for (let i = 0; i < 5; i++) {
            guia += chars.charAt(Math.floor(Math.random() * chars.length));
            guia += nums.charAt(Math.floor(Math.random() * nums.length));
        }
        return guia;
    }
    function enviarPaquete(event) {
        event.preventDefault();
        const descripcion = document.getElementById('formDescripcion').value;
        const peso = document.getElementById('formPeso').value;
        const alto = document.getElementById('formAlto').value;
        const ancho = document.getElementById('formAncho').value;
        const largo = document.getElementById('formLargo').value;
        const idRepartidor = document.getElementById('formIDRepartidor').value;
        const idDestino = document.getElementById('formIDDestino').value;
        const noGuia = generarNoGuia();

        const xmlBody = `<Paquete>
            <NoGuia>${noGuia}</NoGuia>
            <Descripcion>${descripcion}</Descripcion>
            <Peso>${peso}</Peso>
            <Dimensiones>
                <Alto>${alto}</Alto>
                <Ancho>${ancho}</Ancho>
                <Largo>${largo}</Largo>
            </Dimensiones>
            <IDRepartidor>${idRepartidor}</IDRepartidor>
            <IDDestino>${idDestino}</IDDestino>
        </Paquete>`;

        fetch('http://localhost:5000/api/paquetes/crearPaquete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/xml' },
            body: xmlBody
        })
        .then(response => response.text())
        .then(msg => {
            alert("Paquete agregado exitosamente");
            cerrarFormularioPaquete();
            cargarTodosLosPaquetes();
        })
        .catch(error => {
            alert("Error al agregar el paquete");
            cerrarFormularioPaquete();
        });
    }

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
