<?php

namespace App\Validation;

class CedulaRules
{
    /**
     * Valida si un número de cédula ecuatoriana es válido (Módulo 10)
     */
    public function validar_cedula(string $cedula): bool
    {
        $cedula = trim($cedula);

        if (!ctype_digit($cedula) || strlen($cedula) !== 10) {
            return false;
        }

        $provincia = (int) substr($cedula, 0, 2);
        if (($provincia < 1 || $provincia > 24) && $provincia !== 30) {
            return false;
        }

        $tercerDigito = (int) substr($cedula, 2, 1);
        if ($tercerDigito >= 6) {
            return false;
        }

        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $digitoVerificador = (int) substr($cedula, 9, 1);
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $valor = (int) substr($cedula, $i, 1) * $coeficientes[$i];
            if ($valor >= 10) {
                $valor -= 9;
            }
            $suma += $valor;
        }

        $residuo = $suma % 10;
        $resultado = ($residuo === 0) ? 0 : 10 - $residuo;

        return $resultado === $digitoVerificador;
    }
}