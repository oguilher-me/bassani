<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'placa',
        'modelo',
        'car_brand_id',
        'ano_fabricacao',
        'quilometragem_atual',
        'cubic_capacity',
        'data_aquisicao',
        'status',
        'observacoes',
        'next_preventive_maintenance_mileage',
        'licensing_due_date',
        'insurance_due_date',
    ];

    /**
     * Verifica se o veículo comporta um volume de carga informado (m³).
     * Útil para cruzar com a cubagem total dos itens de um pedido/projeto.
     *
     * @param float $requiredVolume Volume necessário em m³
     * @return bool|null  null quando cubic_capacity não está definido
     */
    public function canFitLoad(float $requiredVolume): ?bool
    {
        if ($this->cubic_capacity === null) {
            return null;
        }
        return $this->cubic_capacity >= $requiredVolume;
    }

    public function carBrand()
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function fuelUps()
    {
        return $this->hasMany(FuelUp::class);
    }

    public function vehicleUsages()
    {
        return $this->hasMany(VehicleUsage::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function vehicleFines()
    {
        return $this->hasMany(VehicleFine::class);
    }
}
