<?php

session_start();

class PilaLIFO {
    protected $pila;

    public function __construct(){
        $this->pila = array();
    }
    
    public function apilar($valor) {
        array_push($this->pila, $valor);
    }

    public function desapilar() {
        return array_pop($this->pila);
    }

    public function show(){
        $res = "";
        $size = $this->size();
        foreach ($this->pila as $i){
            $res .= $size-- . "\t\t" . $i . "\n";
        }

        return $res;
    } 

    public function limpiar(){
        $size = $this->size();
        for ($i = 0; $i < $size; $i++){
            array_pop($this->pila);
        }
    }

    public function size() {
        return count($this->pila);
    }
    
}
class CalculadoraRPN{

    protected $prepantalla;
    protected $prepantalla_aux;
    protected $pila;

    public function __construct(){
        $this->prepantalla = "";
        $this->prepantalla_aux = "";
        $this->pila = new PilaLIFO();
    }

    
    public function digitos($n){
        $this->prepantalla_aux .= $n;
        $this->prepantalla = $this->prepantalla_aux;
    }

    public function punto(){
        $this->prepantalla_aux .= ".";
        $this->prepantalla = $this->prepantalla_aux;
    }


    // OPERACIONES ----------------------------------------------------

    public function suma(){
        if ($this->pila->size() >= 2){
            $op2 = $this->pila->desapilar();
            $op1 = $this->pila->desapilar();
            $result = floatval($op1) + floatval($op2);
            $this->pila->apilar($result);

            // this.mostrarPantalla();
        }        
    }

    public function resta(){
        if ($this->pila->size() >= 2){
            $op2 = $this->pila->desapilar();
            $op1 = $this->pila->desapilar();
            $result = floatval($op1) - floatval($op2);
            $this->pila->apilar($result);

            // this.mostrarPantalla();
        }        
    }

    public function multiplicacion(){
        if ($this->pila->size() >= 2){
            $op2 = $this->pila->desapilar();
            $op1 = $this->pila->desapilar();
            $result = floatval($op1) * floatval($op2);
            $this->pila->apilar($result);

            // this.mostrarPantalla();
        }
    }

    public function division(){
        if ($this->pila->size() >= 2){
            $op2 = $this->pila->desapilar();
            $op1 = $this->pila->desapilar();
            $result = floatval($op1) / floatval($op2);
            $this->pila->apilar($result);

            // this.mostrarPantalla();
        }
    }

    public function raiz(){
        if ($this->pila->size() >= 1){
            $op = $this->pila->desapilar();
            $res = sqrt($op);
            $this->pila->apilar($res);

            // this.mostrarPantalla();
        }
    }

    public function sin(){
        if ($this->pila->size() >= 1){
            $op = $this->pila->desapilar();
            $res = sin($op);
            $this->pila->apilar($res);

            // this.mostrarPantalla();
        }
    }

    public function cos(){
        if ($this->pila->size() >= 1){
            $op = $this->pila->desapilar();
            $res = cos($op);
            $this->pila->apilar($res);

            // this.mostrarPantalla();
        }
    }

    public function tan(){
        if ($this->pila->size() >= 1){
            $op = $this->pila->desapilar();
            $res = tan($op);
            $this->pila->apilar($res);

            // this.mostrarPantalla();
        }
    }

    public function arcoSeno(){
        if ($this->pila->size() >= 1){
            $op = $this->pila->desapilar();
            $res = asin($op);
            $this->pila->apilar($res);

            // this.mostrarPantalla();
        }
    }

    public function arcoCoseno(){
        if ($this->pila->size() >= 1){
            $op = $this->pila->desapilar();
            $res = acos($op);
            $this->pila->apilar($res);

            // this.mostrarPantalla();
        }
    }

    public function arcoTangente(){
        if ($this->pila->size() >= 1){
            $op = $this->pila->desapilar();
            $res = atan($op);
            $this->pila->apilar($res);

            // this.mostrarPantalla();
        }
    }

    public function borrar(){
        $this->CE();
        $this->pila->limpiar();
        // this.mostrarPantalla();
    }

    public function CE(){ //limpiar valores a introducir
        $this->prepantalla_aux = "";
        $this->prepantalla = $this->prepantalla_aux;
    }

    public function enter(){
        if ($this->prepantalla_aux != ""){
            $this->pila->apilar($this->prepantalla_aux);
            $this->CE();
        }
    }

    public function mostrarPantalla(){
        return $this->pila->show();
    }
    public function mostrarPrePantalla(){
        return $this->prepantalla;
    }
}

if(count($_POST)>0){
    $calc = $_SESSION['calculadora'];

    if(isset($_POST['/'])) $calc->division();
    if(isset($_POST['x'])) $calc->multiplicacion();
    if(isset($_POST['-'])) $calc->resta();
    if(isset($_POST['+'])) $calc->suma();
    if(isset($_POST['raiz'])) $calc->raiz();
    
    if(isset($_POST['numero7'])) $calc->digitos(7);
    if(isset($_POST['numero8'])) $calc->digitos(8);
    if(isset($_POST['numero9'])) $calc->digitos(9);
    if(isset($_POST['C'])) $calc->borrar();
    if(isset($_POST['CE'])) $calc->CE();

    if(isset($_POST['numero4'])) $calc->digitos(4);
    if(isset($_POST['numero5'])) $calc->digitos(5);
    if(isset($_POST['numero6'])) $calc->digitos(6);
    if(isset($_POST['sin'])) $calc->sin();
    if(isset($_POST['asin'])) $calc->arcoSeno();


    if(isset($_POST['numero1'])) $calc->digitos(1);
    if(isset($_POST['numero2'])) $calc->digitos(2);
    if(isset($_POST['numero3'])) $calc->digitos(3);
    if(isset($_POST['cos'])) $calc->cos();
    if(isset($_POST['acos'])) $calc->arcoCoseno();

    if(isset($_POST['numero0'])) $calc->digitos(0);
    if(isset($_POST['punto'])) $calc->punto();
    if(isset($_POST['ENTER'])) $calc->enter();
    if(isset($_POST['tan'])) $calc->tan();
    if(isset($_POST['atan'])) $calc->arcoTangente();


    $_SESSION['calculadora'] = $calc;
}

if(!isset($_SESSION['calculadora'])){
    $calculadoraRPN = new CalculadoraRPN();
    $_SESSION['calculadora'] = $calculadoraRPN;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Calculadora RPN</title>
    <meta name ="author" content ="Sara María Ramírez Pérez" />
    <!--Definición de la ventana gráfica-->
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" type="text/css" href="CalculadoraRPN.css" />
</head>
<body>

    <h1>Calculadora RPN</h1>
    <form action='#' method='POST'>
               
        <textarea id="p1" disabled title="pantalla"><?php echo $_SESSION['calculadora']->mostrarPantalla();?></textarea>
        <textarea id="p2" disabled title="prePantalla"><?php echo $_SESSION['calculadora']->mostrarPrePantalla();?></textarea>
        <!--(+,-,*,/), (sin, cos, tan, arcoSeno, ArcoCoseno y ArcoTangente)-->
        <label for="p1">HP</label>
        <label for="p2">Reverse Polish Notation</label>


        <button type="submit" name="/">/</button>
        <button type="submit" name="x">x</button>
        <button type="submit" name="-">-</button>
        <button type="submit" name="+">+</button>
        <button type="submit" name="raiz">&radic;</button>

        <button type="submit" name="numero7">7</button>
        <button type="submit" name="numero8">8</button>
        <button type="submit" name="numero9">9</button>
        <button type="submit" name="C">C</button>
        <button type="submit" name="CE">CE</button>

        <button type="submit" name="numero4">4</button>
        <button type="submit" name="numero5">5</button>
        <button type="submit" name="numero6">6</button>
        <button type="submit" name="sin">sin</button>
        <button type="submit" name="asin">asin</button>
        
        <button type="submit" name="numero1">1</button>
        <button type="submit" name="numero2">2</button>
        <button type="submit" name="numero3">3</button>
        <button type="submit" name="cos">cos</button>
        <button type="submit" name="acos">acos</button>

        <button type="submit" name="numero0">0</button>
        <button type="submit" name="punto">.</button>
        <button type="submit" name="ENTER">ENTER</button>
        <button type="submit" name="tan">tan</button>
        <button type="submit" name="atan">atan</button>
    </form>

</body>