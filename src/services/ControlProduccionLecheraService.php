<?php

require_once __DIR__ . '/../models/ControlProduccionLechera.php';
require_once __DIR__ . '/../models/Lactancia.php';

class ControlProduccionLecheraService
{
    private $model;
    private $lactanciaModel;

    public function __construct($db)
    {
        $this->model = new ControlProduccionLechera($db);
        $this->lactanciaModel = new Lactancia($db);
    }

    public function getAll()
    {
        $this->lactanciaModel->closeWhenSanitaryConditionIsEmpty();
        return $this->model->getAll();
    }

    public function getById($id)
    {
        return $this->model->getById($id);
    }

    public function getByCabra($id_cabra)
    {
        $this->lactanciaModel->closeWhenSanitaryConditionIsEmpty();
        return $this->model->getByCabra($id_cabra);
    }

    public function getPaginated($limit, $offset)
    {
        return $this->model->getPaginated($limit, $offset);
    }

    public function getPaginatedFiltered($searchTerm, $id_cabra, $id_lactancia, $fechaDesde, $fechaHasta, $limit, $offset)
    {
        return $this->model->getPaginatedFiltered($searchTerm, $id_cabra, $id_lactancia, $fechaDesde, $fechaHasta, $limit, $offset);
    }

    public function getPaginatedByCabra($id_cabra, $limit, $offset)
    {
        return $this->model->getPaginatedByCabra($id_cabra, $limit, $offset);
    }

    public function count()
    {
        return $this->model->count();
    }

    public function countFiltered($searchTerm, $id_cabra, $id_lactancia = 0, $fechaDesde = '', $fechaHasta = '')
    {
        return $this->model->countFiltered($searchTerm, $id_cabra, $id_lactancia, $fechaDesde, $fechaHasta);
    }

    public function countByCabra($id_cabra)
    {
        return $this->model->countByCabra($id_cabra);
    }

    public function getTop3Producers()
    {
        return $this->model->getTop3Producers();
    }

    public function getProducerCards()
    {
        return $this->model->getProducerCards();
    }

    public function getTotalsBySanitaryCondition()
    {
        return $this->model->getTotalsBySanitaryCondition();
    }

    public function getAnnualSummary()
    {
        return $this->model->getAnnualSummary();
    }

    public function getSanitaryConditionAtDate($idCabra, $fecha)
    {
        return $this->model->getSanitaryConditionAtDate($idCabra, $fecha);
    }

    public function create($id_cabra, $id_lactancia, $fecha_registro, $turno_ordeño, $cantidad_litros)
    {
        return $this->model->create($id_cabra, $id_lactancia, $fecha_registro, $turno_ordeño, $cantidad_litros);
    }

    public function getOrCreateActiveLactancia($idCabra, $fecha)
    {
        $this->lactanciaModel->closeWhenSanitaryConditionIsEmpty();
        return $this->lactanciaModel->getOrCreateActive($idCabra, $fecha);
    }

    public function update($id, $id_cabra, $id_lactancia, $fecha_registro, $turno_ordeño, $cantidad_litros)
    {
        return $this->model->update($id, $id_cabra, $id_lactancia, $fecha_registro, $turno_ordeño, $cantidad_litros);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function getLactancias($idCabra = null)
    {
        $this->lactanciaModel->closeWhenSanitaryConditionIsEmpty();
        return $this->lactanciaModel->getAll($idCabra);
    }

    public function syncLactanciasWithSanitaryStatus()
    {
        return $this->lactanciaModel->closeWhenSanitaryConditionIsEmpty();
    }

    public function isLactatingAtDate($idCabra, $fecha)
    {
        $condition = $this->model->getSanitaryConditionAtDate($idCabra, $fecha);
        return $condition && strtoupper(trim($condition['condicion_especial'])) === 'LACTANTE';
    }

    public function getLactancia($id)
    {
        return $this->lactanciaModel->getById($id);
    }

    public function getLactanciaOptions($idCabra)
    {
        return $this->lactanciaModel->getOptionsByCabra($idCabra);
    }

    public function getPartosWithoutLactancia($idCabra = null)
    {
        return $this->lactanciaModel->getPartosWithoutLactancia($idCabra);
    }

    public function createLactancia($idCabra, $idParto)
    {
        return $this->lactanciaModel->create($idCabra, $idParto);
    }

    public function finishLactancia($id, $fechaFin)
    {
        return $this->lactanciaModel->finish($id, $fechaFin);
    }
}
