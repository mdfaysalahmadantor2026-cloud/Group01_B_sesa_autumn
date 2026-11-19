USE skillswap;
-- All demo accounts use the password: password
INSERT INTO users(name,email,password_hash,university,department,role) VALUES
('Admin','admin@skillswap.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','SkillSwap','Administration','admin'),
('Amina Rahman','amina@skillswap.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Dhaka University','Computer Science','student'),
('Karim Hossain','karim@skillswap.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','BUET','Design','student'),
('Nadia Islam','nadia@skillswap.test','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','North South University','Business','student');
INSERT INTO skills(skill_name) VALUES('Python'),('Graphic Design'),('Public Speaking'),('Guitar'),('Spanish'),('Excel'),('Photography'),('Web Development');
INSERT INTO user_phones(user_id,phone) VALUES(2,'+8801700000001'),(3,'+8801700000002');
INSERT INTO user_skills VALUES(2,1,'offered','Advanced'),(2,8,'offered','Intermediate'),(2,2,'wanted','Beginner'),(3,2,'offered','Advanced'),(3,1,'wanted','Beginner'),(3,7,'offered','Intermediate'),(4,3,'offered','Advanced'),(4,6,'offered','Intermediate'),(4,1,'wanted','Beginner'),(4,4,'wanted','Beginner');
INSERT INTO availability(user_id,day_of_week,start_time,end_time) VALUES(2,'Mon','16:00','19:00'),(2,'Wed','10:00','13:00'),(3,'Tue','14:00','18:00'),(4,'Sat','10:00','14:00');
INSERT INTO requests(sender_id,receiver_id,skill_id,status,requested_date) VALUES(3,2,1,'accepted',NOW()-INTERVAL 10 DAY),(2,3,2,'pending',NOW());
INSERT INTO sessions(request_id,session_date,session_time,mode) VALUES(1,CURDATE()-INTERVAL 3 DAY,'16:00','Online');
INSERT INTO reviews(session_id,reviewer_id,rating,comment) VALUES(1,3,5,'Very patient and clear teacher!');
INSERT INTO notifications(user_id,message,type) VALUES(3,'Welcome to SkillSwap!','info'),(2,'Karim Hossain left you a 5-star review','review'),(3,'Amina Rahman wants to learn Graphic Design from you','request');
