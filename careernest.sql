-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 15, 2026 lúc 06:10 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `careernest`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `answers`
--

CREATE TABLE `answers` (
  `AnswerID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `AnswerText` text NOT NULL,
  `RiasecCode` enum('R','I','A','S','E','C') NOT NULL,
  `ScoreValue` int(11) DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `answers`
--

INSERT INTO `answers` (`AnswerID`, `QuestionID`, `AnswerText`, `RiasecCode`, `ScoreValue`, `CreatedAt`) VALUES
(1, 1, 'Rất phù hợp với tôi', 'R', 2, '2026-04-02 02:01:00'),
(2, 1, 'Khá phù hợp', 'R', 1, '2026-04-02 02:02:00'),
(3, 1, 'Không phù hợp lắm', 'R', 0, '2026-04-02 02:03:00'),
(4, 2, 'Rất phù hợp với tôi', 'I', 2, '2026-04-02 02:04:00'),
(5, 2, 'Khá phù hợp', 'I', 1, '2026-04-02 02:05:00'),
(6, 2, 'Không phù hợp lắm', 'I', 0, '2026-04-02 02:06:00'),
(7, 3, 'Rất phù hợp với tôi', 'A', 2, '2026-04-02 02:07:00'),
(8, 3, 'Khá phù hợp', 'A', 1, '2026-04-02 02:08:00'),
(9, 3, 'Không phù hợp lắm', 'A', 0, '2026-04-02 02:09:00'),
(10, 4, 'Rất phù hợp với tôi', 'S', 2, '2026-04-02 02:10:00'),
(11, 4, 'Khá phù hợp', 'S', 1, '2026-04-02 02:11:00'),
(12, 4, 'Không phù hợp lắm', 'S', 0, '2026-04-02 02:12:00'),
(13, 5, 'Rất phù hợp với tôi', 'E', 2, '2026-04-02 02:13:00'),
(14, 5, 'Khá phù hợp', 'E', 1, '2026-04-02 02:14:00'),
(15, 5, 'Không phù hợp lắm', 'E', 0, '2026-04-02 02:15:00'),
(16, 6, 'Rất phù hợp với tôi', 'C', 2, '2026-04-02 02:16:00'),
(17, 6, 'Khá phù hợp', 'C', 1, '2026-04-02 02:17:00'),
(18, 6, 'Không phù hợp lắm', 'C', 0, '2026-04-02 02:18:00'),
(19, 7, 'Rất phù hợp với tôi', 'R', 2, '2026-04-02 02:19:00'),
(20, 7, 'Khá phù hợp', 'R', 1, '2026-04-02 02:20:00'),
(21, 7, 'Không phù hợp lắm', 'R', 0, '2026-04-02 02:21:00'),
(22, 8, 'Rất phù hợp với tôi', 'I', 2, '2026-04-02 02:22:00'),
(23, 8, 'Khá phù hợp', 'I', 1, '2026-04-02 02:23:00'),
(24, 8, 'Không phù hợp lắm', 'I', 0, '2026-04-02 02:24:00'),
(25, 9, 'Rất phù hợp với tôi', 'A', 2, '2026-04-02 02:25:00'),
(26, 9, 'Khá phù hợp', 'A', 1, '2026-04-02 02:26:00'),
(27, 9, 'Không phù hợp lắm', 'A', 0, '2026-04-02 02:27:00'),
(28, 10, 'Rất phù hợp với tôi', 'S', 2, '2026-04-02 02:28:00'),
(29, 10, 'Khá phù hợp', 'S', 1, '2026-04-02 02:29:00'),
(30, 10, 'Không phù hợp lắm', 'S', 0, '2026-04-02 02:30:00'),
(31, 11, 'Rất phù hợp với tôi', 'E', 2, '2026-04-02 02:31:00'),
(32, 11, 'Khá phù hợp', 'E', 1, '2026-04-02 02:32:00'),
(33, 11, 'Không phù hợp lắm', 'E', 0, '2026-04-02 02:33:00'),
(34, 12, 'Rất phù hợp với tôi', 'C', 2, '2026-04-02 02:34:00'),
(35, 12, 'Khá phù hợp', 'C', 1, '2026-04-02 02:35:00'),
(36, 12, 'Không phù hợp lắm', 'C', 0, '2026-04-02 02:36:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `BlogID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Slug` varchar(255) NOT NULL,
  `Summary` text DEFAULT NULL,
  `Content` longtext NOT NULL,
  `ThumbnailURL` varchar(500) DEFAULT NULL,
  `AuthorID` int(11) DEFAULT NULL,
  `Status` enum('Bản nháp','Đã đăng','Ẩn') DEFAULT 'Bản nháp',
  `Views` int(11) DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `blogs`
--

INSERT INTO `blogs` (`BlogID`, `Title`, `Slug`, `Summary`, `Content`, `ThumbnailURL`, `AuthorID`, `Status`, `Views`, `CreatedAt`, `UpdatedAt`, `Category`) VALUES
(1, 'Ngành Công nghệ thông tin có phù hợp với bạn?', 'nganh-cong-nghe-thong-tin-co-phu-hop-voi-ban', 'Bài viết giúp bạn nhận diện tố chất phù hợp, cơ hội việc làm và lộ trình học ngành Công nghệ thông tin.', '<p>Ngành Công nghệ thông tin phù hợp với người yêu công nghệ, thích giải quyết vấn đề và có tinh thần tự học.</p><p>Ở Việt Nam, nhu cầu nhân lực CNTT vẫn cao nhờ chuyển đổi số, thương mại điện tử, dữ liệu và AI.</p><p>Nếu bạn đang phân vân, hãy thử học một khóa lập trình cơ bản, tham gia dự án nhỏ và làm trắc nghiệm RIASEC để hiểu mình rõ hơn.</p>', 'uploads/blogs/blog-it.jpg', 1, 'Đã đăng', 125, '2026-04-03 01:00:00', '2026-04-03 01:00:00', 'Định hướng'),
(2, 'Marketing học gì và ra trường làm gì?', 'marketing-hoc-gi-va-ra-truong-lam-gi', 'Tổng quan ngắn gọn về kiến thức, vị trí việc làm và những hiểu lầm thường gặp khi học Marketing.', '<p>Marketing không chỉ là chạy quảng cáo hay viết nội dung mà còn liên quan đến nghiên cứu khách hàng, chiến lược thương hiệu và tăng trưởng doanh thu.</p><p>Người học nên sớm làm dự án thực tế để hiểu rõ mình hợp content, ads, brand hay research.</p>', 'uploads/blogs/blog-marketing.jpg', 1, 'Đã đăng', 94, '2026-04-03 02:00:00', '2026-04-03 02:00:00', 'Định hướng'),
(3, '5 kỹ năng giúp học sinh chọn ngành bớt mơ hồ', '5-ky-nang-giup-hoc-sinh-chon-nganh-bot-mo-ho', 'Những kỹ năng nền tảng giúp bạn hiểu bản thân và ra quyết định chọn ngành vững vàng hơn.', '<p>Để chọn ngành tốt hơn, học sinh nên rèn kỹ năng tự nhận thức, tìm kiếm thông tin, so sánh lựa chọn, đặt câu hỏi và quản lý kỳ vọng.</p><p>Chọn ngành không chỉ dựa vào điểm số mà còn cần cân nhắc sở thích, năng lực và bối cảnh nghề nghiệp.</p>', 'uploads/blogs/blog-skills.jpg', 1, 'Đã đăng', 88, '2026-04-03 03:00:00', '2026-04-03 03:00:00', 'Kỹ năng'),
(4, 'Khi nào nên chọn ngành theo sở thích, khi nào nên chọn theo cơ hội việc làm?', 'khi-nao-nen-chon-nganh-theo-so-thich-khi-nao-nen-chon-theo-co-hoi-viec-lam', 'Góc nhìn cân bằng giữa sở thích cá nhân và nhu cầu thị trường lao động.', '<p>Sở thích giúp bạn có động lực lâu dài, còn thị trường việc làm giúp bạn có đầu ra thực tế.</p><p>Cách phù hợp nhất là tìm giao điểm giữa điều bạn thích, điều bạn làm tốt và điều xã hội cần.</p>', 'uploads/blogs/blog-balance.jpg', 1, 'Đã đăng', 72, '2026-04-03 04:00:00', '2026-04-03 04:00:00', 'Tâm lý'),
(5, 'Thương mại điện tử: ngành học đáng chú ý trong thời đại số', 'thuong-mai-dien-tu-nganh-hoc-dang-chu-y-trong-thoi-dai-so', 'Vì sao Thương mại điện tử đang trở thành lựa chọn sáng giá cho thế hệ trẻ?', '<p>Sự phát triển mạnh của bán hàng online, social commerce và nền tảng số khiến Thương mại điện tử trở thành ngành có tốc độ tăng trưởng tốt.</p><p>Người học có thể làm ở nhiều vị trí từ vận hành sàn, growth đến phân tích dữ liệu khách hàng.</p>', 'uploads/blogs/blog-ecommerce.jpg', 1, 'Đã đăng', 67, '2026-04-04 01:00:00', '2026-04-04 01:00:00', 'Xu hướng'),
(6, 'Học Dược học có khó không?', 'hoc-duoc-hoc-co-kho-khong', 'Bài viết giúp bạn hình dung khối lượng kiến thức, cơ hội nghề nghiệp và những lưu ý khi theo học Dược học.', '<p>Dược học là ngành có nền tảng khoa học sức khỏe khá nặng, yêu cầu tính cẩn thận và khả năng ghi nhớ tốt.</p><p>Bù lại, đây là nhóm ngành có tính ứng dụng cao và cơ hội việc làm ổn định tại Việt Nam.</p>', 'uploads/blogs/blog-pharmacy.jpg', 1, 'Đã đăng', 81, '2026-04-04 02:15:00', '2026-04-04 02:15:00', 'Định hướng'),
(7, 'Lộ trình xây dựng portfolio cho sinh viên Thiết kế đồ họa', 'lo-trinh-xay-dung-portfolio-cho-sinh-vien-thiet-ke-do-hoa', 'Một lộ trình thực tế để sinh viên thiết kế chuẩn bị hồ sơ năng lực ngay từ năm đầu.', '<p>Portfolio tốt không cần quá nhiều sản phẩm, nhưng phải thể hiện được tư duy, quá trình làm việc và khả năng giải quyết bài toán thiết kế.</p><p>Hãy bắt đầu từ các dự án nhỏ, cập nhật đều và học cách trình bày tác phẩm rõ ràng.</p>', 'uploads/blogs/blog-design.jpg', 1, 'Đã đăng', 59, '2026-04-04 03:00:00', '2026-04-04 03:00:00', 'Kỹ năng'),
(8, 'Ngành nào phù hợp với nhóm RIASEC Investigative?', 'nganh-nao-phu-hop-voi-nhom-riasec-investigative', 'Gợi ý một số hướng học tập cho người thích nghiên cứu, phân tích và tìm hiểu chuyên sâu.', '<p>Nhóm I thường phù hợp với các ngành như Khoa học dữ liệu, Trí tuệ nhân tạo, Y khoa, Công nghệ sinh học hoặc Dược học.</p><p>Nếu bạn thuộc nhóm này, hãy ưu tiên môi trường học tập đòi hỏi phân tích, thử nghiệm và đào sâu kiến thức.</p>', 'uploads/blogs/blog-riasec-i.jpg', 1, 'Đã đăng', 76, '2026-04-05 01:30:00', '2026-04-05 01:30:00', 'Định hướng'),
(9, '3 hiểu lầm phổ biến về ngành Luật', '3-hieu-lam-pho-bien-ve-nganh-luat', 'Nhiều bạn nghĩ học Luật chỉ để làm luật sư, nhưng thực tế cơ hội rộng hơn nhiều.', '<p>Ngành Luật còn mở ra các vị trí pháp chế doanh nghiệp, tư vấn tuân thủ, hành chính và hỗ trợ quản trị rủi ro.</p><p>Điều quan trọng là người học cần rèn kỹ năng lập luận, đọc hiểu và viết chặt chẽ.</p>', 'uploads/blogs/blog-law.jpg', 1, 'Đã đăng', 53, '2026-04-05 02:00:00', '2026-04-05 02:00:00', 'Định hướng'),
(10, 'Chuẩn bị hồ sơ du học cần bắt đầu từ đâu?', 'chuan-bi-ho-so-du-hoc-can-bat-dau-tu-dau', 'Các bước căn bản giúp học sinh chuẩn bị lộ trình du học bài bản hơn.', '<p>Du học cần được chuẩn bị từ sớm ở ba nhóm chính: ngoại ngữ, thành tích học tập và định hướng ngành học.</p><p>Bạn nên bắt đầu bằng việc xác định mục tiêu quốc gia, ngành học, học phí và tiêu chí xét tuyển.</p>', 'uploads/blogs/blog-study-abroad.jpg', 1, 'Bản nháp', 15, '2026-04-05 03:20:00', '2026-04-05 03:20:00', 'Du học');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `position` varchar(150) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `images`
--

INSERT INTO `images` (`id`, `page`, `position`, `url`, `description`, `created_at`) VALUES
(1, 'Ngành học', 'Công nghệ thông tin', 'uploads/images/majors/1776262328_69df9cb8a0f66.jpg', 'Ảnh minh họa ngành Công nghệ thông tin', '2026-04-02 05:01:00'),
(2, 'Ngành học', 'Marketing', 'uploads/images/majors/1776262568_69df9da8d0b7d.jpg', 'Ảnh minh họa ngành Marketing', '2026-04-02 05:02:00'),
(3, 'Ngành học', 'Y khoa', 'uploads/images/majors/1776262391_69df9cf71fe7d.webp', 'Ảnh minh họa ngành Y khoa', '2026-04-02 05:03:00'),
(4, 'Ngành học', 'Quản trị kinh doanh', 'uploads/images/majors/1776262489_69df9d5953dc2.webp', 'Ảnh minh họa ngành Quản trị kinh doanh', '2026-04-02 05:04:00'),
(5, 'Ngành học', 'Kế toán', 'uploads/images/majors/1776262587_69df9dbb73e1b.jpg', 'Ảnh minh họa ngành Kế toán', '2026-04-02 05:05:00'),
(6, 'Ngành học', 'Tài chính - Ngân hàng', 'uploads/images/majors/1776262605_69df9dcd8744b.jpg', 'Ảnh minh họa ngành Tài chính - Ngân hàng', '2026-04-02 05:06:00'),
(7, 'Ngành học', 'Thương mại điện tử', 'uploads/images/majors/1776262622_69df9dde61cbc.jpg', 'Ảnh minh họa ngành Thương mại điện tử', '2026-04-02 05:07:00'),
(8, 'Ngành học', 'Logistics và Quản lý chuỗi cung ứng', 'uploads/images/majors/1776262529_69df9d810dea8.jpg', 'Ảnh minh họa ngành Logistics và Quản lý chuỗi cung ứng', '2026-04-02 05:08:00'),
(9, 'Ngành học', 'Kỹ thuật phần mềm', 'uploads/images/majors/1776262662_69df9e0673db1.jpg', 'Ảnh minh họa ngành Kỹ thuật phần mềm', '2026-04-02 05:09:00'),
(10, 'Ngành học', 'Trí tuệ nhân tạo', 'uploads/images/majors/1776262409_69df9d0949fdc.jpg', 'Ảnh minh họa ngành Trí tuệ nhân tạo', '2026-04-02 05:10:00'),
(11, 'Ngành học', 'Khoa học dữ liệu', 'uploads/images/majors/1776262637_69df9dedd7c6c.jpg', 'Ảnh minh họa ngành Khoa học dữ liệu', '2026-04-02 05:11:00'),
(12, 'Ngành học', 'Thiết kế đồ họa', 'uploads/images/majors/1776262437_69df9d255667c.jpg', 'Ảnh minh họa ngành Thiết kế đồ họa', '2026-04-02 05:12:00'),
(13, 'Ngành học', 'Ngôn ngữ Anh', 'uploads/images/majors/1776262544_69df9d90595f8.jpg', 'Ảnh minh họa ngành Ngôn ngữ Anh', '2026-04-02 05:13:00'),
(14, 'Ngành học', 'Luật', 'uploads/images/majors/1776261589_69df99d50e48a.jpg', 'Ảnh minh họa ngành Luật', '2026-04-02 05:14:00'),
(15, 'Ngành học', 'Dược học', 'uploads/images/majors/1776262372_69df9ce4884e7.jpg', 'Ảnh minh họa ngành Dược học', '2026-04-02 05:15:00'),
(17, 'Người dùng', '2', 'uploads/images/users/1776262247_69df9c675a672.jpg', 'Ảnh đại diện của Trần Hải Đăng', '2026-04-02 06:17:00'),
(18, 'Người dùng', '3', 'uploads/images/users/1776262232_69df9c58ef443.jpg', 'Ảnh đại diện của Lê Ngọc Mai', '2026-04-02 06:18:00'),
(19, 'Người dùng', '4', 'uploads/images/users/1776262212_69df9c447bf45.jpg', 'Ảnh đại diện của Phạm Khánh Linh', '2026-04-02 06:19:00'),
(20, 'Người dùng', '5', 'uploads/images/users/1776262197_69df9c35dd82c.jpg', 'Ảnh đại diện của Võ Gia Huy', '2026-04-02 06:20:00'),
(21, 'Người dùng', '6', 'uploads/images/users/1776262105_69df9bd9683da.jpg', 'Ảnh đại diện của Đỗ Thu Trang', '2026-04-02 06:21:00'),
(22, 'Người dùng', '7', 'uploads/images/users/1776262080_69df9bc057974.jpg', 'Ảnh đại diện của Trần Minh Tiệp', '2026-04-02 06:22:00'),
(23, 'Người dùng', '8', 'uploads/images/users/1776262063_69df9baf87443.jpg', 'Ảnh đại diện của Nguyễn Thảo Vy', '2026-04-02 06:23:00'),
(41, 'Người dùng', '6', 'uploads/images/users/1776155229_69ddfa5d8e0ed.jpg', 'Ảnh đại diện của Đỗ Thu Trang', '2026-04-14 08:27:09'),
(42, 'Người dùng', '1', 'uploads/images/users/1776267744_69dfb1e05da7b.jpeg', '', '2026-04-15 15:15:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `majordetails`
--

CREATE TABLE `majordetails` (
  `DetailID` int(11) NOT NULL,
  `CareerID` int(11) NOT NULL,
  `Overview` longtext DEFAULT NULL,
  `Skills` longtext DEFAULT NULL,
  `Roadmap` longtext DEFAULT NULL,
  `Opportunities` longtext DEFAULT NULL,
  `SalaryDetail` longtext DEFAULT NULL,
  `SuitableFor` longtext DEFAULT NULL,
  `Subjects` longtext DEFAULT NULL,
  `Universities` longtext DEFAULT NULL,
  `DegreeOptions` longtext DEFAULT NULL,
  `Certifications` longtext DEFAULT NULL,
  `Tools` longtext DEFAULT NULL,
  `Pros` longtext DEFAULT NULL,
  `Cons` longtext DEFAULT NULL,
  `Trends` longtext DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `majordetails`
--

INSERT INTO `majordetails` (`DetailID`, `CareerID`, `Overview`, `Skills`, `Roadmap`, `Opportunities`, `SalaryDetail`, `SuitableFor`, `Subjects`, `Universities`, `DegreeOptions`, `Certifications`, `Tools`, `Pros`, `Cons`, `Trends`, `CreatedAt`, `UpdatedAt`) VALUES
(1, 1, 'Công nghệ thông tin là nhóm ngành đào tạo về phần mềm, dữ liệu, hệ thống và hạ tầng số. Đây là lĩnh vực giữ vai trò trung tâm trong quá trình chuyển đổi số của doanh nghiệp, giáo dục, y tế và dịch vụ tại Việt Nam.', 'Tư duy logic\nGiải quyết vấn đề\nLập trình cơ bản\nĐọc hiểu tài liệu tiếng Anh\nLàm việc nhóm\nTự học công nghệ mới', 'Năm đầu làm quen tư duy lập trình và cơ sở dữ liệu. Từ năm hai có thể chọn hướng web, mobile, backend, an ninh mạng hoặc dữ liệu. Giai đoạn cuối nên xây dựng portfolio, tham gia thực tập và hoàn thiện kỹ năng nghề nghiệp.', 'Lập trình viên web\nLập trình viên mobile\nKỹ sư phần mềm\nChuyên viên QA/QC\nKỹ sư dữ liệu\nQuản trị hệ thống', 'Thực tập sinh hoặc fresher thường bắt đầu từ mức thu nhập cơ bản. Sau 2 đến 3 năm kinh nghiệm, mức lương tăng mạnh nếu có kỹ năng tốt, khả năng đọc hiểu tiếng Anh và kinh nghiệm làm dự án thực tế.', 'Phù hợp với người thích công nghệ, có tư duy logic, kiên nhẫn, thích giải quyết vấn đề và sẵn sàng học liên tục.', 'Toán rời rạc\nCấu trúc dữ liệu và giải thuật\nLập trình hướng đối tượng\nCơ sở dữ liệu\nMạng máy tính\nPhát triển web', 'Đại học Bách khoa Hà Nội\nĐại học Công nghệ - ĐHQGHN\nHọc viện Công nghệ Bưu chính Viễn thông\nĐại học FPT\nĐại học Công nghệ Thông tin - ĐHQG TP.HCM', 'Cử nhân Công nghệ thông tin\nKỹ sư Công nghệ thông tin\nCác chương trình chất lượng cao hoặc liên kết quốc tế', 'IELTS hoặc TOEIC\nAWS Cloud Practitioner\nGoogle Data Analytics cơ bản\nChứng chỉ lập trình theo định hướng chuyên sâu', 'Visual Studio Code\nGit và GitHub\nMySQL hoặc PostgreSQL\nDocker\nFigma cơ bản', 'Cơ hội việc làm rộng\nKhả năng làm việc từ xa tốt\nThu nhập cạnh tranh\nDễ phát triển quốc tế', 'Áp lực cập nhật công nghệ liên tục\nCần kiên trì cao\nCạnh tranh kỹ năng thực tế lớn', 'AI ứng dụng, điện toán đám mây, an toàn thông tin và dữ liệu đang là các hướng tăng trưởng nhanh.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(2, 2, 'Marketing đào tạo người học về cách nghiên cứu thị trường, xây dựng thương hiệu, triển khai truyền thông và tạo tăng trưởng kinh doanh trong môi trường số lẫn truyền thống.', 'Giao tiếp\nViết nội dung\nPhân tích thị trường\nTư duy sáng tạo\nLàm việc nhóm\nĐọc số liệu chiến dịch', 'Giai đoạn đầu học nền tảng kinh tế, hành vi khách hàng và truyền thông. Sau đó có thể chọn hướng digital marketing, brand marketing, content, trade marketing hoặc nghiên cứu thị trường.', 'Chuyên viên marketing\nContent creator\nChuyên viên brand\nChuyên viên digital ads\nNghiên cứu thị trường\nCRM executive', 'Mức thu nhập tăng rõ khi người học có khả năng chạy chiến dịch, đọc dữ liệu quảng cáo, phối hợp bán hàng và phát triển thương hiệu hiệu quả.', 'Phù hợp với người năng động, thích giao tiếp, nhạy xu hướng, yêu thích sáng tạo và quan tâm đến hành vi người tiêu dùng.', 'Nguyên lý marketing\nHành vi khách hàng\nNghiên cứu thị trường\nTruyền thông marketing tích hợp\nDigital marketing\nQuản trị thương hiệu', 'Đại học Kinh tế Quốc dân\nĐại học Thương mại\nĐại học Kinh tế TP.HCM\nĐại học RMIT Việt Nam\nĐại học Tài chính - Marketing', 'Cử nhân Marketing\nCử nhân Thương mại điện tử\nCác chương trình định hướng digital hoặc thương hiệu', 'Google Ads\nMeta Blueprint\nContent marketing\nSEO cơ bản', 'Google Analytics\nMeta Ads Manager\nGoogle Ads\nCanva\nExcel\nPower BI cơ bản', 'Làm việc đa dạng ngành\nPhù hợp thời đại số\nMôi trường năng động', 'Áp lực KPI\nCần cập nhật xu hướng liên tục\nCạnh tranh portfolio', 'MarTech, dữ liệu khách hàng, social commerce và nội dung ngắn tiếp tục là xu hướng nổi bật.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(3, 3, 'Y khoa đào tạo bác sĩ với nền tảng kiến thức y sinh, kỹ năng lâm sàng và đạo đức nghề nghiệp để chăm sóc, chẩn đoán và điều trị cho người bệnh.', 'Kiên trì\nTinh thần trách nhiệm\nGhi nhớ và phân tích kiến thức y học\nGiao tiếp với bệnh nhân\nKhả năng chịu áp lực\nĐạo đức nghề nghiệp', 'Thời gian đào tạo dài và nặng thực hành. Người học trải qua các giai đoạn học cơ sở, tiền lâm sàng, lâm sàng và có thể học tiếp nội trú hoặc chuyên khoa sau tốt nghiệp.', 'Bác sĩ đa khoa\nBác sĩ nội trú\nBác sĩ tại bệnh viện và phòng khám\nNghiên cứu y học\nY tế dự phòng', 'Thu nhập phụ thuộc vào môi trường làm việc, thâm niên, chuyên khoa và năng lực chuyên môn. Đây là nhóm nghề có lộ trình phát triển dài hạn và ổn định.', 'Phù hợp với người có tinh thần phục vụ cộng đồng, học lực tốt, chịu được áp lực học tập và yêu thích khoa học sức khỏe.', 'Giải phẫu\nSinh lý\nSinh hóa\nVi sinh\nDược lý\nNội khoa\nNgoại khoa\nNhi khoa', 'Đại học Y Hà Nội\nĐại học Y Dược TP.HCM\nĐại học Y Dược Huế\nĐại học Y Dược Cần Thơ', 'Bác sĩ đa khoa\nĐào tạo nội trú\nChuyên khoa I\nChuyên khoa II', 'Ngoại ngữ y khoa\nTin học y tế\nCác khóa đào tạo chuyên đề lâm sàng', 'Hồ sơ bệnh án điện tử\nThiết bị lâm sàng\nPhần mềm quản lý bệnh viện', 'Ý nghĩa xã hội lớn\nNhu cầu bền vững\nLộ trình nghề nghiệp rõ', 'Thời gian đào tạo dài\nÁp lực công việc cao\nTrách nhiệm lớn', 'Y tế số, telehealth, dữ liệu y tế và chăm sóc lấy người bệnh làm trung tâm ngày càng phát triển.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(4, 4, 'Quản trị kinh doanh là ngành đào tạo tư duy quản lý, vận hành doanh nghiệp và phát triển kinh doanh trong nhiều lĩnh vực.', 'Giao tiếp\nLãnh đạo nhóm\nLập kế hoạch\nPhân tích thị trường\nThuyết trình\nRa quyết định', 'Sinh viên học nền tảng quản trị, sau đó có thể chọn hướng nhân sự, vận hành, kinh doanh hoặc khởi nghiệp.', 'Chuyên viên kinh doanh\nQuản trị vận hành\nNhân sự\nPhát triển dự án\nKhởi nghiệp', 'Thu nhập tăng tốt nếu có năng lực quản lý, bán hàng và triển khai dự án.', 'Phù hợp với người chủ động, thích tổ chức công việc và có tinh thần lãnh đạo.', 'Quản trị học\nHành vi tổ chức\nQuản trị nhân lực\nQuản trị chiến lược\nKhởi nghiệp', 'Đại học Kinh tế Quốc dân\nĐại học Thương mại\nĐại học Kinh tế TP.HCM', 'Cử nhân Quản trị kinh doanh\nChương trình chất lượng cao', 'MOS\nIELTS hoặc TOEIC\nProject Management cơ bản', 'Excel\nPowerPoint\nCRM\nPhần mềm quản trị doanh nghiệp', 'Linh hoạt nghề nghiệp\nDễ ứng dụng nhiều lĩnh vực', 'Cạnh tranh cao\nĐòi hỏi va chạm thực tế', 'Doanh nghiệp ưu tiên người có tư duy dữ liệu, kỹ năng quản lý dự án và bán hàng hiện đại.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(5, 5, 'Kế toán là ngành đào tạo về ghi nhận, xử lý, phân tích số liệu tài chính và hỗ trợ kiểm soát hoạt động của doanh nghiệp.', 'Cẩn thận\nChính xác\nTư duy số liệu\nTuân thủ quy trình\nSử dụng Excel tốt', 'Người học bắt đầu từ nguyên lý kế toán, sau đó đi sâu vào kế toán tài chính, thuế, kiểm toán và hệ thống thông tin kế toán.', 'Kế toán viên\nKế toán tổng hợp\nKiểm toán nội bộ\nChuyên viên thuế', 'Thu nhập tăng theo kinh nghiệm, chứng chỉ nghề và khả năng xử lý báo cáo tài chính.', 'Phù hợp với người cẩn thận, thích số liệu và làm việc theo quy trình rõ ràng.', 'Nguyên lý kế toán\nKế toán tài chính\nThuế\nKiểm toán\nHệ thống thông tin kế toán', 'Học viện Tài chính\nĐại học Kinh tế Quốc dân\nĐại học Kinh tế TP.HCM', 'Cử nhân Kế toán', 'ACCA nền tảng\nChứng chỉ khai báo thuế\nTin học văn phòng', 'Excel\nMISA\nFast Accounting\nERP', 'Nghề ổn định\nNhu cầu rộng ở mọi doanh nghiệp', 'Mùa quyết toán áp lực cao\nYêu cầu chính xác lớn', 'Kế toán số, ERP và phân tích tài chính đang được chú trọng.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(6, 6, 'Tài chính - Ngân hàng đào tạo kiến thức về tiền tệ, đầu tư, tín dụng, ngân hàng và quản trị vốn.', 'Phân tích\nTư duy số liệu\nGiao tiếp khách hàng\nĐánh giá rủi ro\nĐọc báo cáo', 'Sinh viên học nền tảng kinh tế, tài chính doanh nghiệp, ngân hàng thương mại và đầu tư; sau đó có thể chọn hướng ngân hàng, đầu tư hoặc phân tích tài chính.', 'Chuyên viên tín dụng\nGiao dịch viên\nPhân tích tài chính\nQuan hệ khách hàng\nTư vấn đầu tư', 'Mức thu nhập phụ thuộc vào vị trí, hiệu suất công việc và chuyên môn phân tích.', 'Phù hợp với người thích tài chính, có khả năng phân tích và giao tiếp tốt.', 'Tài chính doanh nghiệp\nNgân hàng thương mại\nThị trường chứng khoán\nQuản trị rủi ro', 'Học viện Ngân hàng\nĐại học Kinh tế Quốc dân\nĐại học Ngân hàng TP.HCM', 'Cử nhân Tài chính - Ngân hàng', 'CFA nền tảng\nIC3 hoặc MOS\nIELTS hoặc TOEIC', 'Excel\nPower BI\nPhần mềm ngân hàng lõi', 'Môi trường chuyên nghiệp\nLộ trình rõ', 'Áp lực doanh số ở một số vị trí', 'Fintech, dữ liệu tài chính và ngân hàng số tăng trưởng mạnh.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(7, 7, 'Thương mại điện tử đào tạo về bán hàng trực tuyến, nền tảng số, dữ liệu khách hàng và vận hành kinh doanh online.', 'Tư duy kinh doanh\nĐọc dữ liệu\nSử dụng nền tảng số\nPhối hợp marketing và vận hành', 'Người học đi từ nền tảng kinh doanh số đến tối ưu gian hàng, quảng cáo hiệu suất, logistics và chăm sóc khách hàng trực tuyến.', 'Vận hành sàn TMĐT\nDigital commerce executive\nChuyên viên growth\nQuản lý gian hàng', 'Thu nhập tốt khi có khả năng tăng doanh thu, tối ưu chuyển đổi và vận hành hiệu quả.', 'Phù hợp với người thích môi trường nhanh, yêu công nghệ và kinh doanh.', 'Thương mại điện tử\nDigital marketing\nPhân tích dữ liệu khách hàng\nLogistics cho TMĐT', 'Đại học Thương mại\nĐại học Kinh tế TP.HCM\nĐại học FPT', 'Cử nhân Thương mại điện tử', 'Google Analytics\nMeta Ads\nSàn thương mại điện tử nội địa', 'Shopee Seller Center\nTikTok Shop Seller Center\nExcel\nGoogle Data Studio', 'Ngành xu hướng\nNhiều cơ hội tại doanh nghiệp số', 'Cập nhật nền tảng liên tục\nÁp lực doanh số', 'Social commerce, livestream commerce và CRM tự động hóa là hướng nổi bật.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(8, 8, 'Logistics và Quản lý chuỗi cung ứng đào tạo cách tối ưu dòng hàng hóa, thông tin và dòng tiền trong doanh nghiệp.', 'Tổ chức\nPhân tích quy trình\nĐiều phối\nGiao tiếp liên phòng ban\nNgoại ngữ', 'Lộ trình học gồm nền tảng logistics, vận tải, kho vận, mua hàng và tối ưu chuỗi cung ứng.', 'Điều phối vận tải\nNhân viên xuất nhập khẩu\nPlanner\nProcurement\nWarehouse supervisor', 'Thu nhập tăng theo kinh nghiệm điều phối và khả năng vận hành chuỗi hiệu quả.', 'Phù hợp với người thích tổ chức, làm việc quy trình và phối hợp nhiều bộ phận.', 'Logistics\nQuản trị chuỗi cung ứng\nXuất nhập khẩu\nVận tải quốc tế', 'Đại học Giao thông Vận tải\nĐại học Kinh tế Quốc dân\nĐại học Quốc tế - ĐHQG TP.HCM', 'Cử nhân Logistics và Quản lý chuỗi cung ứng', 'Chứng chỉ nghiệp vụ xuất nhập khẩu\nNgoại ngữ thương mại', 'Excel\nERP\nWMS\nTMS', 'Nhu cầu tuyển dụng tốt\nỨng dụng thực tế cao', 'Áp lực tiến độ và phối hợp vận hành', 'Chuỗi cung ứng xanh, dữ liệu tồn kho và tự động hóa kho vận phát triển nhanh.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(9, 9, 'Kỹ thuật phần mềm là ngành chuyên sâu về quy trình phát triển phần mềm từ phân tích yêu cầu đến triển khai và bảo trì sản phẩm.', 'Lập trình\nThiết kế hệ thống\nKiểm thử\nLàm việc nhóm\nQuản lý phiên bản', 'Người học đi từ nền tảng lập trình đến kiến trúc phần mềm, quy trình Agile và phát triển sản phẩm thực tế.', 'Software engineer\nBackend developer\nFrontend developer\nTester\nProduct engineer', 'Thu nhập cạnh tranh cao ở thị trường công nghệ khi có kinh nghiệm dự án thực tế.', 'Phù hợp với người thích xây dựng sản phẩm số và làm việc logic.', 'Lập trình nâng cao\nKiến trúc phần mềm\nCông nghệ web\nKiểm thử phần mềm', 'Đại học Bách khoa Hà Nội\nĐại học Công nghệ Thông tin - ĐHQG TP.HCM\nĐại học FPT', 'Cử nhân hoặc Kỹ sư Kỹ thuật phần mềm', 'IELTS hoặc TOEIC\nAWS hoặc Azure cơ bản', 'Java\nC#\nGit\nJira\nDocker', 'Cơ hội tốt\nLàm sản phẩm thực tế rõ ràng', 'Yêu cầu học liên tục\nDễ áp lực deadline', 'Cloud-native, DevOps và AI hỗ trợ lập trình là xu hướng mạnh.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(10, 10, 'Trí tuệ nhân tạo là ngành đào tạo về các mô hình học máy, học sâu và xây dựng hệ thống có khả năng tự động ra quyết định hoặc hỗ trợ con người.', 'Toán nền tảng\nLập trình Python\nTư duy nghiên cứu\nXử lý dữ liệu\nKiên trì thử nghiệm', 'Lộ trình học bắt đầu từ toán, xác suất, lập trình và dữ liệu; sau đó đi sâu vào machine learning, deep learning, NLP hoặc computer vision.', 'AI engineer\nMachine learning engineer\nData scientist\nAI product specialist', 'Thu nhập thường cao ở nhóm vị trí có chuyên môn tốt về mô hình, dữ liệu và triển khai thực tế.', 'Phù hợp với người thích nghiên cứu, logic và công nghệ mới.', 'Xác suất thống kê\nPython\nMachine learning\nDeep learning\nNLP', 'Đại học Bách khoa Hà Nội\nĐại học Công nghệ - ĐHQGHN\nĐại học Công nghệ Thông tin - ĐHQG TP.HCM', 'Cử nhân Trí tuệ nhân tạo\nKỹ sư AI', 'Python for Data Science\nTensorFlow hoặc PyTorch cơ bản\nIELTS', 'Python\nJupyter Notebook\nTensorFlow\nPyTorch\nSQL', 'Ngành công nghệ mũi nhọn\nNhiều tiềm năng quốc tế', 'Yêu cầu nền tảng toán và dữ liệu khá tốt', 'AI ứng dụng vào giáo dục, y tế, tài chính và trợ lý ảo đang bùng nổ.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(11, 11, 'Khoa học dữ liệu đào tạo cách biến dữ liệu thành thông tin phục vụ ra quyết định kinh doanh và vận hành.', 'Thống kê\nSQL\nPhân tích dữ liệu\nTrực quan hóa\nTư duy đặt câu hỏi', 'Người học đi từ làm sạch dữ liệu, truy vấn, phân tích, trực quan hóa đến mô hình dự đoán và kể chuyện bằng dữ liệu.', 'Data analyst\nBusiness analyst\nBI developer\nData scientist junior', 'Thu nhập tăng tốt nếu kết hợp được kỹ năng dữ liệu với hiểu biết nghiệp vụ.', 'Phù hợp với người thích phân tích, đặt giả thuyết và làm việc với số liệu.', 'SQL\nThống kê\nPower BI\nPython phân tích dữ liệu\nData storytelling', 'Đại học Kinh tế Quốc dân\nĐại học Công nghệ Thông tin - ĐHQG TP.HCM\nĐại học FPT', 'Cử nhân Khoa học dữ liệu', 'Google Data Analytics\nPower BI\nSQL nâng cao', 'SQL\nPower BI\nPython\nExcel', 'Nhu cầu tăng nhanh\nỨng dụng đa ngành', 'Cần nền tảng phân tích vững và kỹ năng truyền đạt kết quả', 'Data governance, BI tự phục vụ và AI analytics ngày càng phổ biến.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(12, 12, 'Thiết kế đồ họa đào tạo về ngôn ngữ hình ảnh, bố cục, màu sắc và thiết kế phục vụ truyền thông, thương hiệu và sản phẩm số.', 'Cảm quan thẩm mỹ\nSáng tạo\nKể chuyện bằng hình ảnh\nKỷ luật deadline', 'Sinh viên học nền tảng mỹ thuật, bố cục, typography, rồi đi sâu vào branding, digital design hoặc motion design.', 'Graphic designer\nBrand designer\nUI visual designer\nFreelancer', 'Thu nhập phụ thuộc vào chất lượng portfolio, kinh nghiệm dự án và khả năng làm việc với khách hàng.', 'Phù hợp với người yêu cái đẹp, thích sáng tạo và thể hiện ý tưởng trực quan.', 'Bố cục\nMàu sắc\nTypography\nThiết kế thương hiệu\nThiết kế số', 'Đại học Mỹ thuật Công nghiệp\nĐại học Kiến trúc TP.HCM\nĐại học Văn Lang', 'Cử nhân Thiết kế đồ họa', 'Adobe Certified Professional\nIELTS hoặc TOEIC', 'Photoshop\nIllustrator\nFigma\nAfter Effects', 'Dễ freelance\nPhù hợp nhiều lĩnh vực sáng tạo', 'Cạnh tranh portfolio cao\nÁp lực deadline', 'Thiết kế cho nền tảng số, UI và motion ngắn đang được ưa chuộng.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(13, 13, 'Ngôn ngữ Anh đào tạo năng lực sử dụng tiếng Anh chuyên sâu trong giao tiếp, biên phiên dịch, giáo dục và môi trường quốc tế.', 'Ngoại ngữ tốt\nGiao tiếp\nLắng nghe\nViết học thuật\nHiểu văn hóa', 'Người học phát triển 4 kỹ năng tiếng Anh, sau đó có thể chọn hướng biên phiên dịch, giảng dạy, truyền thông hoặc kinh doanh quốc tế.', 'Biên phiên dịch\nGiáo viên tiếng Anh\nNhân sự quốc tế\nChuyên viên đối ngoại', 'Thu nhập thay đổi theo vị trí, chứng chỉ và khả năng ứng dụng tiếng Anh trong công việc.', 'Phù hợp với người yêu ngôn ngữ, thích giao tiếp và làm việc môi trường quốc tế.', 'Ngữ âm\nNgữ pháp nâng cao\nBiên phiên dịch\nVăn hóa Anh - Mỹ\nTiếng Anh thương mại', 'Đại học Ngoại ngữ - ĐHQGHN\nĐại học Hà Nội\nĐại học Sư phạm TP.HCM', 'Cử nhân Ngôn ngữ Anh', 'IELTS\nTESOL\nMOS', 'CAT tools cơ bản\nMS Office\nNền tảng dạy học trực tuyến', 'Tính ứng dụng rộng\nDễ kết hợp ngành khác', 'Cần duy trì năng lực ngoại ngữ thường xuyên', 'Nhu cầu tiếng Anh chuyên ngành và giao tiếp quốc tế tiếp tục tăng.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(14, 14, 'Luật đào tạo kiến thức pháp lý, tư duy lập luận, kỹ năng phân tích văn bản pháp luật và giải quyết vấn đề theo khuôn khổ pháp luật.', 'Lập luận\nĐọc hiểu văn bản\nTranh biện\nTư duy phản biện\nCẩn trọng', 'Người học đi từ luật cơ sở đến các mảng dân sự, hình sự, thương mại, hành chính và thực tập kỹ năng nghề luật.', 'Chuyên viên pháp chế\nThư ký luật sư\nCán bộ tư pháp\nTư vấn pháp lý', 'Thu nhập tăng dần theo kinh nghiệm, chứng chỉ hành nghề và khả năng xử lý vụ việc.', 'Phù hợp với người thích lập luận, quan tâm công bằng và có khả năng đọc hiểu tốt.', 'Luật hiến pháp\nLuật dân sự\nLuật hình sự\nLuật thương mại\nKỹ năng tranh tụng', 'Đại học Luật Hà Nội\nĐại học Luật TP.HCM\nKhoa Luật - ĐHQGHN', 'Cử nhân Luật', 'Ngoại ngữ pháp lý\nKỹ năng tranh biện\nCác khóa thực hành pháp chế', 'Cơ sở dữ liệu pháp luật\nMS Office\nHệ thống quản lý hồ sơ', 'Nghề có vị thế xã hội\nLộ trình nghề rõ', 'Cần đọc nhiều và chịu áp lực hồ sơ', 'Pháp chế doanh nghiệp, tuân thủ và dữ liệu pháp lý số hóa là hướng phát triển tốt.', '2026-04-02 07:00:00', '2026-04-12 01:30:00'),
(15, 15, 'Dược học đào tạo kiến thức về thuốc, dược lý, bào chế, kiểm nghiệm và tư vấn sử dụng thuốc an toàn, hợp lý.', 'Cẩn thận\nGhi nhớ tốt\nTinh thần trách nhiệm\nYêu thích khoa học sức khỏe', 'Người học bắt đầu từ hóa học, sinh học và dược cơ sở, sau đó đi sâu vào dược lý, bào chế, kiểm nghiệm và dược lâm sàng.', 'Dược sĩ nhà thuốc\nDược sĩ bệnh viện\nTrình dược viên\nKiểm nghiệm dược phẩm', 'Thu nhập ổn định và có thể tăng tốt khi có chứng chỉ hành nghề và kinh nghiệm thực tế.', 'Phù hợp với người cẩn thận, yêu thích lĩnh vực y dược và thích tư vấn cho người khác.', 'Hóa dược\nDược lý\nBào chế\nKiểm nghiệm thuốc\nDược lâm sàng', 'Đại học Dược Hà Nội\nĐại học Y Dược TP.HCM\nĐại học Y Dược Cần Thơ', 'Cử nhân Dược học\nĐịnh hướng dược sĩ lâm sàng hoặc công nghiệp', 'Chứng chỉ hành nghề dược\nNgoại ngữ chuyên ngành', 'Phần mềm quản lý nhà thuốc\nCSDL thuốc\nMS Office', 'Nghề ổn định\nỨng dụng thực tế cao', 'Yêu cầu trách nhiệm và độ chính xác lớn', 'Dược lâm sàng, quản lý thuốc thông minh và công nghệ sản xuất dược hiện đại phát triển nhanh.', '2026-04-02 07:00:00', '2026-04-12 01:30:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `majors`
--

CREATE TABLE `majors` (
  `CareerID` int(11) NOT NULL,
  `CareerCode` varchar(50) NOT NULL,
  `CareerName` varchar(150) NOT NULL,
  `Category` varchar(100) DEFAULT NULL,
  `SalaryMin` decimal(12,2) NOT NULL DEFAULT 0.00,
  `SalaryMax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `Description` text DEFAULT NULL,
  `Highlight` text DEFAULT NULL,
  `DemandLevel` varchar(50) DEFAULT NULL,
  `Rating` decimal(3,1) NOT NULL DEFAULT 0.0,
  `RatingCount` int(11) NOT NULL DEFAULT 0,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `Views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `majors`
--

INSERT INTO `majors` (`CareerID`, `CareerCode`, `CareerName`, `Category`, `SalaryMin`, `SalaryMax`, `Description`, `Highlight`, `DemandLevel`, `Rating`, `RatingCount`, `CreatedAt`, `Views`) VALUES
(1, '7480201', 'Công nghệ thông tin', 'Kỹ thuật & Công nghệ', 10000000.00, 35000000.00, 'Ngành học về lập trình, hệ thống thông tin, dữ liệu và giải pháp công nghệ phục vụ chuyển đổi số.', 'Chuyển đổi số', 'Rất cao', 4.8, 42, '2026-04-01 02:00:00', 1250),
(2, '7340115', 'Marketing', 'Kinh tế & Quản lý', 8000000.00, 25000000.00, 'Ngành học về nghiên cứu thị trường, thương hiệu, truyền thông, hành vi khách hàng và marketing số.', 'Sáng tạo và thị trường', 'Cao', 4.6, 31, '2026-04-01 02:10:00', 980),
(3, '7720101', 'Y khoa', 'Y dược & Sức khỏe', 12000000.00, 40000000.00, 'Ngành đào tạo bác sĩ với nền tảng khoa học sức khỏe, chẩn đoán và điều trị bệnh.', 'Nhân văn và chuyên môn cao', 'Rất cao', 4.9, 28, '2026-04-01 02:20:00', 1320),
(4, '7340101', 'Quản trị kinh doanh', 'Kinh tế & Quản lý', 9000000.00, 28000000.00, 'Ngành học về chiến lược kinh doanh, vận hành, nhân sự, tài chính cơ bản và phát triển doanh nghiệp.', 'Tư duy lãnh đạo', 'Cao', 4.5, 26, '2026-04-01 02:30:00', 860),
(5, '7340301', 'Kế toán', 'Kinh tế & Quản lý', 8000000.00, 22000000.00, 'Ngành học về ghi nhận, xử lý, phân tích và kiểm soát thông tin tài chính trong tổ chức.', 'Chính xác và hệ thống', 'Cao', 4.4, 22, '2026-04-01 02:40:00', 640),
(6, '7340201', 'Tài chính - Ngân hàng', 'Kinh tế & Quản lý', 9000000.00, 30000000.00, 'Ngành học về ngân hàng, đầu tư, quản trị vốn, thị trường tài chính và phân tích tài chính.', 'Phân tích tài chính', 'Cao', 4.5, 24, '2026-04-01 02:50:00', 710),
(7, '7340122', 'Thương mại điện tử', 'Kinh tế & Quản lý', 10000000.00, 30000000.00, 'Ngành học về kinh doanh trực tuyến, vận hành sàn thương mại điện tử, marketing hiệu suất và dữ liệu khách hàng.', 'Kinh doanh số', 'Rất cao', 4.7, 19, '2026-04-01 03:00:00', 760),
(8, '7510605', 'Logistics và Quản lý chuỗi cung ứng', 'Kinh tế & Quản lý', 9000000.00, 28000000.00, 'Ngành học về vận tải, kho bãi, mua hàng, điều phối hàng hóa và tối ưu chuỗi cung ứng.', 'Nhu cầu tuyển dụng ổn định', 'Cao', 4.5, 18, '2026-04-01 03:10:00', 690),
(9, '7480103', 'Kỹ thuật phần mềm', 'Kỹ thuật & Công nghệ', 12000000.00, 40000000.00, 'Ngành học chuyên sâu về phân tích, thiết kế, phát triển, kiểm thử và bảo trì phần mềm.', 'Lập trình và sản phẩm số', 'Rất cao', 4.8, 25, '2026-04-01 03:20:00', 930),
(10, '7480107', 'Trí tuệ nhân tạo', 'Kỹ thuật & Công nghệ', 15000000.00, 45000000.00, 'Ngành học về học máy, thị giác máy tính, xử lý ngôn ngữ tự nhiên và ứng dụng AI.', 'Công nghệ mũi nhọn', 'Rất cao', 4.9, 17, '2026-04-01 03:30:00', 1020),
(11, '7460108', 'Khoa học dữ liệu', 'Kỹ thuật & Công nghệ', 14000000.00, 42000000.00, 'Ngành học về thu thập, làm sạch, phân tích, trực quan hóa dữ liệu và mô hình dự đoán.', 'Phân tích dữ liệu lớn', 'Rất cao', 4.8, 21, '2026-04-01 03:40:00', 880),
(12, '7210403', 'Thiết kế đồ họa', 'Nghệ thuật & Thiết kế', 8000000.00, 25000000.00, 'Ngành học về thiết kế truyền thông, nhận diện thương hiệu, minh họa và sản phẩm số.', 'Sáng tạo thị giác', 'Cao', 4.6, 20, '2026-04-01 03:50:00', 790),
(13, '7220201', 'Ngôn ngữ Anh', 'Ngôn ngữ & Xã hội', 8000000.00, 22000000.00, 'Ngành học về ngôn ngữ, biên phiên dịch, giao tiếp liên văn hóa và ứng dụng tiếng Anh trong nghề nghiệp.', 'Ứng dụng đa lĩnh vực', 'Cao', 4.5, 23, '2026-04-01 04:00:00', 720),
(14, '7380101', 'Luật', 'Luật & Xã hội', 9000000.00, 25000000.00, 'Ngành học về pháp luật dân sự, hình sự, hành chính, kinh doanh và kỹ năng hành nghề pháp lý.', 'Tư duy lập luận', 'Cao', 4.4, 16, '2026-04-01 04:10:00', 611),
(15, '7720201', 'Dược học', 'Y dược & Sức khỏe', 12000000.00, 35000000.00, 'Ngành học về thuốc, dược lý, bào chế, kiểm nghiệm và tư vấn sử dụng thuốc an toàn.', 'Chuyên môn y dược', 'Rất cao', 4.7, 18, '2026-04-01 04:20:00', 950);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `major_riasec`
--

CREATE TABLE `major_riasec` (
  `MajorRiasecID` int(11) NOT NULL,
  `CareerID` int(11) NOT NULL,
  `RiasecCode` enum('R','I','A','S','E','C') NOT NULL,
  `Weight` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `major_riasec`
--

INSERT INTO `major_riasec` (`MajorRiasecID`, `CareerID`, `RiasecCode`, `Weight`) VALUES
(1, 1, 'R', 6),
(2, 1, 'I', 10),
(3, 1, 'A', 3),
(4, 1, 'S', 2),
(5, 1, 'E', 3),
(6, 1, 'C', 6),
(7, 2, 'R', 2),
(8, 2, 'I', 4),
(9, 2, 'A', 8),
(10, 2, 'S', 6),
(11, 2, 'E', 9),
(12, 2, 'C', 5),
(13, 3, 'R', 3),
(14, 3, 'I', 9),
(15, 3, 'A', 2),
(16, 3, 'S', 10),
(17, 3, 'E', 3),
(18, 3, 'C', 7),
(19, 4, 'R', 3),
(20, 4, 'I', 5),
(21, 4, 'A', 4),
(22, 4, 'S', 6),
(23, 4, 'E', 10),
(24, 4, 'C', 7),
(25, 5, 'R', 2),
(26, 5, 'I', 6),
(27, 5, 'A', 1),
(28, 5, 'S', 3),
(29, 5, 'E', 5),
(30, 5, 'C', 10),
(31, 6, 'R', 3),
(32, 6, 'I', 7),
(33, 6, 'A', 2),
(34, 6, 'S', 4),
(35, 6, 'E', 7),
(36, 6, 'C', 9),
(37, 7, 'R', 3),
(38, 7, 'I', 6),
(39, 7, 'A', 7),
(40, 7, 'S', 5),
(41, 7, 'E', 9),
(42, 7, 'C', 6),
(43, 8, 'R', 6),
(44, 8, 'I', 5),
(45, 8, 'A', 2),
(46, 8, 'S', 4),
(47, 8, 'E', 7),
(48, 8, 'C', 8),
(49, 9, 'R', 7),
(50, 9, 'I', 10),
(51, 9, 'A', 3),
(52, 9, 'S', 2),
(53, 9, 'E', 3),
(54, 9, 'C', 6),
(55, 10, 'R', 6),
(56, 10, 'I', 10),
(57, 10, 'A', 2),
(58, 10, 'S', 2),
(59, 10, 'E', 3),
(60, 10, 'C', 5),
(61, 11, 'R', 5),
(62, 11, 'I', 10),
(63, 11, 'A', 3),
(64, 11, 'S', 2),
(65, 11, 'E', 4),
(66, 11, 'C', 7),
(67, 12, 'R', 2),
(68, 12, 'I', 4),
(69, 12, 'A', 10),
(70, 12, 'S', 5),
(71, 12, 'E', 6),
(72, 12, 'C', 3),
(73, 13, 'R', 1),
(74, 13, 'I', 4),
(75, 13, 'A', 6),
(76, 13, 'S', 9),
(77, 13, 'E', 6),
(78, 13, 'C', 5),
(79, 14, 'R', 2),
(80, 14, 'I', 7),
(81, 14, 'A', 3),
(82, 14, 'S', 7),
(83, 14, 'E', 9),
(84, 14, 'C', 8),
(85, 15, 'R', 4),
(86, 15, 'I', 9),
(87, 15, 'A', 2),
(88, 15, 'S', 6),
(89, 15, 'E', 3),
(90, 15, 'C', 8);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `questions`
--

CREATE TABLE `questions` (
  `QuestionID` int(11) NOT NULL,
  `QuestionText` text NOT NULL,
  `QuestionType` enum('single','multiple','scale') DEFAULT 'single',
  `IsActive` tinyint(1) DEFAULT 1,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `questions`
--

INSERT INTO `questions` (`QuestionID`, `QuestionText`, `QuestionType`, `IsActive`, `CreatedAt`) VALUES
(1, 'Bạn thích lắp ráp, sửa chữa hoặc thao tác với máy móc, thiết bị hơn không?', 'single', 1, '2026-04-02 01:00:00'),
(2, 'Bạn có hứng thú với việc phân tích dữ liệu, nghiên cứu và tìm nguyên nhân của vấn đề không?', 'single', 1, '2026-04-02 01:05:00'),
(3, 'Bạn thích vẽ, thiết kế hoặc tạo ra sản phẩm mang dấu ấn cá nhân không?', 'single', 1, '2026-04-02 01:10:00'),
(4, 'Bạn có thích hỗ trợ, hướng dẫn hoặc chăm sóc người khác không?', 'single', 1, '2026-04-02 01:15:00'),
(5, 'Bạn có thích thuyết phục, lãnh đạo hoặc tổ chức hoạt động không?', 'single', 1, '2026-04-02 01:20:00'),
(6, 'Bạn có thích làm việc với số liệu, hồ sơ và quy trình rõ ràng không?', 'single', 1, '2026-04-02 01:25:00'),
(7, 'Bạn thích tạo ra sản phẩm hữu hình hoặc nhìn thấy kết quả thực tế từ công việc của mình không?', 'single', 1, '2026-04-02 01:30:00'),
(8, 'Bạn thấy hứng thú khi đọc tài liệu chuyên sâu và tự mình giải bài toán khó không?', 'single', 1, '2026-04-02 01:35:00'),
(9, 'Bạn thường có nhiều ý tưởng mới và thích thể hiện chúng qua hình ảnh hoặc nội dung không?', 'single', 1, '2026-04-02 01:40:00'),
(10, 'Bạn có kiên nhẫn lắng nghe và đồng hành cùng người khác khi họ gặp khó khăn không?', 'single', 1, '2026-04-02 01:45:00'),
(11, 'Bạn có hứng thú với bán hàng, kinh doanh hoặc khởi xướng dự án mới không?', 'single', 1, '2026-04-02 01:50:00'),
(12, 'Bạn có thích sắp xếp, kiểm tra chi tiết và làm việc theo tiêu chuẩn không?', 'single', 1, '2026-04-02 01:55:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `AttemptID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `StartedAt` datetime DEFAULT current_timestamp(),
  `CompletedAt` datetime DEFAULT NULL,
  `TotalQuestions` int(11) DEFAULT 0,
  `ScoreR` int(11) DEFAULT 0,
  `ScoreI` int(11) DEFAULT 0,
  `ScoreA` int(11) DEFAULT 0,
  `ScoreS` int(11) DEFAULT 0,
  `ScoreE` int(11) DEFAULT 0,
  `ScoreC` int(11) DEFAULT 0,
  `TopCode1` char(1) DEFAULT NULL,
  `TopCode2` char(1) DEFAULT NULL,
  `TopCode3` char(1) DEFAULT NULL,
  `ResultSummary` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`AttemptID`, `UserID`, `StartedAt`, `CompletedAt`, `TotalQuestions`, `ScoreR`, `ScoreI`, `ScoreA`, `ScoreS`, `ScoreE`, `ScoreC`, `TopCode1`, `TopCode2`, `TopCode3`, `ResultSummary`) VALUES
(1, 2, '2026-04-06 19:11:00', '2026-04-06 19:13:30', 12, 4, 4, 1, 1, 2, 2, 'R', 'I', 'E', 'I-R-C'),
(2, 3, '2026-04-07 19:12:00', '2026-04-07 19:14:30', 12, 0, 2, 4, 2, 4, 1, 'A', 'E', 'I', 'E-A-S'),
(3, 4, '2026-04-08 19:13:00', '2026-04-08 19:15:30', 12, 0, 4, 1, 4, 2, 2, 'I', 'S', 'E', 'I-S-C'),
(4, 5, '2026-04-09 19:14:00', '2026-04-09 19:16:30', 12, 2, 2, 0, 2, 2, 4, 'C', 'R', 'I', 'C-I-E'),
(5, 6, '2026-04-10 19:15:00', '2026-04-10 19:17:30', 12, 1, 1, 4, 4, 2, 0, 'A', 'S', 'E', 'A-S-E'),
(6, 8, '2026-04-11 19:16:00', '2026-04-11 19:18:30', 12, 1, 2, 2, 4, 0, 2, 'S', 'I', 'A', 'S-A-I'),
(16, 7, '2026-04-15 22:53:51', '2026-04-15 22:53:51', 12, 4, 2, 0, 2, 4, 1, 'R', 'E', 'I', 'R-E-I');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_attempt_details`
--

CREATE TABLE `quiz_attempt_details` (
  `DetailID` int(11) NOT NULL,
  `AttemptID` int(11) NOT NULL,
  `QuestionID` int(11) NOT NULL,
  `AnswerID` int(11) NOT NULL,
  `RiasecCode` enum('R','I','A','S','E','C') NOT NULL,
  `ScoreEarned` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `quiz_attempt_details`
--

INSERT INTO `quiz_attempt_details` (`DetailID`, `AttemptID`, `QuestionID`, `AnswerID`, `RiasecCode`, `ScoreEarned`) VALUES
(1, 1, 1, 1, 'R', 2),
(2, 1, 2, 4, 'I', 2),
(3, 1, 3, 8, 'A', 1),
(4, 1, 4, 12, 'S', 0),
(5, 1, 5, 14, 'E', 1),
(6, 1, 6, 17, 'C', 1),
(7, 1, 7, 19, 'R', 2),
(8, 1, 8, 22, 'I', 2),
(9, 1, 9, 27, 'A', 0),
(10, 1, 10, 29, 'S', 1),
(11, 1, 11, 32, 'E', 1),
(12, 1, 12, 35, 'C', 1),
(13, 2, 1, 3, 'R', 0),
(14, 2, 2, 5, 'I', 1),
(15, 2, 3, 7, 'A', 2),
(16, 2, 4, 11, 'S', 1),
(17, 2, 5, 13, 'E', 2),
(18, 2, 6, 17, 'C', 1),
(19, 2, 7, 21, 'R', 0),
(20, 2, 8, 23, 'I', 1),
(21, 2, 9, 25, 'A', 2),
(22, 2, 10, 29, 'S', 1),
(23, 2, 11, 31, 'E', 2),
(24, 2, 12, 36, 'C', 0),
(25, 3, 1, 3, 'R', 0),
(26, 3, 2, 4, 'I', 2),
(27, 3, 3, 9, 'A', 0),
(28, 3, 4, 10, 'S', 2),
(29, 3, 5, 14, 'E', 1),
(30, 3, 6, 17, 'C', 1),
(31, 3, 7, 21, 'R', 0),
(32, 3, 8, 22, 'I', 2),
(33, 3, 9, 26, 'A', 1),
(34, 3, 10, 28, 'S', 2),
(35, 3, 11, 32, 'E', 1),
(36, 3, 12, 35, 'C', 1),
(37, 4, 1, 2, 'R', 1),
(38, 4, 2, 5, 'I', 1),
(39, 4, 3, 9, 'A', 0),
(40, 4, 4, 11, 'S', 1),
(41, 4, 5, 14, 'E', 1),
(42, 4, 6, 16, 'C', 2),
(43, 4, 7, 20, 'R', 1),
(44, 4, 8, 23, 'I', 1),
(45, 4, 9, 27, 'A', 0),
(46, 4, 10, 29, 'S', 1),
(47, 4, 11, 32, 'E', 1),
(48, 4, 12, 34, 'C', 2),
(49, 5, 1, 2, 'R', 1),
(50, 5, 2, 6, 'I', 0),
(51, 5, 3, 7, 'A', 2),
(52, 5, 4, 10, 'S', 2),
(53, 5, 5, 14, 'E', 1),
(54, 5, 6, 18, 'C', 0),
(55, 5, 7, 21, 'R', 0),
(56, 5, 8, 23, 'I', 1),
(57, 5, 9, 25, 'A', 2),
(58, 5, 10, 28, 'S', 2),
(59, 5, 11, 32, 'E', 1),
(60, 5, 12, 36, 'C', 0),
(61, 6, 1, 2, 'R', 1),
(62, 6, 2, 5, 'I', 1),
(63, 6, 3, 8, 'A', 1),
(64, 6, 4, 10, 'S', 2),
(65, 6, 5, 15, 'E', 0),
(66, 6, 6, 17, 'C', 1),
(67, 6, 7, 21, 'R', 0),
(68, 6, 8, 23, 'I', 1),
(69, 6, 9, 26, 'A', 1),
(70, 6, 10, 28, 'S', 2),
(71, 6, 11, 33, 'E', 0),
(72, 6, 12, 35, 'C', 1),
(91, 16, 1, 1, 'R', 2),
(92, 16, 2, 5, 'I', 1),
(93, 16, 3, 9, 'A', 0),
(94, 16, 4, 11, 'S', 1),
(95, 16, 5, 13, 'E', 2),
(96, 16, 6, 18, 'C', 0),
(97, 16, 7, 19, 'R', 2),
(98, 16, 8, 23, 'I', 1),
(99, 16, 9, 27, 'A', 0),
(100, 16, 10, 29, 'S', 1),
(101, 16, 11, 31, 'E', 2),
(102, 16, 12, 35, 'C', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `ReviewID` int(11) NOT NULL,
  `CareerID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Rating` int(11) NOT NULL,
  `Comment` text DEFAULT NULL,
  `Status` enum('Chờ duyệt','Đã duyệt','Từ chối') NOT NULL DEFAULT 'Chờ duyệt',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`ReviewID`, `CareerID`, `UserID`, `Rating`, `Comment`, `Status`, `CreatedAt`) VALUES
(1, 1, 2, 5, 'Ngành này mở ra rất nhiều cơ hội việc làm nếu chịu khó học và làm dự án thực tế.', 'Đã duyệt', '2026-04-06 01:00:00'),
(2, 2, 3, 4, 'Marketing phù hợp với người thích sáng tạo, nhưng cũng cần biết đọc số liệu chứ không chỉ làm nội dung.', 'Đã duyệt', '2026-04-06 02:15:00'),
(3, 3, 6, 5, 'Y khoa rất ý nghĩa nhưng thời gian học dài và áp lực lớn, cần xác định mục tiêu rõ ràng.', 'Đã duyệt', '2026-04-06 03:20:00'),
(4, 5, 7, 4, 'Kế toán ổn định, dễ xin việc ở nhiều doanh nghiệp, nhưng đòi hỏi cẩn thận và chịu áp lực quyết toán.', 'Đã duyệt', '2026-04-07 01:45:00'),
(5, 7, 4, 5, 'Thương mại điện tử rất hợp thời, học xong có thể làm vận hành sàn hoặc growth khá nhanh.', 'Đã duyệt', '2026-04-07 02:10:00'),
(6, 8, 5, 4, 'Logistics có nhiều việc tại Việt Nam, nhất là ở khu vực cảng và doanh nghiệp xuất nhập khẩu.', 'Đã duyệt', '2026-04-07 02:30:00'),
(7, 10, 2, 5, 'AI là ngành rất hấp dẫn nhưng nền tảng toán và lập trình phải chắc mới theo lâu dài được.', 'Đã duyệt', '2026-04-08 00:55:00'),
(8, 12, 8, 5, 'Thiết kế đồ họa cho mình nhiều không gian sáng tạo, nhưng muốn thu nhập tốt thì portfolio phải mạnh.', 'Đã duyệt', '2026-04-08 01:20:00'),
(9, 13, 10, 4, 'Ngôn ngữ Anh dễ kết hợp với nhiều nghề khác, nhất là truyền thông, giáo dục và nhân sự quốc tế.', 'Đã duyệt', '2026-04-08 01:45:00'),
(10, 14, 6, 4, 'Luật cần đọc rất nhiều văn bản và luyện tư duy lập luận liên tục, không hợp với người quá nóng vội.', 'Đã duyệt', '2026-04-08 02:30:00'),
(11, 15, 3, 5, 'Dược học có tính ứng dụng cao và cơ hội việc làm ổn định, nhưng khối lượng kiến thức chuyên ngành khá nặng.', 'Chờ duyệt', '2026-04-09 03:10:00'),
(12, 1, 4, 5, 'Mình học CNTT và thấy quan trọng nhất là tự học, tiếng Anh và kỹ năng teamwork khi đi thực tập.', 'Đã duyệt', '2026-04-09 03:40:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `riasec_scores`
--

CREATE TABLE `riasec_scores` (
  `ScoreID` int(11) NOT NULL,
  `AttemptID` int(11) NOT NULL,
  `RiasecCode` enum('R','I','A','S','E','C') NOT NULL,
  `TotalScore` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `riasec_scores`
--

INSERT INTO `riasec_scores` (`ScoreID`, `AttemptID`, `RiasecCode`, `TotalScore`) VALUES
(1, 1, 'R', 4),
(2, 1, 'I', 4),
(3, 1, 'A', 1),
(4, 1, 'S', 1),
(5, 1, 'E', 2),
(6, 1, 'C', 2),
(7, 2, 'R', 0),
(8, 2, 'I', 2),
(9, 2, 'A', 4),
(10, 2, 'S', 2),
(11, 2, 'E', 4),
(12, 2, 'C', 1),
(13, 3, 'R', 0),
(14, 3, 'I', 4),
(15, 3, 'A', 1),
(16, 3, 'S', 4),
(17, 3, 'E', 2),
(18, 3, 'C', 2),
(19, 4, 'R', 2),
(20, 4, 'I', 2),
(21, 4, 'A', 0),
(22, 4, 'S', 2),
(23, 4, 'E', 2),
(24, 4, 'C', 4),
(25, 5, 'R', 1),
(26, 5, 'I', 1),
(27, 5, 'A', 4),
(28, 5, 'S', 4),
(29, 5, 'E', 2),
(30, 5, 'C', 0),
(31, 6, 'R', 1),
(32, 6, 'I', 2),
(33, 6, 'A', 2),
(34, 6, 'S', 4),
(35, 6, 'E', 0),
(36, 6, 'C', 2),
(91, 16, 'R', 4),
(92, 16, 'E', 4),
(93, 16, 'I', 2),
(94, 16, 'S', 2),
(95, 16, 'C', 1),
(96, 16, 'A', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `SettingID` int(11) NOT NULL,
  `SiteName` varchar(150) NOT NULL DEFAULT 'CareerNest',
  `ContactEmail` varchar(150) DEFAULT NULL,
  `ContactPhone` varchar(30) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `LogoURL` varchar(255) DEFAULT NULL,
  `MaintenanceMode` tinyint(1) NOT NULL DEFAULT 0,
  `SiteDescription` text DEFAULT NULL,
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`SettingID`, `SiteName`, `ContactEmail`, `ContactPhone`, `Address`, `LogoURL`, `MaintenanceMode`, `SiteDescription`, `UpdatedAt`) VALUES
(1, 'CareerNest', 'Careernest@gmail.com', '+84 (098) 6611 205', '123, xã Can Lộc, tỉnh Hà Tĩnh', 'uploads/logo/logo_1776265170_69dfa7d291ea6.png', 0, 'Nền tảng hướng nghiệp thông minh dành cho học sinh – sinh viên Việt Nam.', '2026-04-15 15:51:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `FullName` varchar(150) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` varchar(50) NOT NULL DEFAULT 'User',
  `Active` enum('online','offline') NOT NULL DEFAULT 'offline',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`UserID`, `FullName`, `Email`, `PasswordHash`, `Role`, `Active`, `CreatedAt`) VALUES
(1, 'Trần Minh Hoàng', 'minhhoang11062005@gmail.com', '$2y$10$lPTo5L0Ea8mBxercJLlPX.XvwGbZR7RCpF0DVkhR/st20MLT5GwSS', 'Admin', 'offline', '2026-04-01 01:00:00'),
(2, 'Trần Hải Đăng', 'student1@careernest.vn', '$2y$12$YhEUBp8mriMW4gWGeIIRP.uJ30B1gi0yztJaIgfnWwrQ7YVdvDGb.', 'Student', 'offline', '2026-04-02 01:15:00'),
(3, 'Lê Ngọc Mai', 'student2@careernest.vn', '$2y$12$YhEUBp8mriMW4gWGeIIRP.uJ30B1gi0yztJaIgfnWwrQ7YVdvDGb.', 'Student', 'offline', '2026-04-02 02:20:00'),
(4, 'Phạm Khánh Linh', 'student3@careernest.vn', '$2y$12$YhEUBp8mriMW4gWGeIIRP.uJ30B1gi0yztJaIgfnWwrQ7YVdvDGb.', 'Student', 'offline', '2026-04-03 03:10:00'),
(5, 'Võ Gia Huy', 'student4@careernest.vn', '$2y$12$YhEUBp8mriMW4gWGeIIRP.uJ30B1gi0yztJaIgfnWwrQ7YVdvDGb.', 'Student', 'offline', '2026-04-03 07:30:00'),
(6, 'Đỗ Thu Trang', 'user1@careernest.vn', '$2y$12$lceL1QZzV6G1Q/F/EWfTEO0MkJWMS/I1daM2E74GGqEodvK1Bdqcq', 'User', 'offline', '2026-04-04 02:40:00'),
(7, 'Trần Minh Tiệp', 'tiep123@gmail.com', '$2y$10$5HYI0tIEcP2bWsTptG5cVuS/nYsH8VGqTWVp5W3g0re01atS7uuCu', 'Student', 'online', '2026-04-05 04:00:00'),
(8, 'Nguyễn Thảo Vy', 'user3@careernest.vn', '$2y$12$lceL1QZzV6G1Q/F/EWfTEO0MkJWMS/I1daM2E74GGqEodvK1Bdqcq', 'User', 'offline', '2026-04-06 01:30:00'),
(9, 'Nguyễn Thị Anh', 'anh123@gmail.com', '$2y$10$kvUqX68N12eHKNlTEZzmA.hURLfGS7Y74yV.2t.StQRufdNxAdJOS', 'Employer', 'offline', '2026-04-06 03:45:00'),
(10, 'Phan Hoàng Yến', 'user4@careernest.vn', '$2y$12$lceL1QZzV6G1Q/F/EWfTEO0MkJWMS/I1daM2E74GGqEodvK1Bdqcq', 'User', 'offline', '2026-04-07 06:10:00');

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `vw_reviews_full`
-- (See below for the actual view)
--
CREATE TABLE `vw_reviews_full` (
`ReviewID` int(11)
,`CareerID` int(11)
,`UserID` int(11)
,`Rating` int(11)
,`Comment` text
,`Status` enum('Chờ duyệt','Đã duyệt','Từ chối')
,`CreatedAt` timestamp
,`FullName` varchar(150)
,`Email` varchar(150)
,`CareerName` varchar(150)
);

-- --------------------------------------------------------

--
-- Cấu trúc cho view `vw_reviews_full`
--
DROP TABLE IF EXISTS `vw_reviews_full`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_reviews_full`  AS SELECT `r`.`ReviewID` AS `ReviewID`, `r`.`CareerID` AS `CareerID`, `r`.`UserID` AS `UserID`, `r`.`Rating` AS `Rating`, `r`.`Comment` AS `Comment`, `r`.`Status` AS `Status`, `r`.`CreatedAt` AS `CreatedAt`, `u`.`FullName` AS `FullName`, `u`.`Email` AS `Email`, `m`.`CareerName` AS `CareerName` FROM ((`reviews` `r` left join `users` `u` on(`r`.`UserID` = `u`.`UserID`)) left join `majors` `m` on(`r`.`CareerID` = `m`.`CareerID`)) ;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`AnswerID`),
  ADD KEY `QuestionID` (`QuestionID`);

--
-- Chỉ mục cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`BlogID`),
  ADD UNIQUE KEY `Slug` (`Slug`),
  ADD KEY `fk_blogs_author` (`AuthorID`);

--
-- Chỉ mục cho bảng `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_page_position` (`page`,`position`);

--
-- Chỉ mục cho bảng `majordetails`
--
ALTER TABLE `majordetails`
  ADD PRIMARY KEY (`DetailID`),
  ADD UNIQUE KEY `uq_major_details_career` (`CareerID`);

--
-- Chỉ mục cho bảng `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`CareerID`),
  ADD UNIQUE KEY `CareerCode` (`CareerCode`),
  ADD UNIQUE KEY `CareerName` (`CareerName`);

--
-- Chỉ mục cho bảng `major_riasec`
--
ALTER TABLE `major_riasec`
  ADD PRIMARY KEY (`MajorRiasecID`),
  ADD KEY `CareerID` (`CareerID`);

--
-- Chỉ mục cho bảng `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`QuestionID`);

--
-- Chỉ mục cho bảng `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`AttemptID`),
  ADD KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `quiz_attempt_details`
--
ALTER TABLE `quiz_attempt_details`
  ADD PRIMARY KEY (`DetailID`),
  ADD KEY `AttemptID` (`AttemptID`),
  ADD KEY `QuestionID` (`QuestionID`),
  ADD KEY `AnswerID` (`AnswerID`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`ReviewID`),
  ADD UNIQUE KEY `uq_review_user_major` (`CareerID`,`UserID`),
  ADD KEY `fk_reviews_user` (`UserID`);

--
-- Chỉ mục cho bảng `riasec_scores`
--
ALTER TABLE `riasec_scores`
  ADD PRIMARY KEY (`ScoreID`),
  ADD KEY `AttemptID` (`AttemptID`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`SettingID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `answers`
--
ALTER TABLE `answers`
  MODIFY `AnswerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `blogs`
--
ALTER TABLE `blogs`
  MODIFY `BlogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `majordetails`
--
ALTER TABLE `majordetails`
  MODIFY `DetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `majors`
--
ALTER TABLE `majors`
  MODIFY `CareerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `major_riasec`
--
ALTER TABLE `major_riasec`
  MODIFY `MajorRiasecID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT cho bảng `questions`
--
ALTER TABLE `questions`
  MODIFY `QuestionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `AttemptID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `quiz_attempt_details`
--
ALTER TABLE `quiz_attempt_details`
  MODIFY `DetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `riasec_scores`
--
ALTER TABLE `riasec_scores`
  MODIFY `ScoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `SettingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`QuestionID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `fk_blogs_author` FOREIGN KEY (`AuthorID`) REFERENCES `users` (`UserID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `majordetails`
--
ALTER TABLE `majordetails`
  ADD CONSTRAINT `fk_major_details_career` FOREIGN KEY (`CareerID`) REFERENCES `majors` (`CareerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `major_riasec`
--
ALTER TABLE `major_riasec`
  ADD CONSTRAINT `major_riasec_ibfk_1` FOREIGN KEY (`CareerID`) REFERENCES `majors` (`CareerID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quiz_attempt_details`
--
ALTER TABLE `quiz_attempt_details`
  ADD CONSTRAINT `quiz_attempt_details_ibfk_1` FOREIGN KEY (`AttemptID`) REFERENCES `quiz_attempts` (`AttemptID`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempt_details_ibfk_2` FOREIGN KEY (`QuestionID`) REFERENCES `questions` (`QuestionID`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempt_details_ibfk_3` FOREIGN KEY (`AnswerID`) REFERENCES `answers` (`AnswerID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_major` FOREIGN KEY (`CareerID`) REFERENCES `majors` (`CareerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `riasec_scores`
--
ALTER TABLE `riasec_scores`
  ADD CONSTRAINT `riasec_scores_ibfk_1` FOREIGN KEY (`AttemptID`) REFERENCES `quiz_attempts` (`AttemptID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
