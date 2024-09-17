<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240917104414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE VIEW `view_recipe` AS
select
    `r`.`id` AS `id`,
    `r`.`name` AS `name`,
    `r`.`time` AS `time`,
    `r`.`nb_people` AS `nb_people`,
    `r`.`difficulty` AS `difficulty`,
    `r`.`description` AS `description`,
    `r`.`price` AS `price`,
    `r`.`is_favorite` AS `is_favorite`,
    `r`.`is_public` AS `is_public`,
    `r`.`created_at` AS `created_at`,
    `r`.`updated_at` AS `updated_at`,
    `r`.`user_id` AS `user_id`,
    `r`.`image_name` AS `image_name`,
    `r`.`category_id` AS `category_id`,
    round(avg(`m`.`mark`), 2) AS `average`,
    `u`.`full_name` AS `created_by`,
    `c`.`name` AS `category_name`
from (
        (
            (
                `recipe` `r`
                left join `mark` `m` on (`m`.`recipe_id` = `r`.`id`)
            )
            join `user` `u` on (`r`.`user_id` = `u`.`id`)
        )
        left join `category` `c` on (`r`.`category_id` = `c`.`id`)
    )
group by
    `r`.`id`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW view_recipe');
    }
}
