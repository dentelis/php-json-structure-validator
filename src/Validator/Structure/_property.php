<?php

namespace EntelisTeam\Validator\Structure;

abstract class _property
{
    function __construct(public _struct $type, public $required = true)
    {
    }
}