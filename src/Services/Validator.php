<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Services;

use CarlLee\NewebPayLogistics\Exceptions\NewebPayLogisticsException;
use ReflectionClass;

class Validator
{
    /**
     * Validate data against rules.
     *
     * @param array $data
     * @param array $rules
     * @return void
     * @throws NewebPayLogisticsException
     */
    public function validate(array $data, array $rules): void
    {
        foreach ($rules as $field => $ruleString) {
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                if ($rule === 'required') {
                    if (empty($data[$field])) {
                        throw new NewebPayLogisticsException("Missing required field: {$field}");
                    }
                } elseif (strpos($rule, 'class_const:') === 0) {
                    $className = substr($rule, 12);
                    if (!empty($data[$field])) {
                        $this->validateClassConst($data[$field], $className, $field);
                    }
                }
            }
        }
    }

    /**
     * Validate if value exists in class constants.
     *
     * @param mixed $value
     * @param string $className
     * @param string $field
     * @return void
     * @throws NewebPayLogisticsException
     */
    protected function validateClassConst($value, string $className, string $field): void
    {
        if (!class_exists($className)) {
             throw new NewebPayLogisticsException("Validation rule error: Class {$className} not found");
        }

        $reflection = new ReflectionClass($className);
        $constants = $reflection->getConstants();

        if (!in_array($value, $constants)) {
            throw new NewebPayLogisticsException("Invalid value '{$value}' for field {$field}");
        }
    }
}
