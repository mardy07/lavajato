<?php

namespace App\Validation;


use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{

    public $errors;

    public function validate($reequest, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try{
                $rule->setName(ucfirst($field))->assert($reequest->getParam($field));
            } catch (NestedValidationException $e) {
                $e->findMessages([
                    'email' => '{{name}} deve ser um email válido',
                    'lenght' => '{{name}} deve ter um tamanho entre {{minValue}} e {{maxValue}}',
                    'lenghtLower' => '{{name}} deve ter um comprimento maior que {{minValue}}',
                    'lenghtGreater' => '{{name}} deve ter um comprimento menor que {{maxValue}}',
                    'noWhitespace' => '{{name}} não pode conter espaços',
                    'notEmpty' => '{{name}} não pode estar vazio',
                    'cpf' => '{{name}} deve ser um número de CPF válido',
                    'cnpj' => '{{name}} deve ser um número de CNPJ válido',
                    'image' => '{{name}} deve ser uma imagem válida',
                    'max' => '{{name}} deve ser menor que {{interval}}',
                    'min' => '{{name}} deve ser maior que {{interval}}',
                    'numeric' => '{{name}} deve ser numérico',
                    'alnum' => '{{name}} deve conter apenas letras (a-z) e dígitos (0-9)',
                    'alpha' => '{{name}} deve conter apenas letras (a-z)',
                    'length' => '{{name}} deve ter um tamanho entre  {{minValue}} e {{maxValue}}'
                ]);
                $this->errors[$field] = $e->getMessages();
            }
        }
        $_SESSION["errors"] = $this->errors;
        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }

}
