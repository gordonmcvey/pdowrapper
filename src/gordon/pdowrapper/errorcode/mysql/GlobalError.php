<?php

declare(strict_types=1);

namespace gordon\pdowrapper\errorcode\mysql;

use gordon\pdowrapper\interface\errorcode\mysql\IErrorCodeEnum;

/**
 * MySQL GlobalError enum
 *
 * This enum consists of a list of all the error codes and symbols listed on the MySQL global error reference page.
 * All cases where the error code refers to more than one symbol the newer symbol is always used
 *
 * @package gordon\pdowrapper\errorcode\mysql
 * @license https://www.apache.org/licenses/LICENSE-2.0
 * @link https://dev.mysql.com/doc/mysql-errors/8.0/en/global-error-reference.html
 */
enum GlobalError: string implements IErrorCodeEnum
{
    case EE_CANTCREATEFILE                                       = "1";
    case EE_READ                                                 = "2";
    case EE_WRITE                                                = "3";
    case EE_BADCLOSE                                             = "4";
    case EE_OUTOFMEMORY                                          = "5";
    case EE_DELETE                                               = "6";
    case EE_LINK                                                 = "7";
    case EE_EOFERR                                               = "9";
    case EE_CANTLOCK                                             = "10";
    case EE_CANTUNLOCK                                           = "11";
    case EE_DIR                                                  = "12";
    case EE_STAT                                                 = "13";
    case EE_CANT_CHSIZE                                          = "14";
    case EE_CANT_OPEN_STREAM                                     = "15";
    case EE_GETWD                                                = "16";
    case EE_SETWD                                                = "17";
    case EE_LINK_WARNING                                         = "18";
    case EE_OPEN_WARNING                                         = "19";
    case EE_DISK_FULL                                            = "20";
    case EE_CANT_MKDIR                                           = "21";
    case EE_UNKNOWN_CHARSET                                      = "22";
    case EE_OUT_OF_FILERESOURCES                                 = "23";
    case EE_CANT_READLINK                                        = "24";
    case EE_CANT_SYMLINK                                         = "25";
    case EE_REALPATH                                             = "26";
    case EE_SYNC                                                 = "27";
    case EE_UNKNOWN_COLLATION                                    = "28";
    case EE_FILENOTFOUND                                         = "29";
    case EE_FILE_NOT_CLOSED                                      = "30";
    case EE_CHANGE_OWNERSHIP                                     = "31";
    case EE_CHANGE_PERMISSIONS                                   = "32";
    case EE_CANT_SEEK                                            = "33";
    case EE_CAPACITY_EXCEEDED                                    = "34";
    case EE_DISK_FULL_WITH_RETRY_MSG                             = "35";
    case EE_FAILED_TO_CREATE_TIMER                               = "36";
    case EE_FAILED_TO_DELETE_TIMER                               = "37";
    case EE_FAILED_TO_CREATE_TIMER_QUEUE                         = "38";
    case EE_FAILED_TO_START_TIMER_NOTIFY_THREAD                  = "39";
    case EE_FAILED_TO_CREATE_TIMER_NOTIFY_THREAD_INTERRUPT_EVENT = "40";
    case EE_EXITING_TIMER_NOTIFY_THREAD                          = "41";
    case EE_WIN_LIBRARY_LOAD_FAILED                              = "42";
    case EE_WIN_RUN_TIME_ERROR_CHECK                             = "43";
    case EE_FAILED_TO_DETERMINE_LARGE_PAGE_SIZE                  = "44";
    case EE_FAILED_TO_KILL_ALL_THREADS                           = "45";
    case EE_FAILED_TO_CREATE_IO_COMPLETION_PORT                  = "46";
    case EE_FAILED_TO_OPEN_DEFAULTS_FILE                         = "47";
    case EE_FAILED_TO_HANDLE_DEFAULTS_FILE                       = "48";
    case EE_WRONG_DIRECTIVE_IN_CONFIG_FILE                       = "49";
    case EE_SKIPPING_DIRECTIVE_DUE_TO_MAX_INCLUDE_RECURSION      = "50";
    case EE_INCORRECT_GRP_DEFINITION_IN_CONFIG_FILE              = "51";
    case EE_OPTION_WITHOUT_GRP_IN_CONFIG_FILE                    = "52";
    case EE_CONFIG_FILE_PERMISSION_ERROR                         = "53";
    case EE_IGNORE_WORLD_WRITABLE_CONFIG_FILE                    = "54";
    case EE_USING_DISABLED_OPTION                                = "55";
    case EE_USING_DISABLED_SHORT_OPTION                          = "56";
    case EE_USING_PASSWORD_ON_CLI_IS_INSECURE                    = "57";
    case EE_UNKNOWN_SUFFIX_FOR_VARIABLE                          = "58";
    case EE_SSL_ERROR_FROM_FILE                                  = "59";
    case EE_SSL_ERROR                                            = "60";
    case EE_NET_SEND_ERROR_IN_BOOTSTRAP                          = "61";
    case EE_PACKETS_OUT_OF_ORDER                                 = "62";
    case EE_UNKNOWN_PROTOCOL_OPTION                              = "63";
    case EE_FAILED_TO_LOCATE_SERVER_PUBLIC_KEY                   = "64";
    case EE_PUBLIC_KEY_NOT_IN_PEM_FORMAT                         = "65";
    case EE_DEBUG_INFO                                           = "66";
    case EE_UNKNOWN_VARIABLE                                     = "67";
    case EE_UNKNOWN_OPTION                                       = "68";
    case EE_UNKNOWN_SHORT_OPTION                                 = "69";
    case EE_OPTION_WITHOUT_ARGUMENT                              = "70";
    case EE_OPTION_REQUIRES_ARGUMENT                             = "71";
    case EE_SHORT_OPTION_REQUIRES_ARGUMENT                       = "72";
    case EE_OPTION_IGNORED_DUE_TO_INVALID_VALUE                  = "73";
    case EE_OPTION_WITH_EMPTY_VALUE                              = "74";
    case EE_FAILED_TO_ASSIGN_MAX_VALUE_TO_OPTION                 = "75";
    case EE_INCORRECT_BOOLEAN_VALUE_FOR_OPTION                   = "76";
    case EE_FAILED_TO_SET_OPTION_VALUE                           = "77";
    case EE_INCORRECT_INT_VALUE_FOR_OPTION                       = "78";
    case EE_INCORRECT_UINT_VALUE_FOR_OPTION                      = "79";
    case EE_ADJUSTED_SIGNED_VALUE_FOR_OPTION                     = "80";
    case EE_ADJUSTED_UNSIGNED_VALUE_FOR_OPTION                   = "81";
    case EE_ADJUSTED_ULONGLONG_VALUE_FOR_OPTION                  = "82";
    case EE_ADJUSTED_DOUBLE_VALUE_FOR_OPTION                     = "83";
    case EE_INVALID_DECIMAL_VALUE_FOR_OPTION                     = "84";
    case EE_COLLATION_PARSER_ERROR                               = "85";
    case EE_FAILED_TO_RESET_BEFORE_PRIMARY_IGNORABLE_CHAR        = "86";
    case EE_FAILED_TO_RESET_BEFORE_TERTIARY_IGNORABLE_CHAR       = "87";
    case EE_SHIFT_CHAR_OUT_OF_RANGE                              = "88";
    case EE_RESET_CHAR_OUT_OF_RANGE                              = "89";
    case EE_UNKNOWN_LDML_TAG                                     = "90";
    case EE_FAILED_TO_RESET_BEFORE_SECONDARY_IGNORABLE_CHAR      = "91";
    case EE_FAILED_PROCESSING_DIRECTIVE                          = "92";
    case EE_PTHREAD_KILL_FAILED                                  = "93";
}
