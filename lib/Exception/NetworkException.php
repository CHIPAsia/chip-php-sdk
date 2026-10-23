<?php

namespace Chip\Exception;

/**
 * A request failed before any HTTP response existed — DNS failure, connection
 * refused, TLS handshake error or timeout.
 *
 * This is still a `ChipApiException`, so `catch (ChipApiException $e)` covers
 * every failure the SDK can produce. `getCode()` is 0 (there is no HTTP status)
 * and `getResponseBody()` is null; the transport exception is available through
 * `getPrevious()`.
 */
class NetworkException extends ChipApiException
{
}
