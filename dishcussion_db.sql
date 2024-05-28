-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2024 at 03:17 PM
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
-- Database: `dishcussion_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(10) NOT NULL,
  `name` varchar(240) NOT NULL,
  `email` varchar(240) NOT NULL,
  `password` varchar(240) NOT NULL,
  `datetime_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(240) NOT NULL DEFAULT 'active',
  `role` varchar(240) NOT NULL DEFAULT 'moderator'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`, `datetime_creation`, `status`, `role`) VALUES
(22, 'Admin01', 'Admin01@gmail.com', '134096e12368b9bce038ccac61963716c01fa8ee', '2024-05-28 08:12:27', 'active', 'Admin'),
(23, 'Moderator01', 'Moderator01@gmail.com', '602a06e9ceb629a790a284cca83715a3833ab7b4', '2024-05-28 08:12:50', 'active', 'Moderator');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(10) NOT NULL,
  `category` varchar(240) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category`) VALUES
(1, 'Fruit and Vegetables'),
(2, 'Starch'),
(3, 'Protein'),
(4, 'Dairy'),
(5, 'Carbohydrates'),
(6, 'Drinks');

-- --------------------------------------------------------

--
-- Table structure for table `delete_posts`
--

CREATE TABLE `delete_posts` (
  `user_post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `name` varchar(240) NOT NULL,
  `category` varchar(240) NOT NULL,
  `post_title` varchar(240) NOT NULL,
  `post_content` varchar(1000) NOT NULL,
  `post_image` varchar(240) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL,
  `datetime_last_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delete_posts`
--

INSERT INTO `delete_posts` (`user_post_id`, `user_id`, `name`, `category`, `post_title`, `post_content`, `post_image`, `datetime`, `status`, `datetime_last_modified`) VALUES
(39, 20, 'User01', 'Starch', 'aa', 'aa', 'images.jpg', '2024-05-28 20:19:56', 'disabled', '2024-05-28 20:19:56');

-- --------------------------------------------------------

--
-- Table structure for table `posts_comment`
--

CREATE TABLE `posts_comment` (
  `comment_id` int(10) NOT NULL,
  `user_post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `name` varchar(240) NOT NULL,
  `comment` varchar(500) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts_comment`
--

INSERT INTO `posts_comment` (`comment_id`, `user_post_id`, `user_id`, `name`, `comment`, `date`) VALUES
(24, 32, 22, 'User03', 'Can\'t wait  to try it!', '2024-05-28'),
(25, 31, 22, 'User03', 'Great!', '2024-05-28'),
(26, 31, 23, 'User04', 'Amazing!', '2024-05-28'),
(27, 34, 23, 'User04', 'Great Recipe!', '2024-05-28'),
(28, 33, 24, 'User05', 'Taste Great!', '2024-05-28'),
(29, 32, 23, 'User04', 'This is my comment', '2024-05-28');

-- --------------------------------------------------------

--
-- Table structure for table `posts_like`
--

CREATE TABLE `posts_like` (
  `like_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_post_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts_like`
--

INSERT INTO `posts_like` (`like_id`, `user_id`, `user_post_id`) VALUES
(55, 20, 31),
(56, 21, 32),
(57, 21, 31),
(58, 22, 32),
(59, 22, 31),
(60, 23, 31),
(61, 23, 35),
(62, 23, 34),
(63, 24, 31),
(64, 24, 34),
(65, 24, 37),
(66, 24, 33),
(67, 23, 32);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `name` varchar(240) NOT NULL,
  `email` varchar(240) NOT NULL,
  `password` varchar(240) NOT NULL,
  `datetime_creation` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(240) NOT NULL DEFAULT 'disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `datetime_creation`, `status`) VALUES
(20, 'User01', 'User01@gmail.com', '202884d0ebf976b175565124cefefee738897332', '2024-05-28 08:27:36', 'active'),
(21, 'User02', 'User02@gmail.com', 'c5e7df6ed2532d0dbc8c926008e74196ffe3a16c', '2024-05-28 08:27:52', 'active'),
(22, 'User03', 'User03@gmail.com', '7901548b632d992afe4531e32c75a84f6193612b', '2024-05-28 08:28:08', 'active'),
(23, 'User04', 'User04@gmail.com', '7fe75dbf6386e2ee6acea9a069ecbd992893511e', '2024-05-28 08:28:25', 'active'),
(24, 'User05', 'User05@gmail.com', 'f5a9a7fcffbfa74598ebae856ee1ac190b7b717e', '2024-05-28 08:28:50', 'active'),
(25, 'User06', 'User06@gmail.com', '6be80fade342f1bbecfdd512aaf22c0727f30b01', '2024-05-28 08:29:04', 'active'),
(26, 'User07', 'User07@gmail.com', 'd600e5057e5d6f485244b62a5438532ab38a267f', '2024-05-28 08:29:29', 'disabled'),
(27, 'User08', 'User08@gmail.com', '406d8ee519f43cac224300c5393fb2b79fbc89a9', '2024-05-28 10:51:04', 'disabled'),
(28, 'User09', 'User09@gmail.com', 'c95e9ae1a2b29836e1a1f6f1e4ab74dbcceb9b62', '2024-05-28 10:51:28', 'disabled'),
(29, 'User10', 'User10@gmail.com', '5ef1db1f0e5592941fd72417868b49264cc625e9', '2024-05-28 10:51:42', 'disabled'),
(30, 'User11', 'User11@gmail.com', 'c0a4d802ff340e8ffcfc85ea84fb733a384e7587', '2024-05-28 10:52:01', 'disabled');

-- --------------------------------------------------------

--
-- Table structure for table `user_posts`
--

CREATE TABLE `user_posts` (
  `user_post_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `name` varchar(240) NOT NULL,
  `category` varchar(240) NOT NULL,
  `post_title` varchar(240) NOT NULL,
  `post_content` varchar(5000) NOT NULL,
  `post_image` varchar(240) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) NOT NULL DEFAULT 'disabled',
  `datetime_last_modified` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_posts`
--

INSERT INTO `user_posts` (`user_post_id`, `user_id`, `name`, `category`, `post_title`, `post_content`, `post_image`, `datetime`, `status`, `datetime_last_modified`) VALUES
(31, 20, 'User01', 'Fruit and Vegetables', 'One Pot Veggie Hamburger Helper', 'Ingredients:\r\n2 tablespoons olive oil\r\n1 cup of chopped onions\r\n8-ounces mushrooms, coarsely chopped\r\n½ pound ground turkey or beef\r\nKosher salt and fresh cracked black pepper\r\n2 teaspoons chili powder\r\n½ teaspoon paprika\r\n½ teaspoon smoked paprika\r\n1 teaspoon garlic powder\r\n1 pound chickpea elbow pasta (see Note)\r\n1 zucchini, finely grated on a box grater\r\n1 carrot, finely grated on a box grater\r\n1 8-oz can tomato sauce\r\n2 ½ cups chicken or vegetable broth\r\n2 cups shredded cheddar cheese\r\n¾ cup nonfat plain Greek yogurt\r\nParsley and red pepper flakes (optional), for garnish\r\n\r\nInstruction:\r\n1. Place a large nonstick skillet over medium-high heat. Heat the olive oil, then add the onion, mushrooms and ground turkey. Break up the turkey as it browns. Season with a generous pinch of kosher salt and fresh cracked black pepper. Allow the meat to brown and cook through, about 8 to 10 minutes. Drain off any excess liquid.\r\n2. Add the chili powder, paprikas, and garlic powder and toss to coat, cooking for another minute.\r\n3. Add the dry pasta, zucchini, carrot, broth, and tomato sauce. Stir to combine.\r\n4. Place the lid on the pot and simmer over medium-high heat for 7-8 minutes or until the pasta is al dente (see Note). Remove the lid to stir the mixture once or twice during cooking to ensure all the pasta softens.\r\n5. Remove pan from heat, take off the lid and stir in the cheese and yogurt until well combined and melty.\r\n6. Divide the pasta between bowls and top with a sprinkle of fresh parsley and red pepper flakes before serving.', 'OnePotVeggieHamburgerHelper.jpg', '2024-05-28 08:36:44', 'active', '2024-05-28 08:36:51'),
(32, 21, 'User02', 'Fruit and Vegetables', 'Air Fryer Avocado Fries', 'Ingredients:\r\n2 avocados, peeled and sliced\r\n1 cup panko crumbs\r\n¼ cup of almond flour\r\n2 eggs, lightly beaten\r\n¼ tsp. paprika\r\n¼ tsp. garlic powder\r\n¼ tsp. salt\r\nAvocado oil spray\r\n\r\nInstruction:\r\n1. In a small dish, combine the flour, paprika, garlic powder and salt. Stir to combine. In another small dish add the eggs and mix well. In another small dish add the panko breadcrumbs.\r\n2. In an assembly line, roll the first avocado slice in the flour, submerge in the egg wash, followed by the panko breadcrumbs. Repeat with the remaining avocado slices.\r\n3. Spray the air fryer with avocado oil and place each avocado fry into the basket, try not to overlap. Air fry on 390 for 7 minutes, flipping halfway through, or until desired crispness and enjoy!\r\n', 'avocado-fries-1-352x210.webp', '2024-05-28 08:43:41', 'active', '2024-05-28 08:44:14'),
(33, 21, 'User02', 'Starch', 'Garlic Mashed Potatoes', 'Ingredients:\r\n2 pounds (6 medium) potatoes, cut into 1-inch chunks\r\n1 ½ cups lowfat milk\r\n3 Tbsp. margarine\r\n4 cloves garlic, minced\r\n1/8 tsp. salt\r\n1/8 tsp. pepper\r\n\r\nInstruction:\r\nIn large saucepan cook potatoes in 2 inches boiling water, covered, about 10 minutes until tender; drain thoroughly, then shake potatoes over low heat 1 to 2 minutes to dry thoroughly. Mash potatoes with potato masher or beat with electric hand mixer; reserve. Place milk, margarine and garlic in small saucepan; set over medium-low heat and simmer until heated through, beat into potatoes until thoroughly mixed and fluffy. Mix in additional milk, if necessary, to reach desired consistency. Season with salt and pepper.', 'garlic-mashed-potato.jpg', '2024-05-28 08:49:45', 'active', '2024-05-28 09:00:04'),
(34, 22, 'User03', 'Starch', 'Breaded Salmon Nuggets With Spinach-Cashew Dip', 'Ingredients:\r\n½ cup all-purpose flour\r\n2 large eggs\r\n1½ cups whole wheat panko breadcrumbs\r\n1¼ pounds skinless salmon fillets, cut into 2-inch pieces\r\n1 teaspoon kosher salt\r\n¼ teaspoon ground black pepper\r\nNonstick cooking spray\r\n1 DOLE® Lemon, juiced (about ¼ cup)\r\n1 garlic clove, coarsely chopped\r\n½ cup raw cashews\r\n¼ cup packed DOLE® Baby Spinach\r\n1 tablespoon nutritional yeast\r\n¼ teaspoon crushed red pepper flakes\r\n\r\nInstructions:\r\n1. Preheat oven to 425°F; line a rimmed baking pan with parchment paper. Place flour in a wide, shallow dish. Whisk eggs in a separate shallow dish; place breadcrumbs in a third shallow dish. Sprinkle salmon with ¾ teaspoon salt and pepper. Dredge salmon in flour mixture, shaking off excess, then dip in eggs and breadcrumbs to coat; place on prepared pan and spray with nonstick cooking spray. Makes about 20 nuggets.\r\n2. Bake nuggets 10 minutes or until golden brown and internal temperature reaches 145°F.\r\n3. Purée lemon juice, garlic, cashews, spinach, nutritional yeast, crushed red pepper, remaining ¼ teaspoon salt and ¼ cup water in a blender on high until smooth. Makes about 1 cup.\r\n4. Serve nuggets with dip.\r\n', 'Breaded_Salmon_Nugget_0109.jpg', '2024-05-28 08:52:45', 'active', '2024-05-28 09:00:04'),
(35, 22, 'User03', 'Protein', 'Philly Cheesesteak Chickpea Pasta', 'Ingredients:\r\n2 tablespoons extra-virgin olive oil\r\n2 large bell peppers (any color), sliced\r\n1 large sweet onion, sliced\r\nKosher salt and freshly ground black pepper\r\n8 ounces lean ground beef\r\n1/2 teaspoon garlic powder\r\n3 hot pickled cherry peppers, stemmed, seeded and sliced, plus 3 tablespoons brine from the jar\r\n1 1/2 cups low-sodium beef broth\r\n2 teaspoons Worcestershire sauce\r\nOne 8-ounce box chickpea penne or other tubular shape\r\n2 ounces cream cheese, cut into pieces\r\n1 cup shredded mild provolone (about 4 ounces)\r\n2 tablespoons chopped fresh Italian parsley\r\n\r\nInstruction:\r\n1. Heat a large skillet or braiser over medium heat. Add 1 tablespoon of the olive oil. When the oil is hot, add the bell peppers and onions and season with 1/2 teaspoon salt and several grinds of pepper. Cook, tossing occasionally, until the peppers and onions are lightly browned but still crisp, about 3 minutes. Remove to a bowl with a slotted spoon or tongs.\r\n2. Add the remaining tablespoon of oil to the pan. Crumble in the ground beef and season with the garlic powder, a pinch of salt and several grinds of pepper. Cook until browned all over, about 3 minutes. Add the cherry peppers and brine and cook until sizzling, about 30 seconds. Add the beef broth, Worcestershire sauce and 1 1/2 cups water. Bring to a simmer over medium-low heat.\r\n3. Add the chickpea pasta and spread it out in an even layer so it’s mostly submerged. Cover and simmer until it’s just beginning to lose some of its bite but is still quite al dente, 3 to 4 minutes. Stir in the reserved peppers and onions and cover again. Cook until the pasta is al dente and the peppers and onions are crisp-tender, 3 to 4 minutes more depending on your brand of pasta. Scatter in the cream cheese pieces and stir to melt. Remove from the heat.\r\n4. Sprinkle in the provolone and parsley. Stir just to melt the cheese, taking care not to break up the pasta too much. Serve immediately.', '1708055955858.jpeg', '2024-05-28 08:56:54', 'active', '2024-05-28 09:00:05'),
(36, 23, 'User04', 'Protein', 'Dal', 'Ingredients:\r\n1 tablespoon olive oil\r\n1 onion, diced  \r\n4 cloves garlic, minced  \r\n1 1/2 cups red lentils, picked through and rinsed \r\nOne 14.5-ounce can diced tomatoes \r\n2 tablespoons mild curry powder \r\n1 tablespoon yellow curry paste\r\n1 tablespoon garam masala \r\n2 teaspoons garlic powder \r\n2 teaspoons onion powder\r\n1 teaspoon chili powder \r\n1/2 teaspoon ground turmeric \r\nKosher salt and freshly ground black pepper \r\n1/2 cup unsweetened coconut milk \r\nCooked basmati rice, for serving \r\nLime wedges and cilantro leaves, for serving \r\nLime wedges and cilantro leaves, for serving\r\n\r\nInstructions:\r\n1. Heat the olive oil in a medium saucepan over medium heat. Add the onions and garlic and cook, stirring occasionally, until the onions are translucent and softened, about 5 minutes. Stir in the lentils, tomatoes and 3 1/2 cups water and bring to a boil over high heat. Reduce the heat and simmer, uncovered, stirring occasionally, until the lentils are tender, 10 to 15 minutes.\r\n2. Stir in the curry powder, curry paste, garam masala, garlic powder, onion powder, chili powder, turmeric and some salt and pepper. Reduce the heat to low and stir in the coconut milk.   \r\n3. Serve with the basmati rice, lime wedges and cilantro.', '1580311969513.jpeg', '2024-05-28 09:02:44', 'active', '2024-05-28 09:13:59'),
(37, 23, 'User04', 'Dairy', 'Stovetop Mac and Cheese', 'Ingredients:\r\n2 cups (8 oz) elbow pasta\r\n1 cup Swiss, shredded\r\n1 cup Cheddar, shredded\r\n3 tablespoons butter\r\n3 tablespoons flour\r\n1 cup milk\r\n1 cup half and half\r\nSalt, to taste\r\nPepper, to taste\r\nFor Baked Mac and Cheese:\r\n2 tbsp butter, melted\r\n1/4 cup parmesan cheese, shredded 3/4 cup panko bread crumbs\r\n\r\nInstructions:\r\n1. Cook pasta according to package instructions for al dente pasta. \r\n2. While pasta is cooking, measure and prep other ingredients. Shred cheese and set next to stovetop.\r\n3. Strain pasta and set aside, reserving some pasta water in a container. TIP: You can toss pasta in oil to prevent sticking, but when it is added back to the sauce it will un-clump with stirring.\r\n4. Return pot to the stovetop on medium heat.\r\n5. Add butter and flour, stirring regularly until combined. Continue to stir for 1-2 minutes, until nutty and golden.\r\n6. Slowly add in milk and half and half, stirring pot until well combined. A whisk may be handy.\r\n7. Bring to a light boil, and cook for 2 minutes until thickened.\r\n8. Cut the heat. In handfuls, add cheese and stir. Once all cheese is incorporated, add back the strained pasta and stir to coat. If the sauce is too thick, add a little of the reserved pasta water and stir to thin it out.', 'Stovetop-mac-and-cheese_TTU.png', '2024-05-28 09:09:00', 'active', '2024-05-28 09:14:00'),
(38, 23, 'User04', 'Dairy', 'Tres Leches Cupcakes with Whipped Topping', 'Ingredients:\r\nCupcakes:\r\n1 (15.25 oz) box cake mix, yellow or vanilla\r\n1 cup whole milk\r\n1/2 cup butter, melted\r\n4 eggs\r\n1 cup heavy cream\r\n1 (14 oz) can sweetened condensed milk\r\n1 (12 oz) can evaporated milk\r\nWhipped Topping*\r\nCinnamon\r\n\r\nWhipped Topping:\r\n2 cups cold heavy cream\r\n1/4 cup powdered sugar\r\n1 teaspoon vanilla extract\r\nCinnamon, optional\r\n\r\nInstruction:\r\nCupcakes:\r\n1. Make cupcakes according to cake box instructions, substituting milk for water, melted butter for oil, and adding an extra egg for richness. TIP: Each cupcake is about a quarter cup of batter.\r\n2. While cupcakes are baking, mix together heavy cream, sweetened condensed milk, and evaporated milk.\r\n3. Once cupcakes are out of the oven and cooled slightly (yet still warm), poke deep holes in each cake with a skewer or butter knife, about a quarter inch between each poke.\r\n4. Carefully pour milk mixture into each cake, about a 1/4 cup. Go slowly so it can sink in properly!\r\nCover cupcakes and let sit in fridge for at least an hour or overnight.\r\n5. Top each cake with whipped cream, sprinkle with cinnamon. TIP: For extra cinnamon flavor, mix 1/2 teaspoon into whipped topping. Enjoy!\r\n\r\nWhipped Topping:\r\n1. Add cold heavy cream, sugar, and vanilla extract to a large mixing bowl.\r\n2. Beat with a whisk or hand-mixer until stiff peaks form.\r\n3. Fold in cinnamon if desired. Enjoy! ', 'cupcake.png', '2024-05-28 09:12:43', 'active', '2024-05-28 09:14:01');

--
-- Triggers `user_posts`
--
DELIMITER $$
CREATE TRIGGER `backup` AFTER DELETE ON `user_posts` FOR EACH ROW BEGIN
    INSERT INTO delete_posts (
        user_post_id,
        user_id,
        name,
        category,
        post_title,
        post_content,
        post_image,
        datetime,
        status,
        datetime_last_modified
    ) VALUES (
        OLD.user_post_id,
        OLD.user_id,
        OLD.name,
        OLD.category,
        OLD.post_title,
        OLD.post_content,
        OLD.post_image,
        OLD.datetime,
        OLD.status,
        OLD.datetime_last_modified
    );
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `posts_comment`
--
ALTER TABLE `posts_comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_post_id` (`user_post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts_like`
--
ALTER TABLE `posts_like`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `user_post_id` (`user_post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_posts`
--
ALTER TABLE `user_posts`
  ADD PRIMARY KEY (`user_post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `posts_comment`
--
ALTER TABLE `posts_comment`
  MODIFY `comment_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `posts_like`
--
ALTER TABLE `posts_like`
  MODIFY `like_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user_posts`
--
ALTER TABLE `user_posts`
  MODIFY `user_post_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts_comment`
--
ALTER TABLE `posts_comment`
  ADD CONSTRAINT `posts_comment_ibfk_1` FOREIGN KEY (`user_post_id`) REFERENCES `user_posts` (`user_post_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `posts_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `posts_like`
--
ALTER TABLE `posts_like`
  ADD CONSTRAINT `posts_like_ibfk_1` FOREIGN KEY (`user_post_id`) REFERENCES `user_posts` (`user_post_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `posts_like_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_posts`
--
ALTER TABLE `user_posts`
  ADD CONSTRAINT `user_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
