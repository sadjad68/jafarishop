<?php

namespace App\Services\Legacy\Importers;

use App\Services\Legacy\LegacyImportStats;
use App\Services\Legacy\LegacyImportSupport;
use App\Services\Legacy\LegacyMapper;

class UserImporter
{
    public function __construct(private LegacyImportSupport $support)
    {
    }

    public function import(): LegacyImportStats
    {
        $stats = new LegacyImportStats();
        $places = $this->places();
        $this->support->eachPending('users', function ($row) use ($stats, $places) {
            $id = (int) $row->id;
            $now = LegacyMapper::timestamp($row->created_at ?? null);
            $fullName = LegacyMapper::fullName($row->name ?? null, $row->family ?? null);
            $inserted = $this->support->copyRow('users', $id, 'users', [
                'id' => $id,
                'full_name' => $fullName,
                'mobile' => $row->mobile ?? null,
                'email' => $row->email ?? null,
                'confirm_code' => $row->mobile_confirm_code ?? null,
                'password' => $row->password ?? null,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ], $stats);

            if (!$inserted && !$this->support->targetExists('users', $id)) {
                return;
            }

            $address = trim((string) ($row->address ?? ''));
            if ($address === '') {
                return;
            }
            $already = $this->support->new()->table('addresses')
                ->where('user_id', $id)
                ->where('address', $address)
                ->exists();
            if ($already) {
                return;
            }

            $matched = $this->matchPlace($places, $row->province ?? null, $row->city ?? null);
            $this->support->insertNew('addresses', [
                'user_id' => $id,
                'state_id' => $matched['state_id'],
                'city_id' => $matched['city_id'],
                'address' => $address,
                'receiptor_full_name' => $fullName,
                'receiptor_mobile' => $row->mobile ?? null,
                'postal_code' => null,
                'created_at' => $now,
                'updated_at' => LegacyMapper::timestamp($row->updated_at ?? $now),
                'deleted_at' => null,
            ], $stats);
        }, 400);
        $this->support->realignAutoIncrement('users');
        $this->support->realignAutoIncrement('addresses');

        return $stats;
    }

    private function places(): array
    {
        $states = [];
        foreach ($this->support->new()->table('states')->get(['id', 'name']) as $state) {
            $key = LegacyMapper::normalizePlaceName($state->name);
            if ($key !== '') {
                $states[$key] = (int) $state->id;
            }
        }

        $citiesByState = [];
        $citiesByName = [];
        foreach ($this->support->new()->table('cities')->get(['id', 'name', 'state_id']) as $city) {
            $key = LegacyMapper::normalizePlaceName($city->name);
            if ($key === '') {
                continue;
            }
            $citiesByState[(int) $city->state_id . '|' . $key] = (int) $city->id;
            $citiesByName[$key][] = ['id' => (int) $city->id, 'state_id' => (int) $city->state_id];
        }

        return compact('states', 'citiesByState', 'citiesByName');
    }

    private function matchPlace(array $places, ?string $province, ?string $city): array
    {
        $stateId = null;
        $provinceKey = LegacyMapper::normalizePlaceName($province);
        if ($provinceKey !== '' && isset($places['states'][$provinceKey])) {
            $stateId = $places['states'][$provinceKey];
        }

        $cityId = null;
        $cityKey = LegacyMapper::normalizePlaceName($city);
        if ($cityKey !== '') {
            if ($stateId && isset($places['citiesByState'][$stateId . '|' . $cityKey])) {
                $cityId = $places['citiesByState'][$stateId . '|' . $cityKey];
            } elseif (isset($places['citiesByName'][$cityKey]) && count($places['citiesByName'][$cityKey]) === 1) {
                $only = $places['citiesByName'][$cityKey][0];
                $cityId = $only['id'];
                $stateId = $stateId ?: $only['state_id'];
            }
        }

        return ['state_id' => $stateId, 'city_id' => $cityId];
    }
}
