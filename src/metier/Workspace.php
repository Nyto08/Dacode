<?php
declare(strict_types=1);
namespace dacode\metier;

use dacode\metier\DataCode;


abstract class Workspace implements \JsonSerializable {
    private int         $id;
    private ?array      $dataCodeArr;
    private string      $dateCrea;
    private string      $dateModif;

    public function __construct(int $id, ?array $dataCodeArr, string $dateCrea, string $dateModif) {
        $this->setId($id);
        $this->setDataCodeArr($dataCodeArr);
        $this->setDateCrea($dateCrea);
        $this->setDateModif($dateModif);
    }

    private function setId(int $newId) { $this->id = $newId; }

    // public function pushDataCode(?DataCode $dataCode): void {
    //     $this->dataCodeArr[] = $dataCode;
    // }

    public function setDataCodeArr(?array $dataCodeArr): void {
        $this->dataCodeArr = $dataCodeArr;
    }

    private function setDateCrea(string $newDateCrea): void {
        $this->dateCrea = date('D M Y H:i:s', \strtotime($newDateCrea));
    }
    private function setDateModif(string $newDateModif): void {
        $this->dateModif = date('D M Y H:i:s', \strtotime($newDateModif));
    }

    public function getId(): int { return $this->id; }
    public function getDataCodeArr(): ?array { return $this->dataCodeArr; }
    public function getDateCrea(): string { return $this->dateCrea; }
    public function getDatModif(): string { return $this->dateModif; }

    public function __toString(): string {
        return '[' . self::class
            . ': id=' . $this->id
            . ', dataCodeArr=' . $this->dataCodeArr
            . ', dateCrea=' . $this->dateCrea
            . ', dateModif=' . $this->dateModif
            . ']';
    }

    public function jsonSerialize(): mixed {
        return [
            // 'id' => $this->id,
            'dataCodeArr' => $this->dataCodeArr,
            'dateCrea' => $this->dateCrea,
            'dateModif' => $this->dateModif
        ];
    }
}