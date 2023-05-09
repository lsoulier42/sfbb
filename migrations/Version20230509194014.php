<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509194014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE chat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE configuration_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE direct_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE forum_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE topic_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_chat_view_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_topic_view_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, title VARCHAR(255) NOT NULL, order_number INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C12B36786B ON category (title)');
        $this->addSql('CREATE TABLE chat (id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE chat_user (chat_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(chat_id, user_id))');
        $this->addSql('CREATE INDEX IDX_2B0F4B081A9A7125 ON chat_user (chat_id)');
        $this->addSql('CREATE INDEX IDX_2B0F4B08A76ED395 ON chat_user (user_id)');
        $this->addSql('CREATE TABLE configuration (id INT NOT NULL, config_key VARCHAR(255) NOT NULL, config_value VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE direct_message (id INT NOT NULL, author_id INT DEFAULT NULL, chat_id INT DEFAULT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1416AF93F675F31B ON direct_message (author_id)');
        $this->addSql('CREATE INDEX IDX_1416AF931A9A7125 ON direct_message (chat_id)');
        $this->addSql('CREATE TABLE forum (id INT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, sub_title VARCHAR(255) DEFAULT NULL, order_number INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_852BBECD12469DE2 ON forum (category_id)');
        $this->addSql('CREATE TABLE forum_user (forum_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(forum_id, user_id))');
        $this->addSql('CREATE INDEX IDX_4E72769E29CCBAD0 ON forum_user (forum_id)');
        $this->addSql('CREATE INDEX IDX_4E72769EA76ED395 ON forum_user (user_id)');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, author_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D1F55203D ON post (topic_id)');
        $this->addSql('CREATE TABLE topic (id INT NOT NULL, author_id INT DEFAULT NULL, forum_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9D40DE1BF675F31B ON topic (author_id)');
        $this->addSql('CREATE INDEX IDX_9D40DE1B29CCBAD0 ON topic (forum_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(255) NOT NULL, is_enabled BOOLEAN NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, avatar_url VARCHAR(255) DEFAULT NULL, last_connexion TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_activity TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".birth_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".last_connexion IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".last_activity IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_forum (user_id INT NOT NULL, forum_id INT NOT NULL, PRIMARY KEY(user_id, forum_id))');
        $this->addSql('CREATE INDEX IDX_67E94595A76ED395 ON user_forum (user_id)');
        $this->addSql('CREATE INDEX IDX_67E9459529CCBAD0 ON user_forum (forum_id)');
        $this->addSql('CREATE TABLE user_chat (user_id INT NOT NULL, chat_id INT NOT NULL, PRIMARY KEY(user_id, chat_id))');
        $this->addSql('CREATE INDEX IDX_1F1CBE63A76ED395 ON user_chat (user_id)');
        $this->addSql('CREATE INDEX IDX_1F1CBE631A9A7125 ON user_chat (chat_id)');
        $this->addSql('CREATE TABLE user_chat_view (id INT NOT NULL, user_id INT DEFAULT NULL, chat_id INT DEFAULT NULL, last_seen TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5881BB2BA76ED395 ON user_chat_view (user_id)');
        $this->addSql('CREATE INDEX IDX_5881BB2B1A9A7125 ON user_chat_view (chat_id)');
        $this->addSql('COMMENT ON COLUMN user_chat_view.last_seen IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_topic_view (id INT NOT NULL, user_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, last_seen TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_61710CD7A76ED395 ON user_topic_view (user_id)');
        $this->addSql('CREATE INDEX IDX_61710CD71F55203D ON user_topic_view (topic_id)');
        $this->addSql('COMMENT ON COLUMN user_topic_view.last_seen IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B081A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B08A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF93F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE direct_message ADD CONSTRAINT FK_1416AF931A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forum_user ADD CONSTRAINT FK_4E72769E29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE forum_user ADD CONSTRAINT FK_4E72769EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1BF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_forum ADD CONSTRAINT FK_67E94595A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_forum ADD CONSTRAINT FK_67E9459529CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_chat ADD CONSTRAINT FK_1F1CBE63A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_chat ADD CONSTRAINT FK_1F1CBE631A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_chat_view ADD CONSTRAINT FK_5881BB2BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_chat_view ADD CONSTRAINT FK_5881BB2B1A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_topic_view ADD CONSTRAINT FK_61710CD7A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_topic_view ADD CONSTRAINT FK_61710CD71F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE chat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE configuration_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE direct_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE forum_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE topic_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_chat_view_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_topic_view_id_seq CASCADE');
        $this->addSql('ALTER TABLE chat_user DROP CONSTRAINT FK_2B0F4B081A9A7125');
        $this->addSql('ALTER TABLE chat_user DROP CONSTRAINT FK_2B0F4B08A76ED395');
        $this->addSql('ALTER TABLE direct_message DROP CONSTRAINT FK_1416AF93F675F31B');
        $this->addSql('ALTER TABLE direct_message DROP CONSTRAINT FK_1416AF931A9A7125');
        $this->addSql('ALTER TABLE forum DROP CONSTRAINT FK_852BBECD12469DE2');
        $this->addSql('ALTER TABLE forum_user DROP CONSTRAINT FK_4E72769E29CCBAD0');
        $this->addSql('ALTER TABLE forum_user DROP CONSTRAINT FK_4E72769EA76ED395');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8D1F55203D');
        $this->addSql('ALTER TABLE topic DROP CONSTRAINT FK_9D40DE1BF675F31B');
        $this->addSql('ALTER TABLE topic DROP CONSTRAINT FK_9D40DE1B29CCBAD0');
        $this->addSql('ALTER TABLE user_forum DROP CONSTRAINT FK_67E94595A76ED395');
        $this->addSql('ALTER TABLE user_forum DROP CONSTRAINT FK_67E9459529CCBAD0');
        $this->addSql('ALTER TABLE user_chat DROP CONSTRAINT FK_1F1CBE63A76ED395');
        $this->addSql('ALTER TABLE user_chat DROP CONSTRAINT FK_1F1CBE631A9A7125');
        $this->addSql('ALTER TABLE user_chat_view DROP CONSTRAINT FK_5881BB2BA76ED395');
        $this->addSql('ALTER TABLE user_chat_view DROP CONSTRAINT FK_5881BB2B1A9A7125');
        $this->addSql('ALTER TABLE user_topic_view DROP CONSTRAINT FK_61710CD7A76ED395');
        $this->addSql('ALTER TABLE user_topic_view DROP CONSTRAINT FK_61710CD71F55203D');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_user');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE direct_message');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE forum_user');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE topic');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_forum');
        $this->addSql('DROP TABLE user_chat');
        $this->addSql('DROP TABLE user_chat_view');
        $this->addSql('DROP TABLE user_topic_view');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
