<?php

declare(strict_types=1);

namespace gordon\pdowrapper\errorcode\mysql;

use gordon\pdowrapper\interface\errorcode\mysql\IErrorCodeEnum;

/**
 *
 * @package gordon\pdowrapper\errorcode\mysql
 * @license https://www.apache.org/licenses/LICENSE-2.0
 */
enum ClientError: string implements IErrorCodeEnum
{
    case CR_UNKNOWN_ERROR                         = "2000";
    case CR_SOCKET_CREATE_ERROR                   = "2001";
    case CR_CONNECTION_ERROR                      = "2002";
    case CR_CONN_HOST_ERROR                       = "2003";
    case CR_IPSOCK_ERROR                          = "2004";
    case CR_UNKNOWN_HOST                          = "2005";
    case CR_SERVER_GONE_ERROR                     = "2006";
    case CR_VERSION_ERROR                         = "2007";
    case CR_OUT_OF_MEMORY                         = "2008";
    case CR_WRONG_HOST_INFO                       = "2009";
    case CR_LOCALHOST_CONNECTION                  = "2010";
    case CR_TCP_CONNECTION                        = "2011";
    case CR_SERVER_HANDSHAKE_ERR                  = "2012";
    case CR_SERVER_LOST                           = "2013";
    case CR_COMMANDS_OUT_OF_SYNC                  = "2014";
    case CR_NAMEDPIPE_CONNECTION                  = "2015";
    case CR_NAMEDPIPEWAIT_ERROR                   = "2016";
    case CR_NAMEDPIPEOPEN_ERROR                   = "2017";
    case CR_NAMEDPIPESETSTATE_ERROR               = "2018";
    case CR_CANT_READ_CHARSET                     = "2019";
    case CR_NET_PACKET_TOO_LARGE                  = "2020";
    case CR_EMBEDDED_CONNECTION                   = "2021";
    case CR_PROBE_REPLICA_STATUS                  = "2022";
    case CR_PROBE_REPLICA_HOSTS                   = "2023";
    case CR_PROBE_REPLICA_CONNECT                 = "2024";
    case CR_PROBE_SOURCE_CONNECT                  = "2025";
    case CR_SSL_CONNECTION_ERROR                  = "2026";
    case CR_MALFORMED_PACKET                      = "2027";
    case CR_WRONG_LICENSE                         = "2028";
    case CR_NULL_POINTER                          = "2029";
    case CR_NO_PREPARE_STMT                       = "2030";
    case CR_PARAMS_NOT_BOUND                      = "2031";
    case CR_DATA_TRUNCATED                        = "2032";
    case CR_NO_PARAMETERS_EXISTS                  = "2033";
    case CR_INVALID_PARAMETER_NO                  = "2034";
    case CR_INVALID_BUFFER_USE                    = "2035";
    case CR_UNSUPPORTED_PARAM_TYPE                = "2036";
    case CR_SHARED_MEMORY_CONNECTION              = "2037";
    case CR_SHARED_MEMORY_CONNECT_REQUEST_ERROR   = "2038";
    case CR_SHARED_MEMORY_CONNECT_ANSWER_ERROR    = "2039";
    case CR_SHARED_MEMORY_CONNECT_FILE_MAP_ERROR  = "2040";
    case CR_SHARED_MEMORY_CONNECT_MAP_ERROR       = "2041";
    case CR_SHARED_MEMORY_FILE_MAP_ERROR          = "2042";
    case CR_SHARED_MEMORY_MAP_ERROR               = "2043";
    case CR_SHARED_MEMORY_EVENT_ERROR             = "2044";
    case CR_SHARED_MEMORY_CONNECT_ABANDONED_ERROR = "2045";
    case CR_SHARED_MEMORY_CONNECT_SET_ERROR       = "2046";
    case CR_CONN_UNKNOW_PROTOCOL                  = "2047";
    case CR_INVALID_CONN_HANDLE                   = "2048";
    case CR_UNUSED_1                              = "2049";
    case CR_FETCH_CANCELED                        = "2050";
    case CR_NO_DATA                               = "2051";
    case CR_NO_STMT_METADATA                      = "2052";
    case CR_NO_RESULT_SET                         = "2053";
    case CR_NOT_IMPLEMENTED                       = "2054";
    case CR_SERVER_LOST_EXTENDED                  = "2055";
    case CR_STMT_CLOSED                           = "2056";
    case CR_NEW_STMT_METADATA                     = "2057";
    case CR_ALREADY_CONNECTED                     = "2058";
    case CR_AUTH_PLUGIN_CANNOT_LOAD               = "2059";
    case CR_DUPLICATE_CONNECTION_ATTR             = "2060";
    case CR_AUTH_PLUGIN_ERR                       = "2061";
    case CR_INSECURE_API_ERR                      = "2062";
    case CR_FILE_NAME_TOO_LONG                    = "2063";
    case CR_SSL_FIPS_MODE_ERR                     = "2064";
    case CR_DEPRECATED_COMPRESSION_NOT_SUPPORTED  = "2065";
    case CR_COMPRESSION_WRONGLY_CONFIGURED        = "2066";
    case CR_KERBEROS_USER_NOT_FOUND               = "2067";
    case CR_LOAD_DATA_LOCAL_INFILE_REJECTED       = "2068";
    case CR_LOAD_DATA_LOCAL_INFILE_REALPATH_FAIL  = "2069";
    case CR_DNS_SRV_LOOKUP_FAILED                 = "2070";
    case CR_MANDATORY_TRACKER_NOT_FOUND           = "2071";
    case CR_INVALID_FACTOR_NO                     = "2072";
    case CR_CANT_GET_SESSION_DATA                 = "2073";
    case CR_INVALID_CLIENT_CHARSET                = "2074";
}
