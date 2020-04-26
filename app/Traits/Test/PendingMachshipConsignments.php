<?php

namespace App\Traits\Test;

use \Carbon\Carbon;

trait PendingMachshipConsignments
{
    public static function testPendingMachshipConsignments()
    {
        $array = array (
              'object' =>
              array (
                'carrierConsignmentId' => 'string',
                'status' =>
                array (
                  'id' => 0,
                  'name' => 'string',
                  'description' => 'string',
                ),
                'manifestId' => 0,
                'bookedDate' => '2020-02-20T03:15:14.746Z',
                'completedDate' => '2020-02-20T03:15:14.747Z',
                'completedDateUtc' => '2020-02-20T03:15:14.747Z',
                'attachmentCount' => 0,
                'important' => true,
                'carrierName' => 'string',
                'statusHistory' =>
                array (
                  0 =>
                  array (
                    'consignmentId' => 0,
                    'createdByName' => 'string',
                    'statusIsPartial' => true,
                    'consignmentTrackingStatus' =>
                    array (
                      'id' => 0,
                      'name' => 'string',
                      'description' => 'string',
                    ),
                    'statusDateLocal' => '2020-02-20T03:15:14.747Z',
                    'statusDateUtc' => '2020-02-20T03:15:14.747Z',
                    'carrierStatus' => 'string',
                    'carrierStatusDescription' => 'string',
                    'createdByUserId' => 0,
                    'statusInformation' => 'string',
                  ),
                ),
                'trackingPageAccessToken' => 'string',
                'id' => 0,
                'companyId' => 0,
                'company' =>
                array (
                  'id' => 0,
                  'name' => 'string',
                  'accountCode' => 'string',
                  'displayName' => 'string',
                ),
                'carrierServiceId' => 0,
                'carrierService' =>
                array (
                  'id' => 0,
                  'name' => 'string',
                  'abbreviation' => 'string',
                  'displayName' => 'string',
                ),
                'subServiceId' => 0,
                'subService' =>
                array (
                  'id' => 0,
                  'name' => 'string',
                  'abbreviation' => 'string',
                  'serialisedSettings' => 'string',
                  'isDefault' => true,
                ),
                'fromCarrierZoneId' => 0,
                'fromCarrierZone' =>
                array (
                  'id' => 0,
                  'name' => 'string',
                  'abbreviation' => 'string',
                  'displayName' => 'string',
                ),
                'toCarrierZoneId' => 0,
                'toCarrierZone' =>
                array (
                  'id' => 0,
                  'name' => 'string',
                  'abbreviation' => 'string',
                  'displayName' => 'string',
                ),
                'consignmentNumber' => 'string',
                'dateCreated' => '2020-02-20T03:15:14.747Z',
                'createdByUserId' => 0,
                'despatchDate' => '2020-02-20T03:15:14.747Z',
                'despatchDateUtc' => '2020-02-20T03:15:14.747Z',
                'despatchDateLocal' => '2020-02-20T03:15:14.747Z',
                'eta' => '2020-02-20T03:15:14.747Z',
                'etaUtc' => '2020-02-20T03:15:14.747Z',
                'fromCompanyLocationId' => 0,
                'fromName' => 'string',
                'fromContact' => 'string',
                'fromPhone' => 'string',
                'fromEmail' => 'string',
                'fromAddressLine1' => 'string',
                'fromAddressLine2' => 'string',
                'fromLocation' =>
                array (
                  'id' => 0,
                  'postcode' => 'string',
                  'state' =>
                  array (
                    'code' => 'string',
                    'name' => 'string',
                    'id' => 0,
                  ),
                  'timeZone' => 'string',
                  'suburb' => 'string',
                  'description' => 'string',
                  'locationType' => 0,
                ),
                'toLocation' =>
                array (
                  'id' => 0,
                  'postcode' => 'string',
                  'state' =>
                  array (
                    'code' => 'string',
                    'name' => 'string',
                    'id' => 0,
                  ),
                  'timeZone' => 'string',
                  'suburb' => 'string',
                  'description' => 'string',
                  'locationType' => 0,
                ),
                'toCompanyLocationId' => 0,
                'toName' => 'string',
                'toContact' => 'string',
                'toPhone' => 'string',
                'toEmail' => 'string',
                'toAddressLine1' => 'string',
                'toAddressLine2' => 'string',
                'specialInstructions' => 'string',
                'customerReference' => 'string',
                'customerReference2' => 'string',
                'companyCarrierAccount' =>
                array (
                  'id' => 0,
                  'companyId' => 0,
                  'carrierAccountId' => 0,
                  'carrierAccount' =>
                  array (
                    'id' => 0,
                    'name' => 'string',
                    'accountCode' => 'string',
                    'carrierId' => 0,
                    'carrier' =>
                    array (
                      'id' => 0,
                      'name' => 'string',
                      'abbreviation' => 'string',
                      'displayName' => 'string',
                    ),
                    'isInTestMode' => true,
                    'displayName' => 'string',
                  ),
                  'name' => 'string',
                  'abbreviation' => 'string',
                  'displayName' => 'string',
                ),
                'consignmentTotal' =>
                array (
                  'sellPricesCleared' => true,
                  'consignmentCarrierSurchargesCostPrice' => 0,
                  'consignmentCarrierSurchargesSellPrice' => 0,
                  'consignmentCarrierSurchargesFuelExemptCostPrice' => 0,
                  'consignmentCarrierSurchargesFuelExemptSellPrice' => 0,
                  'totalConsignmentCarrierSurchargesCostPrice' => 0,
                  'totalConsignmentCarrierSurchargesSellPrice' => 0,
                  'totalSellPrice' => 0,
                  'totalCostPrice' => 0,
                  'totalBaseSellPrice' => 0,
                  'totalBaseCostPrice' => 0,
                  'totalTaxSellPrice' => 0,
                  'totalTaxCostPrice' => 0,
                  'costFuelLevyPrice' => 0,
                  'sellFuelLevyPrice' => 0,
                  'consignmentRouteCostPrice' => 0,
                  'consignmentRouteSellPrice' => 0,
                  'totalCostBeforeTax' => 0,
                  'totalSellBeforeTax' => 0,
                ),
                'totalWeight' => 0,
                'totalCubic' => 0,
                'totalVolume' => 0,
                'heaviestWeight' => 0,
                'totalItemCount' => 0,
                'insertedBy' => 0,
                'insertedByUserName' => 'string',
                'permanentPickup' => true,
                'distance' => 0,
                'isHourly' => true,
                'isTest' => true,
                'consignmentItems' =>
                array (
                  0 =>
                  array (
                    'companyItemId' => 0,
                    'itemType' => 1,
                    'name' => 'string',
                    'sku' => 'string',
                    'quantity' => 0,
                    'height' => 0,
                    'weight' => 0,
                    'length' => 0,
                    'width' => 0,
                    'references' =>
                    array (
                      0 => 'string',
                    ),
                  ),
                ),
              ),
              'errors' =>
              array (
                0 =>
                array (
                  'validationType' => 0,
                  'memberNames' =>
                  array (
                    0 => 'string',
                  ),
                  'errorMessage' => 'string',
                ),
              ),
            );

        // Converts an array to an object recursively
        
        return json_decode(json_encode($array, JSON_FORCE_OBJECT));
    }
}
