<?php
namespace App\Services;

use App\Traits\ConversionTypes\ValueLookupTraits;
use App\Traits\ConversionTypes\FunctionTraits;
use App\Models\ValueLookups;
use App\Services\Platforms\PlatformAbstract;
use Illuminate\Database\Eloquent\Collection;

class DataConversionService
{

    use ValueLookupTraits;
    use FunctionTraits;

    private $value_lookups;
    private $platform;

    public function __construct(Collection $value_lookups, PlatformAbstract $platform)
    {
        $this->value_lookups = $value_lookups;
        $this->platform = $platform;
    }

    public function getConversionData($field, $data)
    {
        $value = '';
        $machship_field = $field['machship_field'];
        $conversion_type = $field['data_conversion_type'];
        $conversion_value = $field['data_conversion_value'];

        switch ($conversion_type) {
            // constant type of conversion
            case 'constant':
                $is_boolean = $conversion_value == 'true' || $conversion_value == 'false';
                $value = $is_boolean ? filter_var($conversion_value, FILTER_VALIDATE_BOOLEAN) : $conversion_value;
                break;

            // function type of conversion
            case 'function':
                // first we need to check if its for modified function
                $args = empty($conversion_value) ? [] : explode("|", $conversion_value);
                if (count($args) > 0) {
                    // break if the method does not exist
                    if (!method_exists($this, $args[0])) {
                        break;
                    }

                    // determine if there is more args
                    $params = [$data];
                    $func = array_shift($args);
                    foreach ($args as $arg) {
                        $params[] = $arg;
                    }
                    $value = $this->{$func}(...$params);
                } elseif (method_exists($this, $conversion_value)) {
                    $value = $this->{$conversion_value}($data, $conversion_value);
                }
                break;

            // this custom function executes integration's custom function
            case 'custom_function':
                $integration_id = $this->platform->getIntegrationId();
                $func = "integration_{$integration_id}_{$conversion_value}";
                if (method_exists($this->platform, $func)) {
                    $value = $this->platform->{$func}($data);
                }
                break;

            // lookup type of conversion
            case 'lookup':
                $from = empty($data) ? $conversion_value : $data;
                $value = $this->valueLookupSearch($machship_field, $from);
                break;

            // otherwise use the given data
            default:
                $value = $data;
        }

        return $value;
    }

    public function testConversion($data)
    {
        $items = [];
        foreach ($data as $key => $value) {
            $items[$key]['name'] = $value;
        }
        return $items;
    }
}
