<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationType extends Model
{

    protected $fillable = [
        'name',
        'directory',
        'display_name',
        'active',
    ];

    public $timestamps = false;

    /**
     * This function retrieves the default maps of
     * a specific integration type.
     * @return     Array  The integration type default maps.
     */
    public function getDefaultMaps()
    {
        $service = $this->getPlatformService();
        return $service::defaultMaps();
    }


    /**
     * This function retrives the default meta
     * of a specific integration type.
     * @return     Array  The default meta.
     */
    public function getDefaultMeta()
    {
        $service = $this->getPlatformService();
        return $service::defaultMeta();
    }

    public function getDefaultFilter()
    {
        $service = $this->getPlatformService();
        return $service::defaultFilters();
    }

    public function getFilterOptions()
    {
        $service = $this->getPlatformService();
        if (method_exists($service, 'getFilterOptions')) {
            return $service::getFilterOptions();
        } else {
            return [];
        }
    }

    /**
     * This function retrieves platform class
     *
     * @return     Class  The platform class.
     */
    public function getPlatformClass()
    {
        $service = $this->getPlatformService();
        return new $service();
    }

    /**
     * Gets the platform physical path
     *
     * @return     String  The platform path.
     */
    public function getPlatformPath()
    {
        return str_replace('\\', '/', $this->directory);
    }


    /**
     * This function retrives the default client source fields
     * of a specific integration type
     * @return     Array  The default client source field.
     */
    public function getDefaultSourceField()
    {
        $service = $this->getPlatformService();
        return $service::defaultSourceFields();
    }


    // ----------------------- PRIVATE FUNCTIONS ---------------------------------------

    // Useful function to get platform's service path
    private function getPlatformService()
    {
        return $this->directory . '\\' . $this->name . 'Service';
    }
}
