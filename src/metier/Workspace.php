<?php
declare(strict_types=1);
namespace dacode\metier;

use dacode\metier\DataCode;


abstract class Workspace {
    private int         $id;
    private ?array      $dataCode; // plus tard : array of DataCode (par exemple s'il y a un éditeur avec plusieurs langages)
    private string      $dateCrea;
    private string      $dateModif;

    public function __construct(int $id, ?DataCode $dataCode, string $dateCrea, string $dateModif) {
        $this->setId($id);
        $this->setDataCode($dataCode);
        $this->setDateCrea($dateCrea);
        $this->setDateModif($dateModif);
    }

    public function setId(int $newId) { $this->id = $newId; }

    public function setDataCode(?array $newDataCode): void {
        $this->dataCode = $newDataCode;
    }

    private function setDateCrea(string $newDateCrea): void {
        $this->dateCrea = date('D M Y H:i:s', \strtotime($newDateCrea));
    }
    private function setDateModif(string $newDateModif): void {
        $this->dateModif = date('D M Y H:i:s', \strtotime($newDateModif));
    }

    public function getId(): int { return $this->id; }
    public function getDataCode(): ?array { return $this->dataCode; }
    public function getDateCrea(): string { return $this->dateCrea; }
    public function getDatModif(): string { return $this->dateModif; }

    public function __toString(): string {
        return '[' . self::class
            . ': id=' . $this->id
            . ', dataCode=' . $this->dataCode
            . ', dateCrea=' . $this->dateCrea
            . ', dateModif=' . $this->dateModif
            . ']';
    }
}