<?php
use App\Models\Cuenta;
use App\Models\Rango;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Extintor;
use App\Models\Prioridad;
use App\Models\Seguimiento;
use App\Models\EmpresaGuardia;
use App\Models\Guardia;
use App\Models\EmpresaGuardiaSucursal;
use App\Models\GuardiaSucursal;
use App\Models\ControlLLAVES;
use App\Models\ControlDiario;
use App\Models\Tareas;
use App\Models\Lotes;
use App\Models\Producto;
use App\Models\ProductoLote;
use App\Models\Recepcion;
use App\Models\Permisos;
use App\Models\Mensajes;
use App\Models\VacacionPersona;
use App\Models\VacacionesDias;
use App\Models\Trabajadores;
use App\Models\Control;
use App\Models\TablaControl;
use App\Models\ControlDiscos;
use App\Models\SalidaDiscos;
use App\Models\ExtintorDato;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

$CiteType=new ObjectType([
    'name' => 'Siguiente_Cite',
    'description' => 'Obtiene el ultimo CITE y sugiere 1 posterior.',
    'fields'=>[
        'CITE'=>Type::int()
    ]
]);
$ContadorType=new ObjectType([
    'name' => 'Contador_Tipo',
    'description' => 'Cantidad Numerica.',
    'fields'=>[
        'Cantidad'=>Type::int()
    ]
]);
$VacacionesDiasType=new ObjectType([
    'name' => 'Vacaciones_Dias',
    'description' => 'Dias de vacaciones en un año.',
    'fields'=>[
        'ID'=>Type::int(),
        'Dias'=>Type::int()
    ]
]);
$TrabajadoresType=new ObjectType([
    'name' => 'Trabajadores',
    'description' => 'Registro de los Trabajadores.',
    'fields' => function () use(&$TrabajadoresType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'Apellido'=>Type::string(),
            'CI'=>Type::string(),
            'Puesto'=>Type::string(),
            'FechaContratacion'=>Type::string(),
            'Supervisor'=>[
                "type" => $TrabajadoresType,
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $Trabajadores = Trabajadores::where('ID', $idPer)->with(['supervisor'])->first();
                    if ($Trabajadores->supervisor==null) {
                        return null;
                    }
                    return $Trabajadores->supervisor->toArray();
                }
            ]
        ];
    }
]);
$VacacionPersonaType=new ObjectType([
    'name' => 'Vacacion_Persona',
    'description' => 'Intermedia entre Persona y Vacaciones Dias.',
    'fields' => function () use(&$TrabajadoresType,&$VacacionesDiasType){
        return [
            'ID'=>Type::int(),
            'Vacacion'=>[
                "type" => $VacacionesDiasType,
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $VacacionPersona = VacacionPersona::where('ID', $idPer)->with(['vacaciones_dias'])->first();
                    return $VacacionPersona->vacaciones_dias->toArray();
                }
            ],
            'Trabajador'=>[
                "type" => $TrabajadoresType,
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $VacacionPersona = VacacionPersona::where('ID', $idPer)->with(['trabajador'])->first();
                    return $VacacionPersona->trabajador->toArray();
                }
            ],
            'Anho'=>Type::int()
        ];
    }
]);
$ControlDiscosType=new ObjectType([
    'name' => 'Control_de_Discos',
    'description' => 'Registra el secuestro de discos.',
    'fields' => function () use(&$sucursalType,&$SalidaDiscosType){
        return [
            'ID'=>Type::int(),
            'Fecha'=>Type::string(),
            'Sucursal'=>[
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlDiscos = ControlDiscos::where('ID', $id)->with(['sucursal'])->first();
                    if ($ControlDiscos->sucursal==null) {
                        return null;
                    }
                    return $ControlDiscos->sucursal->toArray();
                }
            ],
            'CantidadDiscos'=>Type::int(),
            'ReqFiscal'=>Type::string(),
            'FechaFinalizacion'=>Type::string(),
            'Salida'=>[
                "type" => $SalidaDiscosType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlDiscos = ControlDiscos::where('ID', $id)->with(['salida'])->first();
                    if ($ControlDiscos->salida==null) {
                        return null;
                    }
                    return $ControlDiscos->salida->toArray();
                }
            ]
        ];
    }
]);
$SalidaDiscosType=new ObjectType([
    'name' => 'Salida_de_un_Control_de_Disco',
    'description' => 'Registra la finalizacion de un secuestro de diso.',
    'fields' => function () use(&$ControlDiscosType){
        return [
            'ID'=>Type::int(),
            'Control'=>[
                "type" => $ControlDiscosType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $SalidaDiscos = SalidaDiscos::where('ID', $id)->with(['control'])->first();
                    if ($SalidaDiscos->control==null) {
                        return null;
                    }
                    return $SalidaDiscos->control->toArray();
                }
            ],
            'FechaEntrega'=>Type::string(),
            'Nombre'=>Type::string(),
            'Detalle'=>Type::string()
        ];
    }
]);
$ControlType=new ObjectType([
    'name' => 'Control',
    'description' => 'Registro de los controles de asistencia.',
    'fields' => function () use(&$TrabajadoresType){
        return [
            'ID'=>Type::int(),
            'Trabajador'=>[
                "type" => $TrabajadoresType,
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $Control = Control::where('ID', $idPer)->with(['trabajador'])->first();
                    if ($Control->trabajador==null) {
                        return null;
                    }
                    return $Control->trabajador->toArray();
                }
            ],
            'Fecha'=>Type::string(),
            'Tipo'=>Type::string(),
            'Motivo'=>Type::string()
        ];
    }
]);
$TablaControlType=new ObjectType([
    'name' => 'Tabla_Control',
    'description' => 'Tabla de los controles de asistencia.',
    'fields' => function () use(&$TrabajadoresType){
        return [
            'ID'=>Type::int(),
            'Trabajador'=>[
                "type" => $TrabajadoresType,
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $TablaControl = TablaControl::where('ID', $idPer)->with(['trabajador'])->first();
                    if ($TablaControl->trabajador==null) {
                        return null;
                    }
                    return $TablaControl->trabajador->toArray();
                }
            ],
            'Anho'=>Type::int(),
            'Libre'=>Type::int(),
            'BajaMedica'=>Type::int(),
            'Permisos'=>Type::int(),
            'Faltas'=>Type::int()
        ];
    }
]);
$validacionLoginType=new ObjectType([
    'name' => 'Validacion_de_Login',
    'description' => 'Se valida el inicio al sistema',
    'fields'=>function () use(&$cuentaType){
        return [
            'estado'=>Type::boolean(),
            'id_cuenta'=>Type::int()
        ];
    }
]);
$resType=new ObjectType([
    'name' => 'Respuesta',
    'description' => 'Respuesta de consulta.',
    'fields'=>[
        'Respuesta'=>Type::string()
    ]
]);
$boolType=new ObjectType([
    'name' => 'Bool',
    'description' => 'Dato de validacion',
    'fields'=>[
        'Respuesta'=>Type::boolean()
    ]
]);
$cuentaType=new ObjectType([
    'name' => 'Cuenta',
    'description' => 'Este es el tipo de dato Cuenta',
    'fields'=>function () use(&$rangoType,&$seguimientoType,&$ControlLLAVESType,&$TareasType,&$ProductoLoteType,&$RecepcionType){
        return [
            'ID'=>Type::int(),
            'usuario'=>Type::string(),
            'contra'=>Type::string(),
            'nombre'=>Type::string(),
            'foto'=>Type::string(),
            'DIR'=>Type::string(),
            'Rango' => [
                "type" => $rangoType,
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['rango'])->first();
                    return $cuenta->rango->toArray();
                }
            ],
            'Seguimientos'=>[
                "type" => Type::listOf($seguimientoType),
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['seguimiento'])->first();
                    return $cuenta->seguimiento->toArray();
                }
            ],
            'Seguimientos_Creados'=>[
                "type" => Type::listOf($seguimientoType),
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['seguimiento_creado'])->first();
                    return $cuenta->seguimiento_creado->toArray();
                }
            ],
            'Controles_Llaves'=>[
                "type" => Type::listOf($ControlLLAVESType),
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['control_llaves'])->first();
                    return $cuenta->control_llaves->toArray();
                }
            ],
            'Tareas_Solicitante'=>[
                "type" => Type::listOf($TareasType),
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['tareas_solicitante'])->first();
                    return $cuenta->tareas_solicitante->toArray();
                }
            ],
            'Tareas_Responsable'=>[
                "type" => Type::listOf($TareasType),
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['tareas_responsable'])->first();
                    return $cuenta->tareas_responsable->toArray();
                }
            ],
            'Producto_Lote'=>[
                "type" => Type::listOf($ProductoLoteType),
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['producto_lote'])->first();
                    return $cuenta->producto_lote->toArray();
                }
            ],
            'Recepcion'=>[
                "type" => Type::listOf($RecepcionType),
                "resolve" => function ($root, $args) {
                    $idPer = $root['ID'];
                    $cuenta = Cuenta::where('ID', $idPer)->with(['recepcion'])->first();
                    return $cuenta->recepcion->toArray();
                }
            ],
            'FechaCreado'=>Type::string(),
            'FechaActualizado'=>Type::string(),
            'FechaEliminado'=>Type::string()
        ];
    }
]);
$mensajesType=new ObjectType([
    'name' => 'Mensajes',
    'description' => 'Mensajes en la app',
    'fields'=>function () use(&$cuentaType){
        return [
            'ID'=>Type::int(),
            'De' => [
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $mensajes = Mensajes::where('ID', $id)->with(['De_msg'])->first();
                    return $mensajes->De_msg->toArray();
                }
            ],
            'Para' => [
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $mensajes = Mensajes::where('ID', $id)->with(['Para_msg'])->first();
                    return $mensajes->Para_msg->toArray();
                }
            ],
            'Texto'=>Type::string(),
            'Fecha'=>Type::string(),
            'Leido'=>Type::boolean(),
            'F_Leido'=>Type::string()
        ];
    }
]);
$rangoType=new ObjectType([
    'name' => 'Rango',
    'description' => 'Este es Rango de la Cuenta',
    'fields' => function () use(&$cuentaType,&$permisosType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'Cuentas' => [
                "type" => Type::listOf($cuentaType),
                "resolve" => function ($root, $args) {
                    $idRango = $root['ID'];
                    $rango = Rango::where('ID', $idRango)->with(['cuenta'])->first();
                    return $rango->cuenta->toArray();
                }
            ],
            'Permisos' => [
                "type" => $permisosType,
                "resolve" => function ($root, $args) {
                    $idRango = $root['ID'];
                    $rango = Rango::where('ID', $idRango)->with(['permisos'])->first();
                    if ($rango->permisos==null) {
                        return null;
                    }
                    return $rango->permisos->toArray();
                }
            ]
        ];
    }
]);
$permisosType=new ObjectType([
    'name' => 'Permisos',
    'description' => 'Permisos de un rango.',
    'fields' => function () use(&$rangoType){
        return [
            'ID'=>Type::int(),
            'Rango' => [
                "type" => $rangoType,
                "resolve" => function ($root, $args) {
                    $idPermisos = $root['ID'];
                    $permisos = Permisos::where('ID', $idPermisos)->with(['rango'])->first();
                    if ($permisos->rango==null) {
                        return null;
                    }
                    return $permisos->rango->toArray();
                }
            ],
            'P1'=>Type::boolean(),
            'P2'=>Type::boolean(),
            'P3'=>Type::boolean(),
            'P4'=>Type::boolean(),
            'P5'=>Type::boolean(),
            'P6'=>Type::boolean(),
            'P7'=>Type::boolean(),
            'P8'=>Type::boolean(),
            'P9'=>Type::boolean(),
            'P10'=>Type::boolean(),
            'P11'=>Type::boolean(),
            'P12'=>Type::boolean(),
            'P13'=>Type::boolean(),
            'P14'=>Type::boolean(),
            'P15'=>Type::boolean(),
            'P16'=>Type::boolean(),
            'P17'=>Type::boolean(),
            'P18'=>Type::boolean(),
            'P19'=>Type::boolean(),
            'P20'=>Type::boolean(),
            'P21'=>Type::boolean(),
            'P22'=>Type::boolean(),
            'P23'=>Type::boolean(),
            'P24'=>Type::boolean(),
            'P25'=>Type::boolean(),
            'P26'=>Type::boolean(),
            'P27'=>Type::boolean(),
            'P28'=>Type::boolean(),
            'P29'=>Type::boolean(),
            'P30'=>Type::boolean(),
            'P31'=>Type::boolean(),
            'P32'=>Type::boolean(),
            'P33'=>Type::boolean(),
            'P34'=>Type::boolean(),
            'P35'=>Type::boolean(),
            'P36'=>Type::boolean(),
            'P37'=>Type::boolean(),
            'P38'=>Type::boolean(),
            'P39'=>Type::boolean(),
            'P40'=>Type::boolean(),
            'P41'=>Type::boolean(),
            'P42'=>Type::boolean(),
            'P43'=>Type::boolean(),
            'P44'=>Type::boolean(),
            'P45'=>Type::boolean(),
            'P46'=>Type::boolean(),
            'P47'=>Type::boolean(),
            'P48'=>Type::boolean(),
            'P49'=>Type::boolean(),
            'P50'=>Type::boolean(),
            'P51'=>Type::boolean(),
            'P52'=>Type::boolean(),
            'P53'=>Type::boolean(),
            'P54'=>Type::boolean(),
            'P55'=>Type::boolean(),
            'P56'=>Type::boolean(),
            'P57'=>Type::boolean(),
            'P58'=>Type::boolean(),
            'P59'=>Type::boolean(),
            'P60'=>Type::boolean(),
            'P61'=>Type::boolean()
        ];
    }
]);
$proveedorType=new ObjectType([
    'name' => 'Proveedor',
    'description' => 'Son los proveedores de FCH',
    'fields' => function () use(&$extintorType,&$ProductoType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'Telefono'=>Type::string(),
            'Direccion'=>Type::string(),
            'Correo'=>Type::string(),
            'Extintores'=>[
                "type" => Type::listOf($extintorType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Proveedor = Proveedor::where('ID', $idPri)->with(['extintor'])->first();
                    return $Proveedor->extintor->toArray();
                }
            ],
            'Productos'=>[
                "type" => Type::listOf($ProductoType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Proveedor = Proveedor::where('ID', $idPri)->with(['producto'])->first();
                    return $Proveedor->producto->toArray();
                }
            ]
        ];
    }
]);
$sucursalType=new ObjectType([
    'name' => 'Sucursal',
    'description' => 'Sucursales Chavez',
    'fields' => function () use(&$extintorType,&$seguimientoType,&$empresaguardiasucursalType,&$ControlLLAVESType,&$ControlDiarioType,&$TareasType,&$ProductoLoteType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'CodigoSucursal'=>Type::string(),
            'Telefono'=>Type::string(),
            'Direccion'=>Type::string(),
            'TelfInterno'=>Type::string(),
            'Correo'=>Type::string(),
            'Region'=>Type::string(),
            'Extintor'=>[
                "type" => Type::listOf($extintorType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Sucursal = Sucursal::where('ID', $idPri)->with(['extintor'])->first();
                    return $Sucursal->extintor->toArray();
                }
            ],
            'Seguimiento'=>[
                "type" => Type::listOf($seguimientoType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Sucursal = Sucursal::where('ID', $idPri)->with(['seguimiento'])->first();
                    return $Sucursal->seguimiento->toArray();
                }
            ],
            'Guardias'=>[
                "type" => Type::listOf($empresaguardiasucursalType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Sucursal = Sucursal::where('ID', $idPri)->with(['guardias'])->first();
                    return $Sucursal->guardias->toArray();
                }
            ],
            'Control_Llaves'=>[
                "type" => Type::listOf($ControlLLAVESType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Sucursal = Sucursal::where('ID', $idPri)->with(['control_llaves'])->first();
                    return $Sucursal->control_llaves->toArray();
                }
            ],
            'Control_Diarios'=>[
                "type" => Type::listOf($ControlDiarioType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Sucursal = Sucursal::where('ID', $idPri)->with(['control_diario'])->first();
                    return $Sucursal->control_diario->toArray();
                }
            ],
            'Tareas'=>[
                "type" => Type::listOf($TareasType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Sucursal = Sucursal::where('ID', $idPri)->with(['tareas'])->first();
                    return $Sucursal->tareas->toArray();
                }
            ],
            'Producto_Lote'=>[
                "type" => Type::listOf($ProductoLoteType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Sucursal = Sucursal::where('ID', $idPri)->with(['producto_lote'])->first();
                    return $Sucursal->producto_lote->toArray();
                }
            ]
        ];
    }
]);
$extintorDatoType=new ObjectType([
    'name' => 'Extintor',
    'description' => 'Informacion del Extintor',
    'fields'=>function () use(&$extintorType){
        return [
            'ID'=>Type::int(),
            'Codigo'=>Type::int(),
            'PH'=>Type::int(),
            'Peso'=>Type::string(),
            'Recargo'=>Type::string(),
            'Observacion'=>Type::string(),
            'Extintores' => [
                "type" => $extintorType,
                "resolve" => function ($root, $args) {
                    $idExti = $root['ID'];
                    $ExtintorDato = ExtintorDato::where('ID', $idExti)->with(['extintor'])->first();
                    return $ExtintorDato->extintor->toArray();
                }
            ]
        ];
    }
]);
$extintorType=new ObjectType([
    'name' => 'Extintor_Lote',
    'description' => 'Informacion del Extintor Lote',
    'fields'=>function () use(&$proveedorType,&$sucursalType,&$extintorDatoType){
        return [
            'ID'=>Type::int(),
            'Fecha'=>Type::string(),
            'Tipo'=>Type::string(),
            'Cantidad'=>Type::int(),
            'Sucursal' => [
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $idExti = $root['ID'];
                    $Extintor = Extintor::where('ID', $idExti)->with(['sucursal'])->first();
                    return $Extintor->sucursal->toArray();
                }
            ],
            'Proveedor' => [
                "type" => $proveedorType,
                "resolve" => function ($root, $args) {
                    $idpro = $root['ID'];
                    $Extintor = Extintor::where('ID', $idpro)->with(['proveedor'])->first();
                    return $Extintor->proveedor->toArray();
                }
            ],
            'Extintores'=>[
                "type" => Type::listOf($extintorDatoType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Extintor = Extintor::where('ID', $id)->with(['extintores'])->first();
                    return $Extintor->extintores->toArray();
                }
            ],
            'Creacion'=>Type::string()
        ];
    }
]);
$prioridadType=new ObjectType([
    'name' => 'Priordad',
    'description' => 'Tipo de urgencias',
    'fields' =>function () use(&$seguimientoType,&$TareasType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'Descripcion'=>Type::string(),
            'Seguimientos' => [
                "type" => Type::listOf($seguimientoType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Prioridad = Prioridad::where('ID', $idPri)->with(['seguimiento'])->first();
                    return $Prioridad->seguimiento->toArray();
                }
            ],
            'Tareas' => [
                "type" => Type::listOf($TareasType),
                "resolve" => function ($root, $args) {
                    $idPri = $root['ID'];
                    $Prioridad = Prioridad::where('ID', $idPri)->with(['tareas'])->first();
                    return $Prioridad->tareas->toArray();
                }
            ]
        ];
    }
]);
$seguimientoType=new ObjectType([
    'name' => 'Seguimiento',
    'description' => 'Seguimiento Chavez',
    'fields' => function () use(&$sucursalType,&$cuentaType,&$prioridadType){
        return [
            'ID'=>Type::int(),
            'Codigo'=>Type::string(),
            'FechaCreacion'=>Type::string(),
            'FechaInicio'=>Type::string(),
            'FechaFin'=>Type::string(),
            'Solicitante'=>Type::string(),
            'Descripcion'=>Type::string(),
            'Autorizacion'=>Type::string(),
            'Carpeta'=>Type::string(),
            'Conclusion'=>Type::string(),
            'Sucursal' => [
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Seguimiento = Seguimiento::where('ID', $id)->with(['sucursal'])->first();
                    return $Seguimiento->sucursal->toArray();
                }
            ],
            'Responsable' => [
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Seguimiento = Seguimiento::where('ID', $id)->with(['responsable'])->first();
                    if ($Seguimiento->responsable==null){
                        return null;
                    }
                    return $Seguimiento->responsable->toArray();
                }
            ],
            'Prioridad' => [
                "type" => $prioridadType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Seguimiento = Seguimiento::where('ID', $id)->with(['prioridad'])->first();
                    return $Seguimiento->prioridad->toArray();
                }
            ],
            'Estado'=>Type::string(),
            'Creador' => [
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Seguimiento = Seguimiento::where('ID', $id)->with(['creador'])->first();
                    return $Seguimiento->creador->toArray();
                }
            ],
            'Eliminado'=>Type::boolean(),
            'FechaEliminado'=>Type::string(),
            'Tipo'=>Type::string(),
            'FechaAsignado'=>Type::string()
        ];
    }
]);
$empresaGuardiaType=new ObjectType([
    'name' => 'Empresa_de_Guardias',
    'description' => 'Servicio de Guardias',
    'fields' => function () use(&$empresaguardiasucursalType,&$ControlDiarioType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'Telefono'=>Type::string(),
            'Direccion'=>Type::string(),
            'Correo'=>Type::string(),
            'Supervisores'=>Type::string(),
            'Empresas_Sucursales'=>[
                "type" => Type::listOf($empresaguardiasucursalType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = EmpresaGuardia::where('ID', $id)->with(['empresa_sucursal'])->first();
                    return $a->empresa_sucursal->toArray();
                }
            ],
            'Controles'=>[
                "type" => Type::listOf($ControlDiarioType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = EmpresaGuardia::where('ID', $id)->with(['control_diario'])->first();
                    return $a->control_diario->toArray();
                }
            ]
        ];
    }
]);
$guardiaType=new ObjectType([
    'name' => 'Guardia',
    'description' => 'Informacion de Guardias',
    'fields' => function () use(&$guardiasucursalType,&$ControlDiarioType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'Telefono'=>Type::string(),
            'Precio'=>Type::int(),
            'Controles'=>[
                "type" => Type::listOf($ControlDiarioType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = Guardia::where('ID', $id)->with(['control_diario'])->first();
                    return $a->control_diario->toArray();
                }
            ],
            'Guardia_Sucursal'=>[
                "type" => $guardiasucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = Guardia::where('ID', $id)->with(['guardia_sucursal'])->first();
                    if ($a->guardia_sucursal==null) {
                        return null;
                    }
                    return $a->guardia_sucursal;
                }
            ]
        ];
    }
]);
$empresaguardiasucursalType=new ObjectType([
    'name' => 'Empresa_Guardia_Sucursal',
    'description' => 'Relacion entre Empresa Guardia y Sucursal',
    'fields' => function () use(&$empresaGuardiaType,&$sucursalType,&$guardiasucursalType,&$ControlDiarioType){
        return [
            'ID'=>Type::int(),
            'Empresa'=>[
                "type" => $empresaGuardiaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = EmpresaGuardiaSucursal::where('ID', $id)->with(['empresa'])->first();
                    return $a->empresa->toArray();
                }
            ],
            'Sucursal'=>[
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = EmpresaGuardiaSucursal::where('ID', $id)->with(['sucursal'])->first();
                    return $a->sucursal->toArray();
                }
            ],
            'Ingreso'=>Type::string(),
            'Salida'=>Type::string(),
            'Guardia_Sucursal'=>[
                "type" => Type::listOf($guardiasucursalType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = EmpresaGuardiaSucursal::where('ID', $id)->with(['guardia_sucursal'])->first();
                    return $a->guardia_sucursal->toArray();
                }
            ],
            'Controles'=>[
                "type" => Type::listOf($ControlDiarioType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $a = EmpresaGuardiaSucursal::where('ID', $id)->with(['control_diario'])->first();
                    return $a->control_diario->toArray();
                }
            ]
        ];
    }
]);
$guardiasucursalType=new ObjectType([
    'name' => 'Guardia_Sucursal',
    'description' => 'Guardia en relacion entre Empresa Guardia y Sucursal',
    'fields' => function () use(&$guardiaType,&$empresaguardiasucursalType){
        return [
            'ID'=>Type::int(),
            'Guardia'=>[
                "type" => $guardiaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $GuardiaSucursal = GuardiaSucursal::where('ID', $id)->with(['guardia'])->first();
                    return $GuardiaSucursal->guardia->toArray();
                }
            ],
            'GS'=>[
                "type" => $empresaguardiasucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $GuardiaSucursal = GuardiaSucursal::where('ID', $id)->with(['empresa_guardia_sucursal'])->first();
                    return $GuardiaSucursal->empresa_guardia_sucursal->toArray();
                }
            ]
        ];
    }
]);
$ControlLLAVESType=new ObjectType([
    'name' => 'Control_Llaves',
    'description' => 'Control de las Llaves',
    'fields' => function () use(&$sucursalType,&$cuentaType){
        return [
            'ID'=>Type::int(),
            'FechaInicio'=>Type::string(),
            'FechaDevolucion'=>Type::string(),
            'Sucursal'=>[
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlLLAVES = ControlLLAVES::where('ID', $id)->with(['sucursal'])->first();
                    return $ControlLLAVES->sucursal->toArray();
                }
            ],
            'Entrega'=>Type::string(),
            'Observacion'=>Type::string(),
            'Responsable'=>[
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlLLAVES = ControlLLAVES::where('ID', $id)->with(['responsable'])->first();
                    return $ControlLLAVES->responsable->toArray();
                }
            ]
        ];
    }
]);
$ControlDiarioType=new ObjectType([
    'name' => 'Control_Diarios',
    'description' => 'Controles diarios en CHAVEZ',
    'fields' => function () use(&$sucursalType,&$empresaGuardiaType,&$guardiaType,&$empresaguardiasucursalType){
        return [
            'ID'=>Type::int(),
            'Sucursal'=>[
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlDiario = ControlDiario::where('ID', $id)->with(['sucursal'])->first();
                    return $ControlDiario->sucursal->toArray();
                }
            ],
            'Empresa'=>[
                "type" => $empresaGuardiaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlDiario = ControlDiario::where('ID', $id)->with(['empresa_guardia'])->first();
                    return $ControlDiario->empresa_guardia->toArray();
                }
            ],
            'Tiempo'=>[
                "type"=>$empresaguardiasucursalType,
                "resolve"=>function($root,$args){
                    $id = $root['ID'];
                    $ControlDiario_s = ControlDiario::where('ID', $id)->with(['sucursal'])->first();
                    $ControlDiario_e = ControlDiario::where('ID', $id)->with(['empresa_guardia'])->first();
                    $sucursal = $ControlDiario_s->sucursal;
                    $empresa_guardia = $ControlDiario_e->empresa_guardia;
                    $EmpresaGuardiaSucursal=EmpresaGuardiaSucursal::where('Sucursal',$sucursal->ID)->
                    where('Empresa',$empresa_guardia->ID)->first();
                    return $EmpresaGuardiaSucursal;
                }
            ],
            'Tiempo_Llave'=>[
                "type" => $empresaguardiasucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlDiario = ControlDiario::where('ID', $id)->with(['obtener_gs'])->first();
                    return $ControlDiario->obtener_gs->toArray();
                }
            ],
            'Guardia'=>[
                "type" => $guardiaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ControlDiario = ControlDiario::where('ID', $id)->with(['guardia'])->first();
                    return $ControlDiario->guardia->toArray();
                }
            ],
            'Calculo'=>Type::int(),
            'Observacion'=>Type::string(),
            'Fecha'=>Type::string()
        ];
    }
]);
$TareasType=new ObjectType([
    'name' => 'Tareas',
    'description' => 'Actividades a realizar',
    'fields' => function () use(&$sucursalType,&$cuentaType,&$prioridadType,&$AreaType){
        return [
            'ID'=>Type::int(),
            'Codigo'=>Type::string(),
            'Sucursal'=>[
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Tareas = Tareas::where('ID', $id)->with(['sucursal'])->first();
                    if ($Tareas->sucursal==null) {
                        return null;
                    }
                    return $Tareas->sucursal->toArray();
                }
            ],
            'Detalle'=>Type::string(),
            'FechaInicio'=>Type::string(),
            'Conclusion'=>Type::string(),
            'Solicitante'=>[
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Tareas = Tareas::where('ID', $id)->with(['solicitante'])->first();
                    if ($Tareas->solicitante==null) {
                        return null;
                    }
                    return $Tareas->solicitante->toArray();
                }
            ],
            'Responsable'=>[
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Tareas = Tareas::where('ID', $id)->with(['responsable'])->first();
                    if ($Tareas->responsable==null) {
                        return null;
                    }
                    return $Tareas->responsable->toArray();
                }
            ],
            'Estado'=>Type::string(),
            'Prioridad'=>[
                "type" => $prioridadType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Tareas = Tareas::where('ID', $id)->with(['prioridad'])->first();
                    if ($Tareas->prioridad==null) {
                        return null;
                    }
                    return $Tareas->prioridad->toArray();
                }
            ],
            'Area'=>[
                "type" => $AreaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Tareas = Tareas::where('ID', $id)->with(['areas'])->first();
                    if ($Tareas->areas==null) {
                        return null;
                    }
                    return $Tareas->areas->toArray();
                }
            ],
            'Pospuesta'=>Type::string(),
            'FechaCreacion'=>Type::string(),
            'FechaPospuesta'=>Type::string(),
            'FechaFinalizacion'=>Type::string(),
            'Eliminado'=>Type::boolean(),
            'FechaEliminado'=>Type::string()
        ];
    }
]);
$AreaType=new ObjectType([
    'name' => 'AreaType',
    'description' => 'Seleccion de areas',
    'fields' => [
        'ID'=>Type::int(),
        'codArea'=>Type::string(),
        'Nombre'=>Type::string()
    ]
]);
$LotesType=new ObjectType([
    'name' => 'Lotes',
    'description' => 'Lote para almacenar Productos',
    'fields' => function () use(&$ProductoLoteType){
        return [
            'ID'=>Type::int(),
            'Nombre'=>Type::string(),
            'ProductoLotes'=>[
                "type" => Type::listOf($ProductoLoteType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Lotes = Lotes::where('ID', $id)->with(['producto_lote'])->first();
                    if ($Lotes->producto_lote==null) {
                        return null;
                    }
                    return $Lotes->producto_lote->toArray();
                }
            ]
        ];
    }
]);
$ProductoType=new ObjectType([
    'name' => 'Producto',
    'description' => 'Producto o artefanto para utilizar',
    'fields' => function () use(&$proveedorType,&$ProductoLoteType){
        return [
            'ID'=>Type::int(),
            'Factura'=>Type::string(),
            'Nombre'=>Type::string(),
            'Modelo'=>Type::string(),
            'Proveedor'=>[
                "type" => $proveedorType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Producto = Producto::where('ID', $id)->with(['proveedor'])->first();
                    if ($Producto->proveedor==null) {
                        return null;
                    }
                    return $Producto->proveedor->toArray();
                }
            ],
            'Costo'=>Type::float(),
            'Cantidad'=>Type::int(),
            'Producto_Lote'=>[
                "type" => Type::listOf($proveedorType),
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Producto = Producto::where('ID', $id)->with(['producto_lote'])->first();
                    if ($Producto->producto_lote==null) {
                        return null;
                    }
                    return $Producto->producto_lote->toArray();
                }
            ]
        ];
    }
]);
$ProductoLoteType=new ObjectType([
    'name' => 'Productos_en_Lote',
    'description' => 'Productos almacenados en Lote',
    'fields' => function () use(&$LotesType,&$ProductoType,&$cuentaType,&$sucursalType){
        return [
            'ID'=>Type::int(),
            'Lote'=>[
                "type" => $LotesType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ProductoLote = ProductoLote::where('ID', $id)->with(['lote'])->first();
                    if ($ProductoLote->lote==null) {
                        return null;
                    }
                    return $ProductoLote->lote->toArray();
                }
            ],
            'Producto'=>[
                "type" => $ProductoType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ProductoLote = ProductoLote::where('ID', $id)->with(['producto'])->first();
                    if ($ProductoLote->producto==null) {
                        return null;
                    }
                    return $ProductoLote->producto->toArray();
                }
            ],
            'FechaSalida'=>Type::string(),
            'Responsable'=>[
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ProductoLote = ProductoLote::where('ID', $id)->with(['cuenta'])->first();
                    if ($ProductoLote->cuenta==null) {
                        return null;
                    }
                    return $ProductoLote->cuenta->toArray();
                }
            ],
            'Sucursal'=>[
                "type" => $sucursalType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $ProductoLote = ProductoLote::where('ID', $id)->with(['sucursal'])->first();
                    if ($ProductoLote->sucursal==null) {
                        return null;
                    }
                    return $ProductoLote->sucursal->toArray();
                }
            ],
            'Cantidad'=>Type::int()
        ];
    }
]);
$RecepcionType=new ObjectType([
    'name' => 'Recepcion',
    'description' => 'Recepcion Llaves',
    'fields' => function () use(&$cuentaType){
        return [
            'ID'=>Type::int(),
            'Fecha'=>Type::string(),
            'Responsable'=>Type::int(),
            'Entregado'=>Type::string(),
            'Descripcion'=>Type::string(),
            'Cuenta'=>[
                "type" => $cuentaType,
                "resolve" => function ($root, $args) {
                    $id = $root['ID'];
                    $Recepcion = Recepcion::where('ID', $id)->with(['cuenta'])->first();
                    if ($Recepcion->cuenta==null) {
                        return null;
                    }
                    return $Recepcion->cuenta->toArray();
                }
            ]
        ];
    }
]);
?>