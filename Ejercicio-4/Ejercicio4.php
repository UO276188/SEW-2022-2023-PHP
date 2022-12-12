<?php

class Cobre{
    protected $access_key;
    protected $endpoint;
    protected $base;
    protected $symbols;

    protected $CAD;
    protected $EUR;
    protected $GBP;
    protected $USD; 
    protected $fecha;

    public function __construct(){
        $this->access_key = '1z51wqxzxckpduf2toz2y23d4zd5zr4d1id350rjtj0re0aj4u0yu0q1m9n1'; //API DE PRUEBA (SIL)
        $this->endpoint = 'latest';
        $this->base = 'XCU';
        $this->symbols = '';
        
        $url = 'https://metals-api.com/api/' . $this->endpoint . '?access_key=' . $this->access_key . '&base=' . $this->base . '&symbols=' . $this->symbols ;

        $data = file_get_contents($url); //devuelve un string
        $json = json_decode($data);

        $this->fecha = $json->date;

        $this->CAD = $json->rates->CAD;
        $this->EUR = $json->rates->EUR;
        $this->GBP = $json->rates->GBP;
        $this->USD = $json->rates->USD;
    }

    public function getFecha(){
        return $this->fecha;
    }

    public function canada(){
        return $this->CAD;
    }
    public function euro(){
        return $this->EUR;
    }
    public function dolar(){
        return $this->USD;
    }
    public function libra(){
        return $this->GBP;
    }

    public function toKilos($price){
        return $price*35.274;
    }
}


$convertidor = new Cobre();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name ="author" content ="Sara María Ramírez Pérez" />
    <meta name ="viewport" content ="width=device-width, initial-scale=1.0" />

    <title>SEW - Ejercicio 4</title>
    <link rel="stylesheet" type="text/css" href="Ejercicio4.css" />
</head>
<body>
    <h1>Precio del cobre</h1>
    <h2>Euros</h2>
    <p>Fecha: <?php echo $convertidor->getFecha(); ?> </p>
    <p>Precio por onza = <?php echo $convertidor->euro(); ?></p>
    <p>Precio por kilo = <?php echo $convertidor->toKilos($convertidor->euro()); ?></p>
    
    
    <h2>Dólares</h2>
    <p>Fecha: <?php echo $convertidor->getFecha(); ?> </p>
    <p>Precio por onza = <?php echo $convertidor->dolar(); ?></p>
    <p>Precio por kilo = <?php echo $convertidor->toKilos($convertidor->dolar()); ?></p>

    <h2>Libras esterlinas</h2>
    <p>Fecha: <?php echo $convertidor->getFecha(); ?> </p>
    <p>Precio por onza = <?php echo $convertidor->libra(); ?></p>
    <p>Precio por kilo = <?php echo $convertidor->toKilos($convertidor->libra()); ?></p>

    <h2>Dólares canadienses</h2>
    <p>Fecha: <?php echo $convertidor->getFecha(); ?> </p>
    <p>Precio por onza = <?php echo $convertidor->canada(); ?></p>
    <p>Precio por kilo = <?php echo $convertidor->toKilos($convertidor->canada()); ?></p>
        
</body>
</html>