<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{

    const CACHE_MS_COMPANIES          = '-ms-companies';
    const CACHE_MS_COMPANY_ITEMS      = '-ms-company-items';
    const CACHE_MS_COMPANY_LOCATIONS  = '-ms-company-locations';
    const CACHE_MS_LOCATIONS          = '-ms-locations';
    const CACHE_MS_CARRIERS           = '-ms-carriers';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    const CONSIGNMENT_TYPE_PENDING = 'PENDING';
    const CONSIGNMENT_TYPE_MANIFEST = 'MANIFEST';

    protected $table = 'integrations';

    protected $attributes = [
        'integration_status' => self::STATUS_ACTIVE,
        'last_run' => null
    ];

    protected $fillable = [
        'label',
        'account_id',
        'integration_type_id',
        'integration_status',
        'frequency_mins',
        'last_run',
        'master_consignment_type',
        'offset'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function integrationKeys()
    {
        return $this->hasMany(IntegrationKey::class, 'integration_id');
    }

    public function integrationMeta()
    {
        return $this->hasMany(IntegrationMeta::class, 'integration_id');
    }

    public function integrationSourceFilters()
    {
        return $this->hasMany(IntegrationSourceFilter::class, 'integration_id');
    }

    public function fieldMapper()
    {
        return $this->hasMany(FieldMapper::class, 'integration_id');
    }

    public function valueLookup()
    {
        return $this->hasMany(ValueLookups::class, 'integration_id');
    }

    public function integrationSyncs()
    {
        return $this->hasMany(IntegrationSyncs::class, 'integration_id');
    }

    public function syncLogs()
    {
        return $this->hasMany(SyncLogs::class, 'integration_id');
    }

    public function integrationType()
    {
        return $this->belongsTo(IntegrationType::class);
    }

    public function machshipStatusMapping()
    {
        return $this->hasMany(MachshipStatusMapping::class, 'integration_id');
    }

    public function getIntegrationKeys()
    {
        $keys = array();
        $integrationKeys = $this->integrationKeys;
        foreach ($integrationKeys as $key) {
            $keys[$key->key_type] = $key->key_data;
        }
        return $keys;
    }

    public function getMachshipTokenKey()
    {
        $keys = $this->getIntegrationKeys();

        return isset($keys['machship_token']) ? $keys['machship_token'] : '';
    }

    public function getIntegrationMeta()
    {
        $metas = array();
        $integrationMetas = $this->integrationMeta;
        foreach ($integrationMetas as $meta) {
            $metas[$meta->meta_key] = $meta->meta_value;
        }
        return $metas;
    }


    // ---------------------------- CACHE IDS -------------------------------------------
    public function getMSCompaniesCacheID()
    {
        return $this->id . self::CACHE_MS_COMPANIES;
    }

    public function getMSComapnyItemsCachedID()
    {
        return $this->id . self::CACHE_MS_COMPANY_ITEMS;
    }

    public function getMSCompanyLocationsCachedID()
    {
        return $this->id . self::CACHE_MS_COMPANY_LOCATIONS;
    }

    public function getMSLocationsCachedID()
    {
        return $this->id . self::CACHE_MS_LOCATIONS;
    }

    public function getMSCarriersCachedID()
    {
        return $this->id . self::CACHE_MS_CARRIERS;
    }
}
