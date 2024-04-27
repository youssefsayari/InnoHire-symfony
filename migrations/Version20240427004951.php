<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427004951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etablissement_quiz DROP FOREIGN KEY etablissement_quiz_ibfk_1');
        $this->addSql('ALTER TABLE etablissement_quiz DROP FOREIGN KEY etablissement_quiz_ibfk_2');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY question_ibfk_1');
        $this->addSql('DROP TABLE etablissement_quiz');
        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_pass');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('DROP INDEX id_publication_2 ON commentaire');
        $this->addSql('DROP INDEX id_publication ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_2');
        $this->addSql('ALTER TABLE commentaire CHANGE id_publication id_publiclation INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCD9C4EAC2 FOREIGN KEY (id_publiclation) REFERENCES post (id_post)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('CREATE INDEX IDX_67F068BCD9C4EAC2 ON commentaire (id_publiclation)');
        $this->addSql('DROP INDEX id_utilisateur ON commentaire');
        $this->addSql('CREATE INDEX IDX_67F068BC50EAE44 ON commentaire (id_utilisateur)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY etablissement_ibfk_1');
        $this->addSql('DROP INDEX code_etablissement ON etablissement');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY etablissement_ibfk_1');
        $this->addSql('ALTER TABLE etablissement ADD latitude INT NOT NULL, ADD longitude INT NOT NULL, CHANGE id_utilisateur id_utilisateur INT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('DROP INDEX id_utilisateur ON etablissement');
        $this->addSql('CREATE INDEX IDX_20FD592C50EAE44 ON etablissement (id_utilisateur)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT etablissement_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY messagerie_ibfk_1');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY messagerie_ibfk_2');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY messagerie_ibfk_1');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY messagerie_ibfk_2');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60CF624B39D FOREIGN KEY (sender_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C93173582 FOREIGN KEY (reciver_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('DROP INDEX sender_id ON messagerie');
        $this->addSql('CREATE INDEX IDX_14E8F60CF624B39D ON messagerie (sender_id)');
        $this->addSql('DROP INDEX reciver_id ON messagerie');
        $this->addSql('CREATE INDEX IDX_14E8F60C93173582 ON messagerie (reciver_id)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT messagerie_ibfk_1 FOREIGN KEY (sender_id) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT messagerie_ibfk_2 FOREIGN KEY (reciver_id) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY post_ibfk_1');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY post_ibfk_1');
        $this->addSql('ALTER TABLE post ADD total_reactions INT DEFAULT NULL, ADD nb_comments INT DEFAULT NULL, DROP totalReactions, DROP nbComments');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('DROP INDEX id_utilisateur ON post');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D50EAE44 ON post (id_utilisateur)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT post_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_3');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_2');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_3');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_2');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404D1AA708F FOREIGN KEY (id_post) REFERENCES post (id_post)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640450EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('DROP INDEX id_publication ON reclamation');
        $this->addSql('CREATE INDEX IDX_CE606404D1AA708F ON reclamation (id_post)');
        $this->addSql('DROP INDEX id_utilisateur ON reclamation');
        $this->addSql('CREATE INDEX IDX_CE60640450EAE44 ON reclamation (id_utilisateur)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_3 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_2 FOREIGN KEY (id_post) REFERENCES post (id_post) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur CHANGE cin cin INT DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE mdp mdp VARCHAR(255) DEFAULT NULL, CHANGE role role INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY wallet_ibfk_1');
        $this->addSql('DROP INDEX id_etablissement ON wallet');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY wallet_ibfk_1');
        $this->addSql('ALTER TABLE wallet CHANGE balance balance INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F9ED58849 FOREIGN KEY (id_etablissement) REFERENCES etablissement (id_etablissement)');
        $this->addSql('DROP INDEX id_etablissement_2 ON wallet');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C68921F9ED58849 ON wallet (id_etablissement)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT wallet_ibfk_1 FOREIGN KEY (id_etablissement) REFERENCES etablissement (id_etablissement) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etablissement_quiz (id_etablissement_quiz INT AUTO_INCREMENT NOT NULL, id_etablissement INT NOT NULL, id_quiz INT NOT NULL, visibilite INT DEFAULT 1 NOT NULL, INDEX id_etablissement (id_etablissement), INDEX id_quiz (id_quiz), PRIMARY KEY(id_etablissement_quiz)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE logs (id_log INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, id_etablissement INT NOT NULL, id_wallet INT NOT NULL, id_publication INT NOT NULL, id_commentaire INT NOT NULL, id_quiz INT NOT NULL, id_reclamation INT NOT NULL, id_message INT NOT NULL, PRIMARY KEY(id_log)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE question (id_question INT AUTO_INCREMENT NOT NULL, id_quiz INT NOT NULL, question VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, choix VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, reponse_correcte INT NOT NULL, INDEX id_quiz (id_quiz), PRIMARY KEY(id_question)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quiz (id_quiz INT AUTO_INCREMENT NOT NULL, code_quiz INT NOT NULL, nom_quiz VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix_quiz INT NOT NULL, image_quiz VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX code_quiz (code_quiz), PRIMARY KEY(id_quiz)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quiz_pass (idquiz_pass INT AUTO_INCREMENT NOT NULL, id_candidat INT NOT NULL, code_quiz INT NOT NULL, score INT NOT NULL, PRIMARY KEY(idquiz_pass)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE etablissement_quiz ADD CONSTRAINT etablissement_quiz_ibfk_1 FOREIGN KEY (id_etablissement) REFERENCES etablissement (id_etablissement) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement_quiz ADD CONSTRAINT etablissement_quiz_ibfk_2 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT question_ibfk_1 FOREIGN KEY (id_quiz) REFERENCES quiz (id_quiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCD9C4EAC2');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC50EAE44');
        $this->addSql('DROP INDEX IDX_67F068BCD9C4EAC2 ON commentaire');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC50EAE44');
        $this->addSql('ALTER TABLE commentaire CHANGE id_publiclation id_publication INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (id_publication) REFERENCES post (id_post) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_2 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX id_publication_2 ON commentaire (id_publication)');
        $this->addSql('CREATE INDEX id_publication ON commentaire (id_publication)');
        $this->addSql('DROP INDEX idx_67f068bc50eae44 ON commentaire');
        $this->addSql('CREATE INDEX id_utilisateur ON commentaire (id_utilisateur)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C50EAE44');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C50EAE44');
        $this->addSql('ALTER TABLE etablissement DROP latitude, DROP longitude, CHANGE id_utilisateur id_utilisateur INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT etablissement_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX code_etablissement ON etablissement (code_etablissement)');
        $this->addSql('DROP INDEX idx_20fd592c50eae44 ON etablissement');
        $this->addSql('CREATE INDEX id_utilisateur ON etablissement (id_utilisateur)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60CF624B39D');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C93173582');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60CF624B39D');
        $this->addSql('ALTER TABLE messagerie DROP FOREIGN KEY FK_14E8F60C93173582');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT messagerie_ibfk_1 FOREIGN KEY (sender_id) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT messagerie_ibfk_2 FOREIGN KEY (reciver_id) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_14e8f60c93173582 ON messagerie');
        $this->addSql('CREATE INDEX reciver_id ON messagerie (reciver_id)');
        $this->addSql('DROP INDEX idx_14e8f60cf624b39d ON messagerie');
        $this->addSql('CREATE INDEX sender_id ON messagerie (sender_id)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60CF624B39D FOREIGN KEY (sender_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE messagerie ADD CONSTRAINT FK_14E8F60C93173582 FOREIGN KEY (reciver_id) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D50EAE44');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D50EAE44');
        $this->addSql('ALTER TABLE post ADD totalReactions INT DEFAULT NULL, ADD nbComments INT DEFAULT NULL, DROP total_reactions, DROP nb_comments');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT post_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_5a8a6c8d50eae44 ON post');
        $this->addSql('CREATE INDEX id_utilisateur ON post (id_utilisateur)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D50EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404D1AA708F');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640450EAE44');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404D1AA708F');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640450EAE44');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_3 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_2 FOREIGN KEY (id_post) REFERENCES post (id_post) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_ce606404d1aa708f ON reclamation');
        $this->addSql('CREATE INDEX id_publication ON reclamation (id_post)');
        $this->addSql('DROP INDEX idx_ce60640450eae44 ON reclamation');
        $this->addSql('CREATE INDEX id_utilisateur ON reclamation (id_utilisateur)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404D1AA708F FOREIGN KEY (id_post) REFERENCES post (id_post)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640450EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)');
        $this->addSql('ALTER TABLE utilisateur CHANGE cin cin INT NOT NULL, CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE mdp mdp VARCHAR(255) NOT NULL, CHANGE role role INT NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F9ED58849');
        $this->addSql('ALTER TABLE wallet DROP FOREIGN KEY FK_7C68921F9ED58849');
        $this->addSql('ALTER TABLE wallet CHANGE balance balance INT NOT NULL');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT wallet_ibfk_1 FOREIGN KEY (id_etablissement) REFERENCES etablissement (id_etablissement) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX id_etablissement ON wallet (id_etablissement)');
        $this->addSql('DROP INDEX uniq_7c68921f9ed58849 ON wallet');
        $this->addSql('CREATE UNIQUE INDEX id_etablissement_2 ON wallet (id_etablissement)');
        $this->addSql('ALTER TABLE wallet ADD CONSTRAINT FK_7C68921F9ED58849 FOREIGN KEY (id_etablissement) REFERENCES etablissement (id_etablissement)');
    }
}
