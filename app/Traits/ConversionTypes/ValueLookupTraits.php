<?php

namespace App\Traits\ConversionTypes;

trait ValueLookupTraits
{
    /**
     * Searches for the first match of the value lookups
     *
     * @param      String  $machship_field  The machship field
     * @param      String  $from            The from
     *
     * @return     string  value that matched
     */
    protected function valueLookupSearch($machship_field, $from)
    {

        // value lookups must not be empty
        if (!empty($this->value_lookups)) {
            foreach ($this->value_lookups as $lookups) {
                if ($lookups['machship_field'] == $machship_field &&
                    $lookups['from_value'] == $from
                ) {
                    return $lookups['to_value'];
                }
            }
        }

        return '';
    }
}
