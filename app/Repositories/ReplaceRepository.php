<?php

namespace App\Repositories;

use App\Replace;

class ReplaceRepository
{
    public function find($id)
    {
        return Replace::findOrFail($id);
    }
    public function findByImei(string $imei)
    {
        $replace = $this->queryByImei($imei)->first();
        if(!$replace){
            throw new \Exception("No replace data for the IMEI '{$imei}'");
        }
        return $replace;
    }
    public function create(array $data)
    {
        return Replace::create($data);
    }
    public function destroy(string $imei)
    {
        return $this->findByImei($imei)->delete();
    }
    public function findByDateRange(string $fdate, string $tdate)
    {
        $start = $fdate . ' 00:00:00';
        $end   = $tdate . ' 23:59:59';
        return Replace::whereBetween('created_at', [$start, $end]);
    }
    private function queryByImei(string $imei)
    {
        return Replace::where('imei', $imei)
			->orWhere('sno', $imei);
    }
}
