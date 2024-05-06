// wiki : https://github.com/ajaxorg/ace/wiki
// events : https://ajaxorg.github.io/ace-api-docs/interfaces/ace.Ace.Editor.html#on.on-1

/*
À ajouter dans l'html puis par ex. pour utiliser editor.js -> import Editor from "./editor.js";
  <script src="../ace-editor/ace.js" type="text/javascript" charset="utf-8"></script>
  <script src="../ace-editor/ext-language_tools.js"></script>
*/


export class Editor {
    #editor;
    #iframeRenderNode;
    
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

    // nodeIdToAttach = id de la div (sans le #) et iFrameNode = la référence du node (ce que retourne le querySelector)
    constructor(nodeIdToAttach, langage = this.syntaxMode["html"], iFrameNode = null, enableAutoCompletion = false, wrapEnabled = false) {
        this.#editor = ace.edit(nodeIdToAttach);
        this.#iframeRenderNode = iFrameNode;
    
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
        if (this.#iframeRenderNode !== null) {
            this.updateCodeChange();
            this.#editor.getSession().on("change", this.onCodeChange.bind(this));
        }
    }
    
    set placeholderCode(code) { this.#placeholderCode = code }

    get editor() { return this.#editor }
    get iframeRenderNode() { return this.#iframeRenderNode }
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
        /* !!! Pas de sécurité avec le code saisi, donc du code malveillant peut être exécuté,
        ici le CSP (content security policy) est utilisé pour minimiser les problèmes mais cela
        ne suffit pas contre des attaques XSS par exemple, enfin à partir d'ici plusieurs pistes
        sont envisageables comme l'utilisation de bibliothèques puis des checks avant
        de stocker dans la base de données. */
        this.#iframeRenderNode.setAttribute("srcdoc",
        `<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="Content-Security-Policy" content="default-src 'self; style-src 'self' 'unsafe-inline'">
        </head>
        <body>
        ${this.#editor.getSession().getValue()}
        </body>
        </html>`);
    }
    
    /* ------ Event Listeners ------ */
    
    onCodeChange() {
        // will wait until the user has stopped typing for some time then call updateCodeChange()
        clearTimeout(Editor.codeChangeUpdateTimeout);
        Editor.codeChangeUpdateTimeout = setTimeout((() => { this.updateCodeChange() }), Editor.codeChangeUpdateTimer);
    }
}


export default Editor;