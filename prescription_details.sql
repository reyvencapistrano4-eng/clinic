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

 Date: 24/08/2026 23:13:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for prescription_details
-- ----------------------------
DROP TABLE IF EXISTS `prescription_details`;
CREATE TABLE `prescription_details`  (
  `prescription_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `visit_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `medication name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `dosage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `instruction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`prescription_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of prescription_details
-- ----------------------------
INSERT INTO `prescription_details` VALUES ('RX-000001', 'VS-000005', 'g', 'g', 'g');
INSERT INTO `prescription_details` VALUES ('RX-000002', 'VS-000006', 'h', 'k', 'k');
INSERT INTO `prescription_details` VALUES ('RX-000003', 'VS-000007', 'h', 'p', 'k');

SET FOREIGN_KEY_CHECKS = 1;
