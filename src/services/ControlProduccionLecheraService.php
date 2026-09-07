<?php

require_once __DIR__ . '/../models/ControlProduccionLechera.php';

class ControlProduccionLecheraService
{
    private $model;

    public function __construct($db)
    {
        $this->model = new ControlProduccionLechera($db);
    }

    public function getAll()
    {
        return $this->model->getAll();
    }

    public function getById($id)
    {
        return $this->model->getById($id);
    }

    public function getByCabra($id_cabra)
    {
        return $this->model->getByCabra($id_cabra);
    }

    public function getPaginated($limit, $offset)
    {
        return $this->model->getPaginated($limit, $offset);
    }

    public function getPaginatedFiltered($searchTerm, $id_cabra, $limit, $offset)
    {
        return $this->model->getPaginatedFiltered($searchTerm, $id_cabra, $limit, $offset);
    }

    public function getPaginatedByCabra($id_cabra, $limit, $offset)
    {
        return $this->model->getPaginatedByCabra($id_cabra, $limit, $offset);
    }

    public function count()
    {
        return $this->model->count();
    }

    public function countFiltered($searchTerm, $id_cabra)
    {
        return $this->model->countFiltered($searchTerm, $id_cabra);
    }

    public function countByCabra($id_cabra)
    {
        return $this->model->countByCabra($id_cabra);
    }

    public function getTop3Producers()
    {
        return $this->model->getTop3Producers();
    }

    public function create($id_cabra, $turno_ordeño, $cantidad_litros)
    {
        return $this->model->create($id_cabra, $turno_ordeño, $cantidad_litros);
    }

    public function update($id, $id_cabra, $turno_ordeño, $cantidad_litros)
    {
        return $this->model->update($id, $id_cabra, $turno_ordeño, $cantidad_litros);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
}
