// wiki : https://github.com/ajaxorg/ace/wiki
// events : https://ajaxorg.github.io/ace-api-docs/interfaces/ace.Ace.Editor.html#on.on-1

/*
À ajouter dans l'html puis par ex. pour utiliser editor.js -> import Editor from "./editor.js";
  <script src="../ace-editor/ace.js" type="text/javascript" charset="utf-8"></script>
  <script src="../ace-editor/ext-language_tools.js"></script>
*/


export class Editor {
    #editor;
    #updateCodeCallback;
    #langage;

    static codeChangeUpdateTimer = 500; // 0.8sec after code change -> update render
    static codeChangeUpdateTimeout = 0; // store setTimeout

    static modePath = "ace/mode/";
    static defaultTheme = "ace/theme/github_dark";

    // key/value : editor langage (editor SyntaxMode) / langage name
    static syntaxMode = {
        "html": "HTML",
        "css": "CSS",
        "javascript": "JavaScript",
    };

    // nodeIdToAttach = node ou attacher l'éditeur et callback = la fonction qui recevra le contenu de l'editeur
    constructor(nodeIdToAttach, langage = this.syntaxMode["html"], updateCallback = null, enableAutoCompletion = false, wrapEnabled = false) {
        this.#editor = ace.edit(nodeIdToAttach);
        this.#updateCodeCallback = updateCallback;
    
        // setup editor
        this.#editor.setTheme(Editor.defaultTheme);
    
        this.setLangage(langage);
    
        this.#editor.setOptions({
            enableBasicAutocompletion: true,
            // enableSnippets: true,
            // wrapBehavioursEnabled: true,
            wrap: wrapEnabled,
            enableLiveAutocompletion: enableAutoCompletion,
            fontSize: window.getComputedStyle(document.documentElement).fontSize
        });
    
        // render to the iframe if provided
        if (this.#updateCodeCallback !== null) {
            this.#editor.getSession().on("change", this.onCodeChange.bind(this));
        }
    }

    get editor() { return this.#editor }
    get callback() { return this.#updateCodeCallback }


    setLangage(langage) {
        if (Editor.syntaxMode.hasOwnProperty(langage)) {
            this.#editor.session.setMode(Editor.modePath + langage)
            this.#langage = langage;
        }
        else {
            console.error(`Langage : '${langage}' non supporté.`);
        }
    }
    getLangageEditor() { return this.#langage }

    getLangageName() {
        return Editor.syntaxMode[this.#langage];
    }

    getDataCode() { return this.#editor.getSession().getValue() }

    clearEditor() { this.#editor.setValue('') }

    destroyEditor() {
        this.#editor.getSession().off("change", this.onCodeChange);
        this.#editor.destroy();
    }
    
    /* ------ Event Listeners ------ */
    
    onCodeChange() {
        // will wait until the user has stopped typing for some time then call updateCodeChange()
        clearTimeout(Editor.codeChangeUpdateTimeout);
        Editor.codeChangeUpdateTimeout = setTimeout((() => { this.#updateCodeCallback() }), Editor.codeChangeUpdateTimer);
    }
}


export default Editor;