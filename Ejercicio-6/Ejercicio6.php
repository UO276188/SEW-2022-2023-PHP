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

        $this->database = "sew_ejercicio6";
    }

    public function crearBaseDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }  

        $sentencia = "CREATE DATABASE IF NOT EXISTS " . $this->database. " COLLATE utf8_spanish_ci";

        if($con->query($sentencia) === TRUE){
            echo "<section><p>Base de datos creada con éxito</p></section>";
        } else { 
            echo "<section><p>ERROR en la creación de la Base de Datos: " . $con->error . "</p></section>";
        }   

        $con->close();
    }
    
    public function crearTabla(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        } 
    
        
        $con->select_db($this->database);
        // sexo H - M
        // tarea_correcta SI - NO

        $tabla = "CREATE TABLE IF NOT EXISTS PruebasUsabilidad (
            DNI varchar(9) NOT NULL,
            nombre varchar(255) NOT NULL,
            apellidos varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            telefono varchar(12) NOT NULL,
            edad int NOT NULL,
            sexo varchar(255) NOT NULL, 
            pericia_informatica int NOT NULL,
            tiempo int NOT NULL,
            tarea_correcta varchar(255) NOT NULL, 
            comentarios varchar(255) NOT NULL,
            propuesta_mejora varchar(255) NOT NULL,
            valoracion int NOT NULL
        )";

        if($con->query($tabla) === TRUE){
            echo "<section><p>Tabla 'PruebasUsabilidad' creada con éxito.</p></section>";
        } else { 
            echo "<section><p>ERROR en la creación de la tabla: " . $con->error . "</p></section>";
            exit();
        }  

        $con->close();
    }

    public function insertarDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        } 

        $con->select_db($this->database);
        
        $preparedStmnt = $con->prepare("INSERT INTO PruebasUsabilidad (DNI, nombre, apellidos, email, telefono, edad, sexo, pericia_informatica, tiempo, tarea_correcta, comentarios, propuesta_mejora, valoracion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
        $preparedStmnt->bind_param('sssssisiisssi', $_POST['dni'], $_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['telefono'], $_POST['edad'], $_POST['sexo'], $_POST['pericia'], $_POST['tiempo'], $_POST['tarea_correcta'], $_POST['comentarios'], $_POST['mejora'], $_POST['valoracion']);
        

        $preparedStmnt->execute();

        if($preparedStmnt->affected_rows > 0){
            echo "<section><p>Filas insertadas: " . $preparedStmnt->affected_rows ."</p></section>";
        } else { 
            echo "<section><p>ERROR en la insercción de filas</p></section>";
        }  

        $preparedStmnt->close();
        $con->close();
    }

    public function buscarDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        } 

        $con->select_db($this->database);
        
        $preparedStmntDni = $con->prepare("SELECT * FROM PruebasUsabilidad WHERE DNI = ?");
        $preparedStmntDni->bind_param('s', $_POST['buscarDni']);
        $preparedStmntDni->execute();

        $result = $preparedStmntDni->get_result();

        if ($result->num_rows > 0){
            $print = "<section><table>" .
			"<caption>PruebasUsabilidad</caption>".  
			"<tr>".
			"<th scope=\"col\" id=\"dni\">DNI</th>".
			"<th scope=\"col\" id=\"nombre\">Nombre</th>".
			"<th scope=\"col\" id=\"apellidos\">Apellidos</th>".
			"<th scope=\"col\" id=\"email\">Email</th>".
			"<th scope=\"col\" id=\"telefono\">Telefono</th>".
			"<th scope=\"col\" id=\"edad\">Edad</th>".
			"<th scope=\"col\" id=\"sexo\">Sexo</th>".
			"<th scope=\"col\" id=\"pericia_informatica\">Pericia</th>".
			"<th scope=\"col\" id=\"tiempo\">Tiempo</th>".
			"<th scope=\"col\" id=\"tarea_correcta\">Tarea correcta</th>".
			"<th scope=\"col\" id=\"comentarios\">Comentarios</th>".
			"<th scope=\"col\" id=\"propuesta_mejora\">Propuestas de mejora</th>".
			"<th scope=\"col\" id=\"valoracion\">Valoración</th>".
			"</tr>";

            while($row = $result->fetch_assoc()) {
                $print .= "<tr>" .
                "<td headers=\"dni\">". $row['DNI'] ."</td>" .
                "<td headers=\"nombre\">". $row['nombre'] ."</td>" .
                "<td headers=\"apellidos\">". $row['apellidos'] ."</td>" .
                "<td headers=\"email\">". $row['email'] ."</td>" .
                "<td headers=\"telefono\">". $row['telefono'] ."</td>" .
                "<td headers=\"edad\">". $row['edad'] ."</td>" .
                "<td headers=\"sexo\">". $row['sexo'] ."</td>" .
                "<td headers=\"pericia_informatica\">". $row['pericia_informatica'] ."</td>" .
                "<td headers=\"tiempo\">". $row['tiempo'] ."</td>" .
                "<td headers=\"tarea_correcta\">". $row['tarea_correcta'] ."</td>" .
                "<td headers=\"comentarios\">". $row['comentarios'] ."</td>" .
                "<td headers=\"propuesta_mejora\">". $row['propuesta_mejora'] ."</td>" .
                "<td headers=\"valoracion\">". $row['valoracion'] ."</td>" .
                "</tr>";
            }

            $print .= "</table></section>";
            echo $print;
        } else {
            echo "<section><p>No se han encontrado coincidencias.</p></section>";
        }

        $preparedStmntDni->close();
        $con->close();
    }

    public function modificarDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }

        $con->select_db($this->database);

        $preparedStmntDni = $con->prepare("UPDATE PruebasUsabilidad SET nombre = ?, apellidos = ?, email = ?, telefono = ?, edad = ?, sexo = ?, pericia_informatica = ?, tiempo = ?, tarea_correcta = ?, comentarios = ?, propuesta_mejora = ?, valoracion = ? WHERE DNI = ?");
        $preparedStmntDni->bind_param('ssssisiisssis', $_POST['modificarNombre'], $_POST['modificarApellidos'], $_POST['modificarEmail'], $_POST['modificarTelefono'], $_POST['modificarEdad'], $_POST['modificarSexo'], $_POST['modificarPericia'], $_POST['modificarTiempo'], $_POST['modificarTarea'], $_POST['modificarComentarios'], $_POST['modificarMejora'], $_POST['modificarValoracion'], $_POST['modificarDni']);
        $preparedStmntDni->execute();

        $result = $preparedStmntDni->get_result();

        if ($result->num_rows > 0){
            echo "<section><p>Fila modificada.</p></section>";
        } else {
            echo "<section><p>No se han encontrado coincidencias.</p></section>";
        }


        $preparedStmntDni->close();
        $con->close();
    }

    public function eliminarDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }

        $con->select_db($this->database);
        
        $preparedStmntDni = $con->prepare("DELETE FROM PruebasUsabilidad WHERE DNI = ?");
        $preparedStmntDni->bind_param('s', $_POST['borrarDNI']);
        $preparedStmntDni->execute();

        

        if($preparedStmntDni->affected_rows > 0){
            echo "<section><p>Datos eliminados correctamente.</p></section>";
        } else { 
            echo "<section><p>No se han podido eliminar los datos</p></section>";
        }  
        

        $preparedStmntDni->close();
        $con->close();
    }

    public function generarInforme(){
        $numeroTotalFilas = $this->calcTotal();
        
        if ($numeroTotalFilas > 0){
            $edadMedia = $this->media("edad");
            $porcH = $this->porcentaje("sexo", "H", $numeroTotalFilas);
            $porcM = $this->porcentaje("sexo", "M", $numeroTotalFilas);
            $periciaMedia = $this->media("pericia_informatica");
            $tiempoMedio = $this->media("tiempo");
            $porcCorrecto = $this->porcentaje("tarea_correcta", "SI", $numeroTotalFilas);
            $puntuacionMedia = $this->media("valoracion");

            $print = "<section> <p>Informe</p>" .
            "<ul>" . 
            "<li>Edad media: $edadMedia</li>" . 
            "<li>Frecuencia hombres: $porcH%</li>" . 
            "<li>Frecuencia mujeres: $porcM%</li>" . 
            "<li>Valor medio de pericia informatica: $periciaMedia</li>" . 
            "<li>Media de tiempo: $tiempoMedio</li>" . 
            "<li>Porcentaje de usuarios que han completado la tarea correctamente: $porcCorrecto%</li>" . 
            "<li>Puntuación media: $puntuacionMedia</li>" . 
            "</ul></section>";

            echo $print;

        } else {
            echo "<section><p>No hay datos que mostrar</p></section>";
        }       
    }

    private function calcTotal(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }

        $con->select_db($this->database);
        $total = null;   

        $res = $con->query("SELECT COUNT(*) AS num FROM PruebasUsabilidad");

        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()) {
                $total = $row['num'];
            }
        }

        $con->close();
        return $total;
    }

    private function media($dato){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }

        $con->select_db($this->database);
        
        $result = null;   

        $res = $con->query("SELECT AVG(" . $dato .") AS media FROM PruebasUsabilidad");

        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()) {
                $result = $row['media'];
            }
        }

        $con->close();
        return $result;
    }
    
    private function porcentaje($campo, $valor, $total){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                        
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }

        $con->select_db($this->database);
        
        $result = null;   

        $res = $con->query("SELECT COUNT(*) AS num FROM PruebasUsabilidad WHERE " .$campo. "=\"" . $valor . "\"");

        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()) {
                $result = $row['num'];
            }
        }

        $con->close();

        return ($result / $total) *100;
    }

    public function cargarDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }  

        $con->select_db($this->database);

        //abrir archivo en lectura, modo binario
        $archivo = fopen($_FILES['file']['tmp_name'], "rb");

        //linea - leer csv
        $linea = fgetcsv($archivo);

        $lineasTotales = 0;

        while ($linea){
            $preparedStmnt = $con->prepare("INSERT INTO PruebasUsabilidad (DNI, nombre, apellidos, email, telefono, edad, sexo, pericia_informatica, tiempo, tarea_correcta, comentarios, propuesta_mejora, valoracion) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
            $preparedStmnt->bind_param('sssssisiisssi', $linea[0], $linea[1],$linea[2],$linea[3],$linea[4],$linea[5],$linea[6],$linea[7],$linea[8],$linea[9],$linea[10],$linea[11],$linea[12]);

            $linea = fgetcsv($archivo);
            $preparedStmnt->execute();

            if($preparedStmnt->affected_rows > 0){
                $lineasTotales++;
            }
            $preparedStmnt->close();
        }
        echo "<section><p>Filas importadas: " . $lineasTotales . "</p></section>";  

        $con->close();
    }

    public function exportarDatos(){
        $con = new mysqli($this->host, $this->usuario, $this->password);
                
        if($con->connect_error) {
            echo "<section><p>ERROR en la conexión: " . $con->connect_error . "</p></section>";  
        }  

        $con->select_db($this->database);

        $data = $con->query("SELECT * FROM PruebasUsabilidad");
        
        $result = "";

        if($data->num_rows > 0){
            while($row = $data->fetch_assoc()){
                $result .= $row['DNI'] . "," . $row['nombre'] . "," . $row['apellidos'] . "," . $row['email'] . "," . $row['telefono'] . "," . $row['edad'] . "," . $row['sexo'] . "," . $row['pericia_informatica'] . "," . $row['tiempo'] . "," . $row['tarea_correcta'] . "," . $row['comentarios'] . "," . $row['propuesta_mejora'] . "," . $row['valoracion'] . "\n" ;
            }
        }

        $con->close();

        if (file_put_contents("pruebasUsabilidad.csv", $result) != false){
            echo "<section><p>Archivo generado</p></section>"; 
        }
        
    }

}

$db = new BaseDatos();

if(count($_POST)>0){
    if(isset($_POST['crearBase'])) $db->crearBaseDatos();
    if(isset($_POST['crearTabla'])) $db->crearTabla();
    if(isset($_POST['insertarDatos'])) $db->insertarDatos();
    if(isset($_POST['buscarDatos'])) $db->buscarDatos();
    if(isset($_POST['modificarDatos'])) $db->modificarDatos();
    if(isset($_POST['borrarDatos'])) $db->eliminarDatos();
    if(isset($_POST['generarInforme'])) $db->generarInforme();
    if(isset($_POST['cargarDatos'])) $db->cargarDatos();
    if(isset($_POST['exportarDatos'])) $db->exportarDatos();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>SEW - Ejercicio 6</title>
    <meta name ="author" content ="Sara María Ramírez Pérez" />
    <!--Definición de la ventana gráfica-->
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" type="text/css" href="Ejercicio6.css" />
</head>
<body>
    <h1>Bases de datos</h1>

    <nav>
        <a title="Crear Base de Datos" accesskey="C" tabindex="1" href="#crearBase">Crear base de datos</a>
        <a title="Crear una tabla" accesskey="T" tabindex="2" href="#crearTabla">Crear tabla</a>
        <a title="Insertar datos en una tabla" accesskey="I" tabindex="3" href="#insertarDatos">Insertar datos</a>
        <a title="Buscar datos en una tabla" accesskey="B" tabindex="4" href="#buscarDatos">Buscar datos</a>
        <a title="Modificar datos en una tabla" accesskey="M" tabindex="5" href="#modificarDatos">Modificar datos</a>
        <a title="Eliminar datos de una tabla" accesskey="E" tabindex="6" href="#borrarDatos">Eliminar datos</a>
        <a title="Generar informe" accesskey="G" tabindex="7" href="#generarInforme">Generar informe</a>
        <a title="Cargar datos desde un archivo CSV en una tabla de la Base de Datos" accesskey="V" tabindex="8" href="#cargarDatos">Importar CSV</a>
        <a title="Exportar datos a un archivo en formato CSV los datos desde una tabla de la Base de Datos" accesskey="X" tabindex="9" href="#exportarDatos">Exportar a CSV</a>
    </nav>


    <section> <a id="crearBase"></a>
        <h2>Crear Base de Datos</h2>
        <p>Al pusar el botón se creará la base de datos "sew_ejercicio6"</p>
        <form action='#' method='POST'>
            <button type="submit" name="crearBase">Crear base de datos</button>
		</form>
    </section>

    <section> <a id="crearTabla"></a>
        <h2>Crear Tabla - PruebasUsabilidad</h2>
        <p>Al pusar el botón se creará la tabla "PruebasUsabilidad"</p>
        <form action='#' method='POST'>
            <button type="submit" name="crearTabla">Crear Tabla</button>
		</form>
    </section>

    <section> <a id="insertarDatos"></a>
        <h2>Insertar datos</h2>
        <form action='#' method='POST'>
            <p>
				<label for="dni">DNI:</label>
				<input type="text" id="dni" name="dni" />
			</p>
            <p>
				<label for="nombre">Nombre:</label>
				<input type="text" id="nombre" name="nombre" />
			</p>
            <p>
				<label for="apellidos">Apellidos:</label>
				<input type="text" id="apellidos" name="apellidos" />
			</p>
            <p>
				<label for="email">Email:</label>
				<input type="text" id="email" name="email" />
			</p>
            <p>
				<label for="telefono">Telefono:</label>
				<input type="text" id="telefono" name="telefono" />
			</p>
            <p>
				<label for="edad">Edad:</label>
				<input type="number" id="edad" name="edad" />
			</p>
            <p>
				<label for="sexo">Sexo:</label>
				<input type="text" id="sexo" name="sexo" />
			</p>
            <p>
				<label for="pericia">Pericia informatica:</label>
				<input type="number" id="pericia" name="pericia" />
			</p>
            <p>
				<label for="tiempo">Tiempo transcurrido en segundos:</label>
				<input type="number" id="tiempo" name="tiempo" />
			</p>
            <p>
				<label for="tarea_correcta">Tarea realizada correctamente:</label>
				<input type="text" id="tarea_correcta" name="tarea_correcta" />
			</p>
            <p>
				<label for="comentarios">Comentarios:</label>
				<input type="text" id="comentarios" name="comentarios" />
			</p>
            <p>
				<label for="mejora">Propuestas de mejora de la aplicación:</label>
				<input type="text" id="mejora" name="mejora" />
			</p>
            <p>
				<label for="valoracion">Valoración de la aplicación:</label>
				<input type="number" id="valoracion" name="valoracion" />
			</p>
            <button type="submit" name="insertarDatos">Insertar datos</button>
		</form>
    </section>

    <section> <a id="buscarDatos"></a>
        <h2>Buscar</h2>
        <p>Buscar por DNI</p>
        <form action='#' method='POST'>
            <p>
				<label for="buscarDni">DNI:</label>
				<input type="text" id="buscarDni" name="buscarDni" />
			</p>
            <button type="submit" name="buscarDatos">Buscar</button>
		</form>
    </section>

    <section> <a id="modificarDatos"></a>
        <h2>Modificar fila</h2>
        <p>Modificar los datos de una fila introduciendo DNI.</p>
        <p>El DNI no podrá ser modificado.</p>
        <form action='#' method='POST'>
            <p>
				<label for="modificarDni">DNI:</label>
				<input type="text" id="modificarDni" name="modificarDni" />
			</p>
            <p>
				<label for="modificarNombre">Nombre:</label>
				<input type="text" id="modificarNombre" name="modificarNombre" />
			</p>
            <p>
				<label for="modificarApellidos">Apellidos:</label>
				<input type="text" id="modificarApellidos" name="modificarApellidos" />
			</p>
            <p>
				<label for="modificarEmail">Email:</label>
				<input type="text" id="modificarEmail" name="modificarEmail" />
			</p>
            <p>
				<label for="modificarTelefono">Telefono:</label>
				<input type="text" id="modificarTelefono" name="modificarTelefono" />
			</p>
            <p>
				<label for="modificarEdad">Edad:</label>
				<input type="number" id="modificarEdad" name="modificarEdad" />
			</p>
            <p>
				<label for="modificarSexo">Sexo:</label>
				<input type="text" id="modificarSexo" name="modificarSexo" />
			</p>
            <p>
				<label for="modificarPericia">Pericia informatica:</label>
				<input type="number" id="modificarPericia" name="modificarPericia" />
			</p>
            <p>
				<label for="modificarTiempo">Tiempo transcurrido en segundos:</label>
				<input type="number" id="modificarTiempo" name="modificarTiempo" />
			</p>
            <p>
				<label for="modificarTarea">Tarea realizada correctamente:</label>
				<input type="text" id="modificarTarea" name="modificarTarea" />
			</p>
            <p>
				<label for="modificarComentarios">Comentarios:</label>
				<input type="text" id="modificarComentarios" name="modificarComentarios" />
			</p>
            <p>
				<label for="modificarMejora">Propuestas de mejora de la aplicación:</label>
				<input type="text" id="modificarMejora" name="modificarMejora" />
			</p>
            <p>
				<label for="modificarValoracion">Valoración de la aplicación:</label>
				<input type="number" id="modificarValoracion" name="modificarValoracion" />
			</p>
            <button type="submit" name="modificarDatos">Actualizar datos</button>
		</form>
    </section>

    <section> <a id="borrarDatos"></a>
        <h2>Borrar Datos</h2>
        <form action='#' method='POST'>
            <p>
				<label for="borrarDNI">DNI:</label>
				<input type="text" id="borrarDNI" name="borrarDNI" />
			</p>
            <button type="submit" name="borrarDatos">Borrar</button>
		</form>
    </section>

    <section> <a id="generarInforme"></a>
        <h2>Generar informe</h2>
        <form action='#' method='POST'>
            <button type="submit" name="generarInforme">Generar informe</button>
		</form>
    </section>

    <section> <a id="cargarDatos"></a>
        <h2>Importar datos</h2>
        <p>Selecciona un archivo CSV para importar sus datos.</p>
        <form action='#' method='POST' enctype='multipart/form-data'>
            <p>
				<label for="file">Selecciona el archivo:</label>
				<input type="file" id="file" name="file" />
			</p>
            <button type="submit" name="cargarDatos">Cargar datos</button>
		</form>
    </section>

    <section> <a id="exportarDatos"></a>
        <h2>Exportar datos</h2>
        <form action='#' method='POST'>
            <button type="submit" name="exportarDatos">Exportar datos</button>
		</form>
    </section>


</body>
</html>