<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Libraries\Machship\Machship;
use App\Models\Integration;

class MachshipCacheService
{

    private $days = 1;
    private $expiry;
    private $machship;
    private $integration;

    private $keys = [
        'companies' => '',
        'carriers' => '',
        'company_items' => '',
        'company_locations' => '',
        'locations' => '',
    ];

    private $caches = [
        'companies' => null,
        'carriers' => null,
        'company_items' => null,
        'company_locations' => null,
        'locations' => null,
    ];

    public function __construct(Machship $machship, Integration $integration)
    {
        $this->machship = $machship;
        $this->integration = $integration;

        // make sure machship or integration works
        if (empty($machship) || empty($integration)) {
            return;
        }

        // sets expiry date
        $this->expiry = now()->addDays(1);

        // get all keys for each cache
        $this->keys['companies'] = $integration->getMSCompaniesCacheID();
        $this->keys['carriers']  = $integration->getMSCarriersCachedID();
        $this->keys['company_items'] = $integration->getMSComapnyItemsCachedID();
        $this->keys['company_locations'] = $integration->getMSCompanyLocationsCachedID();
        $this->keys['locations'] = $integration->getMSLocationsCachedID();
    }

    /**
     * Initializes machship caches.
     */
    public function init()
    {

        $machship = $this->machship;

        // makesure machship is available
        if (empty($machship)) {
            return;
        }

        // sets Closures for fetching machships here
        $fetch_companies = $this->getClosureFetch('companies');
        $fetch_carriers = $this->getClosureFetch('carriers');
        $fetch_company_items = $this->getClosureFetch('company_items');
        $fetch_company_locations = $this->getClosureFetch('company_locations');
        $fetch_locations = $this->getClosureFetch('locations');

        // start caching!
        $this->caches['companies'] = Cache::remember($this->keys['companies'], $this->expiry, $fetch_companies);
        $this->caches['company_items'] = Cache::remember($this->keys['company_items'], $this->expiry, $fetch_company_items);
        $this->caches['company_locations'] = Cache::remember($this->keys['company_locations'], $this->expiry, $fetch_company_locations);
        $this->caches['locations']  = Cache::remember($this->keys['locations'], $this->expiry, $fetch_locations);
        $this->caches['carriers'] = Cache::remember($this->keys['carriers'], $this->expiry, $fetch_carriers);
    }

    // gets cache companies
    public function getCompanies()
    {
        return $this->getCacheByKey('companies');
    }

    // gets cache company items
    public function getCompanyItems()
    {
        return $this->getCacheByKey('company_items');
    }

    // gets cache company locations
    public function getCompanyLocations()
    {
        return $this->getCacheByKey('company_locations');
    }

    // gets cache locations
    public function getLocations()
    {
        return $this->getCacheByKey('locations');
    }

    // gets cache carriers
    public function getCarriers()
    {
        return $this->getCacheByKey('carriers');
    }

    /**
     * Gets Machship cache by key.
     * @param      String  $key    The key
     * @return     Cache  The specific machship cache by key.
     */
    private function getCacheByKey($key)
    {
        // if cache carriers already sets
        if (!empty($this->caches[$key])) {
            // return carriers
            return $this->caches[$key];
        }

        $machship = $this->machship;

        // check nessesary data
        if (empty($machship) || empty($this->keys[$key])) {
            return;
        }

        return Cache::remember($this->keys[$key], $this->expiry, $this->getClosureFetch($key));
    }

    /**
     * Gets the closure function to fetch data from machship by specific key item.
     * @param      String  $item   The key item
     * @return     Closure  The closure fetch.
     */
    private function getClosureFetch($item)
    {
        $machship = $this->machship;

        $closure = null;
        switch ($item) {
            case 'companies':
                $closure = function () use (&$machship) {
                    \Log::info('fetch cache get all companies');
                    return $machship->getAllCompanies();
                };
                break;
            case 'carriers':
                $closure = function () use (&$machship) {
                    \Log::info('fetch cache get all carriers');
                    return $machship->getAllCarriers();
                };
                break;
            case 'company_items':
                $closure = function () use (&$machship) {
                    \Log::info('fetch cache get all company item');
                    return $machship->getAllCompanyItem();
                };
                break;
            case 'company_locations':
                $closure = function () use (&$machship) {
                    \Log::info('fetch cache get company locations');
                    return $machship->getAllCompanyLocations();
                };
                break;
            case 'locations':
                $closure = function () use (&$machship) {
                    \Log::info('fetch cache get all locations');
                    return $machship->getAllLocations();
                };
                break;
        }

        return $closure;
    }
}
