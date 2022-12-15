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
class CalculadoraCientifica extends CalculadoraMilan{

    protected $parentesis;
    protected $degr;
    protected $hype;
    protected $upArrow;

    public function __construct(){
        parent::__construct();
        $this->parentesis = false;
        $this->degr = 0;  //0= degrees , 1=radianes, 2 = grad
        $this->hype = false; //if true -> hyperbolicas
        $this->upArrow = false; //If TRUE en vez de seno coseno y tangente -> arsin, arcos, atan
    }


    public function igual(){
        try{
            $this->pantalla_aux = eval("return " . $this->pantalla_aux .';');
           
        } catch(err){
            alert("Error = " . err);
            $this->pantalla_aux="0";
        }
        
        //TODO decimales : multiplicar por el numero de decimales y luego dividir
        $this->pantalla = $this->pantalla_aux;

        $this->eval = true;
        $this->op1 = "";
        $this->op2 = "";
        $this->operador = "";
    }


    public function suma(){
        $this->pantalla_aux .= "+";
        $this->pantalla = $this->pantalla_aux;
        $this->operador = "+";
        if ($this->eval){
            $this->eval = false;
        }
    }

    public function resta(){
        $this->pantalla_aux .= "-";
        $this->pantalla = $this->pantalla_aux;
        $this->operador = "-";
        if ($this->eval){
            $this->eval = false;
        }
    }

    public function multiplicacion(){
        $this->pantalla_aux .= "*";
        $this->pantalla = $this->pantalla_aux;
        $this->operador = "*";
        if ($this->eval){
            $this->eval = false;
        }
    }

    public function division(){
        $this->pantalla_aux .= "/";
        $this->pantalla = $this->pantalla_aux;
        $this->operador = "/";
        if ($this->eval){
            $this->eval = false;
        }
    }

    private function pickLast(){
        if ($this->operador!= ""){
            // $s = $this->pantalla_aux.split(/[\\-|\\+|\\*|\\-\\]/);
            // $length = $s.length;
            // $res = $s[length-1]; //numero a pasar por la funcion
            // $lengthNum = $res.length;
            // $newExp = $this->pantalla_aux.substring(0, $this->pantalla_aux.length-lengthNum);
            // $this->pantalla_aux = $newExp;
            // return $res;
            $s = preg_split('/[\\-|\\+|\\*|\\-\\]/', $this->pantalla_aux); //Devuelve un array
            $length = count($s); //tamaño del array
            $res = $s[$length-1]; //numero a pasar por la funcion
            $lengthNum = strlen($res);
            $newExp = substr($this->pantalla_aux, 0, strlen($this->pantalla_aux)-$lengthNum);
            $this->pantalla_aux = $newExp;
            return $res;
        }
        else {
            $newExp = eval("return " . $this->pantalla_aux . ";");
            $this->pantalla_aux = "";
            return $newExp;
        }
    }

    
    public function sin(){
        $number = $this->pickLast();
        if ($this->upArrow == false){
            if ($this->hype == false){
                $this->pantalla_aux .= sin($this->trigonometric($number));
            } else {
                $this->pantalla_aux .= sinh($this->trigonometric($number));
            }
        } else {
            if ($this->hype == false){
                $this->pantalla_aux .= asin($this->trigonometric($number));
            } else {
                $this->pantalla_aux .= asinh($this->trigonometric($number));
            }
        }
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }

    public function cos(){
        $number = $this->pickLast();
        if ($this->upArrow == false){
            if ($this->hype == false){
                $this->pantalla_aux .= cos($this->trigonometric($number));
            } else {
                $this->pantalla_aux .= cosh($this->trigonometric($number));
            }
        } else {
            if ($this->hype == false){
                $this->pantalla_aux .= acos($this->trigonometric($number));
            } else {
                $this->pantalla_aux .= acosh($this->trigonometric($number));
            }
        }
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }
    public function tan(){
        $number = $this->pickLast();
        if ($this->upArrow == false){
            if ($this->hype == false){
                $this->pantalla_aux .= tan($this->trigonometric($number));
            } else {
                $this->pantalla_aux .= tanh($this->trigonometric($number));
            }
        } else {
            if ($this->hype == false){
                $this->pantalla_aux .= atan($this->trigonometric($number));
            } else {
                $this->pantalla_aux .= atanh($this->trigonometric($number));
            }
        }
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }

    private function trigonometric($number){
        switch($this->degr){
            case 0: // radians -> DEG
                return ($number * (M_PI/180));
            case 1: //rad
                return ($number);
            case 2: //rad -> GRAD
            return ($number * (M_PI/200));
        }
    }



    public function log(){
        $number = $this->pickLast();
        $this->pantalla_aux .= log10($number);
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }
    public function exp(){
        $this->pantalla_aux .= "*10**";
        $this->pantalla = $this->pantalla_aux;
    }

    public function cuadrado(){
        $number = $this->pickLast();
        $this->pantalla_aux .= pow($number, 2);
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }

    public function pot10(){
        $number = $this->pickLast();
        $this->pantalla_aux .= pow(10, $number);
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }

    public function potencia(){
        $this->pantalla_aux .= "**";
        $this->pantalla = $this->pantalla_aux;
    }

    public function mod(){
        $this->pantalla_aux .= "%";
        $this->pantalla = $this->pantalla_aux;
    }


    public function pi(){
        $this->digitos(M_PI);
    }

    public function factorial(){
        $total = 1;
        $this->igual();
        for ($i=1 ; $i <= $this->pantalla_aux; $i++){
            $total = $total * $i;
        }
        $this->pantalla_aux = $total;
        $this->pantalla = $this->pantalla_aux;
        
    }

    public function abreParentesis(){
        if ($this->eval){
            $this->pantalla_aux = "";
            $this->eval = false;
        }
        $this->pantalla_aux .= "(";
        $this->pantalla = $this->pantalla_aux;
    }

    public function cierraParentesis(){
        $this->pantalla_aux .= ")";
        $this->pantalla = $this->pantalla_aux;
        $this->operador = "";
    }

    /* 
    deg(){
        switch(this.degr){
            case 0: //Deg to Rad
                this.degr = 1; 
                document.querySelector('input[type="button"][value="DEG"]').value = "RAD";
                break;
            case 1:  //Rad to Grad
                this.degr = 2; 
                document.querySelector('input[type="button"][value="RAD"]').value = "GRAD";
                break;
            case 2:  //Grad to DEG
                this.degr = 0; 
                document.querySelector('input[type="button"][value="GRAD"]').value = "DEG";
                break;
        }
    }
    hyp(){
        if (this.hype == false){ //añadir hype
            this.hype = true;
            if (this.upArrow == false){
                document.querySelector('input[type="button"][value="sin"]').value = "sinh";
                document.querySelector('input[type="button"][value="cos"]').value = "cosh";
                document.querySelector('input[type="button"][value="tan"]').value = "tanh";
            } else { //a
                document.querySelector('input[type="button"][value="asin"]').value = "asinh";
                document.querySelector('input[type="button"][value="acos"]').value = "acosh";
                document.querySelector('input[type="button"][value="atan"]').value = "atanh";
            }
        } else { //quitar hype            
            this.hype = false;
            if (this.upArrow == false){
                document.querySelector('input[type="button"][value="sinh"]').value = "sin";
                document.querySelector('input[type="button"][value="cosh"]').value = "cos";
                document.querySelector('input[type="button"][value="tanh"]').value = "tan";
            } else { //a
                document.querySelector('input[type="button"][value="asinh"]').value = "sin";
                document.querySelector('input[type="button"][value="acosh"]').value = "cos";
                document.querySelector('input[type="button"][value="atanh"]').value = "tan";
            }
        }
    }
    shift(){
        if (this.upArrow == false){ //añadir a
            this.upArrow = true;
            if (this.hype == false){
                document.querySelector('input[type="button"][value="sin"]').value = "asin";
                document.querySelector('input[type="button"][value="cos"]').value = "acos";
                document.querySelector('input[type="button"][value="tan"]').value = "atan";
            } else { //h
                document.querySelector('input[type="button"][value="sinh"]').value = "asinh";
                document.querySelector('input[type="button"][value="cosh"]').value = "acosh";
                document.querySelector('input[type="button"][value="tanh"]').value = "atanh";
            }
        } else { //quitar a
            this.upArrow = false;
            if (this.hype == false){
                document.querySelector('input[type="button"][value="asin"]').value = "sin";
                document.querySelector('input[type="button"][value="acos"]').value = "cos";
                document.querySelector('input[type="button"][value="atan"]').value = "tan";
            } else { //
                document.querySelector('input[type="button"][value="asinh"]').value = "sinh";
                document.querySelector('input[type="button"][value="acosh"]').value = "cosh";
                document.querySelector('input[type="button"][value="atanh"]').value = "tanh";
            }
        }
    }

    fe(){ //notacion cientifica 
        
        $number = $this->pickLast();
        $this->pantalla_aux .= $number.toExponential();
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }*/
    



    //MEMORIAS----------------
    public function mc(){
        $this->memoria = 0;
        $this->eval = true;
    }
    public function mr(){
        $this->pantalla_aux = $this->memoria;
        $this->pantalla = $this->pantalla_aux;
        $this->eval = true;
    }
    public function ms(){
        $this->memoria = $this->pantalla_aux;
        $this->eval = true;
    }

    //BORRADOS
    public function CE(){
       $number = $this->pickLast(); 
       $this->pantalla = $this->pantalla_aux;
    }

    public function borraFlecha(){
        if ($this->pantalla_aux != ""){
            $newExp = substr($this->pantalla_aux, 0, strlen($this->pantalla_aux)-1);
            $this->pantalla_aux = $newExp;
            $this->pantalla = $this->pantalla_aux;
        }
    }
}

if(count($_POST)>0){
    $calc = $_SESSION['calculadora'];

    // if(isset($_POST['DEG'])) $calc->deg();
    // if(isset($_POST['HYP'])) $calc->hyp();
    // if(isset($_POST['F-E'])) $calc->fe();


    if(isset($_POST['MC'])) $calc->mc();
    if(isset($_POST['MR'])) $calc->mr();
    if(isset($_POST['M+'])) $calc->mMas();
    if(isset($_POST['M-'])) $calc->mMenos();
    if(isset($_POST['MS'])) $calc->ms();

    if(isset($_POST['x^2'])) $calc->cuadrado();
    if(isset($_POST['x^y'])) $calc->potencia();
    if(isset($_POST['sin'])) $calc->sin();
    if(isset($_POST['cos'])) $calc->cos();
    if(isset($_POST['tan'])) $calc->tan();

    if(isset($_POST['raiz'])) $calc->raiz();
    if(isset($_POST['10^x'])) $calc->pot10();
    if(isset($_POST['log'])) $calc->log();
    if(isset($_POST['Exp'])) $calc->exp();
    if(isset($_POST['Mod'])) $calc->mod();

    // if(isset($_POST['shift'])) $calc->shift();
    if(isset($_POST['CE'])) $calc->CE();
    if(isset($_POST['C'])) $calc->borrar();
    if(isset($_POST['borraFlecha'])) $calc->borraFlecha();
    if(isset($_POST['/'])) $calc->division();

    if(isset($_POST['pi'])) $calc->pi();
    if(isset($_POST['numero7'])) $calc->digitos(7);
    if(isset($_POST['numero8'])) $calc->digitos(8);
    if(isset($_POST['numero9'])) $calc->digitos(9);
    if(isset($_POST['x'])) $calc->multiplicacion();

    if(isset($_POST['factorial'])) $calc->factorial(4);
    if(isset($_POST['numero4'])) $calc->digitos(4);
    if(isset($_POST['numero5'])) $calc->digitos(5);
    if(isset($_POST['numero6'])) $calc->digitos(6);
    if(isset($_POST['-'])) $calc->resta();
    
    if(isset($_POST['masMenos'])) $calc->masMenos();
    if(isset($_POST['numero1'])) $calc->digitos(1);
    if(isset($_POST['numero2'])) $calc->digitos(2);
    if(isset($_POST['numero3'])) $calc->digitos(3);
    if(isset($_POST['+'])) $calc->suma();


    if(isset($_POST['abreParentesis'])) $calc->abreParentesis();
    if(isset($_POST['cierraParentesis'])) $calc->cierraParentesis();
    if(isset($_POST['numero0'])) $calc->digitos(0);
    if(isset($_POST['punto'])) $calc->punto();
    if(isset($_POST['igual'])) $calc->igual();
    

    $_SESSION['calculadora'] = $calc;
}

if(!isset($_SESSION['calculadora'])){
    $calculadoraCientifica = new CalculadoraCientifica();
    $_SESSION['calculadora'] = $calculadoraCientifica;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name ="author" content ="Sara María Ramírez Pérez" />
    <!--Definición de la ventana gráfica-->
	<meta name ="viewport" content ="width=device-width, initial-scale=1.0" />

    <title>Calculadora científica</title>
    
    <link rel="stylesheet" type="text/css" href="CalculadoraCientifica.css" />
</head>
<body>
    <h1>Calculadora Científica</h1>
    
    <form action='#' method='POST'>
        <input type="text" id="Label" value = "<?php echo $_SESSION['calculadora']->mostrarPantalla();?>" disabled />
        
        <button type="submit" name="DEG">DEG</button>
        <button type="submit" name="HYP">HYP</button>
        <button type="submit" name="F-E">F-E</button>
        <label for="Label">Windows 10</label>


        <button type="submit" name="MC">MC</button> <!--Elimina cualquier numero almacenado en memoria-->
        <button type="submit" name="MR">MR</button> <!--Recupera el número almacenado en memoria. El número permanece en memoria.-->
        <button type="submit" name="M+">M+</button>
        <button type="submit" name="M-">M-</button>
        <button type="submit" name="MS">MS</button> <!--Almacena en memoria el número mostrado-->


        <button type="submit" name="x^2">x^2</button>
        <button type="submit" name="x^y">x^y</button>
        <button type="submit" name="sin">sin</button>
        <button type="submit" name="cos">cos</button>
        <button type="submit" name="tan">tan</button>

        
        <button type="submit" name="raiz">&radic;</button>
        <button type="submit" name="10^x">10^x</button>
        <button type="submit" name="log">log</button>
        <button type="submit" name="Exp">Exp</button>
        <button type="submit" name="Mod">Mod</button>


        <button type="submit" name="shift">&uarr;</button><!--Flecha arriba-->
        <button type="submit" name="CE">CE</button><!-- eliminas lo ultimo que has metido-->
        <button type="submit" name="C">C</button><!--C=Borrar todo-->
        <button type="submit" name="borraFlecha">←</button><!--Borra ultimo caracter-->
        <button type="submit" name="/">/</button>

        
        <button type="submit" name="pi">&pi;</button>
        <button type="submit" name="numero7">7</button>
        <button type="submit" name="numero8">8</button>
        <button type="submit" name="numero9">9</button>
        <button type="submit" name="x">x</button>


        <button type="submit" name="factorial">n!</button>
        <button type="submit" name="numero4">4</button>
        <button type="submit" name="numero5">5</button>
        <button type="submit" name="numero6">6</button>
        <button type="submit" name="-">-</button>

        
        <button type="submit" name="masMenos">+/-</button>
        <button type="submit" name="numero1">1</button>
        <button type="submit" name="numero2">2</button>
        <button type="submit" name="numero3">3</button>
        <button type="submit" name="+">+</button>


        <button type="submit" name="abreParentesis">(</button>
        <button type="submit" name="cierraParentesis">)</button>
        <button type="submit" name="numero0">0</button>
        <button type="submit" name="punto">.</button>
        <button type="submit" name="igual">=</button>
           
    </form>

</body>
</html>