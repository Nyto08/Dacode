<?php

declare(strict_types=1);

namespace dacode\controller;

use dacode\dao\DaoDacode;
use dacode\controller\Message;
use dacode\controller\CtrlAuth;
use dacode\metier\DataCode;
use dacode\metier\Langage;
use dacode\metier\Workspace;
use dacode\metier\WorkspacePlayground;
use dacode\metier\UserProfile;

class CtrlDacode
{

    public const MAX_SAVE_SLOTS = 8;
    public const EMPTY_SLOT = "- Emplacement vide -";

    public function __construct(
        private DaoDacode $daoPedacode = new DaoDacode(),
    ) {
    }

    public function getIndex() {
        require './view/vindex.php';
    }

    public function getAbout() {
        require './view/client/vabout.php';
    }

    public function getLogIn() {
        

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

    public function getSignIn() {
        

        // user connecté ? redirection vers mon compte
        if (isset($_SESSION['is-logged'])) {
            header('Location: ' . APP_ROOT . '/my-account');
            exit();
        }

        $msg = '';

        if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
            $ctrlAuth = new CtrlAuth();
            
            $isCreated = $ctrlAuth->createUserAccount($_POST['pseudo'], $_POST['email'], $_POST['password'], $this->daoPedacode, true);
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
            $user = $ctrlAuth->getLoggedUser($this->daoPedacode);
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

        header('Location: ' . APP_ROOT . '/accueil');
        exit();
    }

    public function getNotFound() {
        // is ajax
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            http_response_code(404);
            exit();
        } else {
            require './view/v404.php';
        }
    }

    public function getPlayground(): void {
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

    function loadUserDataFromSlot(): void {
        // is ajax
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');

            $slotIndex = isset($_GET['slotIndex']) ? intval($_GET['slotIndex']) : -1;
    
            if (!is_numeric($slotIndex) || $slotIndex < 0 || $slotIndex > CtrlDacode::MAX_SAVE_SLOTS) {
                throw new \Exception(Message::JSON_BAD_SLOT);
            }
            $dataCodeArr = $this->daoPedacode->getCodeFromPlaygSlot($slotIndex, $_SESSION['id']);
    
            $encodedData = json_encode($dataCodeArr);
    
            echo $encodedData ? $encodedData : '';
            exit();
        }
    }

    function deleteUserDataFromSlot(): void {
        // is ajax
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');

            // check for user infos
            if (isset($_SESSION['is-logged'])) {
                $user = $this->daoPedacode->getUserById($_SESSION['id']);
            }
            else {
                throw new \Exception(Message::UNAUTHENTICATED_ERROR, 401);
            }

            $slotIndex = isset($_POST['slotIndex']) ? intval($_POST['slotIndex']) : -1;
    
            if (!is_numeric($slotIndex) || $slotIndex < 0 || $slotIndex > CtrlDacode::MAX_SAVE_SLOTS) {
                throw new \Exception(Message::JSON_BAD_SLOT);
            }
            $workspacePlay = $this->daoPedacode->getPlaygWorkspaceByUserIdAndSlotNoCode($slotIndex, $user->getId());
    
            if ($workspacePlay !== null) {
                $this->daoPedacode->deletePlaygWorkspace($workspacePlay);
            }
    
            exit();
        }
    }

    function saveUserDataFromSlot(): void {
        // is ajax
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            
            // { json data format
            //     name_workspace: inputSlot.value,
            //     slot_index: slotIndex,
            //     editors: [
            //         {
            //             data_code: liveEditors[0].getDataCode(),
            //             langage_name: name: liveEditors[0].getLangageName()
            //         },
            //         {
            //             data_code: liveEditors[1].getDataCode(),
            //             ... etc... pour chaque éditeur
            //         },
            //     ]
            // }

            // check for user infos
            if (isset($_SESSION['is-logged'])) {
                $user = $this->daoPedacode->getUserById($_SESSION['id']);
            }
            else {
                throw new \Exception(Message::UNAUTHENTICATED_ERROR, 401);
                exit();
            }

            // data json decode
            if (!isset($_POST['dataJson'])) {
                throw new \Exception(Message::JSON_DECODE_ERROR, 400);
                exit();
            }

            $workspaceData = json_decode($_POST['dataJson'], true);

            // vérification des données primaires
            $WorkspaceName = isset($workspaceData['name_workspace']) ? trim($workspaceData['name_workspace']) : '';
            $slotIndex = isset($workspaceData['slot_index']) ? intval($workspaceData['slot_index']) : -1;
            $editorArr = isset($workspaceData['editors']) ? $workspaceData['editors'] : [];

            // check step
            if ($WorkspaceName === '') {
                throw new \Exception(Message::JSON_BAD_NAME, 400);
                exit();
            }
            if (count($editorArr) === 0) {
                throw new \Exception(Message::JSON_DATA_CODE_NOT_FOUND, 400);
                exit();
            }
            if (!is_numeric($slotIndex) || $slotIndex < 0 || $slotIndex > CtrlDacode::MAX_SAVE_SLOTS) {
                throw new \Exception(Message::JSON_BAD_SLOT, 400);
                exit();
            }

            // vérification de l'état du workspace playground dans la bdd pour traitement
            $workspacePlayg = $this->daoPedacode->getPlaygWorkspaceByUserIdAndSlotNoCode($slotIndex, $user->getId());
            if ($workspacePlayg === null) {
                $workspacePlayg = $this->daoPedacode->addPlaygWorkspace($user->getId(), $WorkspaceName, $slotIndex);
            }
            else {
                $this->daoPedacode->updatePlaygWorkspace($workspacePlayg);
            }

            $this->daoPedacode->deleteCodeFromWorkspace($workspacePlayg->getId());

            // add non empty code to workspace
            for ($i = 0; $i < count($editorArr); $i++) {
                $dataCode = $editorArr[$i]['data_code'];

                if ($dataCode !== '') {
                    $langage = $this->daoPedacode->getLangageByName($editorArr[$i]['langage_name']);

                    if ($langage === null) {
                        throw new \Exception(Message::JSON_BAD_LANGAGE, 400);
                        exit();
                    }

                    $this->daoPedacode->addCodeToWorkspace($workspacePlayg, $langage, $dataCode);
                }
            }

            echo json_encode([
                'name_workspace' => $workspacePlayg->getName(),
            ]);
            exit();
        }
    }
}
