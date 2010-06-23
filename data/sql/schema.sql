CREATE TABLE commit (id BIGINT AUTO_INCREMENT, revision VARCHAR(200) NOT NULL, scm_id BIGINT NOT NULL, author VARCHAR(100) NOT NULL, timestamp DATETIME NOT NULL, message TEXT NOT NULL, UNIQUE INDEX revision_idx (revision, scm_id), INDEX scm_id_idx (scm_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE file_change (id BIGINT AUTO_INCREMENT, commit_revision VARCHAR(200) NOT NULL, file_path TEXT NOT NULL, file_change_type_id BIGINT NOT NULL, insertions BIGINT, deletions BIGINT, INDEX commit_revision_idx (commit_revision), INDEX file_change_type_id_idx (file_change_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE file_change_type (id BIGINT AUTO_INCREMENT, name VARCHAR(50) NOT NULL, icon VARCHAR(50) NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE scm (id BIGINT AUTO_INCREMENT, name VARCHAR(100) NOT NULL, scm_type_id BIGINT NOT NULL, host TEXT NOT NULL, port BIGINT, username VARCHAR(255), password VARCHAR(255), path TEXT NOT NULL, INDEX scm_type_id_idx (scm_type_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE scm_type (id BIGINT AUTO_INCREMENT, name VARCHAR(50) NOT NULL, default_port BIGINT NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE commit ADD CONSTRAINT commit_scm_id_scm_id FOREIGN KEY (scm_id) REFERENCES scm(id);
ALTER TABLE file_change ADD CONSTRAINT file_change_file_change_type_id_file_change_type_id FOREIGN KEY (file_change_type_id) REFERENCES file_change_type(id);
ALTER TABLE file_change ADD CONSTRAINT file_change_commit_revision_commit_revision FOREIGN KEY (commit_revision) REFERENCES commit(revision);
ALTER TABLE scm ADD CONSTRAINT scm_scm_type_id_scm_type_id FOREIGN KEY (scm_type_id) REFERENCES scm_type(id);
