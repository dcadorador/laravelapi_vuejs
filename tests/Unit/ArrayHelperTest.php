<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Helpers\ArrayHelper;
use stdClass;

/**
 * Array Helper Test class
 *
 * Test class for array helper
 *
 * @author Crestelito Cuyno <cres@fusedsoftware.com>
 */
class ArrayHelperTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * This is to test if the expected output is the same as the actual output. Basically to test if the helper is working.
     *
     * @return void
     */
    public function testValidateArrayHelperResult()
    {

        $object = new stdClass;
        $object->createdDate= "2019-12-17T15:44:02.000-08:00";
        $object->lastModifiedDate= "2019-12-17T15:45:17.000-08:00";
        $object->customForm= null;
        $object->postingPeriod= null;
        
        $object->entity = new stdClass;
        $object->entity->internalId= "asdf";
        $object->entity->externalId= null;
        $object->entity->type= null;
        $object->entity->name= "Enmach Industries";

        $object->createdFrom = new stdClass;
        $object->createdFrom->internalId= "1372751";
        $object->createdFrom->externalId= null;
        $object->createdFrom->type= null;
        $object->createdFrom->name= "Sales Order #SO35373";
        $object->requestedBy= null;
        $object->createdFromShipGroup= 1;
        $object->partner= null;

        $object->shippingAddress = new stdClass;
        $object->shippingAddress->internalId= null;
        $object->shippingAddress->country= null;
        $object->shippingAddress->attention= null;
        $object->shippingAddress->addressee= "Enmach Industries";
        $object->shippingAddress->addrPhone= null;
        $object->shippingAddress->addr1= "17 Charlie Triggs Crescent";
        $object->shippingAddress->addr2= null;
        $object->shippingAddress->addr3= null;
        $object->shippingAddress->city= "BUNDABERG";
        $object->shippingAddress->state= "QLD";
        $object->shippingAddress->zip= "4670";
        $object->shippingAddress->addrText= "
        Enmach Industries\n
        17 Charlie Triggs Crescent\n
        BUNDABERG QLD 4670
        ";
        $object->shippingAddress->override= false;
        $object->shippingAddress->customFieldList= [
            'fieldone' => 'fieldonevalue',
            'fieldtwo' => 'fieldtwovalue',
            'fieldthree' => 'fieldthreevalue',
            'fieldfour' => $object->createdFrom
        ];
        $object->shippingAddress->nullFieldList= new stdClass;
        $object->shippingAddress->nullFieldList->fieldonez= "fieldonevalue";
        $object->shippingAddress->nullFieldList->fieldtwoz= "fieldtwovalue";
        $object->shippingAddress->nullFieldList->fieldthreez= "fieldthreevalue";
        $object->pickedDate= null;
        $object->packedDate= null;
        $object->shippedDate= null;
        $object->shipIsResidential= false;

        $convertedObj = json_decode(json_encode($object), true);
        $keys = ['fieldthreez', 'createdDate', 'name', 'zip', 'state'];

        $output = [
            "name" => "Sales Order #SO35373",
            "fieldthreez" => "fieldthreevalue",
            "zip" => "4670",
            "state" => "QLD",
            "createdDate" => "2019-12-17T15:44:02.000-08:00"
        ];

        $this->assertEquals(ArrayHelper::mapper($keys, $convertedObj), $output);
    }
}
