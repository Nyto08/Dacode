<?php
declare(strict_types=1);

namespace dacode\controller;
use dacode\dao\DaoPedacode;
use dacode\metier\UserProfile;

class CtrlAuth {

    // Messages destinés à être lus par l'utilisateur.
    public const FORM_ERROR = 'Erreur dans le formulaire';
    public const FORM_MISSING_FILEDS = 'Veuillez renseigner tous les champs.';
    public const FORM_INTERNAL_ERROR = 'Une erreur interne s\'est produite.'; // dao/database error
    public const SIGN_IN_PSEUDO_REQUIREMENTS = 'Un pseudo peut être composé de lettres, chiffres, tirets, underscores et espaces et ne peut pas commencer ni terminer par un symbole et doit contenir au moins 4 lettres (caractères spéciaux non supportés).';
    public const SIGN_IN_PSEUDO_EXISTS = 'Ce pseudo est déjà utilisé.';
    public const SIGN_IN_INVALID_EMAIL = 'L\'adresse email n\'est pas valide.';
    public const SIGN_IN_EMAIL_EXISTS = 'Cet email est déjà utilisé.';
    public const SIGN_IN_PASSWORD_REQUIREMENTS = 'Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule, un chiffre et un symbole (@, $, !, %, *, ?, et &).';
    public const LOG_IN_WRONG_PSEUDO = 'Le pseudo est incorrect.';
    public const LOG_IN_WRONG_PASSWORD = 'Le mot de passe est incorrect.';

    
    private string $msg; // dès qu'une opération échoue, une error est systematiquement stockée.
    // session time ?

    public function __construct() {
        $this->msg = '';
    }

    public function getMessage(): string {
        return $this->msg;
    }

    public function connectUserAccount($pseudo, $password, DaoPedacode $dao = null): bool {
        if ($dao === null) {
            $dao = new DaoPedacode();
        }

        // fields check step
        if (!isset($pseudo) || !isset($password)) {
            $this->msg = CtrlAuth::FORM_MISSING_FILEDS;
        }
        elseif (!is_string($pseudo) || !is_string($password)) {
            $this->msg = CtrlAuth::FORM_ERROR;
        }

        // get user by pseudo
        try {
            $user = $dao->getUserByPseudo($pseudo);

            // user is null if not found in dtb
            if ($user === null) {
                $this->msg = CtrlAuth::LOG_IN_WRONG_PSEUDO;
            }
            // pwd check
            elseif (password_verify($password, $user->getHashedPassword())) {
                // ### logged in ###
                $this->logIn($user);
            }
            else {
                $user = null;
                $this->msg = CtrlAuth::LOG_IN_WRONG_PASSWORD;
            }
        }
        catch (\Exception $e) {
            $this->msg = CtrlAuth::FORM_INTERNAL_ERROR;
            // throw new \Error($e->getMessage());
        }
        catch (\Error $e) {
            $this->msg = CtrlAuth::FORM_INTERNAL_ERROR;
            // throw new \Error($e->getMessage());
        }

        return isset($_SESSION['is-logged']);
    }

    public function createUserAccount($pseudo, $email, $password, DaoPedacode $dao = null, $enableLogin = false): bool {
        if ($dao === null) {
            $dao = new DaoPedacode();
        }

        if ($this->validateCreateUserForm($pseudo, $email, $password, $dao)) {
            $hashedPw = password_hash($password, PASSWORD_DEFAULT);
            try {
                $dao->addUserAccount($pseudo, $email, $hashedPw);
                $isCreated = true;
                if ($enableLogin) {
                    $user = $dao->getUserByPseudo($pseudo);
                    $this->logIn($user);
                }
            } catch (\Exception $e) {
                $this->cleanUserFantom($pseudo, $dao); // on efface le user (si créé) car on ne connaît pas l'origine du problème.
                $isCreated = false;
                $this->msg = CtrlAuth::FORM_INTERNAL_ERROR;
            }
        } else {
            $isCreated = false;
        }

        return $isCreated;
    }

    public function validateCreateUserForm($pseudo, $email, $password, $dao): bool {
        define('PSEUDO_REGEX', '/^(?=(?:.*[A-Za-z]){4})(?=.{4,16}$)[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/');
        define('PASSWORD_REGEX', '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,64}$/');

        $isValidated = true;

        // fields check step
        if (!isset($pseudo) || !isset($email) || !isset($password)) {
            $isValidated = false;
            $this->msg = CtrlAuth::FORM_MISSING_FILEDS;
        }
        elseif (!is_string($pseudo) || !is_string($email) || !is_string($password)) {
            $isValidated = false;
            $this->msg = CtrlAuth::FORM_ERROR;
        }
        // pseudo step
        elseif (!preg_match(PSEUDO_REGEX, $pseudo)) {
            $this->msg = CtrlAuth::SIGN_IN_PSEUDO_REQUIREMENTS;
            $isValidated = false;
        }
        elseif ($dao->isUserExistByPseudo($pseudo)) {
            $this->msg = CtrlAuth::SIGN_IN_PSEUDO_EXISTS;
            $isValidated = false;
        }
        // email step
        elseif (filter_var($email, FILTER_VALIDATE_EMAIL) !== $email) {
            $this->msg = CtrlAuth::SIGN_IN_INVALID_EMAIL;
            $isValidated = false;
        }
        elseif ($dao->isUserExistByPseudo($email)) {
            $this->msg = CtrlAuth::SIGN_IN_EMAIL_EXISTS;
            $isValidated = false;
        }
        // password step
        elseif (!preg_match(PASSWORD_REGEX, $password)) {
            $this->msg = CtrlAuth::SIGN_IN_PASSWORD_REQUIREMENTS;
            $isValidated = false;
        }

        return $isValidated;
    }

    public function logIn($user): bool {
        if ($user instanceof UserProfile) {
            session_regenerate_id();
            $_SESSION['is-logged'] = true;
            $_SESSION['pseudo'] = $user->getPseudo();
            $_SESSION['id'] = $user->getId();
            $_SESSION['role'] = $user->getRole(); // ne change que l'affichage pas les accès ! (comme l'icone sur la navbar).
        }

        return isset($_SESSION['is-logged']);
    }

    public function logOut(): void {
        unset($_SESSION['is-logged']);
        unset($_SESSION['pseudo']);
        unset($_SESSION['id']);
        unset($_SESSION['role']);
        // session_destroy();
    }

    public function getLoggedUser($dao = null): ?UserProfile {
        if ($dao === null) {
            $dao = new DaoPedacode();
        }

        if (isset($_SESSION['is-logged'])) {
            try {
                $user = $dao->getUserById($_SESSION['id']);
            } catch (\Exception $e) {
                $user = null;
                $this->logOut();
                $this->msg = CtrlAuth::FORM_INTERNAL_ERROR;
            } catch (\Error $er) {
                $user = null;
                $this->logOut();
                $this->msg = CtrlAuth::FORM_INTERNAL_ERROR;
            }
        } else {
            $user = null;
        }

        return $user;
    }

    public function cleanUserFantom(string $pseudo, DaoPedacode $dao = null): void {
        if ($dao === null) {
            $dao = new DaoPedacode();
        }
        $dao->deleteUserAccountByPseudo($pseudo);
    }
}