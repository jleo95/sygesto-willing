<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 06/03/2019
 * Time: 14:36
 */

namespace Framework;


use function DI\string;

class ErrorsVadation
{

    private $messages = [
        'fr' => [
            'required' => 'Le champs %s est obligatoire',
            'empty' => 'Le champs %s ne doit pas être vide',
            'betwen' => 'Le champs %s doit contenir entre %d et %d caractères',
            'minLength' => 'Le champs %s doit contenier au minimum % caractères',
            'maxLength' => 'Le champs %s doit contenier au maximum % caractères',
            'email' => 'L\'adresse emil saisie n\est pas correct'
        ]
    ];
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $rule;

    /**
     * @var array|null
     */
    private $params;

    /**
     * ErrorsVadation constructor.
     * @param string $key
     * @param string $rule
     * @param array|null $params
     */
    public function __construct(string $key, string $rule, ?array $params = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->params = $params;
    }

    public function __toString()
    {
        $rule = $this->messages['fr'][$this->rule];
        $params = [$rule, $this->key];
        if (!empty($this->params)) {
            foreach ($this->params as $param) {
                $params[] = $param;
            }
        }
//        $params = array_merge([$rule, $this->key], $this->params);
        return (string) call_user_func_array('sprintf', $params);
    }
}