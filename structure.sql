/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for bxnkmo
CREATE DATABASE IF NOT EXISTS `bxnkmo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `bxnkmo`;

-- Dumping structure for table bxnkmo.bank_statement
CREATE TABLE IF NOT EXISTS `bank_statement`
(
    `id`                       int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige ID des Datensatzes',
    `order_iban`               varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'IBAN des Auftraggebers',
    `booking_date`             date                                                          DEFAULT NULL COMMENT 'Datum der Verbuchung',
    `booking_text`             varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Buchungstext',
    `usage`                    varchar(500)                                                  DEFAULT NULL,
    `bank_statement_insert_id` int(10) unsigned NOT NULL COMMENT 'Foreign Key des Inserts',
    `creditor_id`              varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'Gläubiger ID',
    `mandate_reference`        varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'Mandatsreferenz',
    `customer_reference`       varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'Kundenreferenz',
    `collective_reference`     varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'Sammlerreferenz',
    `recipient`                varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'Begünstigter',
    `recipient_iban`           varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'IBAN des Begünstigten',
    `recipient_bic`            varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  DEFAULT NULL COMMENT 'BIC des Begünstigten',
    `amount`                   decimal(10, 2)                                                DEFAULT NULL COMMENT 'Betrag',
    PRIMARY KEY (`id`),
    UNIQUE KEY `u_idx_1` (`recipient_iban`, `booking_date`, `amount`, `usage`) USING BTREE,
    KEY `FK_BankStatement_BankStatementInsert` (`bank_statement_insert_id`),
    CONSTRAINT `FK_BankStatement_BankStatementInsert` FOREIGN KEY (`bank_statement_insert_id`) REFERENCES `bank_statement_insert` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.bank_statement_insert
CREATE TABLE IF NOT EXISTS `bank_statement_insert`
(
    `id`        int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige ID des Inserts',
    `timestamp` timestamp        NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.comparison_operator
CREATE TABLE IF NOT EXISTS `comparison_operator`
(
    `id`   int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige ID des Operators',
    `sign` varchar(8)       NOT NULL COMMENT 'MYSQL Operator Zeichen(kette)',
    `name` varchar(50)      NOT NULL COMMENT 'Anzeigename des Vergleichoperators',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.data_type
CREATE TABLE IF NOT EXISTS `data_type`
(
    `id`   tinyint(3) unsigned NOT NULL COMMENT 'Eindeutige ID des Datentyps',
    `name` varchar(50)         NOT NULL COMMENT 'Name des Datentyps',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT ='Tabelle enthält alle erlaubten Datentypen.';

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.field
CREATE TABLE IF NOT EXISTS `field`
(
    `id`                      int(10) unsigned    NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige ID des Filterfeldes',
    `correspondingColumnName` varchar(25)         NOT NULL COMMENT 'Tabellen Spaltenname aus ''bank_statement''',
    `name`                    varchar(25)         NOT NULL COMMENT 'Anzeigename des Feldes',
    `dataTypeId`              tinyint(3) unsigned NOT NULL COMMENT 'ID des zugehörigen Datentyps',
    PRIMARY KEY (`id`),
    KEY `FK_Field_DataType_DataTypeId` (`dataTypeId`),
    CONSTRAINT `FK_Field_DataType_DataTypeId` FOREIGN KEY (`dataTypeId`) REFERENCES `data_type` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.field_comparison_operator
CREATE TABLE IF NOT EXISTS `field_comparison_operator`
(
    `fieldId`              int(10) unsigned DEFAULT NULL,
    `comparisonOperatorId` int(10) unsigned DEFAULT NULL,
    KEY `FK_FilterFieldComparisonOperator_FilterField` (`fieldId`) USING BTREE,
    KEY `FK_FieldComparisonOperator_ComparisonOperator` (`comparisonOperatorId`) USING BTREE,
    CONSTRAINT `FK_FieldComparisonOperator_ComparisonOperator` FOREIGN KEY (`comparisonOperatorId`) REFERENCES `comparison_operator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_FieldComparisonOperator_Field` FOREIGN KEY (`fieldId`) REFERENCES `field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.filter
CREATE TABLE IF NOT EXISTS `filter`
(
    `id`                int(10) unsigned    NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige ID des Filters',
    `name`              varchar(50)         NOT NULL COMMENT 'Anzeigename des Filters',
    `logicalOperatorId` tinyint(3) unsigned NOT NULL COMMENT 'ID des logischen Operators',
    PRIMARY KEY (`id`),
    KEY `FK_Filter_LogicalOperator` (`logicalOperatorId`),
    CONSTRAINT `FK_Filter_LogicalOperator` FOREIGN KEY (`logicalOperatorId`) REFERENCES `logical_operator` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.filter_field_comparison_operator
CREATE TABLE IF NOT EXISTS `filter_field_comparison_operator`
(
    `filterId`             int(10) unsigned NOT NULL,
    `fieldId`              int(10) unsigned NOT NULL,
    `comparisonOperatorId` int(10) unsigned NOT NULL,
    PRIMARY KEY (`filterId`, `fieldId`, `comparisonOperatorId`) USING BTREE,
    KEY `FK_FilterFieldComparisonOperator_ComparisonOperator` (`comparisonOperatorId`) USING BTREE,
    KEY `FK_FilterFieldComparisonOperator_Field` (`fieldId`) USING BTREE,
    CONSTRAINT `FK_FilterFieldComparisonOperator_ComparisonOperator` FOREIGN KEY (`comparisonOperatorId`) REFERENCES `comparison_operator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_FilterFieldComparisonOperator_Field` FOREIGN KEY (`fieldId`) REFERENCES `field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_FilterFieldComparisonOperator_Filter` FOREIGN KEY (`filterId`) REFERENCES `filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.filter_field_value
CREATE TABLE IF NOT EXISTS `filter_field_value`
(
    `filterId` int(10) unsigned NOT NULL,
    `fieldId`  int(10) unsigned NOT NULL,
    `value`    varchar(150)     NOT NULL DEFAULT '',
    KEY `FK_FilterFieldValue_Filter` (`filterId`) USING BTREE,
    KEY `FK_FilterFieldValue_Field` (`fieldId`) USING BTREE,
    CONSTRAINT `FK_FilterFieldValue_Field` FOREIGN KEY (`fieldId`) REFERENCES `field` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_FilterFieldValue_Filter` FOREIGN KEY (`filterId`) REFERENCES `filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.group
CREATE TABLE IF NOT EXISTS `group`
(
    `id`                int(10) unsigned                                             NOT NULL AUTO_INCREMENT COMMENT 'Eindeutige ID der FIltergruppe',
    `name`              varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Anzeigename der Gruppe',
    `logicalOperatorId` tinyint(3) unsigned                                          NOT NULL COMMENT 'ID des logischen Operators',
    PRIMARY KEY (`id`),
    KEY `FK_Group_LogicalOperator` (`logicalOperatorId`),
    CONSTRAINT `FK_Group_LogicalOperator` FOREIGN KEY (`logicalOperatorId`) REFERENCES `logical_operator` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.group_filter
CREATE TABLE IF NOT EXISTS `group_filter`
(
    `groupId`  int(10) unsigned NOT NULL COMMENT 'Gruppen ID aus der Tabelle ''group''',
    `filterId` int(10) unsigned NOT NULL COMMENT 'Filter ID aus der Tabelle ''filter''',
    UNIQUE KEY `u_group_id_filter_id` (`groupId`, `filterId`) USING BTREE,
    KEY `FK_GroupFilter_Filter` (`filterId`) USING BTREE,
    CONSTRAINT `FK_GroupFilter_Filter` FOREIGN KEY (`filterId`) REFERENCES `filter` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `FK_GroupFilter_Group` FOREIGN KEY (`groupId`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bxnkmo.logical_operator
CREATE TABLE IF NOT EXISTS `logical_operator`
(
    `id`   tinyint(3) unsigned NOT NULL COMMENT 'Eindeutige ID des logischen Operators',
    `sign` varchar(5)          NOT NULL COMMENT 'MYSQL Zeichen des logischen Operators',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4 COMMENT ='Tabelle zum Speichern der erlaubten logischen Operatoren';

-- Data exporting was unselected.

/*!40101 SET SQL_MODE = IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS = IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
