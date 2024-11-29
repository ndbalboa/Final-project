<?php

if (!function_exists('splitFullName')) {
    /**
     * Split full name into first name, middle name, and last name.
     *
     * @param string $fullName
     * @return array
     */
    function splitFullName($fullName)
    {
        $nameParts = explode(' ', trim($fullName));
        $nameCount = count($nameParts);

        $firstName = $nameParts[0];
        $lastName = $nameParts[$nameCount - 1];
        $middleName = $nameCount > 2 ? implode(' ', array_slice($nameParts, 1, $nameCount - 2)) : null;

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName,
            'last_name' => $lastName
        ];
    }
}
