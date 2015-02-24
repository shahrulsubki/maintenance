<?php

namespace Stevebauman\Maintenance\Validators;

/**
 * Class WorkRequestValidator
 * @package Stevebauman\Maintenance\Validators
 */
class WorkRequestValidator extends BaseValidator {

    protected $rules = array(
        'subject' => 'required|min:10',
        'description' => 'required|min:10',
        'best_time' => 'min:4',
    );

}