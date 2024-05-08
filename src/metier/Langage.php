<?php

declare(strict_types=1);

namespace dacode\metier;

class Langage implements \JsonSerializable
{
    private int $id;
    private string $name;
    private string $extension;

    public function __construct(int $id, string $name, string $extension) {
        $this->setId($id);
        $this->setName($name);
        $this->setExtension($extension);
    }

    private function setId(int $newId) {
        $this->id = $newId;
    }
    private function setName(string $newname) {
        $this->name = $newname;
    }
    private function setExtension(string $newExtension) {
        $this->extension = $newExtension;
    }

    public function getId(): int {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getExtension(): string {
        return $this->extension;
    }

    public function __toString(): string {
        return
            '[' . self::class
            . ': id=' . $this->id
            . ', name=' . $this->name
            . ', extension=' . $this->extension
            . ']';
    }

    public function jsonSerialize(): mixed {
        return [
            // 'id' => $this->id,
            'name' => $this->name,
            'extension' => $this->extension
        ];
    }
}
