<?php

namespace App\Services;

use App\Repositories\PromortkeyRepository;

class PromortkeyService
{
    protected $promortkeyRepository;
    public function __construct(PromortkeyRepository $promortkeyRepository)
    {
        $this->promortkeyRepository = $promortkeyRepository;
    }
    public function getAllPromortkeys()
    {
        return $this->promortkeyRepository->all();
    }
    public function storePromortKey(array $data)
    {
        return $this->promortkeyRepository->create($data);
    }
    public function updatePromortKey(int $id, array $data)
    {
        return $this->promortkeyRepository->update($id, $data);
    }
    public function deletePromortKey(int $id)
    {
        return $this->promortkeyRepository->delete($id);
    }
    public function statusUpdate(int $id)
    {
        return $this->promortkeyRepository->changeStatus($id);
    }
}
