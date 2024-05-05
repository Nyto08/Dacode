-- Jeu d'essais
use dacode;

INSERT INTO Subscription (name_sub, type_sub) VALUES
    ('Standard', 'none'),
    ('Premium', 'prime');

INSERT INTO Category (name_cat) VALUES
    ('ma categorie 1'),
    ('ma categorie 2'),
    ('ma categorie 3');

INSERT INTO Chapter (id_cat, title_ch) VALUES
    (1, 'mon chapitre 1'),
    (2, 'mon chapitre 2'),
    (3, 'mon chapitre 3');

INSERT INTO Lesson (title_les, instr_les, id_sub, id_ch) VALUES
    ('ma lesson 1', 'mon instruction 1', 1, 1),
    ('ma lesson 2', 'mon instruction 2', 1, 2),
    ('ma lesson 3', 'mon instruction 3', 2, 2);

INSERT INTO Goal (descr_goal, condi_goal, id_les) VALUES
    ('Créer un h1 avec le texte "Mon H1"', '{"Jeton": "test"}', 1),
    ('Créer un p avec le texte "Mon premier paragraphe"', '{"Jeton": "test"}', 1),
    ('Créer une div englobant le paragraphe', '{"Jeton": "test"}', 2);

INSERT INTO Langage (name_lang, editor_lang) VALUES
    ('HTML', 'html'),
    ('CSS', 'css'),
    ('JavaScript', 'javascript');

INSERT INTO UserProfile (id_sub, pwd_user, role_user, mail_user, pseudo_user, date_sub) VALUES
    (1, 'chassdo', 'admin', 'p5kI9@example.com', 'DarkRoger', '2020-01-01'),
    (1, '123456', 'user', 'k9QpJ@example.com', 'Mr RonChon', '2020-01-01');

select create_playg_workspace(1, 'Practice html', 0) as id_pg_wk1;
select create_playg_workspace(2, 'mon Workspace 1', 0) as id_pg_wk2;
select create_playg_workspace(2, 'mon Workspace 2', 1) as id_pg_wk3;

select create_repo_less_workspace(1, 1) as id_les_repo_wk1;
select create_repo_less_workspace(2, 2) as id_les_repo_wk2;
select create_repo_less_workspace(2, 3) as id_les_repo_wk3;

INSERT INTO Code (id_wk, id_lang, data_cod) VALUES
    (1, 1, '<h1>Hello World</h1>'),
    (2, 1, '<p>Papypuce<p>'),
    (2, 3, 'console.log(42)'),
    (3, 1, '<h1>Welcome to my page !</h1>'),
    (4, 1, '<h2>Welcome to my page !</h2>'),
    (5, 1, '<h3>Welcome to my page !</h3>'),
    (6, 1, '<h4>Welcome to my page !</h4>');

select create_fork_less_workspace(2, 3, 0) as id_les_fork_wk1;