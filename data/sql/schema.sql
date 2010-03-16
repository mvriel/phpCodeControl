CREATE TABLE commit (id VARCHAR(200), project_id BIGINT, author TEXT, timestamp DATETIME, message TEXT, INDEX project_id_idx (project_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE file_change (id BIGINT AUTO_INCREMENT, commit_id VARCHAR(200), file_path TEXT, change_type TEXT, INDEX commit_id_idx (commit_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE project (id BIGINT AUTO_INCREMENT, name TEXT, scm_id BIGINT, INDEX scm_id_idx (scm_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE scm (id BIGINT AUTO_INCREMENT, type TEXT, host TEXT, username TEXT, password TEXT, path TEXT, PRIMARY KEY(id)) ENGINE = INNODB;
ALTER TABLE commit ADD CONSTRAINT commit_project_id_project_id FOREIGN KEY (project_id) REFERENCES project(id);
ALTER TABLE file_change ADD CONSTRAINT file_change_commit_id_commit_id FOREIGN KEY (commit_id) REFERENCES commit(id);
ALTER TABLE project ADD CONSTRAINT project_scm_id_scm_id FOREIGN KEY (scm_id) REFERENCES scm(id);
