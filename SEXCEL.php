<?php

require('vendor/autoload.php');
use App\Models\Sucursal;
use App\Helper\DB;
use Illuminate\Database\Capsule\Manager as Capsule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

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



$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();


$spreadsheet = $reader->load("test.xlsx");

$d=$spreadsheet->getSheet(0)->toArray();


$sheetData = $spreadsheet->getActiveSheet()->toArray();

$i=1;
unset($sheetData[0]);
foreach ($sheetData as $t) {
    if ($t[2]!=null && $t[2]!="") {
        $Sucursal=Sucursal::where('CodigoSucursal', $t[2])->first();
        if ($Sucursal!=null) {
            Sucursal::where('ID', $Sucursal->ID)->update([
                'Nombre'=>$t[1],
                'Region'=>$t[0]
            ]);
        }
    }
	$i++;
}
echo "FIN EXITOSO =>".count($sheetData)." Datos Insertados.";
?>
