<?php

/**
 * This config file holds the default seeds when installing the maintenance application. They are completely optional
 */
return array(

    'priorities' => array(
        array(
            'name' => 'Low',
            'color' => 'default'
        ),
        array(
            'name' => 'Medium',
            'color' => 'warning',
        ),
        array(
            'name' => 'High',
            'color' => 'danger',
        ),
        array(
            'name' => 'Test',
            'color' => 'success'
        ),
    ),

    'statuses' => array(
        array(
            'name' => 'Open',
            'color' => 'danger',
        ),
        array(
            'name' => 'Closed',
            'color' => 'success'
        ),
        array(
            'name' => 'In Progress',
            'color' => 'warning'
        )
    ),

    'metrics' => array(
        array(
            'name' => 'Pieces',
            'symbol' => 'Pc',
        ),
        array(
            'name' => 'Grams',
            'symbol' => 'G',
        ),
        array(
            'name' => 'Kilograms',
            'symbol' => 'Kg',
        ),
        array(
            'name' => 'Tonnes',
            'symbol' => 'T',
        ),
        array(
            'name' => 'Millilitres',
            'symbol' => 'mL',
        ),
        array(
            'name' => 'Litres',
            'symbol' => 'L',
        )
    )

);