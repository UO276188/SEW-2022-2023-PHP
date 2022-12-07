"use strict";
class Calculadora{

    constructor(){
        this.memoria= 0 ;

        
        this.op1 = "";
        this.op2 = "";
        this.operador = "";
        this.pantalla = "";
        this.eval = false;
        document.addEventListener('keydown', this.cargarBotones.bind(this));


        this.lastOp ="";
        this.lastOp1 ="";
        this.lastOp2 ="";
        
    }


    cargarBotones(event){
        if (event.key >= '0' && event.key <= '9') {
            calculadora.digitos(event.key);
        }
        if (event.key === 'Enter') {
            calculadora.igual();
        }
        
        
        if (event.key === '/') {
            calculadora.division();
        }
        if (event.key === '*') {
            calculadora.multiplicacion();
        }
        if (event.key === '-') {
            calculadora.resta();
        }
        if (event.key === '+') {
            calculadora.suma();
        }
        if (event.key === '.') {
            calculadora.punto();
        }
    
        
        if (event.key === 'Delete') { // Supr
            calculadora.borrar();
        }
        if (event.key === 'Backspace') {
            calculadora.CE();
        }

        //MRC
        if (event.code === 'KeyM') {//Tecla M -> MRC
            calculadora.mrc();
        }
        //M+
        if (event.code === 'KeyK') { //Tecla K -> M+
            calculadora.mMas();
        }
        //M-
        if (event.code === 'KeyL') { //Tecla L -> M-
            calculadora.mMenos();
        }

        //%
        if (event.key === 'KeyP') { //Tecla P -> %
            calculadora.porcentaje();
        }
        //Raiz
        if (event.code === 'KeyR') {
            calculadora.raiz();
        }

        //Mas/Menos
        if (event.code === 'KeyS') {
            calculadora.masMenos();
        }
    }

    
    digitos(numero){
        if (this.eval){ //operacion ya evaluada
            this.pantalla = "";
            this.eval = false;
            this.mostrarPantalla();
        }
        
        this.pantalla += String(numero);
        
        this.mostrarPantalla();
    }

    punto(){
        this.pantalla += ".";
        this.mostrarPantalla();
    }

    // OPERACIONES 

    suma(){
        if (this.operador != ""){
            this.op2 = new Number(this.pantalla);
            this.pantalla = eval(this.op1 + this.operador + this.op2); 
            this.op1 = new Number(this.pantalla);
            this.op2 = "";
            this.mostrarPantalla();
        } else{
            this.op1 = new Number (this.pantalla);
            
        }
        
        this.eval = false;
        this.pantalla = "";
        this.operador = "+";
        
    }

    resta(){
        if (this.operador != ""){
            this.op2 = new Number(this.pantalla);
            this.pantalla = eval(this.op1 + this.operador + this.op2); 
            this.op1 = new Number(this.pantalla);
            this.op2 = "";
            this.mostrarPantalla();
        } else{
            this.op1 =  new Number(this.pantalla);
        }
       
        this.eval = false;
        this.pantalla = "";
        this.operador = "-";
        
    }

    multiplicacion(){
        if (this.operador != ""){
            this.op2 = new Number(this.pantalla);
            this.pantalla = eval(this.op1 + this.operador + this.op2); 
            this.op1 = new Number(this.pantalla);
            this.op2 = "";
            this.mostrarPantalla();
        } else{
            this.op1 = new Number (this.pantalla);
            
        }
        
        this.eval = false;
        this.pantalla = "";
        this.operador = "*";

    }

    division(){
        if (this.operador != ""){
            this.op2 = new Number(this.pantalla);
            this.pantalla = eval(this.op1 + this.operador + this.op2); 
            this.op1 = new Number(this.pantalla);
            this.op2 = "";
            this.mostrarPantalla();
        } else{
            this.op1 = new Number (this.pantalla);
            
        }
        this.eval = false;
        this.pantalla = "";
        this.operador = "/";

    }

    mrc(){
        this.pantalla = this.memoria;
        this.mostrarPantalla();

        
        this.memoria = 0;
        this.eval = true;
    
    }

    mMenos(){
        this.igual();
        this.memoria -= this.pantalla;
    }
    mMas(){
        this.igual();
        this.memoria += this.pantalla;
    }


    borrar(){
        this.op1 = "";
        this.op2 = "";
        this.operador = "";
        this.pantalla = "";
        
        this.pantalla = "0";
        this.mostrarPantalla();
        this.pantalla = "";
    }

    CE(){
        this.pantalla = "0";
        this.mostrarPantalla();
        this.pantalla = "";
    }
    masMenos(){
       if (this.pantalla != "") {
        this.pantalla = eval(this.pantalla + '*-1');
        this.mostrarPantalla();
       }
    }
    raiz(){
        //TODO No usar math
        //this.pantalla = Math.sqrt(new Number(this.pantalla));
        this.pantalla = eval(new Number(this.pantalla)**(1/2));
        this.mostrarPantalla();
        this.eval = true;
    }
    porcentaje(){
        if (this.operador=="*" || this.operador=="/"){
            this.pantalla =eval(this.pantalla + '/100');
        } else {
            this.pantalla = eval(new Number(this.pantalla) + '/100' + '*' + this.op1);
        }

        this.igual();
        
    }

    igual(){
        if (this.eval == false){
            this.op2 = new Number(this.pantalla);
        } else{
            this.op1 = this.lastOp1;
            this.op2 = this.lastOp2;
            this.operador = this.lastOp;
        }
        
        try{
            this.pantalla = eval(this.op1 + this.operador + '(' +this.op2 +')');
        } catch(err){
            alert("Error = " + err);
            this.pantalla="0";
        }

        //save for the next igual
        this.lastOp = this.operador;
        this.lastOp1 = this.pantalla;
        this.lastOp2 = this.op2;
        
        

        //TODO decimales : multiplicar por el numero de decimales y luego dividir
        this.pantalla = new Number(this.pantalla);
        this.mostrarPantalla();

        this.eval = true;
        this.op1 = "";
        this.op2 = "";
        this.operador = "";
    }

    mostrarPantalla(){
        document.querySelector('body > form > input[type="text"]').value = this.pantalla;
    }
}

var calculadora = new Calculadora();