<?php
namespace Api\Data\Models;

class AccountModel
{
    public int $id;
    public string $shortCode;
    public string $title;
    public ?int $categoryId;
    public ?string $accountNumber;
    public ?string $bankIcon;
}
?>