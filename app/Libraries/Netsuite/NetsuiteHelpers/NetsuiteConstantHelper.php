<?php
namespace App\Libraries\Netsuite\NetsuiteHelpers;

class NetSuiteConstantHelper
{
    // OPERATOR
    const NETSUITE_ANY_OF_OPERATOR = 'anyOf';
    const NETSUITE_NONE_OF_OPERATOR = 'noneOf';
    const NETSUITE_NOT_EMPTY_OPERATOR = 'notEmpty';
    const NETSUITE_AFTER_OPERATOR = 'after';
    const NETSUITE_IS_OPERATOR = 'is';
    const NETSUITE_CONTAINS_OPERATOR = 'contains';
    const NETSUITE_EMPTY_OPERATOR = 'empty';
    const NETSUITE_SEARCH_DATE_FIELD_OPR_WITHIN = 'within';

    const NETSUITE_CUSTOMER = 'Customer';
    const NETSUITE_SALES_ORDER = 'SalesOrder';
    const NETSUITE_ITEM_FULFILLMENT = 'ItemFulfillment';

    const NETSUITE_CUSTOM_FIELD_COLUMN_VALUE = 'value';
    const NETSUITE_CUSTOM_FIELD_COLUMN_INTERNAL_ID = 'internalId';
    const NETSUITE_CUSTOM_FIELD_COLUMN_SCRIPT_ID = 'scriptId';

    // CUSTOM FIELDS
    const CUSTOM_ACL_FIELD_SHIPPING_WITH = 'custbody_shipping_with';
    const CUSTOM_ACL_FIELD_DO_NOT_SEND_TO_MACSHIP = 'custbody_do_not_send_machship';
    const CUSTOM_ACL_FIELD_SHIPPING_GROUP = 'custbody12';
    const CUSTOM_ACL_FIELD_WAREHOUSE_LOCATION = 'custbody26';
    const CUSTOM_ACL_FIELD_FREIGHT_ACCOUNT_NUMBER = 'custbody_freight_account_number';
    const CUSTOM_ACL_FIELD_DANGEROUS_GOODS = 'custbody_contains_dgs';
    const CUSTOM_ACL_FIELD_CUST_DISPATCH_NOTIF_EMAIL = 'custbody_despatch_notif_email';
    const CUSTOM_ACL_FIELD_TRACKING_NUMBER = 'custbody13';
    const CUSTOM_ACL_FIELD_MACHSHIP_STATUS = 'custbody_machship_status';
    const CUSTOM_ACL_FIELD_CONSIGNMENT_NUMBER = 'custbody_ms_tracking_number';
    const CUSTOM_ACL_FIELD_IFL = 'custbody_ifl';

    public static function itemFulfillmentDataTypes()
    {
        return array(
            "createdDate" => "dateTime",
            "lastModifiedDate" => "dateTime",
            "customForm" => "RecordRef",
            "postingPeriod" => "RecordRef",
            "entity" => "RecordRef",
            "createdFrom" => "RecordRef",
            "requestedBy" => "RecordRef",
            "createdFromShipGroup" => "integer",
            "partner" => "RecordRef",
            "shippingAddress" => "Address",
            "pickedDate" => "dateTime",
            "packedDate" => "dateTime",
            "shippedDate" => "dateTime",
            "shipIsResidential" => "boolean",
            "shipAddressList" => "RecordRef",
            "shipStatus" => "ItemFulfillmentShipStatus",
            "saturdayDeliveryUps" => "boolean",
            "sendShipNotifyEmailUps" => "boolean",
            "sendBackupEmailUps" => "boolean",
            "shipNotifyEmailAddressUps" => "string",
            "shipNotifyEmailAddress2Ups" => "string",
            "backupEmailAddressUps" => "string",
            "shipNotifyEmailMessageUps" => "string",
            "thirdPartyAcctUps" => "string",
            "thirdPartyZipcodeUps" => "string",
            "thirdPartyCountryUps" => "Country",
            "thirdPartyTypeUps" => "ItemFulfillmentThirdPartyTypeUps",
            "partiesToTransactionUps" => "boolean",
            "exportTypeUps" => "ItemFulfillmentExportTypeUps",
            "methodOfTransportUps" => "ItemFulfillmentMethodOfTransportUps",
            "carrierIdUps" => "string",
            "entryNumberUps" => "string",
            "inbondCodeUps" => "string",
            "isRoutedExportTransactionUps" => "boolean",
            "licenseNumberUps" => "string",
            "licenseDateUps" => "dateTime",
            "licenseExceptionUps" => "ItemFulfillmentLicenseExceptionUps",
            "eccNumberUps" => "string",
            "recipientTaxIdUps" => "string",
            "blanketStartDateUps" => "dateTime",
            "blanketEndDateUps" => "dateTime",
            "shipmentWeightUps" => "float",
            "saturdayDeliveryFedEx" => "boolean",
            "saturdayPickupFedex" => "boolean",
            "sendShipNotifyEmailFedEx" => "boolean",
            "sendBackupEmailFedEx" => "boolean",
            "signatureHomeDeliveryFedEx" => "boolean",
            "shipNotifyEmailAddressFedEx" => "string",
            "backupEmailAddressFedEx" => "string",
            "shipDateFedEx" => "dateTime",
            "homeDeliveryTypeFedEx" => "ItemFulfillmentHomeDeliveryTypeFedEx",
            "homeDeliveryDateFedEx" => "dateTime",
            "bookingConfirmationNumFedEx" => "string",
            "intlExemptionNumFedEx" => "string",
            "b13aFilingOptionFedEx" => "ItemFulfillmentB13AFilingOptionFedEx",
            "b13aStatementDataFedEx" => "string",
            "thirdPartyAcctFedEx" => "string",
            "thirdPartyCountryFedEx" => "Country",
            "thirdPartyTypeFedEx" => "ItemFulfillmentThirdPartyTypeFedEx",
            "shipmentWeightFedEx" => "float",
            "termsOfSaleFedEx" => "ItemFulfillmentTermsOfSaleFedEx",
            "termsFreightChargeFedEx" => "float",
            "termsInsuranceChargeFedEx" => "float",
            "insideDeliveryFedEx" => "boolean",
            "insidePickupFedEx" => "boolean",
            "ancillaryEndorsementFedEx" => "ItemFulfillmentAncillaryEndorsementFedEx",
            "holdAtLocationFedEx" => "boolean",
            "halPhoneFedEx" => "string",
            "halAddr1FedEx" => "string",
            "halAddr2FedEx" => "string",
            "halAddr3FedEx" => "string",
            "halCityFedEx" => "string",
            "halZipFedEx" => "string",
            "halStateFedEx" => "string",
            "halCountryFedEx" => "string",
            "hazmatTypeFedEx" => "ItemFulfillmentHazmatTypeFedEx",
            "accessibilityTypeFedEx" => "ItemFulfillmentAccessibilityTypeFedEx",
            "isCargoAircraftOnlyFedEx" => "boolean",
            "tranDate" => "dateTime",
            "tranId" => "string",
            "shipMethod" => "RecordRef",
            "generateIntegratedShipperLabel" => "boolean",
            "shippingCost" => "float",
            "handlingCost" => "float",
            "memo" => "string",
            "transferLocation" => "RecordRef",
            "packageList" => "ItemFulfillmentPackageList",
            "packageUpsList" => "ItemFulfillmentPackageUpsList",
            "packageUspsList" => "ItemFulfillmentPackageUspsList",
            "packageFedExList" => "ItemFulfillmentPackageFedExList",
            "itemList" => "ItemFulfillmentItemList",
            "accountingBookDetailList" => "AccountingBookDetailList",
            "customFieldList" => "CustomFieldList",
            "internalId" => "string",
            "externalId" => "string");
    }

    public static function salesOrderDataTypes()
    {
        return array(
            "createdDate" => "dateTime",
            "customForm" => "RecordRef",
            "entity" => "RecordRef",
            "job" => "RecordRef",
            "currency" => "RecordRef",
            "drAccount" => "RecordRef",
            "fxAccount" => "RecordRef",
            "tranDate" => "dateTime",
            "tranId" => "string",
            "entityTaxRegNum" => "RecordRef",
            "source" => "string",
            "createdFrom" => "RecordRef",
            "orderStatus" => "SalesOrderOrderStatus",
            "nextBill" => "dateTime",
            "opportunity" => "RecordRef",
            "salesRep" => "RecordRef",
            "contribPct" => "string",
            "partner" => "RecordRef",
            "salesGroup" => "RecordRef",
            "syncSalesTeams" => "boolean",
            "leadSource" => "RecordRef",
            "startDate" => "dateTime",
            "endDate" => "dateTime",
            "otherRefNum" => "string",
            "memo" => "string",
            "salesEffectiveDate" => "dateTime",
            "excludeCommission" => "boolean",
            "totalCostEstimate" => "float",
            "estGrossProfit" => "float",
            "estGrossProfitPercent" => "float",
            "exchangeRate" => "float",
            "promoCode" => "RecordRef",
            "currencyName" => "string",
            "discountItem" => "RecordRef",
            "discountRate" => "string",
            "isTaxable" => "boolean",
            "taxItem" => "RecordRef",
            "taxRate" => "float",
            "toBePrinted" => "boolean",
            "toBeEmailed" => "boolean",
            "email" => "string",
            "toBeFaxed" => "boolean",
            "fax" => "string",
            "messageSel" => "RecordRef",
            "message" => "string",
            "paymentOption" => "RecordRef",
            "inputAuthCode" => "string",
            "inputReferenceCode" => "string",
            "checkNumber" => "string",
            "paymentCardCsc" => "string",
            "paymentProcessingProfile" => "RecordRef",
            "handlingMode" => "SalesOrderHandlingMode",
            "outputAuthCode" => "string",
            "outputReferenceCode" => "string",
            "paymentOperation" => "SalesOrderPaymentOperation",
            "dynamicDescriptor" => "string",
            "billingAddress" => "Address",
            "billAddressList" => "RecordRef",
            "shippingAddress" => "Address",
            "shipIsResidential" => "boolean",
            "shipAddressList" => "RecordRef",
            "fob" => "string",
            "shipDate" => "dateTime",
            "actualShipDate" => "dateTime",
            "shipMethod" => "RecordRef",
            "shippingCost" => "float",
            "shippingTax1Rate" => "float",
            "isMultiShipTo" => "boolean",
            "shippingTax2Rate" => "string",
            "shippingTaxCode" => "RecordRef",
            "handlingTaxCode" => "RecordRef",
            "handlingTax1Rate" => "float",
            "handlingTax2Rate" => "string",
            "handlingCost" => "float",
            "trackingNumbers" => "string",
            "linkedTrackingNumbers" => "string",
            "shipComplete" => "boolean",
            "paymentMethod" => "RecordRef",
            "shopperIpAddress" => "string",
            "saveOnAuthDecline" => "boolean",
            "canHaveStackable" => "boolean",
            "creditCard" => "RecordRef",
            "revenueStatus" => "RevenueStatus",
            "recognizedRevenue" => "float",
            "deferredRevenue" => "float",
            "revRecOnRevCommitment" => "boolean",
            "revCommitStatus" => "RevenueCommitStatus",
            "ccNumber" => "string",
            "ccExpireDate" => "dateTime",
            "ccName" => "string",
            "ccStreet" => "string",
            "ccZipCode" => "string",
            "payPalStatus" => "string",
            "creditCardProcessor" => "RecordRef",
            "payPalTranId" => "string",
            "ccApproved" => "boolean",
            "getAuth" => "boolean",
            "authCode" => "string",
            "ccAvsStreetMatch" => "AvsMatchCode",
            "ccAvsZipMatch" => "AvsMatchCode",
            "isRecurringPayment" => "boolean",
            "ccSecurityCodeMatch" => "AvsMatchCode",
            "altSalesTotal" => "float",
            "ignoreAvs" => "boolean",
            "paymentEventResult" => "TransactionPaymentEventResult",
            "paymentEventHoldReason" => "TransactionPaymentEventHoldReason",
            "paymentEventType" => "TransactionPaymentEventType",
            "paymentEventDate" => "dateTime",
            "paymentEventUpdatedBy" => "string",
            "subTotal" => "float",
            "discountTotal" => "float",
            "taxTotal" => "float",
            "altShippingCost" => "float",
            "altHandlingCost" => "float",
            "total" => "float",
            "revRecSchedule" => "RecordRef",
            "revRecStartDate" => "dateTime",
            "revRecEndDate" => "dateTime",
            "paypalAuthId" => "string",
            "balance" => "float",
            "paypalProcess" => "boolean",
            "billingSchedule" => "RecordRef",
            "ccSecurityCode" => "string",
            "threeDStatusCode" => "string",
            "class" => "RecordRef",
            "department" => "RecordRef",
            "subsidiary" => "RecordRef",
            "intercoTransaction" => "RecordRef",
            "intercoStatus" => "IntercoStatus",
            "debitCardIssueNo" => "string",
            "lastModifiedDate" => "dateTime",
            "nexus" => "RecordRef",
            "subsidiaryTaxRegNum" => "RecordRef",
            "taxRegOverride" => "boolean",
            "taxPointDate" => "dateTime",
            "taxDetailsOverride" => "boolean",
            "location" => "RecordRef",
            "pnRefNum" => "string",
            "status" => "string",
            "tax2Total" => "float",
            "terms" => "RecordRef",
            "validFrom" => "dateTime",
            "vatRegNum" => "string",
            "giftCertApplied" => "float",
            "oneTime" => "float",
            "recurWeekly" => "float",
            "recurMonthly" => "float",
            "recurQuarterly" => "float",
            "recurAnnually" => "float",
            "tranIsVsoeBundle" => "boolean",
            "vsoeAutoCalc" => "boolean",
            "syncPartnerTeams" => "boolean",
            "salesTeamList" => "SalesOrderSalesTeamList",
            "partnersList" => "SalesOrderPartnersList",
            "giftCertRedemptionList" => "GiftCertRedemptionList",
            "promotionsList" => "PromotionsList",
            "itemList" => "SalesOrderItemList",
            "shipGroupList" => "SalesOrderShipGroupList",
            "accountingBookDetailList" => "AccountingBookDetailList",
            "taxDetailsList" => "TaxDetailsList",
            "customFieldList" => "CustomFieldList",
            "internalId" => "string",
            "externalId" => "string",
        );
    }

    public static function customerDataTypes()
    {
        return array(
            "customForm" => "RecordRef",
            "entityId" => "string",
            "altName" => "string",
            "isPerson" => "boolean",
            "phoneticName" => "string",
            "salutation" => "string",
            "firstName" => "string",
            "middleName" => "string",
            "lastName" => "string",
            "companyName" => "string",
            "entityStatus" => "RecordRef",
            "parent" => "RecordRef",
            "phone" => "string",
            "fax" => "string",
            "email" => "string",
            "url" => "string",
            "defaultAddress" => "string",
            "isInactive" => "boolean",
            "category" => "RecordRef",
            "title" => "string",
            "printOnCheckAs" => "string",
            "altPhone" => "string",
            "homePhone" => "string",
            "mobilePhone" => "string",
            "altEmail" => "string",
            "language" => "Language",
            "comments" => "string",
            "numberFormat" => "CustomerNumberFormat",
            "negativeNumberFormat" => "CustomerNegativeNumberFormat",
            "dateCreated" => "dateTime",
            "image" => "RecordRef",
            "emailPreference" => "EmailPreference",
            "subsidiary" => "RecordRef",
            "representingSubsidiary" => "RecordRef",
            "salesRep" => "RecordRef",
            "territory" => "RecordRef",
            "contribPct" => "string",
            "partner" => "RecordRef",
            "salesGroup" => "RecordRef",
            "vatRegNumber" => "string",
            "accountNumber" => "string",
            "taxExempt" => "boolean",
            "terms" => "RecordRef",
            "creditLimit" => "float",
            "creditHoldOverride" => "CustomerCreditHoldOverride",
            "monthlyClosing" => "CustomerMonthlyClosing",
            "overrideCurrencyFormat" => "boolean",
            "displaySymbol" => "string",
            "symbolPlacement" => "CurrencySymbolPlacement",
            "balance" => "float",
            "overdueBalance" => "float",
            "daysOverdue" => "integer",
            "unbilledOrders" => "float",
            "consolUnbilledOrders" => "float",
            "consolOverdueBalance" => "float",
            "consolDepositBalance" => "float",
            "consolBalance" => "float",
            "consolAging" => "float",
            "consolAging1" => "float",
            "consolAging2" => "float",
            "consolAging3" => "float",
            "consolAging4" => "float",
            "consolDaysOverdue" => "integer",
            "priceLevel" => "RecordRef",
            "currency" => "RecordRef",
            "prefCCProcessor" => "RecordRef",
            "depositBalance" => "float",
            "shipComplete" => "boolean",
            "taxable" => "boolean",
            "taxItem" => "RecordRef",
            "resaleNumber" => "string",
            "aging" => "float",
            "aging1" => "float",
            "aging2" => "float",
            "aging3" => "float",
            "aging4" => "float",
            "startDate" => "dateTime",
            "alcoholRecipientType" => "AlcoholRecipientType",
            "endDate" => "dateTime",
            "reminderDays" => "integer",
            "shippingItem" => "RecordRef",
            "thirdPartyAcct" => "string",
            "thirdPartyZipcode" => "string",
            "thirdPartyCountry" => "Country",
            "giveAccess" => "boolean",
            "estimatedBudget" => "float",
            "accessRole" => "RecordRef",
            "sendEmail" => "boolean",
            "assignedWebSite" => "RecordRef",
            "password" => "string",
            "password2" => "string",
            "requirePwdChange" => "boolean",
            "campaignCategory" => "RecordRef",
            "sourceWebSite" => "RecordRef",
            "leadSource" => "RecordRef",
            "receivablesAccount" => "RecordRef",
            "drAccount" => "RecordRef",
            "fxAccount" => "RecordRef",
            "defaultOrderPriority" => "float",
            "webLead" => "string",
            "referrer" => "string",
            "keywords" => "string",
            "clickStream" => "string",
            "lastPageVisited" => "string",
            "visits" => "integer",
            "firstVisit" => "dateTime",
            "lastVisit" => "dateTime",
            "billPay" => "boolean",
            "openingBalance" => "float",
            "lastModifiedDate" => "dateTime",
            "openingBalanceDate" => "dateTime",
            "openingBalanceAccount" => "RecordRef",
            "stage" => "CustomerStage",
            "emailTransactions" => "boolean",
            "printTransactions" => "boolean",
            "faxTransactions" => "boolean",
            "defaultTaxReg" => "RecordRef",
            "syncPartnerTeams" => "boolean",
            "isBudgetApproved" => "boolean",
            "globalSubscriptionStatus" => "GlobalSubscriptionStatus",
            "salesReadiness" => "RecordRef",
            "salesTeamList" => "CustomerSalesTeamList",
            "buyingReason" => "RecordRef",
            "downloadList" => "CustomerDownloadList",
            "buyingTimeFrame" => "RecordRef",
            "addressbookList" => "CustomerAddressbookList",
            "subscriptionsList" => "SubscriptionsList",
            "contactRolesList" => "ContactAccessRolesList",
            "currencyList" => "CustomerCurrencyList",
            "creditCardsList" => "CustomerCreditCardsList",
            "partnersList" => "CustomerPartnersList",
            "groupPricingList" => "CustomerGroupPricingList",
            "itemPricingList" => "CustomerItemPricingList",
            "taxRegistrationList" => "CustomerTaxRegistrationList",
            "customFieldList" => "CustomFieldList",
            "internalId" => "string",
            "externalId" => "string",
        );
    }
}
