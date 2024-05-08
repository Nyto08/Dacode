<?php
declare(strict_types=1);
namespace dacode\metier;

use dacode\metier\Workspace;


class WorkspacePlayground extends Workspace {
    private string      $name;
    private int         $slotIndex;

    public function __construct(int $id, ?array $dataCodeArr, string $dateCrea, string $dateModif, string $name, int $slotIndex) {
        parent::__construct($id, $dataCodeArr, $dateCrea, $dateModif);

        $this->setName($name);
        $this->setSlotIndex($slotIndex);
    }

    private function setName(string $newName): void {
        $this->name = \htmlspecialchars(\substr($newName, 0, 20)); // TODO : si name length = 0 mettre la date de modif
    }
    private function setSlotIndex(int $newSlotIndex): void {
        $this->slotIndex = $newSlotIndex; // TODO : limiter à 8
    }

    public function getName(): string { return $this->name; }
    public function getSlotIndex(): int { return $this->slotIndex; }

    public function __toString(): string {
        return '[' . self::class
            . ': name=' . $this->name
            . ', slotIndex=' . $this->slotIndex
            . ', parent=' . $this
            . ']';
    }
}