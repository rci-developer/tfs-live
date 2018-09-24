<?php
/**
 * Wherewithal - Tools
 *
 * @package wherewithal
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\wherewithal;

use \Exception;
use \Throwable;

class tools {

	// Quote and apostrophe curly=>straight.
	const QUOTE_CHARS = array(
		// Windows codepage 1252.
		"\xC2\x82"=>"'",		// U+0082⇒U+201A single low-9 quotation mark.
		"\xC2\x84"=>'"',		// U+0084⇒U+201E double low-9 quotation mark.
		"\xC2\x8B"=>"'",		// U+008B⇒U+2039 single left-pointing angle quotation mark.
		"\xC2\x91"=>"'",		// U+0091⇒U+2018 left single quotation mark.
		"\xC2\x92"=>"'",		// U+0092⇒U+2019 right single quotation mark.
		"\xC2\x93"=>'"',		// U+0093⇒U+201C left double quotation mark.
		"\xC2\x94"=>'"',		// U+0094⇒U+201D right double quotation mark.
		"\xC2\x9B"=>"'",		// U+009B⇒U+203A single right-pointing angle quotation mark.

		// Regular Unicode.		// U+0022 quotation mark (").
								// U+0027 apostrophe     (').
		"\xC2\xAB"=>'"',		// U+00AB left-pointing double angle quotation mark.
		"\xC2\xBB"=>'"',		// U+00BB right-pointing double angle quotation mark.
		"\xE2\x80\x98"=>"'",	// U+2018 left single quotation mark.
		"\xE2\x80\x99"=>"'",	// U+2019 right single quotation mark.
		"\xE2\x80\x9A"=>"'",	// U+201A single low-9 quotation mark.
		"\xE2\x80\x9B"=>"'",	// U+201B single high-reversed-9 quotation mark.
		"\xE2\x80\x9C"=>'"',	// U+201C left double quotation mark.
		"\xE2\x80\x9D"=>'"',	// U+201D right double quotation mark.
		"\xE2\x80\x9E"=>'"',	// U+201E double low-9 quotation mark.
		"\xE2\x80\x9F"=>'"',	// U+201F double high-reversed-9 quotation mark.
		"\xE2\x80\xB9"=>"'",	// U+2039 single left-pointing angle quotation mark.
		"\xE2\x80\xBA"=>"'",	// U+203A single right-pointing angle quotation mark.
	);

	// -----------------------------------------------------------------
	// Type Handling
	// -----------------------------------------------------------------

	/**
	 * Typecast Helper
	 *
	 * @param mixed $value Value.
	 * @param string $type Type.
	 * @return mixed Value.
	 */
	public static function typecast($value=null, $type=null) {
		if (!is_string($type) || !$type) {
			return $value;
		}

		switch (strtolower($type)) {
			case 'binary':
				return static::to_binary($value, false);
			case 'bool':
			case 'boolean':
				return static::to_bool($value, false);
			case 'int':
			case 'integer':
				return static::to_int($value, false);
			case 'double':
			case 'float':
				return static::to_float($value, false);
			case 'string':
				return static::to_string($value, false);
			case 'array':
				return static::to_array($value, false);
		}

		return $value;
	}

	/**
	 * Typecast: Int
	 *
	 * @param mixed $value Value.
	 * @param bool $recursive Recursive.
	 * @return int Value.
	 */
	public static function to_int($value=null, $recursive=true) {
		if (is_int($value)) {
			return $value;
		}

		// Recursive?
		if ($recursive && is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::to_int($v);
			}
		}
		else {
			try {
				// Strip out non-numbery bits.
				if (is_string($value)) {
					$value = (float) filter_var(
						$value,
						FILTER_SANITIZE_NUMBER_FLOAT,
						FILTER_FLAG_ALLOW_FRACTION
					);
				}

				// Convert to an int.
				$value = (int) $value;
			} catch (Throwable $e) {
				$value = 0;
			} catch (Exception $e) {
				$value = 0;
			}
		}

		return $value;
	}

	/**
	 * Typecast: Array
	 *
	 * @param mixed $value Value.
	 * @return array Value.
	 */
	public static function to_array($value=null) {
		try {
			$value = (array) $value;
		} catch (Throwable $e) {
			$value = array();
		} catch (Exception $e) {
			$value = array();
		}

		return $value;
	}

	/**
	 * Typecast: Bool
	 *
	 * @param mixed $value Value.
	 * @param bool $recursive Recursive.
	 * @return bool Value.
	 */
	public static function to_bool($value=null, $recursive=true) {
		if (is_bool($value)) {
			return $value;
		}

		// Recursive?
		if ($recursive && is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::to_bool($v);
			}
		}
		else {
			if (is_string($value)) {
				$value = strtolower($value);

				// Truthy strings.
				if (in_array($value, array('1', 'on', 'true', 'yes'), true)) {
					return true;
				}
				// Falsey strings.
				if (in_array($value, array('0', 'off', 'false', 'no'), true)) {
					return false;
				}
			}
			elseif (is_array($value)) {
				return !!count($value);
			}

			try {
				$value = (bool) $value;
			} catch (Throwable $e) {
				$value = false;
			} catch (Exception $e) {
				$value = false;
			}
		}

		return $value;
	}

	/**
	 * Typecast: Float
	 *
	 * @param mixed $value Value.
	 * @param bool $recursive Recurisve.
	 * @return float Value.
	 */
	public static function to_float($value=null, $recursive=true) {
		if (is_float($value)) {
			return $value;
		}

		// Recursive??
		if ($recursive && is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::to_float($v);
			}
		}
		else {
			try {
				// Strip out non-numbery bits.
				if (is_string($value)) {
					$value = (float) filter_var(
						$value,
						FILTER_SANITIZE_NUMBER_FLOAT,
						FILTER_FLAG_ALLOW_FRACTION
					);
				}

				$value = (float) $value;
			} catch (Throwable $e) {
				$value = 0.0;
			} catch (Exception $e) {
				$value = 0.0;
			}
		}

		return $value;
	}

	/**
	 * Typecast: Binary (1 or 0)
	 *
	 * @param mixed $value Value.
	 * @param bool $recursive Recursive.
	 * @return int Value.
	 */
	public static function to_binary($value=null, $recursive=true) {
		// Recursive??
		if ($recursive && is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::to_binary($v);
			}
		}
		else {
			return !!static::to_bool($value, false) ? 1 : 0;
		}

		return $value;
	}

	/**
	 * Typecast: String
	 *
	 * @param mixed $value Value.
	 * @param bool $stripslash Strip slashes.
	 * @param bool $recursive Recursive.
	 * @return string Value.
	 */
	public static function to_string($value=null, $stripslash=false, $recursive=true) {
		// Recursive??
		if ($recursive && is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::to_string($v, $stripslash);
			}
		}
		else {
			try {
				$value = (string) $value;
				$value = wp_check_invalid_utf8($value);

				// Borrow WP's octet-removal approach.
				while (preg_match('/%[\da-f]{2}/i', $value, $match)) {
					$value = str_replace($match[0], '', $value);
				}

				// In the context of a search, backslashes are
				// completely useless, so we can just remove them all
				// rather than running a more careful stripslashes.
				if ($stripslash) {
					$value = str_replace('\\', '', $value);
				}
			} catch (Throwable $e) {
				$value = '';
			} catch (Exception $e) {
				$value = '';
			}
		}

		return $value;
	}

	// ----------------------------------------------------------------- end type handling



	// -----------------------------------------------------------------
	// Misc Sanitize/Format
	// -----------------------------------------------------------------

	/**
	 * Sanitize Whitespace
	 *
	 * @param string $value Value.
	 * @return string Value.
	 */
	public static function whitespace($value=null) {
		// Recursive??
		if (is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::whitespace($v);
			}
		}
		else {
			try {
				$value = static::to_string($value, false, false);
				$value = preg_replace('/\s+/u', ' ', $value);
				$value = trim($value);
			} catch (Throwable $e) {
				$value = '';
			} catch (Exception $e) {
				$value = '';
			}
		}

		return $value;
	}

	/**
	 * Standardize Quotes
	 *
	 * @param string $value Value.
	 * @param bool $recursive Recursive.
	 * @return string Value.
	 */
	public static function quotes($value=null, $recursive=true) {
		if ($recursive && is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::quotes($v);
			}
			return $value;
		}

		$from = array_keys(static::QUOTE_CHARS);
		$to = array_values(static::QUOTE_CHARS);
		return str_replace($from, $to, static::to_string($value, false, false));
	}

	/**
	 * Escape SQL (once)
	 *
	 * The regular esc_sql() will keep backslashing until the world's
	 * supply of backslashes has run out. This removes all bakslashes
	 * and then runs esc_sql().
	 *
	 * Normally this could ruin data as it might strip necessary
	 * slashes too, however since this is focused on site searches,
	 * those don't really belong anyway.
	 *
	 * @param string $value Value.
	 * @return string Value.
	 */
	public static function sql($value=null) {
		// Recursive??
		if (is_array($value)) {
			foreach ($value as $k=>$v) {
				$value[$k] = static::sql($v);
			}
		}
		else {
			$value = static::to_string($value, true, false);
			$value = esc_sql($value);
		}

		return $value;
	}

	/**
	 * Parse Args
	 *
	 * This is like wp_parse_args() but is recursive, type-enforcing,
	 * and does not accept new keys.
	 *
	 * @param array $args Arguments.
	 * @param array $defaults Defaults.
	 * @return arry Parsed.
	 */
	public static function parse_args($args=null, $defaults=null) {
		$args = static::to_array($args);
		$defaults = static::to_array($defaults);

		if (!count($defaults)) {
			return array();
		}

		foreach ($defaults as $k=>$v) {
			if (array_key_exists($k, $args)) {
				// Recurse.
				if (is_array($defaults[$k]) && count($defaults[$k])) {
					$defaults[$k] = static::parse_args($args[$k], $defaults[$k]);
				}
				// Typecast.
				elseif (!is_null($v) && (gettype($args[$k]) !== gettype($v))) {
					$defaults[$k] = static::typecast($args[$k], gettype($v));
				}
				// Plain ol' copy.
				else {
					$defaults[$k] = $args[$k];
				}
			}
		}

		return $defaults;
	}

	/**
	 * Split String
	 *
	 * @param string $value Value.
	 * @return array Split.
	 */
	public static function str_explode($value=null) {
		$value = static::quotes($value, false);

		if ($value) {
			preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $value, $split);
			if (!is_array($split)) {
				$split = array();
			}
			else {
				$split = $split[0];
			}
		}
		else {
			$split = array();
		}

		// Get rid of enclosing quotes.
		foreach ($split as $k=>$v) {
			while (
				(0 === strpos($split[$k], '"')) &&
				('"' === substr($split[$k], -1))
			) {
				if ('"' === $split[$k]) {
					$split[$k] = '';
				}
				else {
					$split[$k] = substr($split[$k], 1, -2);
				}
			}
		}

		// Make sure we don't have any empty values.
		return array_values(array_filter($split, 'strlen'));
	}

	/**
	 * String Length
	 *
	 * Multi-byte safe string length when possible.
	 *
	 * @param string $value Value.
	 * @return int Length.
	 */
	public static function strlen($value=null) {
		$value = static::to_string($value, false, false);

		// Prefer mb_strlen.
		if (function_exists('mb_strlen')) {
			$length = mb_strlen($str);
			if (!is_int($length)) {
				$length = 0;
			}
		}
		else {
			$length = strlen($str);
		}

		return $length;
	}

	// ----------------------------------------------------------------- end sanitize/format
}

