/*
 Navicat Premium Dump SQL

 Source Server         : Capistrano
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : clinicdb

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 24/08/2026 23:13:07
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clinic_visit
-- ----------------------------
DROP TABLE IF EXISTS `clinic_visit`;
CREATE TABLE `clinic_visit`  (
  `visit_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `LRN` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `personel_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `visit_date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`visit_id`) USING BTREE,
  INDEX `LRN`(`LRN` ASC) USING BTREE,
  INDEX `personel_id`(`personel_id` ASC) USING BTREE,
  CONSTRAINT `LRN` FOREIGN KEY (`LRN`) REFERENCES `student` (`LRN`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `personel_id` FOREIGN KEY (`personel_id`) REFERENCES `medical_personel` (`personel_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of clinic_visit
-- ----------------------------
INSERT INTO `clinic_visit` VALUES ('VS-000001', '1123456789', 'PID-001', '2026-08-13 00:03:49');
INSERT INTO `clinic_visit` VALUES ('VS-000003', '1145678909', 'PID-001', '2026-08-14 00:00:00');
INSERT INTO `clinic_visit` VALUES ('VS-000004', '1145678909', 'PID-001', '2026-08-19 00:00:00');
INSERT INTO `clinic_visit` VALUES ('VS-000005', '1123456789', 'PID-001', '2026-08-19 00:00:00');
INSERT INTO `clinic_visit` VALUES ('VS-000006', '1123456789', 'PID-001', '2026-08-19 00:00:00');
INSERT INTO `clinic_visit` VALUES ('VS-000007', '1123456789', 'PID-001', '2026-08-19 00:00:00');

SET FOREIGN_KEY_CHECKS = 1;
