<?php
require('vendor/autoload.php');
use App\Models\Tareas;
use App\Helper\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => DB::DRIVER_DB,
    'host' => DB::HOST_DB,
    'database' => DB::DATABASE_NAME,
    'username' => DB::USER_DB,
    'password' => DB::PASSWORD_DB,
    'charset' => DB::UTF_8_DB,
    'collation' => DB::COLLATION_UTF,
    'prefix' => DB::PREFIX,
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

if (isset($_FILES["file"]) && isset($_GET["ID"])) {
    $tarea=Tareas::find($_GET["ID"]);
    if ($tarea==null) {
        echo "Tarea Invalida.";
    }else{
        $dir="./graphql/tareas/".$tarea->Codigo."/".$_FILES["file"]["name"];
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $dir)) {
            echo "Archivo Subido.";
        } else {
            echo "Error al subir.";
        }
    }
}else{
    echo "Error en envio.";
}
?>
