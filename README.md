# DaCode
Une web app pour coder dans le navigateur.\
Comprend essentiellement un mode playground pour coder (HTML, CSS et JavaScript) avec une fenêtre de sortie, possède aussi un système de routage et d'authentification basique utilisateur.

![App Screenshot](/playground_showcase2.png?raw=true "playground")


## Installation

- Installer composer si ce n'est pas encore fait, puis mettre le chemin du src dans composer.json, puis mettez à jour le composer avec composer dump-autoload.
```bash
    "autoload": {
        "psr-4": {
            "dacode\\": "C:\\wamp64\\www\\dm\\daCode\\src"
        }
    },
```

- Ce projet utilise Tailwind CSS, pour installer le package, faites npm install sur le répertoire du projet avec le terminal.

- Changer le chemin du require_once par rapport à votre dossier vendor (de composer) présent dans src/index.php .

- Dans la base de données, copier coller le contenu du script sql.

- Adapter le fichier param.ini en conséquence de votre base de données.

## Pourquoi ce projet ?

DaCode portait le nom de PedaCode à l'origine est devait être une app avec des cours sur le dev développé par moi et Aïmane Bougtaïb, le script sql contient encore cet héritage.
