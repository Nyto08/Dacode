<?php
declare(strict_types=1);
namespace dacode\metier;

use dacode\metier\Subscription;


class UserProfile {

    private int $id;
    private string $mail;
    private string $pseudo;
    private string $hashPwd;
    private string $role; // user or admin
    private string $date_creation;
    private ?string $date_sub;
    private Subscription $subscription;

    public function __construct(int $id, string $mail, string $pseudo, string $hashPwd, string $role, string $date_creation, ?string $date_sub, Subscription $subscription) {
        $this->setId($id);
        $this->setMail($mail);
        $this->setPseudo($pseudo);
        $this->setHashedPassword($hashPwd);
        $this->setDateCreation($date_creation);
        $this->setDateSub($date_sub);
        $this->setRole($role);
        $this->setSubscription($subscription);
    }

    public function setId(int $id) { $this->id = $id; }
    public function setMail(string $mail) { $this->mail = $mail; }
    public function setPseudo(string $pseudo) { $this->pseudo = $pseudo; }
    public function setHashedPassword(string $hashPwd) { $this->hashPwd = $hashPwd; }
    public function setDateSub(?string $date_sub) { $this->date_sub = $date_sub; }
    public function setDateCreation(string $date_creation) { $this->date_creation = $date_creation; }
    public function setRole(string $role) { $this->role = $role; }
    public function setSubscription(Subscription $subscription) { $this->subscription = $subscription; }

    public function getId(): int { return $this->id; }
    public function getMail(): string { return $this->mail; }
    public function getPseudo(): string { return $this->pseudo; }
    public function getHashedPassword(): string { return $this->hashPwd; }
    public function getDateSub(): ?string { return $this->date_sub; }
    public function getDateCreation(): string { return $this->date_creation; }
    public function getRole(): string { return $this->role; }
    public function getSubscription(): Subscription { return $this->subscription; }
}