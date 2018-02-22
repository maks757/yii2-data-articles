<?php

use yii\db\Schema;
use yii\db\Migration;

class m160415_153540_create_tables extends Migration {

    public function up() {
        $this->createTable('yii2_data_article', [
            'id' => $this->primaryKey(),
            'image' => $this->string(100)->notNull(),
            'date' => $this->integer(),
            'author' => $this->integer(),
            'position' => $this->integer(),
        ]);

        $this->createTable('yii2_data_article_translation', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer(),
            'language_id' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_article_language_fk',
            'yii2_data_article_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_article_yii2_data_article_translation_fk',
            'yii2_data_article_translation', 'article_id',
            'yii2_data_article', 'id',
            'CASCADE', 'CASCADE');

        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('yii2_data_article_gallery', [
            'id' => $this->primaryKey(),
            'position' => $this->string(100)->notNull(),
            'article_id' => $this->integer(),
        ]);

        $this->addForeignKey('yii2_data_article_gallery_yii2_data_article_fk',
            'yii2_data_article_gallery', 'article_id',
            'yii2_data_article', 'id',
            'CASCADE', 'CASCADE');

        $this->createTable('yii2_data_article_gallery_translation', [
            'id' => $this->primaryKey(),
            'article_gallery_id' => $this->integer(),
            'language_id' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_article_gallery_translation_language_fk',
            'yii2_data_article_gallery_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_article_gallery_t_yii2_data_article_gallery_fk',
            'yii2_data_article_gallery_translation', 'article_gallery_id',
            'yii2_data_article_gallery', 'id',
            'CASCADE', 'CASCADE');


        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('yii2_data_article_image', [
            'id' => $this->primaryKey(),
            'image' => $this->string(100)->notNull(),
            'position' => $this->string(100)->notNull(),
            'article_id' => $this->integer(),
        ]);

        $this->addForeignKey('yii2_data_article_image_yii2_data_article_fk',
            'yii2_data_article_image', 'article_id',
            'yii2_data_article', 'id',
            'CASCADE', 'CASCADE');

        $this->createTable('yii2_data_article_image_translation', [
            'id' => $this->primaryKey(),
            'article_image_id' => $this->integer(),
            'language_id' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_article_image_translation_language_fk',
            'yii2_data_article_image_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_article_image_t_yii2_data_article_image_fk',
            'yii2_data_article_image_translation', 'article_image_id',
            'yii2_data_article_image', 'id',
            'CASCADE', 'CASCADE');

        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('yii2_data_article_text', [
            'id' => $this->primaryKey(),
            'position' => $this->string(100)->notNull(),
            'article_id' => $this->integer(),
        ]);

        $this->addForeignKey('yii2_data_article_text_yii2_data_article_fk',
            'yii2_data_article_text', 'article_id',
            'yii2_data_article', 'id',
            'CASCADE', 'CASCADE');

        $this->createTable('yii2_data_article_text_translation', [
            'id' => $this->primaryKey(),
            'article_text_id' => $this->integer(),
            'language_id' => $this->integer(),
            'text' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_article_text_translation_language_fk',
            'yii2_data_article_text_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_article_text_t_yii2_data_article_text_fk',
            'yii2_data_article_text_translation', 'article_text_id',
            'yii2_data_article_text', 'id',
            'CASCADE', 'CASCADE');

    }

    public function down() {
        return true;
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
