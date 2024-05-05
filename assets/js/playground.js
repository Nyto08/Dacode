import Editor from "./editor.js";

// user data access
const BtnReset = document.querySelector("#code-reset");
const BtnSave = document.querySelector("#code-save");
const BtnLoad = document.querySelector("#code-load");

// user data dialog elements
const modalUserData = document.querySelector("#user-data-modal");
const pSlotActionName = document.querySelector("#slot-action-text");
const divInputSlot = document.querySelector("#input-slot");
const inputSlot = document.querySelector("#slot-text");
const selectUserSlots = document.querySelector("#user-slots");
const btnCancelUserData = document.querySelector("#cancel-user-data");
const btnConfirmUserData = document.querySelector("#confirm-user-data");
const btnDeleteUserData = document.querySelector("#delete-user-data");

// dialog messages
const TXT_DATA_SAVE = "Sauvegarder";
const TXT_DATA_LOAD = "Charger";
const TXT_DATA_SAVE_WORK = "Sauvegarder votre travail";
const TXT_DATA_LOAD_WORK = "Charger votre travail";

const TXT_EMPTY_SLOT = '- Emplacement vide -';

const renderFrame = document.querySelector("#output");

let liveEditor;

function init() {
    liveEditor = new Editor("editor", Editor.syntaxMode.html, renderFrame, true, true);
}

function mountSaveModal() {
    pSlotActionName.innerText = TXT_DATA_SAVE_WORK;

    btnConfirmUserData.setAttribute("value", TXT_DATA_SAVE);
    btnConfirmUserData.innerText = TXT_DATA_SAVE;

    // remove tailwind display: none
    if (divInputSlot.classList.contains("hidden")) divInputSlot.classList.remove("hidden");

    // set the option's text field to have the save name except for '- Emplacement vide -'
    let saveName = selectUserSlots.children[selectUserSlots.selectedIndex].innerText;
    if (saveName.trim() !== TXT_EMPTY_SLOT){
        inputSlot.value = saveName;
        inputSlot.setSelectionRange(0, inputSlot.value.length);
    } else {
        inputSlot.value = '';
    }
}

function mountLoadModal() {
    pSlotActionName.innerText = TXT_DATA_LOAD_WORK;

    btnConfirmUserData.setAttribute("value", TXT_DATA_LOAD);
    btnConfirmUserData.innerText = TXT_DATA_LOAD;

    // add tailwind display: none
    if (!divInputSlot.classList.contains("hidden")) divInputSlot.classList.add("hidden");
}

function setEditorData(dataJson) {
    let data = JSON.parse(dataJson);

    // chaque index représente un éditeur, si il y a 3 index alors il y aura 3 éditeurs (par ex: html, css, js), pour le moment je charge que le 1er mais la bdd et tout le dao peuvent fonctionnent pour supporter plusieurs éditeurs sans problème.
    if (typeof Object && data.length !== 0){
        liveEditor.setLangage(data[0]['langage']['extension']);
        liveEditor.editor.getSession().setValue(data[0]['code']);
    }
}

function requestSaveDataFromSlot(slotIndex) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // traitement des données
                selectUserSlots.children[slotIndex].innerText = xhr.responseText;
            } else {
                console.error('Erreur lors de la sauvegarde des données utilisateur : ' + xhr.status);
            }
        }
    };

    // prep json data
    let data = {
        code_data: liveEditor.editor.getValue(),
        name_workspace: inputSlot.value,
        slot_index: slotIndex,
        langage_name: liveEditor.getLangage(),
        langage_extension: liveEditor.getLangagePretty()
    };

    // charge la fonction avec les paramètres (slotIndex, DataCode)
    let params = 'dataJson=' + JSON.stringify(data);

    // envoi la requête sur cette même page
    xhr.open('post', window.location.href + '/saveWorkspace', true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.send(params);
}

function requestLoadDataFromSlot(slotIndex) {
    let xhr = new XMLHttpRequest();
    let dataJson;

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // traitement des données
                dataJson = xhr.responseText;
                if (dataJson !== '') setEditorData(dataJson);
            } else {
                console.error('Erreur lors du chargement des données utilisateur : ' + xhr.status);
            }
        }
    };
    
    // charge la fonction avec le paramètre slotIndex
    let params = '?slotIndex=' + slotIndex;

    xhr.open('get', window.location.href + '/loadWorkspace' + params, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    
    xhr.send();
}

function requestDeleteDataFromSlot(slotIndex) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // traitement des données
                selectUserSlots.children[slotIndex].innerText = TXT_EMPTY_SLOT;
            } else {
                console.error('Erreur lors de la suppression des données utilisateur : ' + xhr.status);
            }
        }
    };
    
    // charge la fonction avec le paramètre slotIndex
    let params = 'slotIndex=' + slotIndex;

    // envoi la requête sur cette même page
    xhr.open('post', window.location.href + '/deleteWorkspace', true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.send(params);
}

/* ------ Event Listeners ------ */

function onResetCode() {
    liveEditor.clearEditor();
}

function onOpenDataModal() {
    btnCancelUserData.addEventListener("click", onCloseDataModal);
    btnConfirmUserData.addEventListener("click", onConfirmDataAction);
    btnDeleteUserData.addEventListener("click", onDeleteDataAction);

    modalUserData.showModal();
}

function onCloseDataModal() {
    // events are not removed when pressing escape ...
    btnCancelUserData.removeEventListener("click", onCloseDataModal);
    btnConfirmUserData.removeEventListener("click", onConfirmDataAction);
    btnDeleteUserData.removeEventListener("click", onDeleteDataAction);

    modalUserData.close();
}

function onConfirmDataAction() {
    // save or load has been clicked

    let optionIndex = parseInt(selectUserSlots.value);
    
    // on click save or load button on model then ..
    if (btnConfirmUserData.getAttribute("value") === TXT_DATA_SAVE) requestSaveDataFromSlot(optionIndex);
    else if (btnConfirmUserData.getAttribute("value") === TXT_DATA_LOAD) requestLoadDataFromSlot(optionIndex);

    onCloseDataModal();
}

function onDeleteDataAction() {
    requestDeleteDataFromSlot(parseInt(selectUserSlots.value));
    
    onCloseDataModal();
}

// user code data
BtnReset.addEventListener("click", onResetCode);
BtnSave.addEventListener("click", () => {
    onOpenDataModal();
    mountSaveModal();
});
BtnLoad.addEventListener("click", () => {
    onOpenDataModal();
    mountLoadModal();
});

/* ###### Logic Start Here ###### */

init();