<?php
include('../template/cabecera.php');
?>

<?php
$txtid = (isset($_POST['txtid']))?$_POST['txtid']:"";
$txtplaca = (isset($_POST['txtplaca']))?$_POST['txtplaca']:"";
$txtnombrep = (isset($_POST['txtnombrep']))?$_POST['txtnombrep']:"";
$txtimage = (isset($_FILES['txtimage']['name']))?$_FILES['txtimage']['name']:"";
$accion = (isset($_POST['accion']))?$_POST['accion']:"";


include('../config/bd.php');



switch($accion){
        case "Agregar":
            //INSERT INTO `vehiculo` (`id`, `idplaca`, `nombrepiloto`, `imagenc`) VALUES (NULL, 'C-799BRQ', 'KELVIN BELTRAN', 'image.jpg');
            $sentenciaSQL = $conexion ->prepare("INSERT INTO vehiculo(idplaca,nombrepiloto,imagenc) VALUES (:placa,:npiloto,:imagenca);");
            $sentenciaSQL ->bindParam(':placa', $txtplaca);
            $sentenciaSQL ->bindParam(':npiloto', $txtnombrep);

            $fecha = new Datetime();
            $nombreArchivo = ($txtimage!="")?$fecha->getTimestamp()."_".$_FILES["txtimage"]["name"]:"imagen.jpg";

            $tmpImagen = $_FILES["txtimage"]["tmp_name"];

            if($tmpImagen != ""){
                 move_uploaded_file($tmpImagen, "../../img/".$nombreArchivo);
            }


            $sentenciaSQL ->bindParam(':imagenca', $nombreArchivo);
            $sentenciaSQL ->execute();    
            header("Location:vehiculos.php");
            break;

            case "Modificar":
                $sentenciaSQL = $conexion ->prepare("UPDATE vehiculo SET idplaca= :placa, nombrepiloto=:npiloto where id=:id");
                $sentenciaSQL ->bindParam(':id', $txtid);
                $sentenciaSQL ->bindParam(':placa', $txtplaca);
                $sentenciaSQL ->bindParam(':npiloto', $txtnombrep);
               // $sentenciaSQL ->bindParam(':imagenca', $txtimage);
                $sentenciaSQL ->execute(); 
                
                if($txtimage != "")
                {
                    $fecha = new Datetime();
                    $nombreArchivo = ($txtimage!="")?$fecha->getTimestamp()."_".$_FILES["txtimage"]["name"]:"imagen.jpg";
                    $tmpImagen = $_FILES["txtimage"]["tmp_name"];

                    move_uploaded_file($tmpImagen, "../../img/".$nombreArchivo);

                    $sentenciaSQL = $conexion ->prepare("SELECT imagenc FROM vehiculo where id=:id");
                    $sentenciaSQL ->bindParam(':id', $txtid);
                    $sentenciaSQL ->execute();    
                    $lvehiculo = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
                    
                    if(isset($lvehiculo['imagenc']) &&  ($lvehiculo['imagenc']!= "imagen.jpg")){
                            if(file_exists("../../img/".$lvehiculo['imagenc'])){
    
                                unlink("../../img/".$lvehiculo['imagenc']);
                            }
    
                    }

                    $sentenciaSQL = $conexion ->prepare("UPDATE vehiculo SET imagenc=:imagenca where id=:id");
                    $sentenciaSQL ->bindParam(':id', $txtid);
                    $sentenciaSQL ->bindParam(':imagenca', $nombreArchivo);
                    $sentenciaSQL ->execute(); 

                }


            break;
            case "Cancelar":
                   header("Location:vehiculos.php");
            break;
            case "Seleccionar":
                $sentenciaSQL = $conexion ->prepare("SELECT * FROM vehiculo where id=:id");
                $sentenciaSQL ->bindParam(':id', $txtid);
                $sentenciaSQL ->execute();    
                $lvehiculo = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
                $txtplaca = $lvehiculo['idplaca'];
                $txtnombrep = $lvehiculo['nombrepiloto'];
                $txtimage = $lvehiculo['imagenc'];
            break;

            case "Borrar":
                $sentenciaSQL = $conexion ->prepare("SELECT imagenc FROM vehiculo where id=:id");
                $sentenciaSQL ->bindParam(':id', $txtid);
                $sentenciaSQL ->execute();    
                $lvehiculo = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
                
                if(isset($lvehiculo['imagenc']) &&  ($lvehiculo['imagenc']!= "imagen.jpg")){
                        if(file_exists("../../img/".$lvehiculo['imagenc'])){

                            unlink("../../img/".$lvehiculo['imagenc']);
                        }

                }
                $sentenciaSQL = $conexion ->prepare("DELETE FROM vehiculo WHERE id=:id ");
                $sentenciaSQL ->bindParam(':id', $txtid);
                $sentenciaSQL ->execute(); 
                header("Location:vehiculos.php");
            break;



}


$sentenciaSQL = $conexion ->prepare("SELECT * FROM vehiculo");
$sentenciaSQL ->execute();    
$listavehiculo = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


?>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos Vehiculos
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">


                <div class="form-group">
                    <label for="txtid">ID:</label>
                    <input type="text" required readonly class="form-control" value="<?php echo $txtid;?>" id="txtid"  name="txtid" placeholder="ID">
                </div>

                <div class="form-group">
                    <label for="txtplaca">Placa_Vehiculo:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtplaca; ?>" id="txtplaca" name="txtplaca" placeholder="C-999AAA">
                </div>

                <div class="form-group">
                    <label for="txtnombrep">Nombre Piloto:</label>
                    <input type="text" required class="form-control" value="<?php echo $txtnombrep ?>" id="txtnombrep" name="txtnombrep" placeholder="Nombre Piloto">
                </div>

                <div class="form-group">
                    <label for="txtimage">Imagen Camion</label>

</br>

                    <?php   if($txtimage!=""){ ?>

                        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtimage;?>" width="50" alt="" sizes="" srcset="">
                    <?php  }?>
    

                    <input type="file" class="form-control" id="txtimage" name="txtimage" placeholder="Nombre Piloto">
                </div>


                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" <?php echo ($accion == "Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar")?"disabled":"";?> value="Cancelar" class="btn btn-info">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="col-md-7">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Placa Vehiculo</th>
                <th>Nombre Piloto</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listavehiculo as $vehiculo) { ?>
            <tr>
                <td><?php echo $vehiculo['idplaca'];?></td>
                <td><?php echo $vehiculo['nombrepiloto'];?></td>
                <td>
                    
               <img  class="img-thumbnail rounded" src="../../img/<?php echo $vehiculo['imagenc'];?>" width="50" alt="" sizes="" srcset="">
                </td>
                <td>
                    
                seleccionar | Borrar
                
                <FORM method="POST">
                    <input type="hidden" name = "txtid" id = "txtid"  value="<?php echo $vehiculo['id']; ?>">
                    <input type="submit" name = "accion" value="Seleccionar" class="btn btn-primary">
                    <input type="submit" name = "accion" value="Borrar" class="btn btn-danger">

                </FORM>


                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>



</div>



</div>

<?php
include('../template/pie.php');
?>