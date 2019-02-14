ALTER TABLE contacts ADD FULLTEXT INDEX `FullText_index_nom_prenom` (`nom` ASC, `prenom` ASC);

ALTER TABLE users ADD UNIQUE INDEX `index_unique_login` (`login` ASC);