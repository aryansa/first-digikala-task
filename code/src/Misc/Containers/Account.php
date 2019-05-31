<?php

namespace App\Misc\Containers;


interface Account
{
    function getRoles(): array;

    function getEmail(): string;
}