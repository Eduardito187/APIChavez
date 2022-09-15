<?php
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
require('mutations/cuentaMutations.php');
require('mutations/rangoMutations.php');
require('mutations/proveedorMutations.php');
require('mutations/sucursalMutations.php');
require('mutations/extintorMutations.php');
require('mutations/prioridadMutations.php');
require('mutations/seguimientoMutations.php');
require('mutations/controldiarioMutations.php');
require('mutations/empresaMutations.php');
require('mutations/EmpresaGuardiaSucursalMutations.php');
require('mutations/GuardiaMutations.php');
require('mutations/LoteMutations.php');
require('mutations/ProductoMutations.php');
require('mutations/RecepcionMutations.php');
require('mutations/TareasMutations.php');
require('mutations/ControlLlavez.php');
require('mutations/MensajeMutations.php');
require('mutations/AsistenciaMutation.php');
require('mutations/DiscosMutation.php');

$mutations=array();
$mutations+=$cuentaMutations;
$mutations+=$rangoMutations;
$mutations+=$proveedorMutations;
$mutations+=$sucursalMutations;
$mutations+=$extintorMutations;
$mutations+=$prioridadMutations;
$mutations+=$seguimientoMutations;
$mutations+=$controldiarioMutations;
$mutations+=$empresaMutations;
$mutations+=$EmpresaGuardiaSucursalMutations;
$mutations+=$GuardiaMutations;
$mutations+=$LoteMutations;
$mutations+=$ProductoMutations;
$mutations+=$RecepcionMutations;
$mutations+=$TareasMutations;
$mutations+=$ControlLlavez;
$mutations+=$MensajeMutations;
$mutations+=$AsistenciaMutation;
$mutations+=$DiscosMutation;

$rootMutation=new ObjectType([
    'name'=>'Mutation',
    'fields' => $mutations
]);
?>