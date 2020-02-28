<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 06/03/2019
 * Time: 12:46
 */

namespace Framework;


use function DI\string;

class Form
{

    private $serrond = 'div';

    private $errors = [];

    private $data = [];

    /**
     * @param string $serrond
     */
    public function setSerrond(string $serrond)
    {
        $this->serrond = $serrond;
    }

    private function serrond(string $html, ?string $class): string
    {
        return '<' . $this->serrond . ' class="' . $class . '">' . $html . '</' . $this->serrond . '>';
    }

    /**
     * Génère un champs
     *
     * @param string $name
     * @param null|string $label
     * @param null $value
     * @param array|null $options
     * @return string
     */
    public function field(string $name, ?string $label, $value = null, ?array $options = []): string
    {
        $type = $options['type'] ?? 'text';
        $placeholder = $options['placeholder'] ?? '';

        $class = 'from-group';

        if (array_key_exists($name, $this->data)) {
            $value = $this->data[$name];
        }
        $attributes = [
            'class' => 'form-control',
            'name' => $name,
            'id' => $name,
            'placeholder' => $placeholder
        ];

        if ($type === 'textarea') {
            $input = $this->textarea($attributes, $value);
        }elseif (array_key_exists('options', $options)){
            $option = $options['options'];
            $input = $this->select($attributes, $options, $value);
        }else{
            $attributes['type'] = $type;
            $input = $this->input($attributes, $value);
        }
        if ($label) {
            $input = '<label for="' . $name . '">' . $label . '</label>' . $input;
        }

        if (!empty($this->errors)) {
            if (key_exists($name, $this->errors)) {
                $class .= ' has-error';
            }
        }

        return $this->serrond($input, $class);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Génère un champs input (text, password, email)
     *
     * @param array $attributes
     * @param null $value
     * @return string
     */
    private function input(array $attributes, $value = null): string
    {
        $html = '<input ' . $this->getAttributeForm($attributes) . ' value="' . $value . '">';
        if (!empty($this->errors) && key_exists($attributes['name'], $this->errors)) {
            $html .= '<span class="text-danger">' . (string)$this->errors[$attributes['name']] . '</span>';
        }
        return $html;
    }

    /**
     * Génère un champs du type textarea
     *
     * @param array $attributes
     * @param null $value
     * @return string
     */
    private function textarea(array $attributes, $value = null): string
    {
        $html = '<textarea ' . $this->getAttributeForm($attributes) . '>' . $value . '</textarea>';
        if (!empty($this->errors) && key_exists($attributes['name'], $this->errors)) {
            $html .= '<span class="text-danger">' . (string)$this->errors[$attributes['name']] . '</span>';
        }
        return  $html;
    }

    /**
     * Génère un champs combox
     *
     * @param array $attributes
     * @param array $options
     * @param $value
     * @return string
     */
    private function select(array $attributes, array $options, $value)
    {
        dump($options);
        $html = '';
        foreach ($options as $k => $v) {
            if (is_array($k)) {
                $k = $k[0];
            }
            $html .= '<option value="' . $k . '">' . $v . '</option>';
        }
        return "<select " . $this->getAttributeForm($attributes) . ">{$html}</select>";
    }

    private function getAttributeForm(array $attributes) {
        $htmlPart = [];
        foreach ($attributes as $k => $v) {
            if ($v === true){
                $htmlPart [] = (string)$k;
            }else{
                $htmlPart[] = "$k='$v'";
            }
        }
        return implode(' ', $htmlPart);
    }


}