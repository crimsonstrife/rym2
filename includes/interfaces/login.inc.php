<?php

/**
 * Login interface file for the College Recruitment Application
 * Contains all the login functions for users.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: login.inc.php
 * @version 1.0.0
 * @requires PHP 8.1.2+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

interface Login
{
    public function login($username, $password);
    public function logout();
}
