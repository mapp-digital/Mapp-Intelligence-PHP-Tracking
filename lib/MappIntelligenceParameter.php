<?php

require_once __DIR__ . '/MappIntelligenceCustomParameter.php';

/**
 * Class MappIntelligenceParameter
 */
class MappIntelligenceParameter
{
    // special parameter
    public static $USER_AGENT = "X-WT-UA";
    public static $USER_IP = "X-WT-IP";

    // predefined parameter
    public static $EVER_ID = "eid";
    public static $CUSTOM_EVER_ID = "ceid";
    public static $PAGE_URL = "pu";
    public static $ACTION_NAME = "ct";
    public static $CAMPAIGN_ID = "mc";
    public static $CAMPAIGN_ACTION = "mca";
    public static $CUSTOMER_ID = "cd";
    public static $ORDER_VALUE = "ov";
    public static $ORDER_ID = "oi";
    public static $CURRENCY = "cr";
    public static $PAGE_NAME = "pn";
    public static $SEARCH = "is";
    public static $PRODUCT_ID = "ba";
    public static $PRODUCT_COST = "co";
    public static $PRODUCT_QUANTITY = "qn";
    public static $PRODUCT_STATUS = "st";

    // predefined custom parameter and category
    // predefined urm category
    public static $EMAIL;
    public static $EMAIL_RID;
    public static $EMAIL_OPTIN;
    public static $FIRST_NAME;
    public static $LAST_NAME;
    public static $TELEPHONE;
    public static $GENDER;
    public static $BIRTHDAY;
    public static $COUNTRY;
    public static $CITY;
    public static $POSTAL_CODE;
    public static $STREET;
    public static $STREET_NUMBER;
    public static $CUSTOMER_VALIDATION;
    // predefined e-commerce parameter
    public static $COUPON_VALUE;
    public static $PAYMENT_METHOD;
    public static $SHIPPING_SERVICE;
    public static $SHIPPING_SPEED;
    public static $SHIPPING_COSTS;
    public static $GROSS_MARGIN;
    public static $ORDER_STATUS;
    public static $PRODUCT_VARIANT;
    public static $PRODUCT_SOLD_OUT;
    // predefined page parameter
    public static $NUMBER_SEARCH_RESULTS;
    public static $ERROR_MESSAGES;
    public static $PAYWALL;
    public static $ARTICLE_TITLE;
    public static $CONTENT_TAGS;
    public static $PAGE_TITLE;
    public static $PAGE_TYPE;
    public static $PAGE_LENGTH;
    public static $DAYS_SINCE_PUBLICATION;
    public static $TEST_VARIANT;
    public static $TEST_EXPERIMENT;
    // predefined session parameter
    public static $LOGIN_STATUS;
    public static $VERSION;
    public static $TRACKING_PLATFORM;

    // custom parameter and category
    public static $CUSTOM_SESSION_PARAMETER = "cs";
    public static $CUSTOM_PAGE_PARAMETER = "cp";
    public static $CUSTOM_PRODUCT_PARAMETER = "cb";
    public static $CUSTOM_ACTION_PARAMETER = "ck";
    public static $CUSTOM_CAMPAIGN_PARAMETER = "cc";
    public static $CUSTOM_PAGE_CATEGORY = "cg";
    public static $CUSTOM_PRODUCT_CATEGORY = "ca";
    public static $CUSTOM_URM_CATEGORY = "uc";

    // cookie names
    public static $SMART_PIXEL_COOKIE_NAME = "wtstp_eid";
    public static $PIXEL_COOKIE_NAME = "wt3_eid";
    public static $SERVER_COOKIE_NAME_PREFIX = "wteid_";
}

// predefined urm category
MappIntelligenceParameter::$EMAIL = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(700);
MappIntelligenceParameter::$EMAIL_RID = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(701);
MappIntelligenceParameter::$EMAIL_OPTIN= MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(702);
MappIntelligenceParameter::$FIRST_NAME = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(703);
MappIntelligenceParameter::$LAST_NAME = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(704);
MappIntelligenceParameter::$TELEPHONE = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(705);
MappIntelligenceParameter::$GENDER = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(706);
MappIntelligenceParameter::$BIRTHDAY = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(707);
MappIntelligenceParameter::$COUNTRY = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(708);
MappIntelligenceParameter::$CITY = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(709);
MappIntelligenceParameter::$POSTAL_CODE = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(710);
MappIntelligenceParameter::$STREET = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(711);
MappIntelligenceParameter::$STREET_NUMBER = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(712);
MappIntelligenceParameter::$CUSTOMER_VALIDATION = MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY->with(713);

// predefined e-commerce parameter
MappIntelligenceParameter::$COUPON_VALUE = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(563);
MappIntelligenceParameter::$PAYMENT_METHOD = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(761);
MappIntelligenceParameter::$SHIPPING_SERVICE = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(762);
MappIntelligenceParameter::$SHIPPING_SPEED = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(763);
MappIntelligenceParameter::$SHIPPING_COSTS = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(764);
MappIntelligenceParameter::$GROSS_MARGIN = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(765);
MappIntelligenceParameter::$ORDER_STATUS = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(766);
MappIntelligenceParameter::$PRODUCT_VARIANT = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(767);
MappIntelligenceParameter::$PRODUCT_SOLD_OUT = MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER->with(760);

// predefined page parameter
MappIntelligenceParameter::$NUMBER_SEARCH_RESULTS = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(771);
MappIntelligenceParameter::$ERROR_MESSAGES = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(772);
MappIntelligenceParameter::$PAYWALL = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(773);
MappIntelligenceParameter::$ARTICLE_TITLE = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(774);
MappIntelligenceParameter::$CONTENT_TAGS = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(775);
MappIntelligenceParameter::$PAGE_TITLE = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(776);
MappIntelligenceParameter::$PAGE_TYPE = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(777);
MappIntelligenceParameter::$PAGE_LENGTH = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(778);
MappIntelligenceParameter::$DAYS_SINCE_PUBLICATION = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(779);
MappIntelligenceParameter::$TEST_VARIANT = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(781);
MappIntelligenceParameter::$TEST_EXPERIMENT = MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER->with(782);

// predefined session parameter
MappIntelligenceParameter::$LOGIN_STATUS = MappIntelligenceCustomParameter::$CUSTOM_SESSION_PARAMETER->with(800);
MappIntelligenceParameter::$VERSION = MappIntelligenceCustomParameter::$CUSTOM_SESSION_PARAMETER->with(801);
MappIntelligenceParameter::$TRACKING_PLATFORM = MappIntelligenceCustomParameter::$CUSTOM_SESSION_PARAMETER->with(802);
