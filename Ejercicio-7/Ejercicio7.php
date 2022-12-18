<?php

session_start();

class BaseDatos{
    
    private $usuario;
    private $host;
    private $password;

    private $database;

    public function __construct(){
        $this->usuario = "DBUSER2022";
        $this->host = "localhost";
        $this->password = "DBPSWD2022";

        $this->database = "sew_ejercicio7";
    }

    public function crearBaseDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->connect_error) {
            $this->operacion =  "<section><h2>OPERACIÓN</h2><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }  

        $sentencia = "CREATE DATABASE IF NOT EXISTS " . $this->database. " COLLATE utf8_spanish_ci";

        $con->query($sentencia); 

        $con->select_db($this->database);

        //Tabla Cliente

        $cliente = "CREATE TABLE IF NOT EXISTS Cliente (
            dni varchar(9) NOT NULL,
            nombre varchar(255) NOT NULL,
            apellidos varchar(255) NOT NULL,
            telefono varchar(12) NOT NULL,
            direccion varchar(255) NOT NULL
        )";
        $con->query($cliente);

        //Tabla Moto

        $moto = "CREATE TABLE IF NOT EXISTS Moto (
            matricula varchar(7) NOT NULL,
            marca varchar(255) NOT NULL,
            modelo varchar(255) NOT NULL,
            año int(11) NOT NULL,
            cliente varchar(9) NOT NULL
        )";

        $con->query($moto);

        //Tabla Mecanico

        $mecanico = "CREATE TABLE IF NOT EXISTS Mecanico (
            dni varchar(9) NOT NULL,
            nombre varchar(255) NOT NULL,
            apellidos varchar(255) NOT NULL
        )";

        $con->query($mecanico);

        //Tabla Rueda
         
        $rueda = "CREATE TABLE IF NOT EXISTS Rueda (
            codigo varchar(255) NOT NULL,
            fabricante varchar(255) NOT NULL,
            tipo varchar(255) NOT NULL,
            ancho int(11) NOT NULL,
            altura int(11) NOT NULL,
            estructura varchar(1) NOT NULL,
            diametro int(11) NOT NULL,
            carga int(11) NOT NULL,
            velocidad varchar(2) NOT NULL,
            precio float NOT NULL
        )";
        
        $con->query($rueda);
        
        //Tabla Sustitucion

        $sustitucion = "CREATE TABLE IF NOT EXISTS Sustitucion (
            fecha DATE NOT NULL,
            moto varchar(7) NOT NULL,
            ruedaDelantera varchar(255) NOT NULL,
            ruedaTrasera varchar(255) NOT NULL,
            descripcion varchar(255) NOT NULL,
            mecanico varchar(9) NOT NULL,
            precio float NOT NULL,
            estadoCambio varchar(255) NOT NULL,
            estadoPago varchar(255) NOT NULL
        )";

        $con->query($sustitucion);
        

        $clienteKey = "ALTER TABLE Cliente ADD PRIMARY KEY (dni)";
        $con->query($clienteKey);

        $mecanicoKey = "ALTER TABLE Mecanico ADD PRIMARY KEY (dni)";
        $con->query($mecanicoKey);

        $ruedaKey = "ALTER TABLE Rueda ADD PRIMARY KEY (codigo)";
        $con->query($ruedaKey);

        $motoKey = "ALTER TABLE Moto ADD PRIMARY KEY (matricula), ADD FOREIGN KEY (cliente) REFERENCES Cliente(dni)";
        $con->query($motoKey);

        $sustitucionKey = "ALTER TABLE Sustitucion ADD PRIMARY KEY (fecha, moto), 
            ADD FOREIGN KEY (moto) REFERENCES Moto(matricula),
            ADD FOREIGN KEY (mecanico) REFERENCES Mecanico(dni),
            ADD FOREIGN KEY (ruedaDelantera) REFERENCES Rueda(codigo),
            ADD FOREIGN KEY (ruedaTrasera) REFERENCES Rueda(codigo)";
        $con->query($sustitucionKey);

        $con->close();
    }

    public function generarParte(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        $print = "";
        if ($con->select_db($this->database)){
            $pendientes = $con->query("SELECT * FROM sustitucion WHERE estadoCambio = 'pendiente' ORDER BY fecha");

            $result = $pendientes->num_rows;
            
            if ($result > 0){
                $print = "PARTE DE TRABAJO\n" .
                    "Deberán realizarse por órden:\n" . 
                    "FECHA\t\t\t" .
                    "MOTO\t\t" .
                    "R-DELANTERA\t\t" .
                    "R-TRASERA\t\t" .
                    "MECANICO\t\t" .
                    "PRECIO\t\t" .
                    "ESTADO\t\t\t" .
                    "PAGO\t\t\t\t" .
                    "DESCRIPCIÓN\n" ;
                while($row = $pendientes->fetch_assoc()) {
                    $print .= $row['fecha'] . "\t\t".
                        $row['moto'] ."\t\t" .
                        $row['ruedaDelantera'] ."\t\t\t" .
                        $row['ruedaTrasera'] ."\t\t\t" .
                        $row['mecanico'] ."\t\t" .
                        $row['precio'] ."\t\t\t" .
                        $row['estadoCambio'] ."\t\t" .
                        $row['estadoPago'] ."\t\t\t". 
                        $row['descripcion'] ."\n" ;
                }
            }
        }
        
        $con->close();
        file_put_contents("parteTrabajo.txt", $print);
    }

    public function sustituciones(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        $print = "";
        if ($con->select_db($this->database)){
            $pendientes = $con->query("SELECT * FROM sustitucion");

            $result = $pendientes->num_rows;
            
            if ($result > 0){
                $print = "<table>" .
                    "<caption>Sustituciones</caption>".  
                    "<tr>".
                    "<th scope=\"col\" id=\"fecha\">FECHA</th>".
                    "<th scope=\"col\" id=\"motoS\">MOTO</th>".
                    "<th scope=\"col\" id=\"rDelantera\">RUEDA DELANTERA</th>".
                    "<th scope=\"col\" id=\"rTrasera\">RUEDA TRASERA</th>".
                    "<th scope=\"col\" id=\"descripcionS\">DESCRIPCION</th>".
                    "<th scope=\"col\" id=\"mecanicoS\">MECANICO</th>".
                    "<th scope=\"col\" id=\"precioS\">PRECIO</th>".
                    "<th scope=\"col\" id=\"estado\">ESTADO CAMBIO</th>".
                    "<th scope=\"col\" id=\"pago\">ESTADO PAGO</th>".
                    "</tr>";
                while($row = $pendientes->fetch_assoc()) {
                    $print .= "<tr>" .
                        "<td headers=\"fecha\">". $row['fecha'] ."</td>" .
                        "<td headers=\"motoS\">". $row['moto'] ."</td>" .
                        "<td headers=\"rDelantera\">". $row['ruedaDelantera'] ."</td>" .
                        "<td headers=\"rTrasera\">". $row['ruedaTrasera'] ."</td>" .
                        "<td headers=\"descripcionS\">". $row['descripcion'] ."</td>" .
                        "<td headers=\"mecanicoS\">". $row['mecanico'] ."</td>" .
                        "<td headers=\"precioS\">". $row['precio'] ."</td>" .
                        "<td headers=\"estado\">". $row['estadoCambio'] ."</td>" .
                        "<td headers=\"pago\">". $row['estadoPago'] ."</td>" .
                        "</tr>";
                }
                $print .= "</table>";
            }
        }
        
        $con->close();
        return $print;
    }

    public function cambiarEstado(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $preparedStmntDni = $con->prepare("UPDATE sustitucion SET estadoCambio = ? WHERE fecha = ? AND moto = ?");
            $preparedStmntDni->bind_param('sss', $_POST['cambiar_estado_new'], $_POST['cambiar_estado_fecha'], $_POST['cambiar_estado_moto']);
            $preparedStmntDni->execute();
        }

        $con->close();
    }

    public function cambiarPago(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $preparedStmntDni = $con->prepare("UPDATE sustitucion SET estadoPago = ? WHERE fecha = ? AND moto = ?");
            $preparedStmntDni->bind_param('sss', $_POST['cambiar_pago_new'], $_POST['cambiar_pago_fecha'], $_POST['cambiar_pago_moto']);
            $preparedStmntDni->execute();
        }
        
        $con->close();
    }

    public function añadirSustitucion(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $precio=$this->calcularPrecio();
            $preparedStmnt = $con->prepare("INSERT INTO sustitucion (fecha, moto, ruedaDelantera, ruedaTrasera, descripcion, mecanico, precio, estadoCambio, estadoPago) VALUES (?,?,?,?,?,?,?,'PENDIENTE','PENDIENTE')");
            
            $preparedStmnt->bind_param('ssssssd', 
            $_POST['añadir_sustitucion_fecha'], $_POST['añadir_sustitucion_moto'], $_POST['añadir_sustitucion_delantera'], $_POST['añadir_sustitucion_trasera'], 
            $_POST['añadir_sustitucion_descripcion'], $_POST['añadir_sustitucion_mecanico'], $precio);
            $preparedStmnt->execute();
            $preparedStmnt->close();
        }

        $con->close();
    }
    private function calcularPrecio(){
        $precio = 0;
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $delante = $con->prepare("SELECT precio FROM rueda WHERE codigo = ?"); 
            $delante->bind_param('s', $_POST['añadir_sustitucion_delantera']);
            $delante->execute();
            $delanteObj = $delante->get_result();
            $rowD = $delanteObj->fetch_row();
            $delante->close();

            $atras = $con->prepare("SELECT precio FROM rueda WHERE codigo = ?");
            $atras->bind_param('s', $_POST['añadir_sustitucion_trasera']);
            $atras->execute();
            $precioAtras = $atras->get_result();
            $rowA = $precioAtras->fetch_row();
            $atras->close();

            $precio = ($rowD[0] + $rowA[0]) *1.2;
        }
        $con->close();
        return $precio;
    }

    //MECANICO --------------------------------------------------------------------------------------
    public function mecanicos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        $print = "";
        if ($con->select_db($this->database)){
            $pendientes = $con->query("SELECT * FROM mecanico");

            $result = $pendientes->num_rows;
            
            if ($result > 0){
                $print = "<table>" .
                    "<caption>Mecánicos</caption>".  
                    "<tr>".
                    "<th scope=\"col\" id=\"dniM\">DNI</th>".
                    "<th scope=\"col\" id=\"nombreM\">NOMBRE</th>".
                    "<th scope=\"col\" id=\"apellidosM\">APELLIDOS</th>".
                    "</tr>";
                while($row = $pendientes->fetch_assoc()) {
                    $print .= "<tr>" .
                        "<td headers=\"dniM\">". $row['dni'] ."</td>" .
                        "<td headers=\"nombreM\">". $row['nombre'] ."</td>" .
                        "<td headers=\"apellidosM\">". $row['apellidos'] ."</td>" .
                        "</tr>";
                }
                $print .= "</table>";
            }
        }
        
        $con->close();
        return $print;
    }
    public function eliminarMecanico(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $preparedStmnt = $con->prepare("DELETE FROM mecanico WHERE dni = ?");
            
            $preparedStmnt->bind_param('s', $_POST['eliminar_mecanico_dni']);
            $preparedStmnt->execute();
            $preparedStmnt->close();
        }

        $con->close();
    }

    public function añadirMecanico(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $preparedStmnt = $con->prepare("INSERT INTO mecanico (dni, nombre, apellidos) VALUES (?,?,?)");
            
            $preparedStmnt->bind_param('sss', $_POST['añadir_mecanico_dni'], $_POST['añadir_mecanico_nombre'], $_POST['añadir_mecanico_apellidos']);
            $preparedStmnt->execute();
            $preparedStmnt->close();
        }

        $con->close();
    }

    //RUEDA---------------------------------------------------------------------------------------------
    public function ruedas(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        $print = "";
        if ($con->select_db($this->database)){
            $pendientes = $con->query("SELECT * FROM rueda");

            $result = $pendientes->num_rows;
            
            if ($result > 0){
                $print = "<table>" .
                    "<caption>Ruedas</caption>".  
                    "<tr>".
                    "<th scope=\"col\" id=\"codigo\">CODIGO</th>".
                    "<th scope=\"col\" id=\"fabricante\">FABRICANTE</th>".
                    "<th scope=\"col\" id=\"tipo\">TIPO</th>".
                    "<th scope=\"col\" id=\"ancho\">ANCHO</th>".
                    "<th scope=\"col\" id=\"altura\">ALTURA</th>".
                    "<th scope=\"col\" id=\"estructura\">ESTRUCTURA</th>".
                    "<th scope=\"col\" id=\"diametro\">DIÁMETRO</th>".
                    "<th scope=\"col\" id=\"carga\">CARGA</th>".
                    "<th scope=\"col\" id=\"velocidad\">VELOCIDAD</th>".
                    "<th scope=\"col\" id=\"precio\">PRECIO</th>".
                    "</tr>";
                while($row = $pendientes->fetch_assoc()) {
                    $print .= "<tr>" .
                        "<td headers=\"codigo\">". $row['codigo'] ."</td>" .
                        "<td headers=\"fabricante\">". $row['fabricante'] ."</td>" .
                        "<td headers=\"tipo\">". $row['tipo'] ."</td>" .
                        "<td headers=\"ancho\">". $row['ancho'] ."</td>" .
                        "<td headers=\"altura\">". $row['altura'] ."</td>" .
                        "<td headers=\"estructura\">". $row['estructura'] ."</td>" .
                        "<td headers=\"diametro\">". $row['diametro'] ."</td>" .
                        "<td headers=\"carga\">". $row['carga'] ."</td>" .
                        "<td headers=\"velocidad\">". $row['velocidad'] ."</td>" .
                        "<td headers=\"precio\">". $row['precio'] ."</td>" .
                        "</tr>";
                }
                $print .= "</table>";
            }
        }
        
        $con->close();
        return $print;
    }
    public function eliminarRueda(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $preparedStmnt = $con->prepare("DELETE FROM rueda WHERE codigo = ?");
            
            $preparedStmnt->bind_param('s', $_POST['eliminar_rueda_codigo']);
            $preparedStmnt->execute();
            $preparedStmnt->close();
        }

        $con->close();
    }

    public function añadirRueda(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
        if($con->select_db($this->database)){
            $preparedStmnt = $con->prepare("INSERT INTO rueda (codigo, fabricante, tipo, ancho, altura, estructura, diametro, carga, velocidad, precio) VALUES (?,?,?,?,?,?,?,?,?,?)");
            
            $preparedStmnt->bind_param('sssiisiisd', $_POST['añadir_rueda_codigo'], $_POST['añadir_rueda_fabricante'], $_POST['añadir_rueda_tipo'], $_POST['añadir_rueda_ancho'], $_POST['añadir_rueda_alto'], $_POST['añadir_rueda_estructura'], $_POST['añadir_rueda_diametro'], $_POST['añadir_rueda_carga'], $_POST['añadir_rueda_velocidad'], $_POST['añadir_rueda_precio']);
            $preparedStmnt->execute();
            var_dump($preparedStmnt);
            $preparedStmnt->close();
        }

        $con->close();
    }


}
$db = new BaseDatos();

if(count($_POST)>0){
    if(isset($_POST['crearBase'])) $db->crearBaseDatos();
    //SUSTITUCIONES
    if(isset($_POST['cambiarEstado'])) $db->cambiarEstado();
    if(isset($_POST['cambiarPago'])) $db->cambiarPago();
    if(isset($_POST['añadirSustitucion'])) $db->añadirSustitucion();
    //MECANICOS
    if(isset($_POST['eliminarMecanico'])) $db->eliminarMecanico();
    if(isset($_POST['añadirMecanico'])) $db->añadirMecanico();
    //RUEDAS
    if(isset($_POST['eliminarRueda'])) $db->eliminarRueda();
    if(isset($_POST['añadirRueda'])) $db->añadirRueda();


    if(isset($_POST['parte'])) $db->generarParte();
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>SEW - Ejercicio 7</title>
    <meta name ="author" content ="Sara María Ramírez Pérez" />
    <!--Definición de la ventana gráfica-->
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" type="text/css" href="Ejercicio7.css" />
</head>
<body>
    <h1>Cambio de ruedas</h1>

    <nav>
        <a title="Iniciar Base de Datos" accesskey="C" tabindex="1" href="#crearBase">Crear base de datos</a>
        <a title="Sustituciones" accesskey="S" tabindex="2" href="#sustituciones">Sustituciones</a>
        <a title="Mecanicos" accesskey="M" tabindex="3" href="#mecanico">Mecanicos</a>
        <a title="Ruedas" accesskey="R" tabindex="4" href="#rueda">Ruedas</a>
    </nav>


    <section> <a id="crearBase"></a>
        <h2>Iniciar Base de Datos</h2>
        <p>Al pusar el botón se creará la base de datos "sew_ejercicio7", y sus tablas correspondientes.</p>
        <form action='#' method='POST'>
            <button type="submit" name="crearBase">Crear base de datos</button>
		</form>
    </section> 

    <section> <a id="sustituciones"></a>
        <h2>Sustituciones</h2>
        <?php echo $db->sustituciones(); ?>
        <form action='#' method='POST'>
            <button type="submit" name="parte">Descargar parte de trabajo</button>
		</form>

        <h3>Modificar estado de sustitución</h3>
        <form action='#' method='POST'>
            <p>
				<label for="cambiar_estado_fecha">Fecha:</label>
				<input type="text" id="cambiar_estado_fecha" name="cambiar_estado_fecha" />
			</p>
            <p>
				<label for="cambiar_estado_moto">Moto:</label>
				<input type="text" id="cambiar_estado_moto" name="cambiar_estado_moto" />
			</p>
            <p>
				<label for="cambiar_estado_new">Nuevo estado:</label>
                <select name="cambiar_estado_new" id ="cambiar_estado_new">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="ACABADO">Acabado</option>
                </select>
			</p>
            <button type="submit" name="cambiarEstado">Modificar</button>
		</form>

        <h3>Modificar estado pago</h3>
        <form action='#' method='POST'>
            <p>
				<label for="cambiar_pago_fecha">Fecha:</label>
				<input type="text" id="cambiar_pago_fecha" name="cambiar_pago_fecha" />
			</p>
            <p>
				<label for="cambiar_pago_moto">Moto:</label>
				<input type="text" id="cambiar_pago_moto" name="cambiar_pago_moto" />
			</p>
            <p>
				<label for="cambiar_pago_new">Nuevo estado:</label>
                <select name="cambiar_pago_new" id ="cambiar_pago_new">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="PAGADO">Pagado</option>
                </select>
			</p>
            
            <button type="submit" name="cambiarPago">Modificar</button>
		</form>

        <h3>Añadir sustitución</h3>
        <form action='#' method='POST'>
            <p>
				<label for="añadir_sustitucion_fecha">Fecha:</label>
				<input type="text" id="añadir_sustitucion_fecha" name="añadir_sustitucion_fecha" />
			</p>
            <p>
				<label for="añadir_sustitucion_moto">Moto:</label>
				<input type="text" id="añadir_sustitucion_moto" name="añadir_sustitucion_moto" />
			</p>
            <p>
				<label for="añadir_sustitucion_delantera">Rueda delantera:</label>
				<input type="text" id="añadir_sustitucion_delantera" name="añadir_sustitucion_delantera" />
			</p>
            <p>
				<label for="añadir_sustitucion_trasera">Rueda trasera:</label>
				<input type="text" id="añadir_sustitucion_trasera" name="añadir_sustitucion_trasera" />
			</p>
            <p>
				<label for="añadir_sustitucion_descripcion">Descripción:</label>
				<input type="text" id="añadir_sustitucion_descripcion" name="añadir_sustitucion_descripcion" />
			</p>
            <p>
				<label for="añadir_sustitucion_mecanico">Mecánico:</label>
				<input type="text" id="añadir_sustitucion_mecanico" name="añadir_sustitucion_mecanico" />
			</p>
        
            <button type="submit" name="añadirSustitucion">Añadir</button>
		</form>
    </section> 
 
    <section> <a id="mecanico"></a>
        <h2>Mecanicos</h2>
        <?php echo $db->mecanicos(); ?>

        <h3>Eliminar mecánico</h3>
        <p>Solo se podrán eliminar mechanicos que no tengan asignada ninguna sustitución.</p>
        <form action='#' method='POST'>
            <p>
				<label for="eliminar_mecanico_dni">DNI:</label>
				<input type="text" id="eliminar_mecanico_dni" name="eliminar_mecanico_dni" />
			</p>
            <button type="submit" name="eliminarMecanico">Eliminar</button>
		</form>

        <h3>Añadir mecánico</h3>
        <form action='#' method='POST'>
            <p>
				<label for="añadir_mecanico_dni">DNI:</label>
				<input type="text" id="añadir_mecanico_dni" name="añadir_mecanico_dni" />
			</p>
            <p>
				<label for="añadir_mecanico_nombre">Nombre:</label>
				<input type="text" id="añadir_mecanico_nombre" name="añadir_mecanico_nombre" />
			</p>
            <p>
				<label for="añadir_mecanico_apellidos">Apellidos:</label>
				<input type="text" id="añadir_mecanico_apellidos" name="añadir_mecanico_apellidos" />
			</p>
            <button type="submit" name="añadirMecanico">Añadir</button>
		</form>
    </section>

    <section> <a id="rueda"></a>
        <h2>Ruedas</h2>
        <?php echo $db->ruedas(); ?>

        <h3>Eliminar ruedas</h3>
        <p>Solo se podrán eliminar ruedas que no hayan sido usadas en ninguna sustitución.</p>
        <form action='#' method='POST'>
            <p>
				<label for="eliminar_rueda_codigo">Codigo:</label>
				<input type="text" id="eliminar_rueda_codigo" name="eliminar_rueda_codigo" />
			</p>
            <button type="submit" name="eliminarRueda">Eliminar</button>
		</form>

        <h3>Añadir rueda</h3>
        <form action='#' method='POST'>
            <p>
				<label for="añadir_rueda_codigo">Codigo:</label>
				<input type="text" id="añadir_rueda_codigo" name="añadir_rueda_codigo" />
			</p>
            <p>
				<label for="añadir_rueda_fabricante">Fabricante:</label>
				<input type="text" id="añadir_rueda_fabricante" name="añadir_rueda_fabricante" />
			</p>
            <p>
				<label for="añadir_rueda_tipo">Tipo:</label>
				<input type="text" id="añadir_rueda_tipo" name="añadir_rueda_tipo" />
			</p>
            <p>
				<label for="añadir_rueda_ancho">Ancho:</label>
				<input type="number" id="añadir_rueda_ancho" name="añadir_rueda_ancho" />
			</p>
            <p>
				<label for="añadir_rueda_alto">Alto:</label>
				<input type="number" id="añadir_rueda_alto" name="añadir_rueda_alto" />
			</p>
            <p>
				<label for="añadir_rueda_estructura">Estructura:</label>
				<input type="text" id="añadir_rueda_estructura" name="añadir_rueda_estructura" />
			</p>
            <p>
				<label for="añadir_rueda_diametro">Diametro:</label>
				<input type="number" id="añadir_rueda_diametro" name="añadir_rueda_diametro" />
			</p>
            <p>
				<label for="añadir_rueda_carga">Carga:</label>
				<input type="number" id="añadir_rueda_carga" name="añadir_rueda_carga" />
			</p>
            <p>
				<label for="añadir_rueda_velocidad">Velocidad:</label>
				<input type="text" id="añadir_rueda_velocidad" name="añadir_rueda_velocidad" />
			</p>
            <p>
				<label for="añadir_rueda_precio">Precio:</label>
				<input type="number" step="0.01" id="añadir_rueda_precio" name="añadir_rueda_precio" />
			</p>
            <button type="submit" name="añadirRueda">Añadir</button>
		</form>
    </section>


</body>
</html>
