<?php

session_start();

class CalculadoraMilan{

    protected $memoria;
    protected $op1;
    protected $op2;
    protected $operador;
    protected $pantalla;
    protected $eval;
    protected $lastOp;
    protected $lastOp1;
    protected $lastOp2;


    public function __construct(){
        $this->memoria= 0 ;

        $this->op1 = "";
        $this->op2 = "";
        $this->operador = "";
        $this->pantalla = "";
        $this->eval = false;
        
        // document.addEventListener('keydown', this.cargarBotones.bind(this));

        $this->lastOp ="";
        $this->lastOp1 ="";
        $this->lastOp2 ="";
    }

    public function digitos($numero){
        if ($this->eval){ //operacion ya evaluada
            $this->pantalla = "";
            $this->eval = false;
            this.mostrarPantalla(); //TODO
        }
        
        $this->pantalla .= String($numero);
        
        this.mostrarPantalla();
    }
    
    public function punto(){
        $this->pantalla .= ".";
        this.mostrarPantalla();
    }

    // OPERACIONES -------------------------------------------------------------------------------

    public function suma(){
        if ($this->operador != ""){
            $this->op2 = new Number($this->pantalla);
            $this->pantalla = eval($this->op1 + $this->operador + $this->op2); 
            $this->op1 = new Number($this->pantalla);
            $this->op2 = "";
            this.mostrarPantalla();
        } else{
            $this->op1 = new Number ($this->pantalla);
            
        }
        
        $this->eval = false;
        $this->pantalla = "";
        $this->operador = "+";
    }

    public function resta(){
        if ($this->operador != ""){
            $this->op2 = new Number($this->pantalla);
            $this->pantalla = eval($this->op1 + $this->operador + $this->op2); 
            $this->op1 = new Number($this->pantalla);
            $this->op2 = "";
            this.mostrarPantalla();
        } else{
            $this->op1 =  new Number($this->pantalla);
        }
       
        $this->eval = false;
        $this->pantalla = "";
        $this->operador = "-";
    }

    public function multiplicacion(){
        if ($this->operador != ""){
            $this->op2 = new Number($this->pantalla);
            $this->pantalla = eval($this->op1 + $this->operador + $this->op2); 
            $this->op1 = new Number($this->pantalla);
            $this->op2 = "";
            this.mostrarPantalla();
        } else{
            $this->op1 = new Number ($this->pantalla);
        }
        
        $this->eval = false;
        $this->pantalla = "";
        $this->operador = "*";
    }

    public function division(){
        if ($this->operador != ""){
            $this->op2 = new Number($this->pantalla);
            $this->pantalla = eval($this->op1 + $this->operador + $this->op2); 
            $this->op1 = new Number($this->pantalla);
            $this->op2 = "";
            this.mostrarPantalla();
        } else{
            $this->op1 = new Number ($this->pantalla);
        }

        $this->eval = false;
        $this->pantalla = "";
        $this->operador = "/";
    }

    public function masMenos(){
        if ( $this-> != "") {
            $this->pantalla = eval( $this->pantalla + '*-1');
            this.mostrarPantalla();
        }
    }
    
    public function raiz(){
        $this->pantalla = eval(new Number($this->pantalla)**(1/2));
        this.mostrarPantalla();
        $this->eval = true;
    }
    public function porcentaje(){
        if ($this->operador=="*" || $this->operador=="/"){
            $this->pantalla =eval($this->pantalla + '/100');
        } else {
            $this->pantalla = eval(new Number($this->pantalla) + '/100' + '*' + $this->op1);
        }

        this.igual(); 
    }

    // MEMORIAS -------------------------------------------------------------------------------

    public function mrc(){
        $this->pantalla = $this->memoria;
        this.mostrarPantalla();
        
        $this->memoria = 0;
        $this->eval = true;
    }

    public functio mMenos(){
        this.igual();
        $this->memoria -= $this->pantalla;
    }
    public function mMas(){
        this.igual();
        $this->memoria .= $this->pantalla;
    }

    // BORRAR ------------------------------------------------------------------------------
    public function borrar(){
        $this->op1 = "";
        $this->op2 = "";
        $this->operador = "";
        $this->pantalla = "";
        
        $this->pantalla = "0";
        this.mostrarPantalla();
        $this->pantalla = "";
    }

    public function CE(){
        $this->pantalla = "0";
        this.mostrarPantalla();
        $this->pantalla = "";
    }

    // IGUAL ----------------------------------

    public function igual(){
        if ($this->eval == false){
            $this->op2 = new Number($this->pantalla);
        } else{
            $this->op1 = $this->lastOp1;
            $this->op2 = $this->lastOp2;
            $this->operador = $this->lastOp;
        }
        
        try{
            $this->pantalla = eval($this->op1 + $this->operador + '(' + $this->op2 +')');
        } catch(err){
            alert("Error = " + err);
            $this->pantalla="0";
        }

        //save for the next igual
        $this->lastOp = $this->operador;
        $this->lastOp1 = $this->pantalla;
        $this->lastOp2 = $this->op2;
        
    
        //TODO decimales : multiplicar por el numero de decimales y luego dividir
        $this->pantalla = new Number($this->pantalla);
        this.mostrarPantalla();

        $this->eval = true;
        $this->op1 = "";
        $this->op2 = "";
        $this->operador = "";
    }

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
    
    <form target="#" action="POST">
        
        <label for="Label">MILAN</label>
        <input type="text" id="Label" value ="<?$echo calculadora.getPantalla()?>" disabled>

        <input type="button" value="C" onclick="calculadora.borrar()"> <!--C=Borrar-->
        <input type="button" value="CE" onclick="calculadora.CE()"> <!--CE-->
        <input type="button" value="&radic;" onclick="calculadora.raiz()"> <!--RAÍZ CUADRADA-->
        <input type="button" value="%" onclick="calculadora.porcentaje()">

        <input type="button" value="mrc" onclick="calculadora.mrc()">
        <input type="button" value="m-" onclick="calculadora.mMenos()"> 
        <input type="button" value="m+" onclick="calculadora.mMas()">
        <input type="button" value="/" onclick="calculadora.division()">

        <input type="button" value="7" onclick="calculadora.digitos(7)">
        <input type="button" value="8" onclick="calculadora.digitos(8)">
        <input type="button" value="9" onclick="calculadora.digitos(9)">
        <input type="button" value="x" onclick="calculadora.multiplicacion()">

        <input type="button" value="4" onclick="calculadora.digitos(4)">
        <input type="button" value="5" onclick="calculadora.digitos(5)">
        <input type="button" value="6" onclick="calculadora.digitos(6)">
        <input type="button" value="-" onclick="calculadora.resta()">

        <input type="button" value="1" onclick="calculadora.digitos(1)">
        <input type="button" value="2" onclick="calculadora.digitos(2)">
        <input type="button" value="3" onclick="calculadora.digitos(3)">
        <input type="button" value="+" onclick="calculadora.suma()">

        <input type="button" value="0" onclick="calculadora.digitos(0)">
        <input type="button" value="." onclick="calculadora.punto()">
        <input type="button" value="=" onclick="calculadora.igual()">
        <input type="button" value="+/-" onclick="calculadora.masMenos()"> 

         
    </form>

</body>
</html>