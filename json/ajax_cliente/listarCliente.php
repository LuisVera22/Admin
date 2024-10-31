<?php
require_once '../../src/Repository/clienteRepository.php';
require_once '../../src/Controller/clienteController.php';
require_once '../../lib/openssl.php';
require_once '../../config/enviroment.php';

class listAjaxCliente
{
    public function listCliente()
    {
        $response = ClienteController::mostrarCliente();

        for ($i = 0; $i < count($response); $i++) {
            $encript = Openssl::encriptar($response[$i]['id'], $_ENV['SECRET_KEY']);
            if ($response[$i]['lastnames'] == null && $response[$i]['names'] == null || $response[$i]['lastnames'] == "" && $response[$i]['names'] == "") {
                $response[$i]['personal'] = $response[$i]['bussinesnames'];
            } else {
                $response[$i]['personal'] = $response[$i]['lastnames'] . ", " . $response[$i]['names'];
            }

            if ($response[$i]['enabled'] == 1) {
                $response[$i]['enabled'] = '<div class="text-center"><span class="badge badge-success badge-shadow p-2">Activo</span></div>';
                $response[$i]['acciones'] = '
                <div class="text-center">
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#info-modal" onclick="visualizarCliente(' . "'" . $encript . "'" . ')"><i class="fa fa-eye"></i></button>
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#actualizar-modal" onclick="actualizarCliente(' . "'" . $encript . "'" . ')"><i class="fa fa-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger" onclick="eliminarCliente(' . "'" . $encript . "'" . ')"><i class="fas fa-trash"></i></button>
                </div>';
            } else {
                if ($response[$i]['enabled'] == 0) {
                    $response[$i]['enabled'] = '<div class="text-center"><span class="badge badge-danger badge-shadow p-2">Inactivo</span></div>';
                    $response[$i]['acciones'] = '
                    <div class="text-center">                      
                      <button type="button" class="btn btn-outline-success" onclick="reingresarCliente(' . "'" . $encript . "'" . ')"><i class="fas fa-door-open"></i></button>
                    </div>';
                }
            }
        }
        echo json_encode($response);
    }
}
$resp = new listAjaxCliente;
$resp->listCliente();