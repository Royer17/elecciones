
<?php

$conexion = mysqli_connect("localhost","root","","ventas_test");

$el_continente = $_POST['continente'];

$query = $conexion->query("SELECT * FROM detalle_ingreso WHERE iddetalle_ingreso = $el_continente");

echo '<option value="0">Seleccione</option>';

while ( $row = $query->fetch_assoc() )
{
	echo '<option value="' . $row['precio_venta']. '">' . $row['precio_venta'] . '</option>' . "\n";
}
