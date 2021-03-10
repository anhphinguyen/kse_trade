-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2021 at 01:27 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.1.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kse_trading`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_account`
--

CREATE TABLE `tbl_account_account` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_type` int(10) UNSIGNED NOT NULL,
  `account_code` varchar(200) NOT NULL,
  `account_username` varchar(500) NOT NULL,
  `account_password` varchar(500) NOT NULL,
  `account_fullname` varchar(500) NOT NULL,
  `account_email` varchar(500) NOT NULL,
  `account_phone` varchar(20) NOT NULL,
  `account_status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `account_token` varchar(500) NOT NULL,
  `force_sign_out` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_account_account`
--

INSERT INTO `tbl_account_account` (`id`, `id_type`, `account_code`, `account_username`, `account_password`, `account_fullname`, `account_email`, `account_phone`, `account_status`, `account_token`, `force_sign_out`) VALUES
(1, 1, '189395459-5', 'frubenfeld0', 'zf1mCbEj', 'Farlie Rubenfeld', 'frubenfeld0@cocolog-nifty.com', '428-564-8493', 'Y', '', '0'),
(2, 1, '425567338-1', 'cscimonelli1', 'tmIPzVvJaeu', 'Carolus Scimonelli', 'cscimonelli1@senate.gov', '842-747-8569', 'Y', '', '0'),
(3, 1, '253155581-1', 'hlawrenz2', 'CXagDIiT', 'Hagen Lawrenz', 'hlawrenz2@yahoo.co.jp', '659-265-7928', 'Y', '', '0'),
(4, 1, '715099443-1', 'troggieri3', 'va8CvXMGv5XP', 'Tracey Roggieri', 'troggieri3@redcross.org', '840-582-9010', 'Y', '', '0'),
(5, 1, '254353188-2', 'cpafford4', 'N8yOSOV', 'Coop Pafford', 'cpafford4@twitter.com', '875-315-0267', 'Y', '', '0'),
(6, 2, '729163505-5', 'cgrzelewski5', 'WWNSEWp', 'Cassandry Grzelewski', 'cgrzelewski5@eventbrite.com', '844-519-4378', 'Y', '', '0'),
(7, 2, '688593945-5', 'gmelding6', 'DEuYJNpnH52', 'Gael Melding', 'gmelding6@intel.com', '509-550-7995', 'Y', '', '0'),
(8, 2, '562144033-1', 'slambot7', 'JBeQ4g7f9vSZ', 'Sherie Lambot', 'slambot7@cpanel.net', '854-539-4118', 'Y', '', '0'),
(9, 2, '141581835-5', 'veakley8', 'h8ioAW5LMPdr', 'Van Eakley', 'veakley8@twitpic.com', '328-373-7659', 'Y', '', '0'),
(10, 2, '929023332-X', 'mforst9', 'igK5engu', 'Murray Forst', 'mforst9@comcast.net', '105-293-5071', 'Y', '', '0'),
(11, 2, '085256213-6', 'aruggea', 'nPibBdf', 'Angelika Rugge', 'aruggea@mozilla.org', '332-750-6432', 'Y', '', '0'),
(12, 3, 'S46929A', 'fscrogginsb', 'vg9F4I', 'Filippa Scroggins', 'fscrogginsb@hud.gov', '541-199-9492', 'Y', '', '0'),
(13, 3, 'A203', 'ahazelgrovec', 'goXqSyY7eQVo', 'August Hazelgrove', 'ahazelgrovec@taobao.com', '243-644-4087', 'Y', '', '0'),
(14, 3, 'M8080XA', 'ascorrerd', 'R0UXu7cJ09', 'April Scorrer', 'ascorrerd@gizmodo.com', '406-431-7536', 'Y', '', '0'),
(15, 3, 'T783XXA', 'byosifove', 'T7PO0IfHZq', 'Bartolomeo Yosifov', 'byosifove@ebay.com', '266-158-4218', 'Y', '', '0'),
(16, 3, 'S52012', 'ccounterf', 'M6CA3dGOX', 'Cori Counter', 'ccounterf@unc.edu', '587-125-4112', 'Y', '', '0'),
(17, 3, 'T17928', 'pnickollg', 'DpH7FQ1', 'Phillipp Nickoll', 'pnickollg@youtube.com', '675-648-9416', 'Y', '', '0'),
(18, 3, 'S62175D', 'abrewitth', 'TmErcYsFZ2X', 'Anders Brewitt', 'abrewitth@sun.com', '447-773-2020', 'Y', '', '0'),
(19, 3, 'Y35891A', 'adrewei', 'h2hWMCu5M', 'Ashely Drewe', 'adrewei@usa.gov', '808-906-1076', 'Y', '', '0'),
(20, 2, 'S2509XS', 'tpeperellj', '9EyST2Md', 'Tiebout Peperell', 'tpeperellj@ovh.net', '123456', 'N', '', '0'),
(21, 1, 'admin', 'admin1', '96e79218965eb72c92a549dd5a330112', 'Admin', 'admin@gmail.com', '0808080', 'Y', 'afdsfasdfasdf ', '0'),
(22, 1, '', 'minhkhanh', 'e10adc3949ba59abbe56e057f20f883e', 'thân văn tuệ', 'thanvantue9@gmail.com', '0981701910', 'Y', '', '0'),
(23, 2, 'officer01', 'officer', 'e10adc3949ba59abbe56e057f20f883e', 'Cassandry Grzelewski', 'cgrzelewski5@eventbrite.com', '844-519-4378', 'Y', 'lajhkdfhasdjkfkjasf', '0'),
(24, 3, 'S46929A', 'sale', 'e10adc3949ba59abbe56e057f20f883e', 'Filippa Scroggins', 'fscrogginsb@hud.gov', '541-199-9492', 'Y', 'sdfagyjfaygfjkda', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_authorize`
--

CREATE TABLE `tbl_account_authorize` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_admin` int(10) UNSIGNED NOT NULL,
  `grant_permission` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_account_authorize`
--

INSERT INTO `tbl_account_authorize` (`id`, `id_admin`, `grant_permission`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 2, 1),
(8, 2, 2),
(9, 2, 3),
(10, 3, 6),
(11, 3, 3),
(12, 3, 4),
(13, 3, 1),
(14, 4, 1),
(15, 4, 2),
(16, 5, 1),
(17, 5, 2),
(18, 5, 3),
(19, 5, 4),
(20, 5, 5),
(21, 21, 1),
(22, 21, 2),
(23, 21, 3),
(24, 21, 4),
(25, 21, 5),
(26, 21, 6),
(27, 21, 7);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_permission`
--

CREATE TABLE `tbl_account_permission` (
  `id` int(10) UNSIGNED NOT NULL,
  `permission` varchar(500) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_account_permission`
--

INSERT INTO `tbl_account_permission` (`id`, `permission`, `description`) VALUES
(1, 'module_exchange', 'Quản lý sàn giao dịch'),
(2, 'module_account', 'Quản lý tài khoản'),
(3, 'module_customer', 'Quản lý khách hàng'),
(4, 'module_request_payment', 'Quản lý yêu cầu rút tiền'),
(5, 'module_confirm_deposit', 'Quản lý yêu cầu nạp tiền'),
(6, 'module_report', 'Quản lý thống kê - báo cáo'),
(7, 'module_force', 'Cưỡng chế đăng xuất');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_account_type`
--

CREATE TABLE `tbl_account_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_account` varchar(500) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_account_type`
--

INSERT INTO `tbl_account_type` (`id`, `type_account`, `description`) VALUES
(1, 'admin', 'Quản lý'),
(2, 'customer_services', 'Chăm sóc khách hàng'),
(3, 'sales', 'Kinh doanh');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_app_deploy`
--

CREATE TABLE `tbl_app_deploy` (
  `id` int(10) UNSIGNED NOT NULL,
  `live_version` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_app_deploy`
--

INSERT INTO `tbl_app_deploy` (`id`, `live_version`) VALUES
(1, '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bank_info`
--

CREATE TABLE `tbl_bank_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `bank_code` varchar(200) NOT NULL,
  `bank_full_name` varchar(500) NOT NULL,
  `bank_short_name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_bank_info`
--

INSERT INTO `tbl_bank_info` (`id`, `bank_code`, `bank_full_name`, `bank_short_name`) VALUES
(1, '323', 'Ngân hàng An Bình', 'ABBank'),
(2, '602', 'Ngân hàng ANZ Việt Nam', 'ANZ'),
(3, '307', 'Ngân hàng Á Châu', 'ACB'),
(4, '612', 'BANGKOK  BANK', 'BANGKOK  BANK'),
(5, '648', 'NH ĐT&PT Campuchia CN HCM', 'BIDC HCM'),
(6, '638', 'NH ĐT&PT Campuchia CN Hà Nội', 'BIDC HN'),
(7, '202', 'Ngân hàng Đầu tư và Phát triển Việt Nam', 'BIDV'),
(8, '620', 'BANK OF CHINA', 'BC'),
(9, '615', 'Bank of Communications', 'BOC'),
(10, '614', 'BNP Paribas Bank HCM', 'BNP Paribas HCM'),
(11, '653', 'BANK OF TOKYO - MITSUBISHI UFJ - HN', 'BTMU HN'),
(12, '622', 'BANK OF TOKYO - MITSUBISHI UFJ - TP HCM', 'BTMU HCM'),
(13, '327', 'NHTMCP Bản Việt', 'VietCapital Bank'),
(14, '359', 'Ngân hàng TMCP Bảo Việt', 'Baoviet Bank'),
(15, '657', 'Ngan hang BNP Paribas CN Ha Noi', 'BNP Paribas HN'),
(16, '634', 'Ngân hàng Cathay', 'CTU'),
(17, '611', 'China Construction Bank Corporation', 'CCBC'),
(18, '661', 'Ngân hàng TNHH MTV CIMB Việt Nam', 'CIMB'),
(19, '605', 'Citi Bank Ha Noi', 'CitibankHN'),
(20, '654', 'Citi Bank TP HCM', 'CitibankHCM'),
(21, '901', 'Ngân hàng Hợp tác Việt Nam', 'COOPBANK'),
(22, '643', 'Commonwealth Bank of Australia', 'CBA'),
(23, '621', 'Credit Agricole Corporate and Investment Bank', 'CACIB'),
(24, '650', 'DBS Bank Ltd', 'DBS'),
(25, '619', 'DEUTSCHE BANK', 'DB'),
(26, '304', 'Ngân hàng Đông Á', 'Dong A Bank, DAB'),
(27, '630', 'First Commercial Bank', 'FCNB'),
(28, '608', 'First Commercial Bank Ha Noi', 'FCNB HN'),
(29, '320', 'Ngân hàng Dầu khí Toàn cầu', 'GP Bank'),
(30, '603', 'Ngân hàng Hong Leong Viet Nam', 'HLO'),
(31, '321', 'Ngân hàng Phát triển TP HCM', 'HDBank'),
(32, '640', 'Hua Nan Commercial Bank', 'HNCB'),
(33, '649', 'ICB of China CN Ha Noi', 'ICB'),
(34, '502', 'Indovina Bank', 'IVB'),
(35, '641', 'Industrial Bank of Korea', 'IBK'),
(36, '203', 'Ngân hàng TMCP Ngoại Thương', 'Vietcombank, VCB'),
(37, '701', 'Kho Bạc Nhà Nước', 'KBNN'),
(38, '353', 'Ngân hàng Kiên Long', 'Kienlongbank'),
(39, '631', 'Ngân hàng Kookmin', 'KMB'),
(40, '626', 'Korea Exchange Bank', 'KEB'),
(41, '357', 'Ngan hàng TMCP Bưu điện Liên Việt', 'Lienvietbank,  LPB'),
(42, '635', 'Malayan Banking Berhad', 'MBB'),
(43, '302', 'Ngân hàng Hàng Hải Việt Nam', 'Maritime Bank, MSB'),
(44, '609', 'Malayan Banking Berhad', 'Maybank'),
(45, '623', 'Mega ICBC Bank', 'MICB'),
(46, '311', 'Ngân hàng Quân Đội', 'MB'),
(47, '613', 'Mizuho Corporate Bank', 'Mizuho Bank'),
(48, '639', 'Mizuho Corporate Bank - TP HCM', 'MCB_HCM'),
(49, '306', 'Ngân hàng Nam Á', 'Nam A Bank, NAB'),
(50, '352', 'Ngân hàng Quoc Dan', 'NCB'),
(51, '324', 'Ngân hàng Việt Hoa', 'Viet Hoa Bank'),
(52, '315', 'Ngân hàng Vũng Tàu', 'Vung Tau'),
(53, '601', 'Ngân hàng BPCEIOM CN  TP Hồ Chí Minh', 'BPCEICOM'),
(54, '645', 'Ngân hàng The Hongkong và Thượng Hải', 'HSBC HN'),
(55, '313', 'Ngân hàng Bắc Á', 'NASBank, NASB'),
(56, '319', 'Ngân hàng Đại Dương', 'Ocean Bank'),
(57, '333', 'Ngân hàng Phương Đông', 'Oricombank, OCB, PhuongDong Bank'),
(58, '625', 'Oversea - Chinese Bank', 'OCBC'),
(59, '341', 'Ngân hàng Xăng dầu Petrolimex', 'PG Bank'),
(60, '360', 'NH TMCP Đại Chúng Viet Nam', 'PVcombank'),
(61, '902', 'Quỹ tín dụng cơ sở', 'QTDCS'),
(62, '348', 'Ngân hàng Sài Gòn - Hà Nội', 'SHB'),
(63, '308', 'Ngân hàng Sài Gòn Công Thương', 'Saigonbank'),
(64, '334', 'Ngân hàng TMCP Sài Gòn', 'SCB'),
(65, '303', 'Ngân hàng Sài Gòn Thương Tín', 'Sacombank'),
(66, '616', 'Ngân hàng TNHH MTV Shinhan Việt Nam', 'Shinhan Bank'),
(67, '632', 'Ngân hàng SinoPac', 'SPB'),
(68, '317', 'Ngân hàng TMCP Đông Nam Á', 'SeABank'),
(69, '604', 'Ngân hàng Standard Chartered Bank Việt Nam', 'SCBank'),
(70, '646', 'Ngân hàng Standard Chartered Bank HN', 'SCBank HN'),
(71, '101', 'Ngân Hàng Nhà Nước', 'SBV'),
(72, '636', 'Sumitomo Mitsui Banking Corporation HCM', 'SMBC'),
(73, '936', 'Sumitomo Mitsui Banking Corporation HN', 'SMBC HN'),
(74, '642', 'Taipei Fubon Commercial Bank Ha Noi', 'TFCBHN'),
(75, '651', 'Taipei Fubon Commercial Bank TP Ho Chi Minh', 'TFCBTPHCM'),
(76, '627', 'The Chase Manhattan Bank', 'CHASE'),
(77, '629', 'Ngân hàng CTBC CN TP Hồ Chí Minh', 'CTBC'),
(78, '617', 'NH TNHH Một Thành Viên HSBC Việt Nam', 'HSBC'),
(79, '606', 'The Shanghai Commercial & Savings Bank CN Đồng Nai', 'SCSB'),
(80, '600', 'Ngân hàng The Siam Commercial Public', 'SIAM'),
(81, '618', 'United Oversea Bank', 'UOB'),
(82, '501', 'Ngân hàng VID Public', 'VID public'),
(83, '204', 'Ngân hàng NN & PTNT VN', 'Agribank, VBARD'),
(84, '355', 'Ngân hàng Việt Á', 'VietA Bank, VAB'),
(85, '505', 'Ngân hàng Liên doanh Việt Nga', 'VRB'),
(86, '207', 'Ngân hàng Chính sách xã hội Việt Nam', 'VBSP'),
(87, '339', 'NH TMCP Xây dựng Việt Nam', 'VNCB'),
(88, '208', 'Ngân hàng Phát triển Việt Nam', 'VDB'),
(89, '305', 'Ngân hàng Xuất nhập khẩu Việt Nam', 'Eximbank, EIB'),
(90, '314', 'Ngân hàng Quốc tế', 'VIBank, VIB'),
(91, '201', 'Ngân hàng công thương Việt Nam', 'Vietinbank'),
(92, '401', 'Công ty cổ phần chuyển mạch tài chính quốc gia Việt Nam', 'Banknetvn'),
(93, '309', 'Ngân hàng Thương mại cổ phần Việt Nam Thịnh Vượng', 'VPBank'),
(94, '310', 'Ngân hàng Kỹ thương Việt Nam', 'Techcombank, TCB'),
(95, '356', 'Ngân hàng Việt Nam Thương Tín', 'Vietbank'),
(96, '624', 'WOORI BANK Hà Nội', 'WHHN'),
(97, '637', 'NH Woori HCM', 'WHHCM');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_customer`
--

CREATE TABLE `tbl_customer_customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_bank` int(10) UNSIGNED NOT NULL,
  `customer_introduce` varchar(200) NOT NULL,
  `customer_code` varchar(200) NOT NULL,
  `customer_phone` varchar(200) NOT NULL,
  `customer_password` varchar(500) NOT NULL,
  `customer_fullname` varchar(500) NOT NULL,
  `customer_cert_no` varchar(500) NOT NULL,
  `customer_cert_img` varchar(500) NOT NULL,
  `customer_account_no` varchar(200) NOT NULL,
  `customer_account_holder` varchar(500) NOT NULL,
  `customer_account_img` varchar(500) NOT NULL,
  `customer_wallet_bet` varchar(500) NOT NULL DEFAULT '0',
  `customer_wallet_payment` varchar(500) NOT NULL,
  `customer_limit_payment` varchar(500) NOT NULL DEFAULT '50000000',
  `customer_token` varchar(500) NOT NULL,
  `customer_active` enum('Y','N') NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_customer_customer`
--

INSERT INTO `tbl_customer_customer` (`id`, `id_bank`, `customer_introduce`, `customer_code`, `customer_phone`, `customer_password`, `customer_fullname`, `customer_cert_no`, `customer_cert_img`, `customer_account_no`, `customer_account_holder`, `customer_account_img`, `customer_wallet_bet`, `customer_wallet_payment`, `customer_limit_payment`, `customer_token`, `customer_active`) VALUES
(1, 0, 'S62175D', '41163-645', '347-699-5573', 'RUg7dhq8v', 'Ali Nealand', '3563852336224586', '', '3543389826522000', 'Anstice Riccetti', '', '97042.88', '52405.93', '50000000', '030b2142f0ee6207534b3bbe05c73847', 'N'),
(2, 0, 'S32110D', '0615-6502', '212-347-4179', 'JyXt3FasY', 'Raphaela Baudoux', '3589251334536903', '', '30585882972239', 'Dietrich Golson', '', '163868.32', '38531.34', '50000000', '0e9f1887f90affc411c405116b77f757', 'N'),
(3, 0, 'V0499XA', '58980-501', '269-320-1277', 'auHw7ENX1IBK', 'Mil Khidr', '3589541147085159', '', '4996784021076135', 'Rheba Connachan', '', '43944.07', '48623.77', '50000000', 'dce8e432f663250b780d08202c2891cb', 'N'),
(4, 0, 'I70691', '36987-3273', '334-814-5838', 'F1oXMBvx9L', 'Rocky Handyside', '3544731410686585', '', '3563746309789452', 'Hildegarde O\'Hoolahan', '', '7320.03', '79060.77', '50000000', 'b66381ddac773c4495b8571562f90551', 'N'),
(5, 0, 'R4702', '68196-291', '303-398-6351', 'k7xrwlfZgN', 'Allison Saiz', '3528002825119962', '', '201841198021945', 'Gabie Titchen', '', '14146.98', '72146.54', '50000000', '0b2dda6458e30945901ac8b7171ea340', 'N'),
(6, 0, 'S62631S', '50458-307', '804-733-0118', 'd14EKS43', 'Imogene Blaber', '670656382616675814', '', '3534747567007039', 'Bondie Dmytryk', '', '24219.61', '38208.29', '50000000', 'cea20dd5994c35179a7361a383d913ba', 'N'),
(7, 0, 'S63634D', '49288-0623', '693-334-5900', 'fQFnonITpvln', 'Tedda Risbridger', '5191950087783209', '', '6331101133044076', 'Celie Pomeroy', '', '97266.04', '75961.34', '50000000', '8531803dbf0553462b05ce598c74bcf2', 'N'),
(8, 0, 'M8080XA', '54868-4801', '733-718-1888', 'c33RXO', 'Pattie Blunden', '201637148528447', '', '3578551431379617', 'Inez Ainscow', '', '69930.10', '10422.43', '50000000', '7d20227482299a6b3c0b31f611ebf83e', 'N'),
(9, 0, 'S76099S', '58414-2020', '861-741-1551', 'ibIq3WnL', 'Jorry Kop', '4749970597991', '', '6709614711059765', 'Cloe Largan', '', '65294.55', '89269.20', '50000000', 'd78e8d74aea84a952e103461d26cf536', 'N'),
(10, 0, 'T783XXA', '49348-865', '597-272-3738', '0LX1yfwl3W', 'Chip Apark', '5602242197043867258', '', '3563688270439578', 'Elyssa Trematick', '', '83183.84', '19276.56', '50000000', 'e2ff21a74a6a36898c8aa585f3a4ec95', 'N'),
(11, 0, 'Y35891A', '36987-3278', '124-923-0765', 'mP3ZiQBC', 'Elroy Chsteney', '3571545912579486', '', '5415713811267521', 'Claudianus Habben', '', '54570.88', '70541.09', '50000000', 'e16a1ddaab80db2adaef3f5eed6a5845', 'N'),
(12, 0, 'A203', '0409-7889', '773-785-0314', 'm4Hn39V9cujN', 'Malvin Khalid', '3547719992924430', '', '3576222618445082', 'Ulysses Gotmann', '', '40643.09', '61699.88', '50000000', 'a2b439dd9aaa6cdf60d26632de45687a', 'N'),
(13, 0, 'S9431XS', '52959-030', '488-142-5221', 'FHFamPN', 'Britteny Tomaino', '3550910413294347', '', '4936060971265768218', 'Marris Corke', '', '79316.07', '13645.45', '50000000', '4020fba299a3802de3d3a15ec144bf67', 'N'),
(14, 0, 'S62101', '21130-854', '830-611-3250', 'GigIAcr', 'Fredric Sycamore', '5472410332985414', '', '3543272449431249', 'Cassandry Sawl', '', '179575.14', '65404.98', '50000000', '65e9ff45df6f2afd02e0fcbde5336c87', 'N'),
(15, 0, 'S52012', '58668-2011', '620-988-3864', 'sU7oQkq38q6S', 'Gustavo Bythway', '3589551306568267', '', '6761464885841712', 'Ulrike Powland', '', '29730.17', '18594.79', '50000000', '0c3fd745764686368f0b9cd104243f33', 'N'),
(16, 0, 'C435', '55154-1497', '482-606-9470', 'XFLLtJ', 'Donovan Baskeyfield', '30112825423707', '', '5002352613860271', 'Tabina Wellbank', '', '27171.77', '63007.55', '50000000', '34ea85bdb9d0026d2a1b8d0719c09438', 'N'),
(17, 0, 'D592', '50078-201', '826-140-8102', 'bLQys1V', 'Wyndham Drepp', '5010129493273274', '', '5002353580556710', 'Ceil Gitthouse', '', '73712.02', '85164.86', '50000000', '4c7745f0f821f22255da1f293ec11b19', 'N'),
(18, 0, 'S75802A', '76237-257', '373-505-9221', '1NnXqI51crz', 'Gayel Sinderland', '374288257262781', '', '4917543019176802', 'Cirstoforo De Michetti', '', '11843.09', '25784.77', '50000000', 'ded323b27b455e04c4a620f2910d7ec0', 'N'),
(19, 0, 'S52236G', '11584-0476', '176-643-3731', '4l4Cfkrvl', 'Moishe Fearenside', '50202612343252162', '', '5610537699763936', 'Trixie Dmitr', '', '38389.24', '83799.81', '50000000', '4efc9c8f21246408e0fe321ab2d0d8b1', 'N'),
(20, 0, 'S4292XA', '76237-137', '753-843-3272', 'l1icMc', 'Theresa Gilstin', '201413013899796', '', '3587694926262408', 'Lottie Arger', '', '80352.58', '71673.95', '50000000', '6c7182ea24c1aaa8c6ad43582d78ff95', 'N'),
(21, 0, 'S82199G', '49999-311', '703-747-9037', 'FsmeneFyR8', 'Gifford Leed', '3537744657680046', '', '63040834264348048', 'Timothea McMylor', '', '39119', '1000', '50000000', '98fbac4d8c9377973ddc3ff7a30c623c', 'N'),
(22, 0, 'S96922S', '25021-668', '258-870-9172', 'o5ceW7', 'Boy Leal', '3577207230318600', '', '337941902241917', 'Ida Martensen', '', '32436.61', '19213.59', '50000000', '5b47bc0c240a3539bb2df062f8e12fd8', 'N'),
(23, 0, 'S2509XS', '64125-137', '241-733-1194', 'VHKRaUGFQ', 'Darill Alexandrescu', '3562977527680618', '', '30154113226772', 'Jarvis Haversum', '', '106580.93', '43045.13', '50000000', '873db8dc4f92f535d47902bde689e808', 'N'),
(24, 0, 'T17928', '59535-7601', '709-141-6489', '289UNs', 'Rosco Simeon', '5553057775438906', '', '201975270273956', 'Karly Coviello', '', '46747.36', '69239.24', '50000000', '3b74cee887dd5238512dc74872745037', 'N'),
(25, 0, 'S42491A', '58118-0009', '268-949-9852', 'UtGuXbphpkl', 'Saidee Spedding', '5018521310265651', '', '3589177611980466', 'Gussie Pedersen', '', '27230.69', '83864.06', '50000000', '1b362287edb8e568fdb782b6fe8a9972', 'N'),
(26, 0, 'S52235M', '54868-5730', '755-348-5062', 'VXSNIEx4M', 'Bellanca Byfford', '6331109877754287856', '', '490583296925500557', 'Marchelle Lamprecht', '', '64248.14', '45358.49', '50000000', '8bb2b4f3ab226261c9277e624ea486c8', 'N'),
(27, 0, 'T2690', '76255-2001', '414-141-5150', 'bJT1RJjmB98h', 'Evvie Thursby', '3529649929075612', '', '3581412705994620', 'Ronny Ruben', '', '31612.73', '38228.62', '50000000', '64a59a85b2f7bdfb28a1f93d45900dda', 'N'),
(28, 0, 'Z90722', '0409-7808', '273-332-0414', 'QwRGYdxmB', 'Werner Brimmacombe', '201951636158769', '', '374283481120889', 'Lettie de Copeman', '', '51267.63', '83854.06', '50000000', '90ccff9df150d8e146442f61181a126a', 'N'),
(29, 0, 'S46929A', '35000-582', '915-151-4111', 'Ugs6kCoAykL', 'Oliy Ropert', '3563362734320887', '', '3543644239094207', 'Jannel Benneyworth', '', '5579.44', '81329.34', '50000000', '15e7d57a2aeec7d057852cae51933406', 'N'),
(30, 0, 'T372X4A', '55154-0908', '614-657-3610', 'g4KRBTmEmo1x', 'Joeann Bendson', '50184162908450399', '', '5602212574488690', 'Jill Symcock', '', '35692.16', '22231.58', '50000000', '1a8eb6f39dacac3012a40397cc33d34f', 'N'),
(33, 91, '', 'KH15257757', '0967709163', 'e10adc3949ba59abbe56e057f20f883e', 'phi', '212254367', 'images/customer_customer/Capture_Copy_0.PNG', '1234567890', 'tâm', 'images/customer_customer/JPEG_20210309_133106_3225373963381442144.jpg', '200000', '', '50000000', '', 'Y'),
(34, 0, '', 'KH15261142', '0336829222', 'e10adc3949ba59abbe56e057f20f883e', '', '212254367', 'images/customer_customer/Capture_Copy_1.PNG', '', '', '', '24900', '100', '50000000', '', 'Y'),
(35, 0, 'KH13456789', 'KH15261362', '0336819000', 'e10adc3949ba59abbe56e057f20f883e', '', '123456789', 'images/customer_customer/Screenshot (1).png', '', '', '', '39280', '', '50000000', '', 'Y'),
(36, 1, 'dfwfw', 'KH15375770', '0336819005', '', 'Phung Quoc Minh Khanh', '74422444', 'images/customer_customer/Screenshot (1)_Copy_0.png', '46546512757', 'Phùng Quốc Minh Khánh', 'images/customer_customer/Screenshot (1)_Copy_1.png', '0', '', '50000000', '', 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_demo`
--

CREATE TABLE `tbl_customer_demo` (
  `id` int(10) UNSIGNED NOT NULL,
  `demo_name` varchar(200) NOT NULL,
  `demo_wallet_bet` varchar(500) NOT NULL,
  `demo_wallet_payment` varchar(500) NOT NULL,
  `demo_token` varchar(500) NOT NULL,
  `demo_active` enum('Y','N') NOT NULL,
  `force_sign_out` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_customer_demo`
--

INSERT INTO `tbl_customer_demo` (`id`, `demo_name`, `demo_wallet_bet`, `demo_wallet_payment`, `demo_token`, `demo_active`, `force_sign_out`) VALUES
(1, 'T63122S', '12397.40', '22533.08', '257b8398597a2f8729b870953b0b9548', 'N', '0'),
(2, 'J120', '77366.44', '83784.17', 'b87d98ddca8627d546ddcba253eb9939', 'N', '0'),
(3, 'N6042', '64059.75', '73708.17', '5fad6223026e957d85c47be9a626c7d8', 'N', '0'),
(4, 'S0182', '3035.44', '46215.36', '66437ac501f9ba8ab830a48796bb4fce', 'N', '0'),
(5, 'M9942', '82121.24', '45074.01', 'fd4f6c726d0ac71d403ac69bf33ce6d3', 'N', '0'),
(6, 'O7581', '90475.49', '94562.94', '7986eb31a56b2874cd1f032181c4a290', 'N', '0'),
(7, 'X748', '33032.87', '43598.77', 'e8115c38483fd56c5a5f1030ffc072c6', 'N', '0'),
(8, 'X140XXA', '56770.96', '57126.84', 'd27f69f3b78b49c2170afc8707a88d44', 'N', '0'),
(9, 'V893XXD', '7820.11', '47324.70', '3294d73f21e50f6370a7ba877eb4ef3e', 'N', '0'),
(10, 'H21233', '54818.96', '44139.53', '73dc4a13119ea813cd7359782793e40c', 'N', '0'),
(11, 'H34231', '18607.82', '82290.01', 'b8b297237f5d66606d3c0de1da0c859d', 'N', '0'),
(12, 'H43823', '99405.79', '89884.97', '7013e8cbcf4204f33b99ac862b50975d', 'N', '0'),
(13, 'Q691', '28677.78', '47005.99', '6740a132645471bf4e71d16f33760136', 'N', '0'),
(14, 'S89311', '57210.56', '24090.62', '7e9010982857c6858c8e5a4a53332a63', 'N', '0'),
(15, 'T25631D', '8649.11', '33168.18', '15c7d630c72ed378c1debb15f2905df4', 'N', '0'),
(16, 'H401390', '5965.19', '62982.62', 'b611381c733a32d4ed8f499ec8a49c5c', 'N', '0'),
(17, 'V700', '71285.12', '81088.52', '5855b0e0493625b8dda47abe201a3b6c', 'N', '0'),
(18, 'S92333G', '82825.59', '64326.18', '018080fa5d0f853396a1e088a4b50a2f', 'N', '0'),
(19, 'S32039D', '72184.99', '13453.86', '338859e436e0e22b381ff6b2ae39867f', 'N', '0'),
(20, 'S1095XS', '23335.87', '53060.39', 'b6bfa3fa56e552cb06604f99c6544140', 'N', '0'),
(21, 'M2655', '24723.03', '65007.51', '8abe5ab839240a5b44409683bd8ea4e6', 'N', '0'),
(22, 'O318X12', '9791.86', '86130.10', '62de565837e058cb4bd51c2cfed30158', 'N', '0'),
(23, 'S63073A', '84917.43', '48074.43', '7f10636e6ab3b13de9366009d65d2a04', 'N', '0'),
(24, 'S91322D', '71478.30', '88035.56', 'b3fa8e3b78da1ad175685dc6cacbc321', 'N', '0'),
(25, 'E10341', '6285.25', '55899.49', 'd87e92f44f6f820debc1f697f73bca1b', 'N', '0'),
(26, 'S48011S', '31864.83', '66116.91', '495410fc5e376d23d460e58656f4b810', 'N', '0'),
(27, 'I605', '95441.47', '44655.98', '25a0361627ca8e9a3f116ec4de2cda51', 'N', '0'),
(28, 'W90', '78237.13', '44092.98', 'bc30f24cc197afe34fdba312396246aa', 'N', '0'),
(29, 'S82241N', '95219.16', '86171.01', 'fe5b1d9c636b31fb2f01386e8925a494', 'N', '0'),
(30, 'H18629', '40339.68', '99508.28', '955f2b57fe4a49112dc68ea65f3c72c2', 'N', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_demo_log`
--

CREATE TABLE `tbl_customer_demo_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_demo` int(10) UNSIGNED NOT NULL,
  `trading_log` varchar(500) NOT NULL,
  `trading_bet` varchar(500) NOT NULL,
  `trading_type` enum('up','down') NOT NULL,
  `trading_result` enum('up','down') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exchange_exchange`
--

CREATE TABLE `tbl_exchange_exchange` (
  `id` int(10) UNSIGNED NOT NULL,
  `exchange_name` varchar(200) NOT NULL,
  `exchange_open` varchar(200) NOT NULL,
  `exchange_close` varchar(200) NOT NULL,
  `exchange_period` varchar(200) NOT NULL,
  `exchange_idle` varchar(20) NOT NULL DEFAULT '60',
  `exchange_percent` int(10) UNSIGNED NOT NULL DEFAULT '91',
  `exchange_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `exchange_updated_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_exchange_exchange`
--

INSERT INTO `tbl_exchange_exchange` (`id`, `exchange_name`, `exchange_open`, `exchange_close`, `exchange_period`, `exchange_idle`, `exchange_percent`, `exchange_active`, `exchange_updated_by`) VALUES
(18, '', '1615378860', '1615380060', '120', '60', 91, 'Y', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exchange_period`
--

CREATE TABLE `tbl_exchange_period` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_exchange` int(10) UNSIGNED NOT NULL,
  `period_open` varchar(200) NOT NULL,
  `period_close` varchar(200) NOT NULL,
  `period_point_idle` varchar(200) NOT NULL,
  `period_now` varchar(200) DEFAULT NULL,
  `period_result` enum('up','down') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_exchange_period`
--

INSERT INTO `tbl_exchange_period` (`id`, `id_exchange`, `period_open`, `period_close`, `period_point_idle`, `period_now`, `period_result`) VALUES
(144, 18, '1615378860', '1615378980', '1615378920', '1615378978', 'up'),
(145, 18, '1615378980', '1615379100', '1615379040', '1615379098', 'up'),
(146, 18, '1615379100', '1615379220', '1615379160', '1615379218', 'up'),
(147, 18, '1615379220', '1615379340', '1615379280', '1615379228', NULL),
(148, 18, '1615379340', '1615379460', '1615379400', NULL, NULL),
(149, 18, '1615379460', '1615379580', '1615379520', NULL, NULL),
(150, 18, '1615379580', '1615379700', '1615379640', NULL, NULL),
(151, 18, '1615379700', '1615379820', '1615379760', NULL, NULL),
(152, 18, '1615379820', '1615379940', '1615379880', NULL, NULL),
(153, 18, '1615379940', '1615380060', '1615380000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_exchange_temporary`
--

CREATE TABLE `tbl_exchange_temporary` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_exchange` int(10) UNSIGNED NOT NULL,
  `exchange_open` varchar(200) NOT NULL,
  `exchange_close` varchar(200) NOT NULL,
  `exchange_period` varchar(200) NOT NULL,
  `exchange_idle` varchar(200) NOT NULL DEFAULT '60',
  `exchange_updated_by` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_exchange_temporary`
--

INSERT INTO `tbl_exchange_temporary` (`id`, `id_exchange`, `exchange_open`, `exchange_close`, `exchange_period`, `exchange_idle`, `exchange_updated_by`) VALUES
(2, 1, '1615428240', '1615478640', '120', '60', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_graph_info`
--

CREATE TABLE `tbl_graph_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_exchange` int(10) UNSIGNED NOT NULL,
  `id_period` int(10) UNSIGNED NOT NULL,
  `x_y` text NOT NULL,
  `point_map` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_graph_info`
--

INSERT INTO `tbl_graph_info` (`id`, `id_exchange`, `id_period`, `x_y`, `point_map`) VALUES
(44, 18, 144, '[{\"x\":1615378860,\"y\":10.351},{\"x\":1615378861,\"y\":10.517},{\"x\":1615378862,\"y\":10.76},{\"x\":1615378863,\"y\":10.005},{\"x\":1615378864,\"y\":9.888},{\"x\":1615378865,\"y\":10.931},{\"x\":1615378866,\"y\":9.205},{\"x\":1615378867,\"y\":9.62},{\"x\":1615378868,\"y\":9.418},{\"x\":1615378869,\"y\":10.41},{\"x\":1615378870,\"y\":9.891},{\"x\":1615378871,\"y\":9.757},{\"x\":1615378872,\"y\":9.209},{\"x\":1615378873,\"y\":10.246},{\"x\":1615378874,\"y\":10.909},{\"x\":1615378875,\"y\":10.891},{\"x\":1615378876,\"y\":10.822},{\"x\":1615378877,\"y\":9.555},{\"x\":1615378878,\"y\":10.061},{\"x\":1615378879,\"y\":10.821},{\"x\":1615378880,\"y\":10.304},{\"x\":1615378881,\"y\":10.131},{\"x\":1615378882,\"y\":9.762},{\"x\":1615378883,\"y\":10.047},{\"x\":1615378884,\"y\":10.862},{\"x\":1615378885,\"y\":10.246},{\"x\":1615378886,\"y\":10.449},{\"x\":1615378887,\"y\":9.953},{\"x\":1615378888,\"y\":10.116},{\"x\":1615378889,\"y\":10.031},{\"x\":1615378890,\"y\":10.767},{\"x\":1615378891,\"y\":10.819},{\"x\":1615378892,\"y\":9.176},{\"x\":1615378893,\"y\":9.605},{\"x\":1615378894,\"y\":10.608},{\"x\":1615378895,\"y\":10.73},{\"x\":1615378896,\"y\":9.669},{\"x\":1615378897,\"y\":10.102},{\"x\":1615378898,\"y\":9.82},{\"x\":1615378899,\"y\":10.595},{\"x\":1615378900,\"y\":9.805},{\"x\":1615378901,\"y\":10.904},{\"x\":1615378902,\"y\":10.477},{\"x\":1615378903,\"y\":9.564},{\"x\":1615378904,\"y\":11},{\"x\":1615378905,\"y\":10.946},{\"x\":1615378906,\"y\":10.167},{\"x\":1615378907,\"y\":9.81},{\"x\":1615378908,\"y\":9.048},{\"x\":1615378909,\"y\":9.99},{\"x\":1615378910,\"y\":10.268},{\"x\":1615378911,\"y\":9.696},{\"x\":1615378912,\"y\":9.896},{\"x\":1615378913,\"y\":10.961},{\"x\":1615378914,\"y\":9.226},{\"x\":1615378915,\"y\":10.616},{\"x\":1615378916,\"y\":10.255},{\"x\":1615378917,\"y\":9.752},{\"x\":1615378918,\"y\":9.795},{\"x\":1615378919,\"y\":9.633},{\"x\":1615378920,\"y\":10.082},{\"x\":1615378921,\"y\":10.453},{\"x\":1615378922,\"y\":10.431},{\"x\":1615378923,\"y\":10.614},{\"x\":1615378924,\"y\":10.062},{\"x\":1615378925,\"y\":10.36},{\"x\":1615378926,\"y\":10.453},{\"x\":1615378927,\"y\":10.173},{\"x\":1615378928,\"y\":10.589},{\"x\":1615378929,\"y\":10.494},{\"x\":1615378930,\"y\":10.087},{\"x\":1615378931,\"y\":10.546},{\"x\":1615378932,\"y\":10.691},{\"x\":1615378933,\"y\":10.203},{\"x\":1615378934,\"y\":10.55},{\"x\":1615378935,\"y\":10.514},{\"x\":1615378936,\"y\":10.827},{\"x\":1615378937,\"y\":10.479},{\"x\":1615378938,\"y\":10.338},{\"x\":1615378939,\"y\":10.344},{\"x\":1615378940,\"y\":10.749},{\"x\":1615378941,\"y\":10.641},{\"x\":1615378942,\"y\":10.459},{\"x\":1615378943,\"y\":10.389},{\"x\":1615378944,\"y\":10.593},{\"x\":1615378945,\"y\":10.526},{\"x\":1615378946,\"y\":10.45},{\"x\":1615378947,\"y\":10.616},{\"x\":1615378948,\"y\":10.551},{\"x\":1615378949,\"y\":10.331},{\"x\":1615378950,\"y\":10.471},{\"x\":1615378951,\"y\":10.131},{\"x\":1615378952,\"y\":10.577},{\"x\":1615378953,\"y\":10.065},{\"x\":1615378954,\"y\":10.074},{\"x\":1615378955,\"y\":10.815},{\"x\":1615378956,\"y\":10.299},{\"x\":1615378957,\"y\":10.328},{\"x\":1615378958,\"y\":10.183},{\"x\":1615378959,\"y\":10.846},{\"x\":1615378960,\"y\":10.381},{\"x\":1615378961,\"y\":10.85},{\"x\":1615378962,\"y\":10.671},{\"x\":1615378963,\"y\":10.36},{\"x\":1615378964,\"y\":10.574},{\"x\":1615378965,\"y\":10.661},{\"x\":1615378966,\"y\":10.257},{\"x\":1615378967,\"y\":10.462},{\"x\":1615378968,\"y\":10.336},{\"x\":1615378969,\"y\":10.288},{\"x\":1615378970,\"y\":10.811},{\"x\":1615378971,\"y\":10.509},{\"x\":1615378972,\"y\":10.511},{\"x\":1615378973,\"y\":10.467},{\"x\":1615378974,\"y\":10.454},{\"x\":1615378975,\"y\":10.315},{\"x\":1615378976,\"y\":10.506},{\"x\":1615378977,\"y\":10.647},{\"x\":1615378978,\"y\":11.081260896653532}]', '{\"x\":1615378860,\"y\":10.351}'),
(45, 18, 145, '[{\"x\":1615378980,\"y\":9.15},{\"x\":1615378981,\"y\":9.997},{\"x\":1615378982,\"y\":10.278},{\"x\":1615378983,\"y\":9.397},{\"x\":1615378984,\"y\":9.662},{\"x\":1615378985,\"y\":9.627},{\"x\":1615378986,\"y\":10.863},{\"x\":1615378987,\"y\":9.82},{\"x\":1615378988,\"y\":9.739},{\"x\":1615378989,\"y\":9.011},{\"x\":1615378990,\"y\":9.062},{\"x\":1615378991,\"y\":10.4},{\"x\":1615378992,\"y\":10.966},{\"x\":1615378993,\"y\":10.401},{\"x\":1615378994,\"y\":9.032},{\"x\":1615378995,\"y\":10.809},{\"x\":1615378996,\"y\":10.808},{\"x\":1615378997,\"y\":9.062},{\"x\":1615378998,\"y\":10.224},{\"x\":1615378999,\"y\":9.002},{\"x\":1615379000,\"y\":9.873},{\"x\":1615379001,\"y\":10.597},{\"x\":1615379002,\"y\":9.817},{\"x\":1615379003,\"y\":10.907},{\"x\":1615379004,\"y\":10.975},{\"x\":1615379005,\"y\":10.425},{\"x\":1615379006,\"y\":10.159},{\"x\":1615379007,\"y\":10.377},{\"x\":1615379008,\"y\":10.462},{\"x\":1615379009,\"y\":9.16},{\"x\":1615379010,\"y\":10.244},{\"x\":1615379011,\"y\":10.718},{\"x\":1615379012,\"y\":10.407},{\"x\":1615379013,\"y\":9.671},{\"x\":1615379014,\"y\":9.422},{\"x\":1615379015,\"y\":10.375},{\"x\":1615379016,\"y\":9.887},{\"x\":1615379017,\"y\":9.953},{\"x\":1615379018,\"y\":9.91},{\"x\":1615379019,\"y\":10.687},{\"x\":1615379020,\"y\":9.245},{\"x\":1615379021,\"y\":9.889},{\"x\":1615379022,\"y\":9.09},{\"x\":1615379023,\"y\":9.46},{\"x\":1615379024,\"y\":9.523},{\"x\":1615379025,\"y\":9.083},{\"x\":1615379026,\"y\":10.619},{\"x\":1615379027,\"y\":9.883},{\"x\":1615379028,\"y\":9.756},{\"x\":1615379029,\"y\":9.68},{\"x\":1615379030,\"y\":10.972},{\"x\":1615379031,\"y\":10.479},{\"x\":1615379032,\"y\":9.871},{\"x\":1615379041,\"y\":9.441},{\"x\":1615379042,\"y\":9.104},{\"x\":1615379043,\"y\":9.445},{\"x\":1615379044,\"y\":8.963},{\"x\":1615379045,\"y\":9.003},{\"x\":1615379046,\"y\":9.177},{\"x\":1615379047,\"y\":9.554},{\"x\":1615379048,\"y\":9.024},{\"x\":1615379049,\"y\":9.038},{\"x\":1615379050,\"y\":9.522},{\"x\":1615379051,\"y\":9.032},{\"x\":1615379052,\"y\":9.638},{\"x\":1615379053,\"y\":9.15},{\"x\":1615379054,\"y\":9.091},{\"x\":1615379055,\"y\":9.489},{\"x\":1615379056,\"y\":8.944},{\"x\":1615379057,\"y\":9.117},{\"x\":1615379058,\"y\":9.123},{\"x\":1615379059,\"y\":9.247},{\"x\":1615379060,\"y\":9.111},{\"x\":1615379061,\"y\":8.975},{\"x\":1615379062,\"y\":9.592},{\"x\":1615379063,\"y\":9.39},{\"x\":1615379064,\"y\":9.504},{\"x\":1615379065,\"y\":9.488},{\"x\":1615379066,\"y\":9.061},{\"x\":1615379067,\"y\":9.015},{\"x\":1615379068,\"y\":8.867},{\"x\":1615379069,\"y\":8.919},{\"x\":1615379070,\"y\":9.318},{\"x\":1615379071,\"y\":9.513},{\"x\":1615379072,\"y\":9.096},{\"x\":1615379073,\"y\":8.984},{\"x\":1615379074,\"y\":8.897},{\"x\":1615379075,\"y\":8.946},{\"x\":1615379076,\"y\":9.067},{\"x\":1615379077,\"y\":9.223},{\"x\":1615379078,\"y\":9.518},{\"x\":1615379079,\"y\":9.606},{\"x\":1615379080,\"y\":8.887},{\"x\":1615379081,\"y\":9.562},{\"x\":1615379082,\"y\":8.875},{\"x\":1615379083,\"y\":8.984},{\"x\":1615379084,\"y\":9.235},{\"x\":1615379085,\"y\":8.972},{\"x\":1615379086,\"y\":8.986},{\"x\":1615379087,\"y\":9.575},{\"x\":1615379088,\"y\":9.099},{\"x\":1615379089,\"y\":9.29},{\"x\":1615379090,\"y\":9.232},{\"x\":1615379091,\"y\":9.354},{\"x\":1615379092,\"y\":9.591},{\"x\":1615379093,\"y\":8.941},{\"x\":1615379094,\"y\":8.947},{\"x\":1615379095,\"y\":9.345},{\"x\":1615379096,\"y\":9.524},{\"x\":1615379097,\"y\":9.116},{\"x\":1615379098,\"y\":9.906650997464657}]', '{\"x\":1615378980,\"y\":9.15}'),
(46, 18, 146, '[{\"x\":1615379100,\"y\":9.15},{\"x\":1615379101,\"y\":9.538},{\"x\":1615379102,\"y\":10.441},{\"x\":1615379103,\"y\":10.641},{\"x\":1615379104,\"y\":9.29},{\"x\":1615379105,\"y\":10.25},{\"x\":1615379106,\"y\":9.076},{\"x\":1615379107,\"y\":9.885},{\"x\":1615379108,\"y\":9.061},{\"x\":1615379109,\"y\":10.431},{\"x\":1615379110,\"y\":10.356},{\"x\":1615379111,\"y\":9.498},{\"x\":1615379122,\"y\":10.615},{\"x\":1615379123,\"y\":10.36},{\"x\":1615379124,\"y\":10.813},{\"x\":1615379125,\"y\":9.249},{\"x\":1615379126,\"y\":9.178},{\"x\":1615379127,\"y\":10.591},{\"x\":1615379128,\"y\":9.659},{\"x\":1615379150,\"y\":10.093},{\"x\":1615379151,\"y\":9.354},{\"x\":1615379152,\"y\":9.342},{\"x\":1615379153,\"y\":10.375},{\"x\":1615379154,\"y\":10.966},{\"x\":1615379155,\"y\":10.016},{\"x\":1615379157,\"y\":10.99},{\"x\":1615379158,\"y\":10.209},{\"x\":1615379159,\"y\":10.654},{\"x\":1615379160,\"y\":9.384},{\"x\":1615379161,\"y\":9.313},{\"x\":1615379162,\"y\":9.532},{\"x\":1615379163,\"y\":9.193},{\"x\":1615379164,\"y\":8.94},{\"x\":1615379165,\"y\":9.021},{\"x\":1615379166,\"y\":9.264},{\"x\":1615379167,\"y\":9.25},{\"x\":1615379168,\"y\":8.922},{\"x\":1615379169,\"y\":9.556},{\"x\":1615379170,\"y\":9.064},{\"x\":1615379171,\"y\":8.924},{\"x\":1615379172,\"y\":9.455},{\"x\":1615379173,\"y\":8.863},{\"x\":1615379174,\"y\":9.384},{\"x\":1615379175,\"y\":9.048},{\"x\":1615379176,\"y\":9.142},{\"x\":1615379177,\"y\":9.338},{\"x\":1615379178,\"y\":9.1},{\"x\":1615379179,\"y\":8.874},{\"x\":1615379180,\"y\":9.157},{\"x\":1615379181,\"y\":9.509},{\"x\":1615379182,\"y\":9.158},{\"x\":1615379183,\"y\":8.897},{\"x\":1615379184,\"y\":8.901},{\"x\":1615379185,\"y\":9.524},{\"x\":1615379186,\"y\":8.899},{\"x\":1615379187,\"y\":9.51},{\"x\":1615379188,\"y\":9.402},{\"x\":1615379189,\"y\":9.151},{\"x\":1615379190,\"y\":9.044},{\"x\":1615379191,\"y\":9.071},{\"x\":1615379192,\"y\":9.501},{\"x\":1615379193,\"y\":9.099},{\"x\":1615379194,\"y\":9.448},{\"x\":1615379195,\"y\":9.14},{\"x\":1615379196,\"y\":9.539},{\"x\":1615379197,\"y\":8.9},{\"x\":1615379198,\"y\":9.424},{\"x\":1615379199,\"y\":9.083},{\"x\":1615379200,\"y\":8.936},{\"x\":1615379201,\"y\":9.628},{\"x\":1615379202,\"y\":9.075},{\"x\":1615379203,\"y\":9.17},{\"x\":1615379204,\"y\":8.947},{\"x\":1615379205,\"y\":9.199},{\"x\":1615379206,\"y\":9.212},{\"x\":1615379207,\"y\":9.402},{\"x\":1615379208,\"y\":8.99},{\"x\":1615379209,\"y\":9.23},{\"x\":1615379210,\"y\":9.208},{\"x\":1615379211,\"y\":9.126},{\"x\":1615379212,\"y\":9.107},{\"x\":1615379213,\"y\":9.556},{\"x\":1615379214,\"y\":9.182},{\"x\":1615379215,\"y\":9.633},{\"x\":1615379216,\"y\":8.902},{\"x\":1615379217,\"y\":9.392},{\"x\":1615379218,\"y\":9.302594254562212}]', '{\"x\":1615379100,\"y\":9.15}'),
(47, 18, 147, '[{\"x\":1615379220,\"y\":9.558},{\"x\":1615379221,\"y\":10.506},{\"x\":1615379222,\"y\":9.039},{\"x\":1615379223,\"y\":9.243},{\"x\":1615379224,\"y\":9.803},{\"x\":1615379225,\"y\":10.064},{\"x\":1615379226,\"y\":9.925},{\"x\":1615379227,\"y\":10.765},{\"x\":1615379228,\"y\":9.645}]', '{\"x\":1615379220,\"y\":9.558}');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_deposit`
--

CREATE TABLE `tbl_request_deposit` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_customer` int(10) UNSIGNED NOT NULL,
  `request_code` varchar(200) NOT NULL,
  `request_value` varchar(500) NOT NULL,
  `request_time_completed` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_request_deposit`
--

INSERT INTO `tbl_request_deposit` (`id`, `id_customer`, `request_code`, `request_value`, `request_time_completed`) VALUES
(2, 21, 'NT15256618', '250000', '1615256618'),
(3, 30, 'NT15261033', '300000', '1615261033'),
(4, 1, 'NT15265666', '50000', '1615265666'),
(5, 1, 'NT15265668', '50000', '1615265668'),
(6, 1, 'NT15265669', '50000', '1615265669'),
(7, 1, 'NT15265669', '50000', '1615265669'),
(8, 1, 'NT15265830', '50000', '1615265830'),
(9, 1, 'NT15265884', '50000', '1615265884'),
(10, 1, 'NT15265885', '50000', '1615265885'),
(11, 1, 'NT15271474', '50000', '1615271474'),
(12, 1, 'NT15271475', '50000', '1615271475'),
(13, 2, 'NT15271478', '50000', '1615271478'),
(14, 2, 'NT15271479', '50000', '1615271479'),
(15, 2, 'NT15271482', '50000', '1615271482'),
(16, 23, 'NT15271483', '50000', '1615271483'),
(17, 1, 'NT15271488', '50000', '1615271488'),
(18, 1, 'NT15271489', '50000', '1615271489'),
(19, 34, 'NT15271684', '50000', '1615271684'),
(20, 34, 'NT15271686', '50000', '1615271686'),
(21, 34, 'NT15271687', '50000', '1615271687'),
(22, 33, 'NT15271690', '50000', '1615271690'),
(23, 33, 'NT15271691', '50000', '1615271691'),
(24, 33, 'NT15271692', '50000', '1615271692'),
(25, 32, 'NT15271696', '50000', '1615271696'),
(26, 32, 'NT15271708', '50000', '1615271708'),
(27, 32, 'NT15271709', '50000', '1615271709'),
(28, 32, 'NT15271710', '50000', '1615271710'),
(29, 32, 'NT15271717', '50000', '1615271717'),
(30, 32, 'NT15271719', '50000', '1615271719'),
(31, 32, 'NT15271731', '50000', '1615271731'),
(32, 34, 'NT15271741', '50000', '1615271741'),
(33, 34, 'NT15271742', '50000', '1615271742'),
(34, 32, 'NT15271746', '50000', '1615271746'),
(35, 32, 'NT15271747', '50000', '1615271747'),
(36, 31, 'NT15271751', '50000', '1615271751'),
(37, 31, 'NT15271751', '50000', '1615271751'),
(38, 31, 'NT15271752', '50000', '1615271752'),
(39, 14, 'NT15271756', '50000', '1615271756'),
(40, 14, 'NT15271757', '50000', '1615271757'),
(41, 14, 'NT15271758', '50000', '1615271758'),
(42, 34, 'NT15271766', '50000', '1615271766'),
(43, 34, 'NT15271767', '50000', '1615271767'),
(44, 34, 'NT15272004', '50000', '1615272004'),
(45, 34, 'NT15272005', '50000', '1615272005'),
(46, 33, 'NT15272007', '50000', '1615272007'),
(47, 32, 'NT15272011', '50000', '1615272011'),
(48, 32, 'NT15272014', '50000', '1615272014'),
(49, 32, 'NT15272014', '50000', '1615272014'),
(50, 32, 'NT15272015', '50000', '1615272015'),
(51, 35, 'NT15272018', '50000', '1615272018'),
(52, 35, 'NT15272019', '50000', '1615272019'),
(53, 35, 'NT15272055', '50000', '1615272055');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_request_payment`
--

CREATE TABLE `tbl_request_payment` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_customer` int(10) UNSIGNED NOT NULL,
  `request_created` varchar(200) NOT NULL,
  `request_code` varchar(200) NOT NULL,
  `request_value` varchar(500) NOT NULL,
  `request_completed` varchar(200) DEFAULT NULL,
  `request_status` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `request_comment` text,
  `request_img` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_request_payment`
--

INSERT INTO `tbl_request_payment` (`id`, `id_customer`, `request_created`, `request_code`, `request_value`, `request_completed`, `request_status`, `request_comment`, `request_img`) VALUES
(13, 21, '1615277031', 'RT15277031', '500', '', '3', '', ''),
(14, 21, '1615277032', 'RT15277032', '500', '', '2', '', ''),
(15, 21, '1615277033', 'RT15277033', '500', '', '3', '', ''),
(16, 21, '1615277033', 'RT15277033', '500', '', '4', '', ''),
(17, 21, '1615277042', 'RT15277042', '1000', '', '4', '', ''),
(18, 21, '1615277042', 'RT15277042', '1000', '', '4', '', ''),
(19, 21, '1615277043', 'RT15277043', '1000', '', '4', '', ''),
(20, 21, '1615277045', 'RT15277045', '1000', '', '4', '', ''),
(21, 34, '1615367633', 'RT15367633', '100', '', '3', '', ''),
(22, 34, '1615367633', 'RT15367733', '5000', '', '3', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_support_category`
--

CREATE TABLE `tbl_support_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `support_category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_support_category`
--

INSERT INTO `tbl_support_category` (`id`, `support_category`) VALUES
(1, 'Beer - Pilsner Urquell'),
(2, 'Beer - Heinekin'),
(3, 'Appetizer - Smoked Salmon / Dill'),
(4, 'Strawberries - California'),
(5, 'Paper - Brown Paper Mini Cups'),
(6, 'Tomatoes - Orange'),
(7, 'Potato - Sweet'),
(8, 'Crab - Meat'),
(9, 'Radish'),
(10, 'Island Oasis - Wildberry'),
(11, 'Breakfast Quesadillas'),
(12, 'French Pastries'),
(13, 'Muskox - French Rack'),
(14, 'Apron'),
(15, 'Nut - Macadamia'),
(16, 'Wine - White, Colubia Cresh'),
(17, 'Island Oasis - Peach Daiquiri'),
(18, 'Beans - Fine'),
(19, 'Cream - 18%'),
(20, 'Bread - Onion Focaccia'),
(21, 'Egg - Salad Premix'),
(22, 'Coriander - Ground'),
(23, 'Raspberries - Frozen'),
(24, 'Potato - Sweet'),
(25, 'Coffee Cup 16oz Foam'),
(26, 'Mustard - Seed'),
(27, 'Wine - Red, Pelee Island Merlot'),
(28, 'Curry Powder'),
(29, 'Cream - 18%'),
(30, 'Initation Crab Meat');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_support_customer`
--

CREATE TABLE `tbl_support_customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_customer` int(10) UNSIGNED NOT NULL,
  `id_support_category` int(10) UNSIGNED NOT NULL,
  `id_support_info` int(10) UNSIGNED NOT NULL,
  `support_date` varchar(200) NOT NULL,
  `support_status` enum('processing','completed','','') NOT NULL DEFAULT 'processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_support_customer`
--

INSERT INTO `tbl_support_customer` (`id`, `id_customer`, `id_support_category`, `id_support_info`, `support_date`, `support_status`) VALUES
(1, 1, 1, 36, '1615272340', 'processing'),
(2, 33, 16, 37, '1615274224', 'processing'),
(3, 33, 3, 38, '1615274275', 'processing'),
(4, 33, 3, 39, '1615274277', 'processing'),
(5, 33, 3, 40, '1615274413', 'processing');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_support_info`
--

CREATE TABLE `tbl_support_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_category` int(10) UNSIGNED NOT NULL,
  `support_request` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_support_info`
--

INSERT INTO `tbl_support_info` (`id`, `id_category`, `support_request`) VALUES
(1, 2, 'volutpat eleifend donec ut dolor morbi vel lectus in quam fringilla rhoncus mauris enim leo rhoncus sed vestibulum'),
(2, 23, 'sit amet nunc viverra dapibus nulla suscipit ligula in lacus'),
(3, 18, 'nam nulla integer pede justo lacinia eget tincidunt eget tempus vel pede morbi porttitor lorem id ligula suspendisse'),
(4, 9, 'sit amet lobortis sapien sapien non mi integer ac neque duis bibendum morbi'),
(5, 18, 'eget elit sodales scelerisque mauris sit amet eros suspendisse accumsan tortor quis turpis sed ante vivamus tortor duis mattis egestas'),
(6, 11, 'ut volutpat sapien arcu sed augue aliquam erat volutpat in congue etiam justo etiam pretium iaculis justo in'),
(7, 15, 'risus dapibus augue vel accumsan tellus nisi eu orci mauris lacinia sapien quis'),
(8, 12, 'ultricies eu nibh quisque id justo sit amet sapien dignissim vestibulum vestibulum ante ipsum primis in'),
(9, 21, 'lacinia aenean sit amet justo morbi ut odio cras mi pede malesuada in imperdiet et commodo vulputate justo in blandit'),
(10, 1, 'platea dictumst maecenas ut massa quis augue luctus tincidunt nulla mollis molestie lorem quisque ut erat curabitur'),
(11, 12, 'sit amet erat nulla tempus vivamus in felis eu sapien cursus vestibulum proin eu mi nulla ac enim'),
(12, 29, 'quam nec dui luctus rutrum nulla tellus in sagittis dui vel nisl duis ac'),
(13, 10, 'purus phasellus in felis donec semper sapien a libero nam dui proin leo odio porttitor id consequat in'),
(14, 10, 'pulvinar sed nisl nunc rhoncus dui vel sem sed sagittis nam congue risus semper porta volutpat quam pede lobortis ligula'),
(15, 6, 'quis libero nullam sit amet turpis elementum ligula vehicula consequat morbi a ipsum integer'),
(16, 8, 'nulla nunc purus phasellus in felis donec semper sapien a libero nam dui proin leo odio porttitor id consequat in'),
(17, 10, 'etiam faucibus cursus urna ut tellus nulla ut erat id mauris vulputate'),
(18, 29, 'faucibus orci luctus et ultrices posuere cubilia curae donec pharetra'),
(19, 12, 'ac diam cras pellentesque volutpat dui maecenas tristique est et'),
(20, 28, 'sit amet erat nulla tempus vivamus in felis eu sapien cursus'),
(21, 12, 'fermentum donec ut mauris eget massa tempor convallis nulla neque'),
(22, 27, 'dui maecenas tristique est et tempus semper est quam pharetra magna ac consequat metus sapien ut'),
(23, 30, 'curabitur gravida nisi at nibh in hac habitasse platea dictumst aliquam augue quam sollicitudin vitae'),
(24, 27, 'rhoncus sed vestibulum sit amet cursus id turpis integer aliquet massa id lobortis convallis tortor risus'),
(25, 10, 'posuere cubilia curae duis faucibus accumsan odio curabitur convallis duis consequat dui nec nisi volutpat eleifend'),
(26, 26, 'varius integer ac leo pellentesque ultrices mattis odio donec vitae nisi'),
(27, 15, 'orci eget orci vehicula condimentum curabitur in libero ut massa volutpat convallis morbi odio odio elementum eu interdum eu tincidunt'),
(28, 8, 'quisque id justo sit amet sapien dignissim vestibulum vestibulum ante ipsum primis in faucibus orci luctus et'),
(29, 25, 'sem sed sagittis nam congue risus semper porta volutpat quam pede lobortis ligula sit amet eleifend'),
(30, 12, 'sapien quis libero nullam sit amet turpis elementum ligula vehicula consequat morbi a'),
(31, 22, 'habitasse platea dictumst morbi vestibulum velit id pretium iaculis diam erat'),
(32, 27, 'fringilla rhoncus mauris enim leo rhoncus sed vestibulum sit amet cursus id turpis integer aliquet massa id lobortis convallis'),
(33, 28, 'id ornare imperdiet sapien urna pretium nisl ut volutpat sapien arcu sed augue aliquam erat'),
(34, 9, 'iaculis congue vivamus metus arcu adipiscing molestie hendrerit at vulputate vitae nisl'),
(35, 6, 'massa id lobortis convallis tortor risus dapibus augue vel accumsan'),
(36, 1, ''),
(37, 16, 'g6fyfyg'),
(38, 3, ''),
(39, 3, ''),
(40, 3, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_trading_log`
--

CREATE TABLE `tbl_trading_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_exchange_period` int(11) NOT NULL,
  `id_customer` int(10) UNSIGNED NOT NULL,
  `trading_log` varchar(200) NOT NULL,
  `trading_bet` varchar(500) NOT NULL,
  `trading_type` enum('up','down') NOT NULL,
  `trading_result` enum('win','lose') DEFAULT NULL,
  `trading_percent` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_trading_log`
--

INSERT INTO `tbl_trading_log` (`id`, `id_exchange_period`, `id_customer`, `trading_log`, `trading_bet`, `trading_type`, `trading_result`, `trading_percent`) VALUES
(1, 1, 33, '1615257867', '5000', 'up', 'win', '91'),
(2, 2, 33, '1615257870', '5000', 'down', 'win', '91'),
(3, 2, 34, '1615257880', '5000', 'down', 'win', '91'),
(4, 1, 34, '1615257890', '5000', 'up', 'lose', '91'),
(5, 1, 1, '1615337798', '50000', 'up', '', ''),
(6, 1, 1, '1615338248', '50000', 'up', '', ''),
(7, 1, 35, '1615338272', '50000', 'up', '', ''),
(8, 1, 1, '1615338316', '50000', 'up', '', ''),
(9, 1, 35, '1615338347', '50000', 'up', '', ''),
(10, 1, 35, '1615338402', '10', 'up', '', ''),
(11, 1, 1, '1615338415', '1', 'up', '', ''),
(12, 1, 35, '1615338436', '2424', 'up', '', ''),
(13, 1, 35, '1615338495', '1000', 'up', '', ''),
(14, 1, 35, '1615338508', '1000', 'up', '', ''),
(15, 1, 35, '1615338535', '1', 'up', '', ''),
(16, 1, 35, '1615338575', '5242', 'up', '', ''),
(17, 1, 35, '1615338604', '10', 'down', '', ''),
(18, 1, 35, '1615338608', '10', 'up', '', ''),
(19, 1, 35, '1615338632', '1000', 'up', '', ''),
(20, 1, 35, '1615338658', '1', 'up', '', ''),
(21, 1, 35, '1615338832', '2', 'up', '', ''),
(22, 1, 35, '1615338835', '2', 'up', '', ''),
(23, 1, 35, '1615338836', '2', 'up', '', ''),
(24, 1, 35, '1615338836', '2', 'up', '', ''),
(25, 1, 35, '1615338836', '2', 'up', '', ''),
(26, 1, 35, '1615338837', '2', 'up', '', ''),
(27, 1, 35, '1615338837', '2', 'up', '', ''),
(28, 1, 35, '1615338837', '2', 'up', '', ''),
(29, 1, 35, '1615338837', '2', 'up', '', ''),
(30, 1, 35, '1615338864', '4', 'up', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_account_account`
--
ALTER TABLE `tbl_account_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_account_authorize`
--
ALTER TABLE `tbl_account_authorize`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_account_permission`
--
ALTER TABLE `tbl_account_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_account_type`
--
ALTER TABLE `tbl_account_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_app_deploy`
--
ALTER TABLE `tbl_app_deploy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_bank_info`
--
ALTER TABLE `tbl_bank_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_customer`
--
ALTER TABLE `tbl_customer_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_demo`
--
ALTER TABLE `tbl_customer_demo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_customer_demo_log`
--
ALTER TABLE `tbl_customer_demo_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_exchange_exchange`
--
ALTER TABLE `tbl_exchange_exchange`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_exchange_period`
--
ALTER TABLE `tbl_exchange_period`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_exchange_temporary`
--
ALTER TABLE `tbl_exchange_temporary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_graph_info`
--
ALTER TABLE `tbl_graph_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_request_deposit`
--
ALTER TABLE `tbl_request_deposit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_request_payment`
--
ALTER TABLE `tbl_request_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_support_category`
--
ALTER TABLE `tbl_support_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_support_customer`
--
ALTER TABLE `tbl_support_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_support_info`
--
ALTER TABLE `tbl_support_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_trading_log`
--
ALTER TABLE `tbl_trading_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_account_account`
--
ALTER TABLE `tbl_account_account`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tbl_account_authorize`
--
ALTER TABLE `tbl_account_authorize`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_account_permission`
--
ALTER TABLE `tbl_account_permission`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_account_type`
--
ALTER TABLE `tbl_account_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_app_deploy`
--
ALTER TABLE `tbl_app_deploy`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_bank_info`
--
ALTER TABLE `tbl_bank_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `tbl_customer_customer`
--
ALTER TABLE `tbl_customer_customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_customer_demo`
--
ALTER TABLE `tbl_customer_demo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_customer_demo_log`
--
ALTER TABLE `tbl_customer_demo_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_exchange_exchange`
--
ALTER TABLE `tbl_exchange_exchange`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_exchange_period`
--
ALTER TABLE `tbl_exchange_period`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `tbl_exchange_temporary`
--
ALTER TABLE `tbl_exchange_temporary`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_graph_info`
--
ALTER TABLE `tbl_graph_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_request_deposit`
--
ALTER TABLE `tbl_request_deposit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tbl_request_payment`
--
ALTER TABLE `tbl_request_payment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_support_category`
--
ALTER TABLE `tbl_support_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_support_customer`
--
ALTER TABLE `tbl_support_customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_support_info`
--
ALTER TABLE `tbl_support_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tbl_trading_log`
--
ALTER TABLE `tbl_trading_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
