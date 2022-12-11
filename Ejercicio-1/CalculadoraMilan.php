<?php

session_start();

class CalculadoraMilan{

    protected $memoria;
    protected $op1;
    protected $op2;
    protected $operador;
    protected $pantalla;
    protected $pantalla_aux;
    protected $eval;
    protected $lastOp;
    protected $lastOp1;
    protected $lastOp2;


    public function __construct(){
        $this->memoria= 0 ;

        $this->op1 = "";
        $this->op2 = "";
        $this->operador = "";
        $this->pantalla = "0";
        $this->pantalla_aux = "";
        $this->eval = false;

        $this->lastOp ="";
        $this->lastOp1 ="";
        $this->lastOp2 ="";
    }

    public function digitos($numero){
        if ($this->eval){ //operacion ya evaluada
            $this->pantalla_aux = "";
            $this->eval = false;
        }
        
        $this->pantalla_aux .= $numero;
        
        $this->pantalla = $this->pantalla_aux;
    }
    
    public function punto(){
        $this->pantalla_aux .= ".";
        $this->pantalla = $this->pantalla_aux;
    }

    // OPERACIONES -------------------------------------------------------------------------------

    public function suma(){
        if ($this->operador != ""){
            $this->op2 = $this->pantalla_aux;
            $this->pantalla_aux = eval("return " . $this->op1 . $this->operador . $this->op2 . ';'); 
            $this->op1 = $this->pantalla_aux;
            $this->op2 = "";
            $this->pantalla = $this->pantalla_aux;
        } else{
            $this->op1 = $this->pantalla_aux;
        }
        
        $this->eval = false;
        $this->pantalla_aux = "";
        $this->operador = "+";
    }

    public function resta(){
        if ($this->operador != ""){
            $this->op2 = $this->pantalla_aux;
            $this->pantalla_aux = eval("return " . $this->op1 . $this->operador . $this->op2 .';'); 
            $this->op1 = $this->pantalla_aux;
            $this->op2 = "";
            $this->pantalla = $this->pantalla_aux;
        } else{
            $this->op1 =  $this->pantalla_aux;
        }
       
        $this->eval = false;
        $this->pantalla_aux = "";
        $this->operador = "-";
    }

    public function multiplicacion(){
        if ($this->operador != ""){
            $this->op2 = $this->pantalla_aux;
            $this->pantalla_aux = eval("return " . $this->op1 . $this->operador . $this->op2 . ','); 
            $this->op1 = $this->pantalla_aux;
            $this->op2 = "";
            $this->pantalla = $this->pantalla_aux;
        } else{
            $this->op1 = $this->pantalla_aux;
        }
        
        $this->eval = false;
        $this->pantalla_aux = "";
        $this->operador = "*";
    }

    public function division(){
        if ($this->operador != ""){
            $this->op2 = $this->pantalla_aux;
            $this->pantalla_aux = eval("return " . $this->op1 . $this->operador . $this->op2 . ';'); 
            $this->op1 = $this->pantalla_aux;
            $this->op2 = "";
            $this->pantalla = $this->pantalla_aux;
        } else{
            $this->op1 =$this->pantalla_aux;
        }

        $this->eval = false;
        $this->pantalla_aux = "";
        $this->operador = "/";
    }

    public function masMenos(){
        if ( $this->pantalla_aux != "") {
            $this->pantalla_aux = eval("return " . $this->pantalla_aux . '*-1;');
            $this->pantalla = $this->pantalla_aux;
        }
    }
    
    public function raiz(){
        $this->pantalla_aux = eval("return " . $this->pantalla_aux**(1/2) . ';');
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }
    public function porcentaje(){
        if ($this->operador=="*" || $this->operador=="/"){
            $this->pantalla_aux =eval("return " . $this->pantalla_aux . '/100;');
        } else {
            $this->pantalla_aux = eval("return " . $this->pantalla_aux . '/100' . '*' . $this->op1 . ';');
        }

        $this->igual(); 
    }

    // MEMORIAS -------------------------------------------------------------------------------

    public function mrc(){
        $this->pantalla_aux = $this->memoria;
        $this->pantalla = $this->pantalla_aux;
        
        $this->memoria = 0;
        $this->eval = true;
    }

    public function mMenos(){
        $this->igual();
        $this->memoria = eval("return " . $this->memoria . '-' . $this->pantalla_aux . ';');
    }
    public function mMas(){
        $this->igual();
        $this->memoria = eval("return " . $this->memoria . '+' . $this->pantalla_aux . ';');
    }

    // BORRAR ------------------------------------------------------------------------------
    public function borrar(){
        $this->op1 = "";
        $this->op2 = "";
        $this->operador = "";
        $this->pantalla_aux = "";
        
        $this->pantalla_aux = "0";
        $this->pantalla = $this->pantalla_aux;
        $this->pantalla_aux = "";
    }

    public function CE(){
        $this->pantalla_aux = "0";
        $this->pantalla = $this->pantalla_aux;
        $this->pantalla_aux = "";
    }

    // IGUAL ----------------------------------

    public function igual(){
        if ($this->eval == false){
            $this->op2 = $this->pantalla_aux;
        } else{
            $this->op1 = $this->lastOp1;
            $this->op2 = $this->lastOp2;
            $this->operador = $this->lastOp;
        }
        
        try{
            $st = $this->op1 . $this->operador .  $this->op2 . ';';
            $this->pantalla_aux = eval("return $st");
        } catch(err){
            alert("Error = " . err);
            $this->pantalla_aux="0";
        }

        //save for the next igual
        $this->lastOp = $this->operador;
        $this->lastOp1 = $this->pantalla_aux;
        $this->lastOp2 = $this->op2;
        
    
        //TODO decimales : multiplicar por el numero de decimales y luego dividir
       
        $this->pantalla = $this->pantalla_aux;
        

        $this->eval = true;
        $this->op1 = "";
        $this->op2 = "";
        $this->operador = "";
    }

    public function mostrarPantalla(){
        return $this->pantalla;
    }

}

if(count($_POST)>0){
    $calc = $_SESSION['calculadora'];

    if(isset($_POST['C'])) $calc->borrar();
    if(isset($_POST['CE'])) $calc->CE();
    if(isset($_POST['raiz'])) $calc->raiz();
    if(isset($_POST['porcentaje'])) $calc->porcentaje();
    
    if(isset($_POST['mrc'])) $calc->mrc();
    if(isset($_POST['m-'])) $calc->mMenos();
    if(isset($_POST['m+'])) $calc->mMas();
    if(isset($_POST['/'])) $calc->division();

    if(isset($_POST['numero7'])) $calc->digitos(7);
    if(isset($_POST['numero8'])) $calc->digitos(8);
    if(isset($_POST['numero9'])) $calc->digitos(9);
    if(isset($_POST['x'])) $calc->multiplicacion();

    if(isset($_POST['numero4'])) $calc->digitos(4);
    if(isset($_POST['numero5'])) $calc->digitos(5);
    if(isset($_POST['numero6'])) $calc->digitos(6);
    if(isset($_POST['-'])) $calc->resta();

    if(isset($_POST['numero1'])) $calc->digitos(1);
    if(isset($_POST['numero2'])) $calc->digitos(2);
    if(isset($_POST['numero3'])) $calc->digitos(3);
    if(isset($_POST['+'])) $calc->suma();

    if(isset($_POST['numero0'])) $calc->digitos(0);
    if(isset($_POST['punto'])) $calc->punto();
    if(isset($_POST['igual'])) $calc->igual();
    if(isset($_POST['masMenos'])) $calc->masMenos();

    $_SESSION['calculadora'] = $calc;
}

if(!isset($_SESSION['calculadora'])){
    $calculadoraMilan = new CalculadoraMilan();
    $_SESSION['calculadora'] = $calculadoraMilan;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name ="author" content ="Sara María Ramírez Pérez" />
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />

    <title>Calculadora milán</title>
    
    <link rel="stylesheet" type="text/css" href="CalculadoraMilan.css" />
</head>
<body>
    <h1>Calculadora Básica</h1>
    
    <form action='#' method='POST'>
        
        <label for="Label">MILAN</label>
        <input type="text" id="Label" value="<?php echo $_SESSION['calculadora']->mostrarPantalla();?>" disabled>

        <button type="submit" name="C">C</button>
        <button type="submit" name="CE">CE</button>
        <button type="submit" name="raiz">&radic;</button>
        <button type="submit" name="porcentaje">%</button>

        <button type="submit" name="mrc">mrc</button>
        <button type="submit" name="m-">m-</button>
        <button type="submit" name="m+">m+</button>
        <button type="submit" name="/">/</button>

        <button type="submit" name="numero7">7</button>
        <button type="submit" name="numero8">8</button>
        <button type="submit" name="numero9">9</button>
        <button type="submit" name="x">x</button>

        <button type="submit" name="numero4">4</button>
        <button type="submit" name="numero5">5</button>
        <button type="submit" name="numero6">6</button>
        <button type="submit" name="-">-</button>

        <button type="submit" name="numero1">1</button>
        <button type="submit" name="numero2">2</button>
        <button type="submit" name="numero3">3</button>
        <button type="submit" name="+">+</button>

        <button type="submit" name="numero0">0</button>
        <button type="submit" name="punto">.</button>
        <button type="submit" name="igual">=</button>
        <button type="submit" name="masMenos">+/-</button>
    </form>

</body>
</html>