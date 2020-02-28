<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 06/03/2019
 * Time: 12:46
 */

namespace Framework;

class Validator
{

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
//        dump($this->data);
    }

    /**
     * @param string ...$keys
     * @return Validator
     */
    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->get($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }
        return $this;
    }


    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->get($key);
            if (empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    public function email(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->get($key);
            if (!$this->isValideEmail($value)) {
                $this->addError($key, 'email');
            }
        }
        return $this;
    }

    public function length(string $key, ?int $min = null, ?int $max = null): self
    {
        $value = $this->get($key);
        $length = mb_strlen($value);
        if (!is_null($min) && !is_null($min) && ($min > $length || $max < $length)) {
            $this->addError($key, 'betwen', [$min, $max]);
            return $this;
        }

        if (!is_null($min) && $min > $length) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        }
        if (!is_null($max) && $max < $length) {
            $this->addError($key, 'maxLength', [$max]);
            return $this;
        }
        return $this;
    }

    public function password(string $key, ?int $min = null, ?int $max, ?array $options = null)
    {
        $value = $this->getValue($key);
        $pattern = '/^[a-z0-9]+(-?[a-z0-9]+)*$/';
        if (!is_null($value) && !preg_match($pattern, $value)) {
            $this->addError($key, 'slug');
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    private function get(string $key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    private function addError(string $key, string $rule, ?array $params = null): void
    {
        $this->errors[$key] = new ErrorsVadation($key, $rule, $params);
    }

    private function isValideEmail(string $value): bool
    {
//        $value = $this->getValue($key);
//        $pattern = '/^[a-z0-9]+(_?[a-z0-9]+)*@[a-z0-9]+(_?[a-z0-9]+)*$/';
//        if (!is_null($value) && !preg_match($pattern, $value)) {
//            $this->addError($key, 'slug');
//        }
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }


}