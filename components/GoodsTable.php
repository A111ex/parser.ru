<?php

namespace app\components;

use yii\base\Object;

class GoodsTable extends Object {

    /**
     * Удалить таблицу типа товара
     */
    public function delGoodsTable($type) {
        $sql = "DROP TABLE IF EXISTS `goods_t_{$type}` ;";
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $rowCount = $command->execute();
    }

    /**
     * Создать таблицу типа товара
     */
    public function addGoodsTable($type) {
        //Проверить, существует ли такая таблица
        //Проверить корректность имени
        //Создать, если не существует
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `goods_t_{$type}` (
  `goods_id` INT NOT NULL,
  `discription` TEXT(3000) NULL COMMENT 'Описание',
  `photo` VARCHAR(255) NULL COMMENT 'Фото',
  PRIMARY KEY (`goods_id`),
  CONSTRAINT `fk_goods_t_{$type}_goods`
    FOREIGN KEY (`goods_id`)
    REFERENCES `goods` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
SQL;
        $connection = \Yii::$app->db;
        $command = $connection->createCommand($sql);
        $rowCount = $command->execute();
    }

    /**
     * Добавить свойство в таблицу тип товара
     */
    public function addParamGoodsTable($type, $param, $typeParam) {
        
    }

}
