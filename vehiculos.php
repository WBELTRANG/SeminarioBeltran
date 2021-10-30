
<?php
include("template/cabecera.php")
?>

<?php
include("administrador/config/bd.php");

$sentenciaSQL = $conexion ->prepare("SELECT * FROM vehiculo");
$sentenciaSQL ->execute();    
$listavehiculo = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>


<?php
foreach($listavehiculo as $vehiculo){
?>

<div class="col-md-3">
<div class="card">
    <img class="card-img-top" src="./img/<?php echo $vehiculo['imagenc'];?>" alt="">
    <div class="card-body">
        <h4 class="card-title"><?php echo $vehiculo['idplaca'];?></h4>
        <a name="" id="" class="btn btn-primary" href="#" role="button">Ver mas</a>
    </div>
</div>
</div>
<?php
}
?>

<?php
include("template/pie.php")
?>