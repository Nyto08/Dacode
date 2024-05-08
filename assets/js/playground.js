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

let liveEditors = [];

function init() {
    let editorHtml = document.getElementById("editor-area-html");
    let editorCss = document.getElementById("editor-area-css");
    let editorJs = document.getElementById("editor-area-javascript");
    
    liveEditors.push(new Editor(editorHtml, "html", updateEditorData, true, true));
    liveEditors.push(new Editor(editorCss, "css", updateEditorData, true, true));
    liveEditors.push(new Editor(editorJs, "javascript", updateEditorData, true, true));
}

function mountSaveModal() {
    pSlotActionName.innerText = TXT_DATA_SAVE_WORK;

    btnConfirmUserData.setAttribute("value", TXT_DATA_SAVE);
    btnConfirmUserData.innerText = TXT_DATA_SAVE;

    // remove tailwind display: none
    if (divInputSlot.classList.contains("hidden")) divInputSlot.classList.remove("hidden");

    // set the option's text field to have the save name except for '- Emplacement vide -'
    let saveName = selectUserSlots.children[selectUserSlots.selectedIndex].innerText.trim();
    if (saveName !== TXT_EMPTY_SLOT){
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

function setDataToEditor(data) {

    // clear each editor first
    liveEditors.forEach(editor => editor.clearEditor());

    /* une fois le workspace chargés avec le code et le langage associé,
    trouver l'éditeur dans lequel insérer ces données, si aucun éditeur n'a le langage du json,
    alors on change le langage de l'éditeur et on insère quand même le code. */
    data.dataCodeArr.forEach(dataCode => {
        let foundLangage = false;
        let langage = dataCode.langage.extension;

        // on cherche l'éditeur qui match le même langage que les données chargées.
        liveEditors.forEach(editor => {
            if (editor.getLangageEditor() === langage) {
                editor.setDataCode(dataCode.code);
                foundLangage = true;
            }
        });

        // si on ne l'a pas trouvé, on l'ajoute.
        if (!foundLangage) {
            liveEditors.setLangage(dataCode.langage.extension);
            liveEditors.setDataCode(dataCode.code);
        }
    });
}

function updateEditorData() {
    renderFrame.setAttribute("srcdoc",
    `<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
        <body>
        ${liveEditors[0].getDataCode()}
        <style>
        ${liveEditors[1].getDataCode()}
        </style>
        <script>
        ${liveEditors[2].getDataCode()}
        </script>
        </body>
    </html>`);
}

function requestSaveDataFromSlot(slotIndex) {
    let xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // traitement des données
                let data = JSON.parse(xhr.responseText);
                if (data !== null && data.name_workspace !== undefined) {
                    selectUserSlots.children[slotIndex].innerText = data.name_workspace;
                }
            } else {
                console.error('Erreur lors de la sauvegarde des données utilisateur : ' + xhr.status);
            }
        }
    };

    // prep json data
    let data = {
        name_workspace: inputSlot.value,
        slot_index: slotIndex,
        editors: [
            {
                data_code: liveEditors[0].getDataCode(),
                langage_name: liveEditors[0].getLangageName()
            },
            {
                data_code: liveEditors[1].getDataCode(),
                langage_name: liveEditors[1].getLangageName()
            },
            {
                data_code: liveEditors[2].getDataCode(),
                langage_name: liveEditors[2].getLangageName()
            }
        ]
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
    if (selectUserSlots.children[slotIndex].innerText === TXT_EMPTY_SLOT) return;

    let xhr = new XMLHttpRequest();
    let dataJson;

    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // traitement des données
                dataJson = JSON.parse(xhr.responseText);
                if (dataJson !== null) setDataToEditor(dataJson);
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
    if (selectUserSlots.children[slotIndex].innerText === TXT_EMPTY_SLOT) return;

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
    liveEditors.forEach(editor => {
        editor.clearEditor();
    });;
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
    
    // onCloseDataModal();
}

// function onLangageChange(event) {
//     // FIXME : find editor caller
//     liveEditors.setLangage(event.target.value);
// }

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