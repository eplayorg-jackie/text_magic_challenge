<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415181755 extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<SQL
CREATE TABLE challenges (
  id UUID NOT NULL, 
  created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
  passed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
  actual_status INT NOT NULL,
  user_id UUID DEFAULT NULL, 
  PRIMARY KEY(id)
);
SQL);

        $this->addSql(<<<SQL
CREATE TABLE questions (
  id UUID NOT NULL, 
  definition VARCHAR(255) NOT NULL, 
  is_active BOOLEAN DEFAULT NULL, 
  created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
  updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
  PRIMARY KEY(id)
);
SQL);

        $this->addSql(<<<SQL
CREATE TABLE challenge_items (
  id UUID NOT NULL, 
  position INT NOT NULL, 
  is_correct BOOLEAN DEFAULT NULL, 
  decision INT NOT NULL, 
  created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
  passed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
  challenge_id UUID DEFAULT NULL, 
  question_id UUID DEFAULT NULL, 
  item_id UUID DEFAULT NULL, 
  PRIMARY KEY(id)
);
SQL);

        $this->addSql(<<<SQL
CREATE TABLE items (
  id UUID NOT NULL, 
  expression VARCHAR(20) NOT NULL, 
  is_correct BOOLEAN DEFAULT NULL, 
  created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
  question_id UUID DEFAULT NULL, 
  PRIMARY KEY(id)
);
SQL);

        $this->addSql(<<<SQL
CREATE TABLE users (
  id UUID NOT NULL, 
  email VARCHAR(255) NOT NULL, 
  created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
  updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
  PRIMARY KEY(id)
);
SQL);

        $this->addSql("CREATE INDEX IDX_7B5A7E0A76ED395 ON challenges (user_id)");
        $this->addSql("CREATE INDEX IDX_E5CC605F98A21AC6 ON challenge_items (challenge_id)");
        $this->addSql("CREATE INDEX IDX_E5CC605F1E27F6BF ON challenge_items (question_id)");
        $this->addSql("CREATE INDEX IDX_E5CC605F126F525E ON challenge_items (item_id)");
        $this->addSql("CREATE INDEX IDX_E11EE94D1E27F6BF ON items (question_id)");

        $this->addSql(<<<SQL
ALTER TABLE challenges 
  ADD CONSTRAINT FK_7B5A7E0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
SQL);

        $this->addSql(<<<SQL
ALTER TABLE challenge_items 
  ADD CONSTRAINT FK_E5CC605F98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenges (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
SQL);

        $this->addSql(<<<SQL
ALTER TABLE challenge_items 
  ADD CONSTRAINT FK_E5CC605F1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
SQL);

        $this->addSql(<<<SQL
ALTER TABLE challenge_items 
  ADD CONSTRAINT FK_E5CC605F126F525E FOREIGN KEY (item_id) REFERENCES items (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
SQL);

        $this->addSql(<<<SQL
ALTER TABLE items 
  ADD CONSTRAINT FK_E11EE94D1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE;
SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("ALTER TABLE items DROP CONSTRAINT FK_E11EE94D1E27F6BF");
        $this->addSql("ALTER TABLE challenge_items DROP CONSTRAINT FK_E5CC605F126F525E");
        $this->addSql("ALTER TABLE challenge_items DROP CONSTRAINT FK_E5CC605F1E27F6BF");
        $this->addSql("ALTER TABLE challenge_items DROP CONSTRAINT FK_E5CC605F98A21AC6");
        $this->addSql("ALTER TABLE challenges DROP CONSTRAINT FK_7B5A7E0A76ED395");

        $this->addSql("DROP TABLE users");
        $this->addSql("DROP TABLE challenge_items");
        $this->addSql("DROP TABLE items");
        $this->addSql("DROP TABLE questions");
        $this->addSql("DROP TABLE challenges");
    }

}
