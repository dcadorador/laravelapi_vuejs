<?php
namespace App\Services;

use App\Models\FieldMapper;
use App\Services\Platforms\PlatformAbstract;
use App\Helpers\ArrayHelper;

class FieldMapService
{

    private $client;
    private $source_data;

    /**
     * Constructs a new instance.
     *
     * @param      Object  $client  The client library
     */
    public function __construct(PlatformAbstract $client)
    {
        $this->client = $client;
    }

    /**
     * Sets the source data.
     *
     * @param      Array   $source_data  The source data from machship or client
     */
    public function setSourceData($source_data)
    {
        $this->source_data = $source_data;
    }

    /**
     * Gets the mapped by field.
     * Steps :
     * 1. Check map type of the field
     * 2. Interpret each map type
     * 3. Return string value
     * @param      Object  $field  mapper field data
     * @param      $field['data_direction']
     * @param      $field['machship_field']
     * @param      $field['map_type']
     * @return     String  value of mapped data
     */
    public function getMappedByField($field)
    {
        $data = '';
        switch ($field['map_type']) {
            case 'skip':
                $data = $field['source_field'];
                break;

            case 'customfield':
                $data = $this->client->getCustomfield(
                    $field['source_field'],
                    $this->source_data
                );
                break;

            // use direct
            case 'direct':
                $data = ArrayHelper::directMapper($field['source_field'], $this->source_data);
                break;

            case 'direct_simple':
                $key = $field['source_field'];
                $source = $this->source_data;
                $data = empty($source[$key]) ? null : $source[$key];
                break;

            // TODO
            case 'array':
                // TODO what to do with array
                break;
        }

        return $data;
    }
}
