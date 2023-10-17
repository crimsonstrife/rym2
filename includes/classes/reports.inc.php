<?php

abstract class Report
{
    public abstract function getReport(): array;

    public abstract function getReportById(int $id): array;

    public abstract function findReport(string $search): array;
}
