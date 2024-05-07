// wiki : https://github.com/ajaxorg/ace/wiki
// events : https://ajaxorg.github.io/ace-api-docs/interfaces/ace.Ace.Editor.html#on.on-1

/*
À ajouter dans l'html puis par ex. pour utiliser editor.js -> import Editor from "./editor.js";
  <script src="../ace-editor/ace.js" type="text/javascript" charset="utf-8"></script>
  <script src="../ace-editor/ext-language_tools.js"></script>
*/


export class Editor {
    #editor;
    #callback;
    
    // contient le code présent par defaut (quand l'utilisateur arrive sur la page ceci sera chargé)
    #placeholderCode;
    #langage;

    static codeChangeUpdateTimer = 500; // 0.8sec after code change -> update render
    static codeChangeUpdateTimeout = 0; // store setTimeout

    static modePath = "ace/mode/";
    static defaultTheme = "ace/theme/tomorrow_night_eighties";

    // key/value : editor langage (editor SyntaxMode) / langage name
    static syntaxMode = {
        "html": "HTML",
        "css": "CSS",
        "javascript": "JavaScript",
    };

    // nodeIdToAttach = node ou attacher l'éditeur et callback = la fonction qui recevra le contenu de l'editeur
    constructor(nodeIdToAttach, langage = this.syntaxMode["html"], callback = null, enableAutoCompletion = false, wrapEnabled = false) {
        this.#editor = ace.edit(nodeIdToAttach);
        this.#callback = callback;
    
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
    
        this.#editor.getSession().setValue(this.#placeholderCode);
    
        // render to the iframe if provided
        if (this.#callback !== null) {
            this.#editor.getSession().on("change", this.onCodeChange.bind(this));
        }
    }
    
    set placeholderCode(code) { this.#placeholderCode = code }

    get editor() { return this.#editor }
    get callback() { return this.#callback }
    get placeholderCode() { return this.#placeholderCode }


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

    setCodeToPlaceholder() { this.#editor.setValue(this.#placeholderCode) }

    clearEditor() { this.#editor.setValue('') }

    destroyEditor() {
        this.#editor.getSession().off("change", this.onCodeChange);
        this.#editor.destroy();
    }
    
    updateCodeChange() {
        this.#callback();
    }
    
    /* ------ Event Listeners ------ */
    
    onCodeChange() {
        // will wait until the user has stopped typing for some time then call updateCodeChange()
        clearTimeout(Editor.codeChangeUpdateTimeout);
        Editor.codeChangeUpdateTimeout = setTimeout((() => { this.updateCodeChange() }), Editor.codeChangeUpdateTimer);
    }
}


export default Editor;