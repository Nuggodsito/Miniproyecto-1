<?php
declare(strict_types=1);

/**
 * Clase Utilities (PSR-1: StudlyCaps).
 * Métodos estáticos para validación, sanitización, funciones matemáticas y helpers.
 */
class Utilities
{
    // --- Sanitización / Escape ---
    public static function escapeHtml(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public static function sanitizeString($value): string
    {
        // trim + eliminar caracteres de control y escapar para evitar XSS
        $s = trim((string)$value);
        $s = preg_replace('/[\x00-\x1F\x7F]/u', '', $s);
        return self::escapeHtml($s);
    }

    public static function sanitizeInt($value, $default = null)
    {
        if ($value === null) {
            return $default;
        }
        $filtered = filter_var($value, FILTER_VALIDATE_INT);
        return ($filtered === false) ? $default : (int)$filtered;
    }

    public static function sanitizeFloat($value, $default = null)
    {
        if ($value === null) {
            return $default;
        }
        $filtered = filter_var($value, FILTER_VALIDATE_FLOAT);
        return ($filtered === false) ? $default : (float)$filtered;
    }

    public static function sanitizeEmail($value, $default = null)
    {
        if ($value === null) {
            return $default;
        }
        $filtered = filter_var($value, FILTER_VALIDATE_EMAIL);
        return ($filtered === false) ? $default : $filtered;
    }

    public static function sanitizeUrl($value, $default = null)
    {
        if ($value === null) {
            return $default;
        }
        $filtered = filter_var($value, FILTER_VALIDATE_URL);
        return ($filtered === false) ? $default : $filtered;
    }

    // --- Validaciones ---
    public static function validateRegex($value, string $pattern): bool
    {
        return preg_match($pattern, (string)$value) === 1;
    }

    public static function validateIntRange($value, int $min, int $max): bool
    {
        $i = self::sanitizeInt($value, null);
        return ($i !== null) && ($i >= $min) && ($i <= $max);
    }

    // --- Helpers request / navigation ---
    public static function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }

    /**
     * Genera un enlace reutilizable para volver al menú.
     * Requisito PDF: "Que ese enlace esté en una función, pasar el parámetro URL."
     */
    public static function backToMenu(string $url, string $label = 'Volver al menú'): string
    {
        $u = self::escapeHtml($url);
        $l = self::escapeHtml($label);
        return "<p><a href=\"{$u}\">{$l}</a></p>";
    }

    // --- Funciones matemáticas útiles para los problemas ---
    public static function mean(array $values): float
    {
        $n = count($values);
        if ($n === 0) return 0.0;
        return array_sum($values) / $n;
    }

    public static function stdDev(array $values): float
    {
        $n = count($values);
        if ($n === 0) return 0.0;
        $mean = self::mean($values);
        $sum = 0.0;
        foreach ($values as $v) {
            $sum += pow($v - $mean, 2);
        }
        return sqrt($sum / $n);
    }

    public static function minValue(array $values)
    {
        return count($values) ? min($values) : null;
    }

    public static function maxValue(array $values)
    {
        return count($values) ? max($values) : null;
    }

    public static function power($base, $exp)
    {
        return pow($base, $exp);
    }

    // --- Accesores rápidos POST/GET con saneamiento ---
    public static function postString(string $name, $default = ''): string
    {
        return isset($_POST[$name]) ? self::sanitizeString($_POST[$name]) : $default;
    }

    public static function postInt(string $name, $default = null)
    {
        return isset($_POST[$name]) ? self::sanitizeInt($_POST[$name], $default) : $default;
    }

    public static function postFloat(string $name, $default = null)
    {
        return isset($_POST[$name]) ? self::sanitizeFloat($_POST[$name], $default) : $default;
    }
}