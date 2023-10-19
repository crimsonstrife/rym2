<?php

/**
 * Majors interface file for the College Recruitment Application
 * Contains all the functions for the Majors interface.
 *
 * Author:  Patrick Barnhardt
 * Author Email: pbarnh1@wgu.edu
 * Date:   10/16/2023
 *
 * @package RYM2
 * Filename: major.inc.php
 * @version 1.0.0
 * @requires PHP 7.2.5+
 */

declare(strict_types=1); // Forces PHP to adhere to strict typing, if types do not match an error is thrown.

interface Major
{

    public function getAllMajors(): array;

    public function getMajor(int $major_id): array;

    public function addMajor(string $major_name, int $created_by): bool;

    public function updateMajor(int $major_id, string $major_name, int $updated_by): bool;

    public function deleteMajor(int $major_id): bool;

    public function getMajorNameById(int $major_id): string;

    public function getMajorByName(string $major_name): bool;

    public function getMajorIdByName(string $major_name): int;

    public function getMajorCreatedDate(int $major_id): string;

    public function getMajorCreatedBy(int $major_id): User;

    public function getMajorUpdatedDate(int $major_id): string;

    public function getMajorUpdatedBy(int $major_id): User;

    public function getMajorCount(): int;

    public function setMajorCreatedDate(int $major_id, string $created_at): bool;

    public function setMajorCreatedBy(int $major_id, int $user_id): bool;

    public function setMajorUpdatedDate(int $major_id, string $updated_at): bool;

    public function setMajorUpdatedBy(int $major_id, int $user_id): bool;
};
