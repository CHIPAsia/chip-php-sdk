<?php

declare(strict_types=1);

namespace Chip\Exception;

use Exception;

/**
 * Thrown when a money field (price, discount, totals, overrides) receives a value
 * that cannot be safely sent to the CHIP API — e.g. a float with a genuine
 * fractional sen component (108.5) or a non-numeric type. Extends the base
 * SDK exception so existing catch blocks keep working.
 */
class InvalidMoneyValueException extends Exception
{
}
