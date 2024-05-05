<?php
declare(strict_types=1);
namespace dacode\dao;

class DaoException extends \Exception {
    public const PARAM_KO               = "Parametre BDD indisponibles";
    public const CATEGORY_KO            = "Cette categorie est inexistante";
    public const CATEGORY_EXIST         = "Cette categorie existe déjà";
    public const USER_KO                = "Cet utilisateur est inexistant";
    public const LESSON_KO              = "Cette leçon est inexistant";
    public const LESSON_VIDE           = "Aucune leçon";
    public const LESSON_EXIST           = "Cette leçon existe déjà";
}