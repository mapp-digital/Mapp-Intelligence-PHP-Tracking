<?php

/**
 * Interface MappIntelligenceMessages
 */
class MappIntelligenceMessages
{
    // Mapp Intelligence tracking
    public static $REQUIRED_TRACK_ID_AND_DOMAIN = 'The Mapp Intelligence "trackDomain" and "trackId" are required';
    public static $TO_LARGE_BATCH_SIZE = 'Batch size is larger than %s req. (%s req.)';
    public static $TO_LARGE_PAYLOAD_SIZE = 'Payload size is larger than 24MB (%sMB)';
    public static $CURL_PHP_EXTENSION_IS_REQUIRED = 'The cURL PHP extension is required to use the consumer %s';
    public static $EXEC_MUST_BE_EXIST = 'The \'exec\' function must be exist to use the consumer %s';
    public static $EXEC_MUST_BE_ENABLED = 'The \'exec\' function must be enabled to use the consumer %s';
    public static $GENERIC_ERROR = '%s (%s)';
    public static $IS_NOT_A_VALID_RESOURCE = '%s is not a valid stream resource (%s)';
    public static $CREATE_NEW_LOG_FILE = 'Create new file %s (%s) => %s';
    public static $USE_EXISTING_LOG_FILE = 'Use existing file %s (%s)';
    public static $CANNOT_RENAME_TEMPORARY_FILE = 'Create new file, because cannot rename temporary file';
    public static $DIRECTORY_NOT_EXIST = 'Directory not exist (%s)';
    public static $WRITE_BATCH_DATA = 'Write batch data in %s (%s req.)';
    public static $EXECUTE_COMMAND = 'Execute command: %s';
    public static $SEND_BATCH_DATA = 'Send batch data to %s (%s req.)';
    public static $BATCH_REQUEST_STATUS = 'Batch request responding the status code %s';
    public static $BATCH_RESPONSE_TEXT = '[%s]: %s';
    public static $REQUIRED_TRACK_ID_AND_DOMAIN_FOR_COOKIE;
    public static $REQUIRED_TRACK_ID_AND_DOMAIN_FOR_TRACKING;
    public static $TRACKING_IS_DEACTIVATED = 'Mapp Intelligence tracking is deactivated';
    public static $SENT_BATCH_REQUESTS = 'Sent batch requests, current queue size is %s req.';
    public static $BATCH_REQUEST_FAILED = 'Batch request failed!';
    public static $CURRENT_QUEUE_STATUS = 'Batch of %s req. sent, current queue size is %s req.';
    public static $QUEUE_IS_EMPTY = 'MappIntelligenceQueue is empty';
    public static $ADD_THE_FOLLOWING_REQUEST_TO_QUEUE = 'Add the following request to queue (%s req.): %s';
    public static $MAPP_INTELLIGENCE = '[Mapp Intelligence]: ';

    // Mapp Intelligence cronjob
    public static $REQUIRED_TRACK_ID = 'Argument "-i" or alternative "--trackId" are required';
    public static $REQUIRED_TRACK_DOMAIN = 'Argument "-d" or alternative "--trackDomain" are required';
    public static $UNSUPPORTED_OPTION = 'Unsupported config option (%s=%s)';
    public static $OPTION_TRACK_ID = 'Enter your Mapp Intelligence track ID provided by Mapp.';
    public static $OPTION_TRACK_DOMAIN = 'Enter your Mapp Intelligence tracking domain.';
    public static $OPTION_CONSUMER_TYPE = 'Enter your current file consumer type. '
        . 'Options: "FILE", "FILE_ROTATION". Defaults to "FILE".';
    public static $OPTION_CONFIG = 'Enter the path to your configuration file (*.ini).';
    public static $OPTION_FILENAME = 'Enter the path to your request logging file. '
        . 'Only relevant for file consumer type "FILE". Defaults to "%s".';
    public static $OPTION_FILE_PATH = 'Enter the path to your request logging files. '
        . 'Only relevant for file consumer type "FILE_ROTATION". Defaults to "%s".';
    public static $OPTION_FILE_PREFIX = 'Enter the prefix for your request logging files. '
        . 'Only relevant for file consumer type "FILE_ROTATION". Defaults to "%s".';
    public static $OPTION_DEACTIVATE = 'Deactivate the tracking functionality.';
    public static $OPTION_HELP = 'Display the help (this text) and exit.';
    public static $OPTION_DEBUG = 'Activates the debug mode. The debug mode sends messages to the command line.';
    public static $OPTION_VERSION = 'Display version and exit.';
    public static $REQUEST_LOG_FILES_NOT_FOUND = 'Request log files "%s" not found';
    public static $RENAME_EXPIRED_TEMPORARY_FILE = 'Rename expired temporary file into log file';
    public static $RENAMING_FAILED = 'Renaming from %s to %s failed';
    public static $HELP_SYNTAX = 'php cronjob.php';
    public static $HELP_HEADER = 'Send the logfile requests to the Mapp tracking server '
        . 'and delete your logfiles to keep it at a manageable size.';
    public static $HELP_FOOTER = '';
}

MappIntelligenceMessages::$REQUIRED_TRACK_ID_AND_DOMAIN_FOR_COOKIE
    = MappIntelligenceMessages::$REQUIRED_TRACK_ID_AND_DOMAIN . ' for user cookie';
MappIntelligenceMessages::$REQUIRED_TRACK_ID_AND_DOMAIN_FOR_TRACKING
    = MappIntelligenceMessages::$REQUIRED_TRACK_ID_AND_DOMAIN . ' to track data';

MappIntelligenceMessages::$OPTION_FILENAME
    = sprintf(MappIntelligenceMessages::$OPTION_FILENAME, sys_get_temp_dir() . '/MappIntelligenceRequests.log');
MappIntelligenceMessages::$OPTION_FILE_PATH
    = sprintf(MappIntelligenceMessages::$OPTION_FILE_PATH, sys_get_temp_dir() . '/');
MappIntelligenceMessages::$OPTION_FILE_PREFIX
    = sprintf(MappIntelligenceMessages::$OPTION_FILE_PREFIX, 'MappIntelligenceRequests');
