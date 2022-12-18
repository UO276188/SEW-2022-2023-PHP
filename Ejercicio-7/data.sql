INSERT INTO cliente (dni, nombre, apellidos, telefono, direccion) VALUES
    ("11111111C", "Sara", "Ramirez Garcia", "142536789", "Aviles, Asturias"),
    ("22222222D", "Alberto", "Gonzalez Diaz", "475489623", "Oviedo, Asturias"),
    ("33333333F", "Andres", "Rodriguez Perez", "999856412", "Gijon, Asturias");

INSERT INTO moto (matricula, marca, modelo, año, cliente) VALUES 
    ("0675FJS", "Triumph", "Tiger955i", 2006, "11111111C"),
    ("7458DHV", "Honda", "CBF600f", 2004, "11111111C"),
    ("4865NWJ", "BMW", "R1200GS", 2020, "22222222D"),
    ("6589HKL", "BMW", "R1250RT", 2020, "33333333F");

INSERT INTO mecanico (dni, nombre, apellidos) VALUES   
    ("74586523F", "Carlos", "Rodriguez Gonzalez"),
    ("54862358H", "Pedro", "Bermudez Igual");

INSERT INTO rueda (codigo, fabricante, tipo, ancho, altura, estructura, diametro, carga, velocidad, precio) VALUES
    ("45df", "Michelin", "PilotRoad4", 160, 60, "R", 17, 69, "W", 135),
    ("8d45", "Michelin", "PilotRoad3", 110, 80, "R", 19, 59, "V", 106),
    ("g47h", "Michelin", "PilotPower", 180, 55, "R", 17, 73, "W", 84),
    ("68l4", "Michelin", "PilotSport", 120, 70, "R", 17, 75, "W", 90);

INSERT INTO sustitucion(fecha, moto, ruedaDelantera, ruedaTrasera, descripcion, mecanico, precio, estadoCambio, estadoPago) VALUES
    ("2022-09-12", "0675FJS", "8d45", "45df", "Desgaste", "54862358H", 289.2, "ACABADO", "PAGADO"),
    ("2022-09-25", "7458DHV", "68l4", "g47h", "Se necesita equilibrar", "54862358H", 208.8, "ACABADO", "PENDIENTE"),
    ("2022-12-14", "4865NWJ", "8d45", "g47h", "Rueda delantera torcida", "74586523F", 228, "PENDIENTE", "PENDIENTE");