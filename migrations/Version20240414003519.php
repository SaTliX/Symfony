<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414003519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answers (id INT AUTO_INCREMENT NOT NULL, id_question_id INT DEFAULT NULL, label LONGTEXT NOT NULL, is_true TINYINT(1) NOT NULL, INDEX IDX_50D0C6066353B48 (id_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE qcm (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, time TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, id_qcm_id INT DEFAULT NULL, label LONGTEXT NOT NULL, image LONGTEXT DEFAULT NULL, INDEX IDX_B6F7494ED955F44B (id_qcm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, id_question_id INT DEFAULT NULL, label LONGTEXT NOT NULL, is_true TINYINT(1) NOT NULL, INDEX IDX_5FB6DEC76353B48 (id_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_answer (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, answers_id_id INT NOT NULL, qcm_id_id INT NOT NULL, question_id_id INT NOT NULL, result INT DEFAULT NULL, UNIQUE INDEX UNIQ_54EB92A59D86650F (user_id_id), UNIQUE INDEX UNIQ_54EB92A5F981B84 (answers_id_id), UNIQUE INDEX UNIQ_54EB92A5F16A9A2D (qcm_id_id), UNIQUE INDEX UNIQ_54EB92A54FAF8F53 (question_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6066353B48 FOREIGN KEY (id_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ED955F44B FOREIGN KEY (id_qcm_id) REFERENCES qcm (id)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC76353B48 FOREIGN KEY (id_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE student_answer ADD CONSTRAINT FK_54EB92A59D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student_answer ADD CONSTRAINT FK_54EB92A5F981B84 FOREIGN KEY (answers_id_id) REFERENCES answers (id)');
        $this->addSql('ALTER TABLE student_answer ADD CONSTRAINT FK_54EB92A5F16A9A2D FOREIGN KEY (qcm_id_id) REFERENCES qcm (id)');
        $this->addSql('ALTER TABLE student_answer ADD CONSTRAINT FK_54EB92A54FAF8F53 FOREIGN KEY (question_id_id) REFERENCES question (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93FF6241A6');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6066353B48');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ED955F44B');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC76353B48');
        $this->addSql('ALTER TABLE student_answer DROP FOREIGN KEY FK_54EB92A59D86650F');
        $this->addSql('ALTER TABLE student_answer DROP FOREIGN KEY FK_54EB92A5F981B84');
        $this->addSql('ALTER TABLE student_answer DROP FOREIGN KEY FK_54EB92A5F16A9A2D');
        $this->addSql('ALTER TABLE student_answer DROP FOREIGN KEY FK_54EB92A54FAF8F53');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE qcm');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE student_answer');
    }
}
