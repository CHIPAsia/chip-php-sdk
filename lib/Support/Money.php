<?php

declare(strict_types=1);

namespace Chip\Support;

use Chip\Exception\InvalidMoneyValueException;

/**
 * Shared numeric coercion helpers for CHIP money fields.
 *
 * CHIP Collect requires money fields (price, discount, total_price_override,
 * totals, overrides) to be integers in minor units (sen). Floating point math
 * upstream (e.g. ringgit * 100) frequently produces values that *are* whole
 * numbers but do not have an integer PHP type, such as 28.999999999999996 for
 * 0.29 * 100. JSON-encoding such a value sends a fractional number and the API
 * rejects it with 400 "A valid integer is required.".
 *
 * - A numeric value within 1e-9 of an integer is coerced to that integer
 *   (covers float precision noise, int-valued floats and numeric strings like
 *   "108.00").
 * - A numeric value with a genuine fractional part (e.g. 108.5) throws
 *   InvalidMoneyValueException instead of silently truncating (PHP's implicit
 *   int cast would send the wrong amount).
 *
 * Null passes through untouched so optional fields stay omitted.
 */
final class Money
{
    private const EPSILON = 1e-9;

    /**
     * Coerce a money value to the integer the CHIP API requires.
     *
     * @param int|float|string|null $value
     * @return ($value is null ? int|null : int)
     */
    public static function coerce(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (! is_numeric($value)) {
            throw new InvalidMoneyValueException(
                'Money value must be numeric (int, float or numeric string), got: '
                . get_debug_type($value)
            );
        }

        // int-valued numerics short-circuit without float conversion.
        if (is_int($value)) {
            return $value;
        }

        $float = (float) $value;

        if (! is_finite($float)) {
            throw new InvalidMoneyValueException('Money value must be a finite number.');
        }

        $rounded = round($float);

        if (abs($float - $rounded) < self::EPSILON) {
            return (int) $rounded;
        }

        throw new InvalidMoneyValueException(sprintf(
            'Money value must be a whole number of minor units; got %s. '
            . 'Round to sen before passing it to the SDK.',
            var_export($value, true)
        ));
    }
}
