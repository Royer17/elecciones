<?php

$conexion = mysqli_connect("localhost","root","","bdventas");

$query = $conexion->query("SELECT * FROM detalle_ingreso");

echo '<option value="0">Seleccione</option>';

while ( $row = $query->fetch_assoc() )
{
	echo '<option value="' . $row['iddetalle_ingreso']. '">' . $row['precio_venta'] . '</option>' . "\n";
}
