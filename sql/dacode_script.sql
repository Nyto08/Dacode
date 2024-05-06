-- Création base de données
create database pedacode;

-- Utilisation base de donnée
use pedacode;


-- Suppression tables
drop table if exists WkPlayg;
drop table if exists WkLessFork;
drop table if exists WkLessRepo;
drop table if exists Langage;
drop table if exists Code;
drop table if exists Workspace;
drop table if exists UserProfile;
drop table if exists Goal;
drop table if exists Lesson;
drop table if exists Subscription;
drop table if exists Category;
drop table if exists Chapter;


-- Création tables sur la base
-- Le moteur InnoDB est important pour le que ON DELETE CASCADE fonctionne
CREATE TABLE Category (
    id_cat INT AUTO_INCREMENT,
    name_cat VARCHAR(20) NOT NULL,
    PRIMARY KEY (id_cat)
) ENGINE=InnoDB;

CREATE TABLE Chapter (
    id_ch INT AUTO_INCREMENT,
    id_cat INT,
    title_ch VARCHAR(20) NOT NULL,
    PRIMARY KEY (id_ch),
    FOREIGN KEY (id_cat) REFERENCES Category(id_cat) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Subscription (
    id_sub INT AUTO_INCREMENT,
    name_sub VARCHAR(15) NOT NULL,
    type_sub VARCHAR(12) NOT NULL,
    PRIMARY KEY (id_sub)
) ENGINE=InnoDB;

CREATE TABLE UserProfile (
    id_user INT AUTO_INCREMENT,
    id_sub INT,
    pwd_user VARCHAR(64) NOT NULL,
    role_user ENUM('admin', 'user') DEFAULT 'user',
    mail_user VARCHAR(64) NOT NULL UNIQUE,
    pseudo_user VARCHAR(16) NOT NULL UNIQUE,
    crea_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_sub DATE,
    PRIMARY KEY (id_user),
    FOREIGN KEY (id_sub) REFERENCES Subscription(id_sub)
) ENGINE=InnoDB;

CREATE TABLE Workspace (
    id_wk INT AUTO_INCREMENT,
    id_user INT,
    crea_wk TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modif_wk TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_wk),
    FOREIGN KEY (id_user) REFERENCES UserProfile(id_user) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Langage (
    id_lang INT AUTO_INCREMENT,
    name_lang VARCHAR(16) NOT NULL,
    editor_lang VARCHAR(16) NOT NULL,
    PRIMARY KEY (id_lang)
) ENGINE=InnoDB;

CREATE TABLE Code (
    id_cod INT AUTO_INCREMENT,
    id_wk INT,
    id_lang INT,
    data_cod TEXT,
    PRIMARY KEY (id_cod),
    FOREIGN KEY (id_wk) REFERENCES Workspace(id_wk) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_lang) REFERENCES Langage(id_lang)
) ENGINE=InnoDB;

CREATE TABLE Lesson (
    id_les INT AUTO_INCREMENT,
    title_les VARCHAR(20),
    instr_les VARCHAR(4200),
    modif_les TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_ch INT,
    id_sub INT,
    PRIMARY KEY (id_les),
    FOREIGN KEY (id_ch) REFERENCES Chapter(id_ch) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_sub) REFERENCES Subscription(id_sub)
) ENGINE=InnoDB;

CREATE TABLE Goal (
    id_goal INT AUTO_INCREMENT,
    descr_goal VARCHAR(150),
    condi_goal VARCHAR(2500),
    id_les INT,
    PRIMARY KEY (id_goal),
    FOREIGN KEY (id_les) REFERENCES Lesson(id_les) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE WkPlayg (
    id_wkpg INT AUTO_INCREMENT,
    id_wk INT,
    slot_idx_wk TINYINT NOT NULL,
    name_wk VARCHAR(20),
    PRIMARY KEY (id_wkpg),
    FOREIGN KEY (id_wk) REFERENCES Workspace(id_wk) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE WkLessFork (
    id_wklesfork INT AUTO_INCREMENT,
    id_wk INT,
    id_les INT,
    is_completed BIT(1) DEFAULT 0,
    PRIMARY KEY (id_wklesfork),
    FOREIGN KEY (id_wk) REFERENCES Workspace(id_wk) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_les) REFERENCES Lesson(id_les) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE WkLessRepo (
    id_wklesrepo INT AUTO_INCREMENT,
    id_wk INT,
    id_les INT,
    PRIMARY KEY (id_wklesrepo),
    FOREIGN KEY (id_wk) REFERENCES Workspace(id_wk) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (id_les) REFERENCES Lesson(id_les) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Les indexes
create index idx_cod ON Code(id_cod);
create index idx_wk ON Workspace(id_wk);
create index idx_wk_pg ON WkPlayg(id_wkpg);
create index idx_wk_les_save ON WkLessFork(id_wklesfork);
create index idx_wk_les_cr ON WkLessRepo(id_wklesrepo);
create index idx_cat on Category(id_cat, name_cat);
create index idx_ch on Chapter(id_ch, title_ch);
create index idx_goal on Goal(id_goal);
create index idx_sub on Subscription(id_sub);
create index idx_les on Lesson(id_les);
create index idx_user on UserProfile(id_user, pseudo_user);
create index idx_lang on Langage(id_lang);


-- Suppression de trigger / fonctions
drop trigger if exists del_wk_cascade_les;
drop function if exists create_playg_workspace;
drop function if exists create_model_less_workspace;
drop function if exists create_fork_less_workspace;

delimiter //
-- Trigger quand une leçon est supprimée alors les workspaces associés le seront aussi
CREATE TRIGGER del_wk_cascade_les BEFORE DELETE ON Lesson
FOR EACH ROW
BEGIN
    DELETE FROM Workspace WHERE id_wk IN (SELECT id_wk FROM WkLessFork where WkLessFork.id_les = OLD.id_les);
END;
//

-- Fonction nécessaire pour créer un workspace, ainsi on récupère l'id
CREATE FUNCTION create_playg_workspace (idUser INT, nameWk VARCHAR(20), slotIdx INT) RETURNS INT
BEGIN
    INSERT INTO Workspace (id_user) VALUES (idUser);

    SET @idWk = LAST_INSERT_ID();

    INSERT INTO WkPlayg (id_wk, name_wk, slot_idx_wk) VALUES (@idWk, nameWk, slotIdx);

    RETURN @idWk;
END;
//
-- Fonction nécessaire pour créer un workspace repo (leçon créée par un admin), ainsi on récupère l'id
CREATE FUNCTION create_model_less_workspace (idUser INT, idLes INT) RETURNS INT
BEGIN
    INSERT INTO Workspace (id_user) VALUES (idUser);

    SET @idWk = LAST_INSERT_ID();

    INSERT INTO WkLessRepo (id_wk, id_les) VALUES (@idWk, idLes);

    RETURN @idWk;
END;
//
-- Fonction nécessaire pour créer un workspace fork (code d'une leçon sauvegardé par un utilisateur), ainsi on récupère l'id
CREATE FUNCTION create_fork_less_workspace (idUser INT, idLes INT, is_completed BIT(1)) RETURNS INT
BEGIN
    INSERT INTO Workspace (id_user) VALUES (idUser);

    SET @idWk = LAST_INSERT_ID();

    INSERT INTO WkLessFork (id_wk, id_les, is_completed) VALUES (@idWk, idLes, is_completed);

    -- Cette seconde partie de la fonction sert à récupérer le code pour pour le copier (fork)
    SELECT id_cod FROM Code WHERE id_wk IN (SELECT id_wk FROM WkLessRepo WHERE WkLessRepo.id_les = idLes)
        INTO @id_cod;
    
    SELECT id_lang, data_cod FROM Code WHERE id_cod = @id_cod
        INTO @id_lang, @data;

    INSERT INTO Code (id_wk, id_lang, data_cod) VALUES (@idWk, @id_lang, @data);

    RETURN @idWk;
END;
//

delimiter ;