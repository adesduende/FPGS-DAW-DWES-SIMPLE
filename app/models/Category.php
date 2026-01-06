<?php
namespace sportshop\app\models;

use \sportshop\app\utils\GUID;
class Category extends Identity
{
    public string $Name;
    public string $Description;

    public function __construct(?GUID $id, string $name, string $description)
    {
        parent::__construct($id);

        $this->Name = $name;
        $this->Description = $description;
    }
}
?>