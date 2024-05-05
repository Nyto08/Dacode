<?php
declare(strict_types=1);
namespace dacode\metier;


class Subscription {

    private int $id;
    private string $subName;
    private string $subType;

    function __construct(int $id, string $subName, string $subType) {
        $this->setId($id);
        $this->setSubName($subName);
        $this->setSubType($subType);
    }

    public function setId(int $id) { $this->id = $id; }
    public function setSubName(string $subName) { $this->subName = $subName; }
    public function setSubType(string $subType) { $this->subType = $subType; }
    
    public function getId(): int { return $this->id; }
    public function getSubName(): string { return $this->subName; }
    public function getSubType(): string { return $this->subType; }
}
