<?php

require_once __DIR__ . '/MappIntelligenceParameter.php';
require_once __DIR__ . '/MappIntelligenceCustomParameterMapping.php';

/**
 * Class MappIntelligenceCustomParameter
 */
class MappIntelligenceCustomParameter
{
    public static $CUSTOM_SESSION_PARAMETER;
    public static $CUSTOM_PAGE_PARAMETER;
    public static $CUSTOM_PRODUCT_PARAMETER;
    public static $CUSTOM_ACTION_PARAMETER;
    public static $CUSTOM_CAMPAIGN_PARAMETER;
    public static $CUSTOM_PAGE_CATEGORY;
    public static $CUSTOM_PRODUCT_CATEGORY;
    public static $CUSTOM_URM_CATEGORY;
}

MappIntelligenceCustomParameter::$CUSTOM_SESSION_PARAMETER = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_SESSION_PARAMETER
);
MappIntelligenceCustomParameter::$CUSTOM_PAGE_PARAMETER = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_PAGE_PARAMETER
);
MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_PARAMETER = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_PRODUCT_PARAMETER
);
MappIntelligenceCustomParameter::$CUSTOM_ACTION_PARAMETER = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_ACTION_PARAMETER
);
MappIntelligenceCustomParameter::$CUSTOM_CAMPAIGN_PARAMETER = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_CAMPAIGN_PARAMETER
);
MappIntelligenceCustomParameter::$CUSTOM_PAGE_CATEGORY = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_PAGE_CATEGORY
);
MappIntelligenceCustomParameter::$CUSTOM_PRODUCT_CATEGORY = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_PRODUCT_CATEGORY
);
MappIntelligenceCustomParameter::$CUSTOM_URM_CATEGORY = new MappIntelligenceCustomParameterMapping(
    MappIntelligenceParameter::$CUSTOM_URM_CATEGORY
);
