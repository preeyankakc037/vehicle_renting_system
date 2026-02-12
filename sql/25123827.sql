-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2026 at 05:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehicle_rental_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `renter_id` int(11) NOT NULL,
  `pickup_date` date NOT NULL,
  `dropoff_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `pickup_location` varchar(255) DEFAULT NULL,
  `dropoff_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `vehicle_id`, `renter_id`, `pickup_date`, `dropoff_date`, `total_price`, `booking_status`, `pickup_location`, `dropoff_location`, `created_at`, `updated_at`) VALUES
(7, 13, 13, '2026-01-26', '2026-01-26', 1800.00, 'confirmed', NULL, NULL, '2026-01-26 09:54:42', '2026-01-26 09:55:22');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','read','replied') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`message_id`, `name`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'Anam Joshi', 'preeyankakc.07@gmail.com', 'Interest on Listing my vehicle ', 'How much i have to pay in order to become a owner.', 'pending', '2026-01-26 10:30:54'),
(2, 'Anam Joshi ', 'preeyankakc.07@gmail.com', 'Feedback: 5-Day Scorpio Rental', 'This was an amazing rent.  I want to list my Tour Buses do you work with these too.', 'pending', '2026-01-27 17:47:00');

-- --------------------------------------------------------

--
-- Table structure for table `owner_verifications`
--

CREATE TABLE `owner_verifications` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_name` varchar(100) DEFAULT NULL,
  `id_proof_type` enum('Citizenship','License','Passport') NOT NULL,
  `id_proof_number` varchar(100) NOT NULL,
  `admin_notes` text DEFAULT NULL,
  `admin_feedback` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','fixes_needed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner_verifications`
--

INSERT INTO `owner_verifications` (`verification_id`, `user_id`, `business_name`, `id_proof_type`, `id_proof_number`, `admin_notes`, `admin_feedback`, `status`, `created_at`, `updated_at`) VALUES
(2, 11, 'Velocity by Priyanka', 'Citizenship', '3809', NULL, NULL, 'approved', '2026-01-26 08:39:07', '2026-01-26 08:39:15'),
(3, 12, 'Ray Pulse ', 'License', ' 01-06-00571574', NULL, NULL, 'approved', '2026-01-26 09:32:22', '2026-01-26 09:33:00');

-- --------------------------------------------------------

--
-- Table structure for table `rental_policies`
--

CREATE TABLE `rental_policies` (
  `policy_id` int(11) NOT NULL,
  `policy_title` varchar(200) NOT NULL,
  `policy_description` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_policies`
--

INSERT INTO `rental_policies` (`policy_id`, `policy_title`, `policy_description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Age Requirement', 'Renters must be at least 21 years old with a valid driver\'s license. Drivers under 25 may incur an additional young driver fee.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(2, 'Insurance Coverage', 'Basic insurance is included in the rental price. Additional coverage options are available at checkout for enhanced protection.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(3, 'Fuel Policy', 'All vehicles are provided with a full tank of fuel. Please return the vehicle with a full tank to avoid refueling charges.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(4, 'Damage Policy', 'Renters are responsible for any damage to the vehicle during the rental period. Please inspect the vehicle carefully before departure.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(5, 'Cancellation Policy', 'Free cancellation up to 24 hours before pickup. Cancellations within 24 hours are subject to a 50% cancellation fee.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(6, 'Late Return', 'Late returns are subject to additional charges of 10% per hour. Please contact us if you need to extend your rental period.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(7, 'Mileage Policy', 'Unlimited mileage included for rentals within Nepal. Out-of-country rentals are not permitted.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(8, 'Payment Policy', 'Payment is required at the time of booking. We accept cash and online payment methods.', 1, '2026-01-25 02:32:28', '2026-01-25 02:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `renter_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `vehicle_id`, `renter_id`, `rating`, `comment`, `created_at`) VALUES
(1, 13, 13, 5, 'Had an amazing experience renting a Scorpio for 5 days. The car was in great condition with absolutely no issues throughout the trip. The owner was incredibly kind and even worked with me on a fair negotiated price. Highly recommend for a smooth, hassle-free rental!', '2026-01-26 09:57:52');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(50) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key_name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'platform_name', 'Pathek', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(2, 'support_email', 'support@pathek.com', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(3, 'currency', 'NPR', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(4, 'min_price', '500', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(5, 'max_price', '50000', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(6, 'max_images', '3', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(7, 'verification_required', '1', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(8, 'min_booking_duration', '1', '2026-01-25 02:32:28', '2026-01-25 02:32:28'),
(9, 'cancellation_allowed', '1', '2026-01-25 02:32:28', '2026-01-25 02:32:28');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`log_id`, `admin_id`, `action`, `details`, `created_at`) VALUES
(1, 10, 'update_user', 'User ID 16 status to banned', '2026-01-26 17:36:37');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `user_role` enum('admin','owner_pending','owner_verified','owner_rejected','renter') NOT NULL DEFAULT 'renter',
  `account_status` enum('active','blocked') DEFAULT 'active',
  `otp_hash` varchar(255) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `is_email_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `phone`, `user_role`, `account_status`, `otp_hash`, `otp_expires_at`, `is_email_verified`, `created_at`, `updated_at`) VALUES
(10, 'System Administrator', 'admin@gmail.com', '$2y$10$SwlQFCIS.USkjmhxK977ketqvBj0UF4PGz4sVqkQUtO60LOSQRPOC', '+1234567890', 'admin', 'active', NULL, NULL, 1, '2026-01-26 04:15:39', '2026-01-26 04:16:25'),
(11, 'Priyanka  Khatri ', 'priyankakc.037@gmail.com', '$2y$10$MA/ag91MSwiHzstgPvLvCOV1Mj9R/DezTTYUyJKriSZj5JJToHrBq', '9847185132', 'owner_verified', 'active', NULL, NULL, 1, '2026-01-26 08:37:17', '2026-01-26 08:39:15'),
(12, 'Sarala  Basnet', 'aatreyaray07@gmail.com', '$2y$10$uuyIRtzoiRVwtIidygSSauNXDH3WinVBW/fTqibJgBu/Lw6Rln0Se', '9848193160', 'owner_verified', 'active', NULL, NULL, 1, '2026-01-26 09:30:53', '2026-01-26 09:33:00'),
(13, 'Anam Joshi', 'preeyankakc.07@gmail.com', '$2y$10$23.J/qyyIXwlugvTfooY4ed29g3AzXy4z5XDagggQbFEdUeuvUAvO', '9847185101', 'renter', 'active', NULL, NULL, 1, '2026-01-26 09:51:53', '2026-01-26 09:51:53'),
(15, 'Mina  Khatri', 'mina.khatri370@gmail.com', '$2y$10$ifFF8euEFpxTRrK3E38hvOAFtetxGhrprGR3Nh0rT/SO8sTF4qV66', '9847185107', 'renter', 'active', NULL, NULL, 1, '2026-01-26 15:22:41', '2026-01-26 15:33:43'),
(17, 'Priyanka Khatri', 'preeyanka_25123827@sunway.edu.np', '$2y$10$eNhh48E4eX5uSHw32ea6guLy2woZMkP335TZ.vStZ571qXbm6iU3O', '9847185137', 'renter', 'active', '$2y$10$yveqY.G35ZzS7j60vZw7/O9P.6KtaTfu.grTzn27u7CaPxWMWxw7K', '2026-01-27 06:34:25', 0, '2026-01-27 05:24:25', '2026-01-27 05:24:25');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `vehicle_name` varchar(150) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `vehicle_type` enum('Car','Bike','Scooty') NOT NULL,
  `year` int(11) NOT NULL,
  `plate_number` varchar(50) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `seating_capacity` int(11) DEFAULT NULL,
  `driver_available` tinyint(1) DEFAULT 0,
  `utility_type` enum('Personal','Commercial','Tourism') DEFAULT 'Personal',
  `pickup_type` enum('Airport','Domestic Tour','City Use','General') DEFAULT 'General',
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT 'assets/images/default-vehicle.jpg',
  `image_path_2` varchar(255) DEFAULT NULL,
  `image_path_3` varchar(255) DEFAULT NULL,
  `admin_feedback` text DEFAULT NULL,
  `availability_status` enum('available','booked','maintenance') DEFAULT 'available',
  `approval_status` enum('pending','approved','rejected','fixes_needed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `owner_id`, `vehicle_name`, `brand`, `model`, `vehicle_type`, `year`, `plate_number`, `price_per_day`, `seating_capacity`, `driver_available`, `utility_type`, `pickup_type`, `description`, `image_path`, `image_path_2`, `image_path_3`, `admin_feedback`, `availability_status`, `approval_status`, `created_at`, `updated_at`) VALUES
(9, 11, ' Toyota  Land Cruiser', ' Toyota ', 'Land Cruiser', 'Car', 2024, 'BA 4 CHA 8901', 4500.00, 7, 1, 'Personal', 'General', 'Performance & Safety Features\r\nThe 2026 Prado is powered by a high-torque 2.8L turbo-diesel engine paired with a full-time 4WD system and a 10-speed automatic transmission. It features the \"Multi-Terrain Select\" system and \"Crawl Control,\" allowing the vehicle to automatically manage speed and traction over Nepal’s steepest and rockiest terrains. For safety, it is equipped with Toyota Safety Sense 3.0, which includes 360-degree cameras, emergency braking, and lane-keep assist for maximum protection.\r\nInterior & Luxury Facilities\r\nThe cabin offers a premium 7-seater environment featuring genuine leather upholstery, a panoramic sunroof, and 3-zone climate control for passenger comfort. High-tech facilities include a 12.3-inch multimedia touchscreen with wireless Apple CarPlay/Android Auto and a refrigerated \"cool box\" in the center console for beverages. Designed for long Himalayan journeys, it provides power-adjustable heated seats and a high-fidelity JBL sound system to ensure a first-class travel experience.\r\n\r\n', 'uploads/vehicles/1769417382_land-cruiser-prado-car-in-forest-min.webp', 'uploads/vehicles/1769417382_Land-Cruiser-Prado inside view.jpg', '', NULL, 'available', 'approved', '2026-01-26 08:49:42', '2026-01-26 08:49:42'),
(10, 11, 'Ford Mustang GT ', 'Ford', 'Mustang GT ', 'Car', 2026, ' Bagmati B PA 9876', 8000.00, 4, 1, 'Personal', 'General', 'Performance & Safety Features\r\nThe 2026 Ford Mustang GT is powered by a 5.0L Coyote V8 engine delivering 480 horsepower, optimized for high-speed performance and precision handling. It features an advanced MagneRide Damping System and selectable Drive Modes (Normal, Sport, and Track) that adjust steering and throttle response instantly. For safety, it is equipped with Ford Co-Pilot360 technology, including Blind Spot Information, Pre-Collision Assist, and a high-definition rearview camera.\r\nInterior & Luxury Facilities\r\nThe interior offers a futuristic \"Jet Fighter\" inspired cabin with heated and cooled leather Recaro sport seats and customizable ambient lighting. Premium facilities include a B&O Sound System with 12 speakers, a wireless charging pad, and a massive 13.2-inch infotainment display with SYNC 4 connectivity. Despite its sporty nature, it provides modern comforts like dual-zone climate control and a leather-wrapped flat-bottom steering wheel for a luxury driving experience.', 'uploads/vehicles/1769418125_ford_mustang.avif', 'uploads/vehicles/1769418125_mustang_side_view.webp', '', NULL, 'available', 'approved', '2026-01-26 09:02:05', '2026-01-26 09:26:33'),
(11, 11, 'Ather Energy  Ather ', 'Ather Energy', ' Ather ', 'Scooty', 2026, 'L P 12 Cha 5678 ', 400.00, NULL, 0, 'Personal', 'General', 'The Ather 450X is a high-performance electric scooter featuring a 6.4 kW motor that delivers instant acceleration (0-40 km/h in 3.3s) and a 7-inch water-resistant TFT touchscreen with integrated Google Maps and Bluetooth connectivity. Designed for Nepal’s hilly terrain, it comes equipped with essential facilities like \"AutoHold\" to prevent rolling back on slopes, a dedicated \"Park Assist\" reverse mode, and an IP67-rated battery that ensures reliable performance even during the monsoon season.\r\n\r\n\r\n\r\n', 'uploads/vehicles/1769418378_ather.jpg', '', '', NULL, 'available', 'approved', '2026-01-26 09:06:18', '2026-01-26 11:56:42'),
(12, 12, 'Hero MotoCorp XPulse 200 4V', 'Hero MotoCorp', 'XPulse 200 4V', 'Bike', 2023, 'G  03 PA 4321', 700.00, 0, 0, 'Personal', '', 'The Hero Xpulse 200 4V is an agile adventure motorcycle powered by a 200cc oil-cooled 4-valve engine, specifically engineered for Nepal\'s demanding off-road trails and gravel paths. It features a best-in-class 220mm ground clearance and adjustable long-travel suspension, complemented by facilities such as a fully digital console with turn-by-turn navigation, three-mode ABS (Road, Off-road, Rally), and an upswept exhaust designed for deep water crossings. With its lightweight chassis and high-intensity LED lighting, it offers the perfect combination of daily commuting practicality and weekend mountain exploration.', 'uploads/vehicles/1769420403_X-pulse_onilne khabar.png', '', '', NULL, 'maintenance', 'approved', '2026-01-26 09:40:03', '2026-01-26 09:59:15'),
(13, 12, 'Mahindra  Scorpio-N Z8L', 'Mahindra ', 'Scorpio-N Z8L', 'Car', 2023, 'B KA 2468', 1800.00, 7, 1, 'Commercial', '', 'The Mahindra Scorpio-N is a robust and rugged 7-seater SUV designed to be a workhorse in diverse terrains like those found in Nepal. It is powered by a torquey 2.0L mStallion Turbo-Petrol or 2.2L mHawk Diesel engine that offers smooth performance for both city commuting and cross-country hauling. Key features include reliable 4Xplor intelligent 4WD capability for off-road security, and facilities such as an electric sunroof, a sophisticated 12-speaker Sony 3D immersive sound system, and AdrenoX connected car technology with integrated Alexa voice assistant functionality. It offers exceptional ground clearance and reliable utility, making it a highly practical and affordable choice for group travel in the region', 'uploads/vehicles/1769420581_Mahindra_Scorpio.jpg', 'uploads/vehicles/1769420581_scoripio_inside_view.avif', '', NULL, 'available', 'approved', '2026-01-26 09:43:01', '2026-01-26 09:43:01'),
(14, 12, 'Royal Enfield (Reborn Series) Classic 350 ', 'Royal Enfield (Reborn Series)', 'Classic 350 ', 'Bike', 2024, 'L 02 PA 5566', 600.00, NULL, 0, 'Personal', 'General', 'The Royal Enfield Classic 350 is a timeless cruiser powered by a smooth 349cc J-Series engine that delivers the signature low-end torque required for effortless highway cruising and mountain climbs in Nepal. It features a heavy-duty steel twin-downtube frame for stability and dual-channel ABS for enhanced safety during steep descents. Facilities include a comfortable upright riding posture designed for long-distance comfort, and a USB charging port to keep devices powered during cross-country Himalayan tours. Its classic \"thump\" and retro aesthetic make it the ultimate status symbol for adventure travelers and enthusiasts alike.', 'uploads/vehicles/1769420708_Royal_Enfiled_gma_nepal.jpg', '', '', NULL, 'available', 'approved', '2026-01-26 09:45:08', '2026-01-26 09:59:08');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`wishlist_id`, `user_id`, `vehicle_id`, `created_at`) VALUES
(2, 11, 9, '2026-01-26 08:59:39'),
(3, 13, 14, '2026-01-26 10:38:41'),
(4, 13, 10, '2026-01-27 16:55:31'),
(5, 13, 11, '2026-01-27 16:55:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `idx_vehicle` (`vehicle_id`),
  ADD KEY `idx_renter` (`renter_id`),
  ADD KEY `idx_status` (`booking_status`),
  ADD KEY `idx_dates` (`pickup_date`,`dropoff_date`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `owner_verifications`
--
ALTER TABLE `owner_verifications`
  ADD PRIMARY KEY (`verification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rental_policies`
--
ALTER TABLE `rental_policies`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `renter_id` (`renter_id`),
  ADD KEY `idx_vehicle` (`vehicle_id`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`user_role`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`),
  ADD KEY `idx_owner` (`owner_id`),
  ADD KEY `idx_status` (`availability_status`),
  ADD KEY `idx_approval` (`approval_status`),
  ADD KEY `idx_type` (`vehicle_type`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`vehicle_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `owner_verifications`
--
ALTER TABLE `owner_verifications`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rental_policies`
--
ALTER TABLE `rental_policies`
  MODIFY `policy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`renter_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `owner_verifications`
--
ALTER TABLE `owner_verifications`
  ADD CONSTRAINT `owner_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`renter_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
