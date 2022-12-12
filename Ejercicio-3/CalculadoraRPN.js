"use strict";
class PilaLIFO {
    constructor() {
        this.pila = new Array();
    }
    apilar(valor) {
        this.pila.push(valor);
    }

    desapilar() {
        return (this.pila.pop());
    }

    show(){
        var res = "";
        var size = this.size();
        for (var i in this.pila)
            res += size-- + "\t\t" + this.pila[i]+"\n";

        return res;
    } 

    limpiar(){
        var size = this.size();
        for (var i = 0; i < size; i++){
            this.pila.pop();
        }
    }

    size() {
        return this.pila.length;
    }
    
}
class CalculadoraRPN{

    constructor() {
        this.prepantalla = "";
        this.pila = new PilaLIFO();

        document.addEventListener('keydown', this.cargarBotones.bind(this));
    }


    cargarBotones(event){
        if (event.key >= '0' && event.key <= '9') {
            calculadora.digitos(event.key);
        }
        if (event.key === 'Enter') {
            calculadora.enter();
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
        if (event.code === 'KeyR') {    //R = raiz
            calculadora.raiz();
        }
    
        
        if (event.key === 'Delete') { // Supr
            calculadora.borrar(); //C
        }
        if (event.key === 'Backspace') {
            calculadora.CE();
        }

        if (event.code === 'KeyS') {    //S = seno
            calculadora.sin();
        }
        if (event.code === 'KeyC') {    //C = coseno
            calculadora.cos();
        }
        if (event.code === 'KeyT') {    //T = tangente 
            calculadora.tan();
        }
        if (event.code === 'KeyD') {    //D = arcoseno
            calculadora.arcoSeno();
        }
        if (event.code === 'KeyF') {    //C = arcocoseno
            calculadora.arcoCoseno();
        }
        if (event.code === 'KeyG') {    //G = arcotangente 
            calculadora.arcoTangente();
        }
    }

    
    digitos(n){
        this.prepantalla += n;
        this.mostrarPrePantalla();
    }

    punto(){
        this.prepantalla += ".";
        this.mostrarPrePantalla();
    }


    // OPERACIONES ----------------------------------------------------

    suma(){
        if (this.pila.size() >= 2){
            var op2 = this.pila.desapilar();
            var op1 = this.pila.desapilar();
            var result = parseFloat(op1) + parseFloat(op2);
            this.pila.apilar(result);

            this.mostrarPantalla();
        }        
    }

    resta(){
        if (this.pila.size() >= 2){
            var op2 = this.pila.desapilar();
            var op1 = this.pila.desapilar();
            var result = parseFloat(op1) - parseFloat(op2);
            this.pila.apilar(result);

            this.mostrarPantalla();
        }        
    }

    multiplicacion(){
        if (this.pila.size() >= 2){
            var op2 = this.pila.desapilar();
            var op1 = this.pila.desapilar();
            var result = parseFloat(op1) * parseFloat(op2);
            this.pila.apilar(result);

            this.mostrarPantalla();
        }
    }

    division(){
        if (this.pila.size() >= 2){
            var op2 = this.pila.desapilar();
            var op1 = this.pila.desapilar();
            var result = parseFloat(op1) / parseFloat(op2);
            this.pila.apilar(result);

            this.mostrarPantalla();
        }
    }

    raiz(){
        if (this.pila.size() >= 1){
            var op = this.pila.desapilar();
            var res = Math.sqrt(op);
            this.pila.apilar(res);

            this.mostrarPantalla();
        }
    }

    sin(){
        if (this.pila.size() >= 1){
            var op = this.pila.desapilar();
            var res = Math.sin(op);
            this.pila.apilar(res);

            this.mostrarPantalla();
        }
    }

    cos(){
        if (this.pila.size() >= 1){
            var op = this.pila.desapilar();
            var res = Math.cos(op);
            this.pila.apilar(res);

            this.mostrarPantalla();
        }
    }

    tan(){
        if (this.pila.size() >= 1){
            var op = this.pila.desapilar();
            var res = Math.tan(op);
            this.pila.apilar(res);

            this.mostrarPantalla();
        }
    }

    arcoSeno(){
        if (this.pila.size() >= 1){
            var op = this.pila.desapilar();
            var res = Math.asin(op);
            this.pila.apilar(res);

            this.mostrarPantalla();
        }
    }

    arcoCoseno(){
        if (this.pila.size() >= 1){
            var op = this.pila.desapilar();
            var res = Math.acos(op);
            this.pila.apilar(res);

            this.mostrarPantalla();
        }
    }

    arcoTangente(){
        if (this.pila.size() >= 1){
            var op = this.pila.desapilar();
            var res = Math.atan(op);
            this.pila.apilar(res);

            this.mostrarPantalla();
        }
    }



    borrar(){
        this.CE();
        this.pila.limpiar();
        this.mostrarPantalla();
    }

    CE(){ //limpiar valores a introducir
        this.prepantalla = "";
        this.mostrarPrePantalla();
    }





    enter(){
        if (this.prepantalla != ""){
            this.pila.apilar(this.prepantalla);
            this.CE();
            this.mostrarPantalla();
        }
    }

    mostrarPantalla(){
        document.querySelector('body > form > textarea[title="pantalla"]').value = this.pila.show();
    }
    mostrarPrePantalla(){
        document.querySelector('body > form > textarea[title="prePantalla"]').value = this.prepantalla;
    }
}

var calculadora = new CalculadoraRPN();

