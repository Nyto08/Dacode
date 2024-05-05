<?php

declare(strict_types=1);

namespace dacode\controller;

use dacode\dao\DaoPedacode;
use dacode\controller\Message;
use dacode\controller\CtrlAuth;
use dacode\metier\DataCode;
use dacode\metier\Langage;
use dacode\metier\Workspace;

class CtrlDacode
{

    public const MAX_SAVE_SLOTS = 8;
    public const EMPTY_SLOT = "- Emplacement vide -";

    public function __construct(
        private DaoPedacode $daoPedacode = new DaoPedacode(),
    ) {
    }

    public function getIndex() {
        require './view/vindex.php';
    }

    public function getLogin() {
        

        // user connecté ? redirection vers mon compte
        if (isset($_SESSION['is-logged'])) {
            header('Location: ' . APP_ROOT . '/my-account');
            exit();
        }

        $msg = '';

        if (isset($_POST['pseudo']) && isset($_POST['password'])) {
            $ctrlAuth = new CtrlAuth();
            $ctrlAuth->connectUserAccount($_POST['pseudo'], $_POST['password'], $this->daoPedacode);
            if (isset($_SESSION['is-logged'])) {
                header('Location: ' . APP_ROOT . '/my-account');
                exit();
            }
            else {
                $msg = $ctrlAuth->getMessage();
            }
        }

        require './view/client/vlogIn.php';
    }

    public function getCreateAccount() {
        

        // user connecté ? redirection vers mon compte
        if (isset($_SESSION['is-logged'])) {
            header('Location: ' . APP_ROOT . '/my-account');
            exit();
        }

        $msg = '';

        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
            $ctrlAuth = new CtrlAuth();
            
            $isCreated = $ctrlAuth->createUserAccount($_POST['pseudo'], $_POST['email'], $_POST['password'], $this->daoPedacode);
            if ($isCreated) {
                header('Location: ' . APP_ROOT . '/my-account');
                exit();
            }
            else {
                $msg = $ctrlAuth->getMessage();
            }
        }

        require './view/client/vsignIn.php';
    }

    public function getMyAccount() {
        
        if (isset($_SESSION['is-logged'])) {
            $ctrlAuth = new CtrlAuth();
            $ctrlAuth->getLoggedUser($this->daoPedacode);
        }
        else {
            header('Location: ' . APP_ROOT . '/login');
            exit();
        }

        require './view/client/vmyAccount.php';
    }

    public function getLogout() {
        $ctrlAuth = new CtrlAuth();
        $ctrlAuth->logOut();

        // TODO : redigirer vers la dernière page
        header('Location: ' . APP_ROOT . '/accueil');
        exit();
    }

    public function getCursus() {
        
        require './view/client/vcursus.php';
    }

    public function getNotFound()
    {
        
        require './view/v404.php';
    }

    public function getPlayground(): void
    {
        // echo 'getPlayground';
        

        $mappedWorkspaces = array_fill(0, CtrlDacode::MAX_SAVE_SLOTS, CtrlDacode::EMPTY_SLOT);

        if (isset($_SESSION['is-logged'])) {
            $playgWorkspaces = $this->daoPedacode->getPlaygWorkspacesByUserId($_SESSION['id']);

            // Met les workspaces dans l'ordre des emplacements
            foreach ($playgWorkspaces as $workspace) {
                $mappedWorkspaces[$workspace->getSlotIndex()] = $workspace->getName();
            }
        }
        $workspacesSlots = $mappedWorkspaces;

        require './view/client/vplayground.php';
    }

    // ################################
    // ######### AJAX section #########
    // ################################

    function loadUserDataFromSlot(): void
    {
        

        $slotIndex = isset($_GET['slotIndex']) ? intval($_GET['slotIndex']) : -1;

        if (!is_numeric($slotIndex) || $slotIndex < 0 || $slotIndex > CtrlDacode::MAX_SAVE_SLOTS) {
            throw new \Exception(Message::INVALID_WORKSPACE_PLAYG_SLOT . ' : ' . $slotIndex);
        }
        $dataCodeArr = $this->daoPedacode->getCodeFromPlaygSlot($slotIndex, $_SESSION['id']);

        $encodedData = json_encode($dataCodeArr);

        echo $encodedData ? $encodedData : '';
        exit();
    }

    function deleteUserDataFromSlot(): void
    {
        

        $slotIndex = isset($_POST['slotIndex']) ? intval($_POST['slotIndex']) : -1;

        if (!is_numeric($slotIndex) || $slotIndex < 0 || $slotIndex > CtrlDacode::MAX_SAVE_SLOTS) {
            throw new \Exception(Message::INVALID_WORKSPACE_PLAYG_SLOT . ' : ' . $slotIndex);
        }
        $workspaceId = $this->daoPedacode->getPlaygWorkspaceIdByUserId($slotIndex, $_SESSION['id']);

        if ($workspaceId !== null) {
            $this->daoPedacode->deletePlaygWorkspace($workspaceId);
        }

        exit();
    }

    function saveUserDataFromSlot(): void
    {
        // json
        // code_data: liveEditor.editor.getValue(),
        // name_workspace: inputSlot.value,
        // langage_name: liveEditor.getLangage(),
        // langage_extension: liveEditor.getLangagePretty()

        

        $workspaceData = json_decode($_POST['dataJson'], true);
        $slotIndex = isset($workspaceData['slot_index']) ? intval($workspaceData['slot_index']) : -1;

        if (!is_numeric($slotIndex) || $slotIndex < 0 || $slotIndex > CtrlDacode::MAX_SAVE_SLOTS) {
            throw new \Exception(Message::INVALID_WORKSPACE_PLAYG_SLOT . ' : ' . $slotIndex);
        }

        if (
            !isset($workspaceData['code_data']) && !is_string($workspaceData['code_data'])
            || !isset($workspaceData['langage_name']) && !is_string($workspaceData['langage_name'])
            || !isset($workspaceData['langage_extension']) && !is_string($workspaceData['langage_extension'])
        ) {
            throw new \Exception(Message::INVALID_JSON_DATA);
        }

        $langage = $this->daoPedacode->getLangageByName($workspaceData['langage_extension']);

        // le langage est invalide ou n'existe pas dans la bdd
        // if ($langage === null) { return ''; } // TODO : throw error

        $workspaceId = $this->daoPedacode->getPlaygWorkspaceIdByUserId($slotIndex, $_SESSION['id']);

        // current workspace is null, create it
        if ($workspaceId === null) {
            $workspaceId = $this->daoPedacode->addPlaygWorkspace($_SESSION['id'], $workspaceData['name_workspace'], $slotIndex);
        }
        // Workspace exists, update name
        else {
            $this->daoPedacode->updatePlaygWorkspaceName($workspaceId, $workspaceData['name_workspace']);
        }

        // delete all code from workspace byb id
        $this->daoPedacode->deleteCodeFromWorkspace($workspaceId);

        // insert new codes
        $dataCode = new DataCode($workspaceId, $workspaceData['code_data'], $langage);
        $this->daoPedacode->addCodeFromWorkspace($dataCode);

        // TODO : return date if there is no name
        echo $workspaceData['name_workspace'];
        exit();
    }
}
