<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class checkDatePlage implements Rule
{
    private $dateProgrammation;
    private $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($dateProgrammation)
    {
        $this->dateProgrammation = $dateProgrammation;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $dateProgrammation = new \DateTime($this->dateProgrammation);
        $datePlage = new \DateTime($value);
        if ($datePlage < $dateProgrammation){
            $this->message = 'La date de plage doit être supérieur ou égale à la date programmation';
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
