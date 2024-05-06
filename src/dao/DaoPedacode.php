<?php

declare(strict_types=1);

namespace dacode\dao;

use dacode\dao\Database;
use dacode\dao\DaoException;
use dacode\dao\Requests;
use dacode\metier\WorkspacePlayground;
use dacode\metier\DataCode;
use dacode\metier\Langage;
use dacode\metier\Chapter;
use dacode\metier\Lesson;
use dacode\metier\Category;
use dacode\metier\Goal;
use dacode\metier\UserProfile;
use dacode\metier\Subscription;

class DaoPedacode
{
    private \PDO $conn;

    public function __construct()
    {
        try {
            $this->conn = Database::getConnection();
        } catch (\Exception $e) {
            throw new \Exception('Exception DaoPedacode - constructor : ' .  $e->getMessage());
        }
    }

    // appelé à la connexion user uniquement
    public function getUserByPseudo(string $pseudo): ?UserProfile {
        $query = Requests::SELECT_USER_BY_PSEUDO;

        $user = null;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(1, $pseudo, \PDO::PARAM_STR);
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_OBJ);
            if ($row) {
                $subscription = new Subscription($row->id_sub, $row->name_sub, $row->type_sub);
                $user = new UserProfile($row->id_user, $row->mail_user, $row->pseudo_user, $row->pwd_user, $row->role_user, $row->crea_date, $row->date_sub, $subscription);
            }
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }

        return $user;
    }

    public function getUserById(int $id): ?UserProfile {
        $query = Requests::SELECT_USER_BY_ID;

        $user = null;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(1, $id, \PDO::PARAM_INT);
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_OBJ);
            if ($row) {
                $subscription = new Subscription($row->id_sub, $row->name_sub, $row->type_sub);
                $user = new UserProfile($row->id_user, $row->mail_user, $row->pseudo_user, $row->pwd_user, $row->role_user, $row->crea_date, $row->date_sub, $subscription);
            }
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }

        return $user;
    }

    public function addUserAccount(string $pseudo, string $email, string $password): bool {
        $query = Requests::ADD_USER_ACCOUNT;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(":id_sub", 1, \PDO::PARAM_INT);
            $statement->bindValue(":pwd_user", $password, \PDO::PARAM_STR);
            $statement->bindValue(":role_user", 'user', \PDO::PARAM_STR);
            $statement->bindValue(":mail_user", $email, \PDO::PARAM_STR);
            $statement->bindValue(":pseudo_user", $pseudo, \PDO::PARAM_STR);
            $statement->execute();
            $row = $statement->fetch();
            return true;
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    public function deleteUserAccountByPseudo(string $pseudo): bool {
        $query = Requests::DELETE_USER_BY_PSEUDO;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(":pseudo_user", $pseudo, \PDO::PARAM_STR);
            $statement->execute();
            $row = $statement->fetch();
            return true;
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    public function isUserExistByPseudo(string $pseudo): bool {
        $query = Requests::VERIFY_USER_BY_PSEUDO;
        
        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(1, $pseudo, \PDO::PARAM_STR);
            $statement->execute();
            $row = $statement->fetch();

            return isset($row['id_user']);
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    public function isUserExistByMail(string $mail): bool {
        $query = Requests::VERIFY_USER_BY_MAIL;
        
        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(1, $mail, \PDO::PARAM_STR);
            $statement->execute();
            $row = $statement->fetch();

            return isset($row['mail_user']);
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    public function getPlaygWorkspacesByUserId(int $userId): ?array
    {
        $playgWorkspaces = [];
        $query = Requests::SELECT_PLAYG_WORKSPACES_BY_USER_ID;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(1, $userId, \PDO::PARAM_INT);
            $statement->execute();
            while ($row = $statement->fetch(\PDO::FETCH_OBJ)) {
                $newPlaygWorkspace = new WorkspacePlayground($row->id_wk, null, $row->crea_wk, $row->modif_wk, $row->name_wk, $row->slot_idx_wk);
                $playgWorkspaces[] = $newPlaygWorkspace;
            }
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }

        return $playgWorkspaces;
    }

    public function getCodeFromPlaygSlot(int $slotIdx, int $userId): ?array
    {
        $dataCodeArr = [];
        $query = Requests::SELECT_CODE_FROM_PLAYG_SLOT;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':slot_idx', $slotIdx, \PDO::PARAM_INT);
            $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $statement->execute();
            while ($row = $statement->fetch(\PDO::FETCH_OBJ)) {
                $langage = new Langage($row->id_lang, $row->name_lang, $row->editor_lang);
                $dataCode = new DataCode($row->id_wk, $row->data_cod, $langage);
                $dataCodeArr[] = $dataCode;
            }
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }

        return $dataCodeArr;
    }

    public function addCodeFromWorkspace(DataCode $dataCode)
    {
        $query = Requests::INSERT_CODE_IN_WORKSPACE;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_wk', $dataCode->getId(), \PDO::PARAM_INT);
            $statement->bindValue(':id_lang', $dataCode->getLangage()->getId(), \PDO::PARAM_INT);
            $statement->bindValue(':data_code', $dataCode->getCode(), \PDO::PARAM_STR);
            $statement->execute();
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    public function deleteCodeFromWorkspace(int $idWk)
    {
        $query = Requests::DELETE_CODE_BY_WORKSPACE_ID;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_wk', $idWk, \PDO::PARAM_INT);
            $statement->execute();
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    // crée un workspace (mode playground) et renvoie l'id de celui-ci
    public function addPlaygWorkspace(int $userId, string $nameWk, int $slotIdx): ?int
    {
        $query = Requests::FUNC_CREATE_PLAYG_WORKSPACE;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $statement->bindValue(':name_wk', $nameWk, \PDO::PARAM_STR);
            $statement->bindValue(':slot_idx', $slotIdx, \PDO::PARAM_INT);
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_OBJ);
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }

        return $row->id_wk ?? null;
    }

    public function deletePlaygWorkspace(int $idWk): void
    {
        $query = Requests::DEL_PLAYG_WORKSPACE;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_wk', $idWk, \PDO::PARAM_INT);
            $statement->execute();
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    // renvoie l'id d'un workspace (pour le mode playground) si il existe
    public function getPlaygWorkspaceIdByUserId(int $slotIdx, int $userId): ?int
    {
        $query = Requests::SELECT_PLAYG_WORKSPACE_BY_USER_ID_AND_SLOT;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':slot_idx', $slotIdx, \PDO::PARAM_INT);
            $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_OBJ);
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }

        return $row->id_wk ?? null;
    }

    public function updatePlaygWorkspaceName(int $idWk, string $nameWk)
    {
        $query = Requests::UPDATE_PLAYG_WORKSPACE_NAME;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_wk', $idWk, \PDO::PARAM_INT);
            $statement->bindValue(':name_wk', $nameWk, \PDO::PARAM_STR);
            $statement->execute();
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }
    
    public function getLangages(): ?array
    {
        $langages = [];
        $query = Requests::SELECT_LANGAGES;

        try {
            $cursor = $this->conn->query($query);
            $cursor->execute();
            while ($row = $cursor->fetch(\PDO::FETCH_OBJ)) {
                $newLangage = new Langage($row->id_lang, $row->name_lang, $row->editor_lang);
                $langages[] = $newLangage;
            }
        } catch (\Exception $er) {
            //throw $th;
        } catch (\Error $er) {
            //throw $th;
        }

        return $langages;
    }

    public function getLangageByName(string $nameLang): ?Langage
    {
        $query = Requests::SELECT_LANGAGE_BY_NAME;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':name_lang', $nameLang, \PDO::PARAM_STR);
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_OBJ);
            $langage = new Langage($row->id_lang, $row->name_lang, $row->editor_lang);
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }

        return $langage;
    }

    public function getLangageById(int $idLang)
    {
        $query = Requests::SELECT_LANGAGE_BY_ID;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_lang', $idLang, \PDO::PARAM_INT);
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_OBJ);
            if ($row->id_lang) {
                $langage = new Langage($row->id_lang, $row->name_lang, $row->editor_lang);
                return $langage;
            }
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    public function updateLangage(Langage $langage)
    {
        $query = Requests::UPDATE_LANGAGE;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_lang', $langage->getId(), \PDO::PARAM_INT);
            $statement->bindValue(':name_lang', $langage->getName(), \PDO::PARAM_STR);
            $statement->bindValue(':editor_lang', $langage->getExtension(), \PDO::PARAM_STR);
            $statement->execute();
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }
    public function addLangage(Langage $langage)
    {
        $query = Requests::INSERT_LANGAGE;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_lang', $langage->getId(), \PDO::PARAM_INT);
            $statement->bindValue(':name_lang', $langage->getName(), \PDO::PARAM_STR);
            $statement->bindValue(':editor_lang', $langage->getExtension(), \PDO::PARAM_STR);
            $statement->execute();
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }

    public function delLangage(int $idLang)
    {
        $query = Requests::DELETE_LANGAGE;

        try {
            $statement = $this->conn->prepare($query);
            $statement->bindValue(':id_lang', $idLang, \PDO::PARAM_INT);
            $statement->execute();
        } catch (\Exception $er) {
            throw new \Exception($er->getMessage());
        } catch (\Error $er) {
            throw new \Error($er->getMessage());
        }
    }
}
