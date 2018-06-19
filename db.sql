

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+03:00";

DROP DATABASE IF EXISTS rgc_victor;

CREATE DATABASE IF NOT EXISTS rgc_victor;
--
-- Table structure for table `groups`
--

CREATE TABLE if not exists rgc_victor.groups(
    Id int primary key AUTO_INCREMENT,
    alias varchar(50) not null UNIQUE,
    permissions text null
)ENGINE=InnoDB;


CREATE TABLE if not exists rgc_victor.users (
 user_Id int(11) NOT NULL AUTO_INCREMENT,
 user_fname varchar(20) NOT NULL,
 user_lname varchar(20) NOT NULL,
 user_alias varchar(50) NOT NULL UNIQUE,
 user_password varchar(250) NOT NULL,
 contact varchar(13) DEFAULT NULL,
 user_email varchar(150) NULL,
 dob date DEFAULT NULL,
 gender enum('male','female') NOT NULL,
 user_pic varchar(300) null,
 salt varchar(50) DEFAULT NULL,
 user_role int NOT NULL,
 reg_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY (user_Id),
 foreign key (user_role) REFERENCES groups(Id)
)ENGINE=InnoDB;

create table if not exists rgc_victor.patients(
    patient_Id int primary key AUTO_INCREMENT,
    fname varchar(50) not null,
    lname varchar(50) not null,
    phone_no varchar(20) not null,
    email varchar(100) null,
    patient_address varchar(250) not null,
    gender enum("Male","Female") not null,
    dateOfBirth date null,
    marital_status enum("married", "single", "divorsed", "other"),
    nok varchar(250) not null,
    nok_contact varchar(20) not null,
    nok_relationship enum("father","mother","sibling","friend","other"),
    discharged boolean not null default 0
)ENGINE=InnoDB;

create table if not exists rgc_victor.medication(
    med_id int primary key AUTO_INCREMENT,
    med_name varchar(250) not null,
    med_type enum("tablet","ointment","injectable","oral liguid"),
    med_color varchar(50) null,
    med_cost int,
    med_status enum("available","finished","deficient"),
    FULLTEXT(med_name)
)ENGINE=InnoDB;

create table rgc_victor.visits(
    visit_Id int not null UNIQUE AUTO_INCREMENT,
    patient_Id int not null,
    admition_executer_Id int not null,
    attending_doctor int null,
    attending_lab_pro int null,
    attending_pharm_pro int null,
    visitStatus enum('in queue','started', 'terminated') not null,
    visitLabStatus enum('none', 'returned','on-going') null,
    visitStartTimeStamp int(20) null,
    visitEndTimeStamp int(20) null,
    visitVitalTemperature int null,
    visitVitalWeight float null,
    visitVitalHeight float null,
    visitVitalPressure varchar(20) null,
    visitVitalPulse int null,
    visitNotes text null,
    visitDiagnosis varchar(250) null,
    visitCount int null,
    lastVisit int(20) null,
    PRIMARY key(visit_Id,patient_Id),
    foreign key (patient_Id) REFERENCES patients(patient_Id),
    constraint user_that_admitted_patient foreign key (admition_executer_Id) REFERENCES users(user_Id),
    constraint doctor_working_on_visit foreign key (attending_doctor) REFERENCES users(user_Id),
    constraint technician_working_on_lab_test foreign key (attending_lab_pro) REFERENCES users(user_Id),
    constraint pharmacist_working_on_prescription foreign key (attending_pharm_pro) REFERENCES users(user_Id)
)ENGINE=InnoDB;

CREATE table rgc_victor.visit_medication(
    visit_med_Id int primary key AUTO_INCREMENT,
    visit_id int not null,
    med_id int not null,
    visit_med_dosage varchar(250),
    visit_med_duration int,
    visit_med_quantity int,
    served boolean null default 0,
    foreign key(med_id) references rgc_victor.medication(med_id),
    foreign key(visit_id) references rgc_victor.visits(visit_Id)
)ENGINE=InnoDB;

create table if not exists rgc_victor.queues(
    queue_Id int primary key AUTO_INCREMENT,
    visit_Id int not null,
    queue_atendant_group enum("doctor","laboratory","pharmacy"),
    queue_response enum("finished","processing","served","pending", "pending from lab"),
    constraint queu_patient foreign key(visit_Id) REFERENCES visits(visit_Id)
)ENGINE=InnoDB;

create table if not exists rgc_victor.laboratory_test(
    test_Id int primary key AUTO_INCREMENT,
    test_name varchar(250) not null,
    test_description text null,
    test_cost int null
)ENGINE=InnoDB;

create table if not exists rgc_victor.visit_test(
    visit_test_Id int primary key AUTO_INCREMENT,
    visit_Id int not null,
    lab_test_id int not null,
    results varchar(250) null,
    visit_test_comment text,
    taken boolean not null,
    request_date varchar(50) not null,
    date_taken varchar(50) null,
    foreign key (visit_Id) REFERENCES visits(visit_Id),
    foreign key (lab_test_id) REFERENCES laboratory_test(test_Id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rgc_victor.records(
    patient_id int not null,
    visit_id int not null,
    file_num varchar(50) not null,
    visit_start_date date not null,
    duration_in_days int not null,
    doc int null,
    pharm int null,
    exec int not null,
    lab int null
);

/**
// counts
*/
create view rgc_victor.visit_count as SELECT count(visit_Id) as numbers FROM rgc_victor.visits;
create view rgc_victor.lab_count as SELECT count(lab_test_id)  numbers FROM rgc_victor.visit_test;
create view rgc_victor.pharm_count as SELECT count(visit_med_Id) numbers FROM rgc_victor.visit_medication;

insert into rgc_victor.groups(alias,permissions) values
('administrator','{"admin":1,"display_queue":1,"add_patient":1}'),
('receptionist','{"issue_bill":1,"add_patient":1}'),
('doctor','{"can_diagnise":1,"display_queue":1,"add_patient":1}'),
("laboratory",''),
("pharmacist",''),
("patient",'');

INSERT INTO rgc_victor.users (user_Id, user_fname, user_lname, user_alias, user_password, contact, user_email, dob, gender, salt, user_role, reg_date) VALUES
(1000, "victor", "Mbasa", "admin", "Y”G»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ", "0785357200", "mvicktor@gmail.com", "1978-5-15", "male", "885c055a800cc57cfb6283388ef490ab", 1, "2018-03-17 07:22:16"),
(1001, "Brendah", "Agaba", "rec", "Y”G»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ", "0788635696", "agababright96@gmail.com", "1978-5-15", "female", "885c055a800cc57cfb6283388ef490ab", 2, "2018-03-17 07:22:32"),
(1002, "Lydia", "Nakasirye", "doc", "Y”G»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ", "0778314279", "nakasiryelydia@gmail.com", "1978-5-15", "female", "885c055a800cc57cfb6283388ef490ab", 3, "2018-03-17 07:22:48"),
(1003, "Florence", "Okitui", "lab", "Y”G»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ", "0772649119", "olilidaniel48@gmail.com", "1994-3-4", "male", "885c055a800cc57cfb6283388ef490ab",4 , "2018-03-17 07:22:57"),
(1004, "victor", "Mbaine", "pharm", "Y”G»*üÁYöÌt´õ¹˜ÚY³Êõ©ÁsÊÏÅ", "0785357200", "mvicktor@gmail.com", "1978-5-15", "male", "885c055a800cc57cfb6283388ef490ab", 5, "2018-03-17 07:23:05");

insert into rgc_victor.medication (med_name,med_type,med_color,med_cost,med_status) values ("panadol","tablet","white",500,"available"),
("amoxiline","tablet","green",500,"available"),
("quistodol","tablet","red",500,"available"),
("melaline","tablet","purple",500,"available"),
("conaxine","tablet","aqua",500,"available"),
("meladol","tablet","blue",500,"available");
--
-- Dumping data for table `patients`
--

INSERT INTO rgc_victor.patients(patient_Id,fname,lname,phone_no,email,patient_address,gender,dateOfBirth,marital_status,nok,nok_contact,nok_relationship,discharged) values
(1,'Kakooza','family','0712937191',null,'WANDEGEYA','Male','1978-05-08','married','mulungu sama gona','0752354253','sibling',0),
(2,'KENEMA','PROSPER','0712789648',null,'Kisaasi','Male','1990-05-08','married','mulungu sama gona','0752354253','sibling',0),
(3,'NAKAGWA','SARAH','0712845963',null,'Makerere','Male','1988-05-08','married','mulungu sama gona','0752354253','sibling',0),
(4,'SSEGAWA','POUTIANO','0712458975',null,'Nankulabye','Male','1969-05-08','married','mulungu sama gona','0752354253','sibling',0),
(5,'ATWINE','ROBERT','0712523245',null,'Makerere','Male','1974-05-08','married','mulungu sama gona','0752354253','sibling',0),
(6,'NAGITA','BECCA','0712784512',null,'Nankulabye','Female','1978-05-08','married','mulungu sama gona','0752354253','sibling',0),
(7,'GONZA','NELSON','0712781568',null,'Bwaise','Male','1990-05-08','married','mulungu sama gona','0752354253','sibling',0),
(8,'ATEGEKA','ROBINAH','0712986574',null,'WANDEGEYA','Female','1969-05-08','married','mulungu sama gona','0752354253','sibling',0),
(9,'MUTITI','JAMES','0712545698',null,'Makerere','Male','1969-05-08','married','mulungu sama gona','0752354253','sibling',0),
(10,'GONZA','BRIAN','0712589751',null,'WANDEGEYA','Male','1974-05-08','married','mulungu sama gona','0752354253','sibling',0),
(11,'KOBUZAARE','ROBERT','0712589864',null,'WANDEGEYA','Male','1986-05-08','married','mulungu sama gona','0752354253','sibling',0),
(12,'KOBUZAARE','EDIDAH','0712897755',null,'Nankulabye','Female','1983-05-08','married','mulungu sama gona','0752354253','sibling',0),
(13,'KAYAGA','AALIYAH','0712879956',null,'Kisaasi','Female','1988-05-08','married','mulungu sama gona','0752354253','sibling',0),
(14,'JEMIMAH','ROBERT','0787995491',null,'Makerere','Female','1988-05-08','married','mulungu sama gona','0752354253','sibling',0),
(15,'MUHEREZA','EDWIN','0787952191',null,'WANDEGEYA','Male','1990-05-08','married','mulungu sama gona','0752354253','sibling',0),
(16,'KYOMUHANGI','MEDRIN','0778954791',null,'Nankulabye','Male','1983-05-08','married','mulungu sama gona','0752354253','sibling',0),
(17,'KARERIINDE','SOLOMON','0758845191',null,'WANDEGEYA','Male','1978-05-08','married','mulungu sama gona','0752354253','sibling',0),
(18,'NANYANZI','MARIAH','0714567981',null,'Bwaise','Male','1991-05-08','married','mulungu sama gona','0752354253','sibling',0),
(19,'AISHA','KYOBE','0717789871',null,'WANDEGEYA','Male','1991-05-08','married','mulungu sama gona','0752354253','sibling',0),
(20,'PATIENCE','NYINAMATSIKO','0710997751',null,'WANDEGEYA','Female','1986-05-08','married','mulungu sama gona','0752354253','sibling',0),
(21,'FLORENCE','NAGAWA','0754234354',null,'Makerere','Female','1974-05-08','married','mulungu sama gona','0752354253','sibling',0),
(22,'REHEMA','NAMYALO','0712789900',null,'WANDEGEYA','Female','1974-05-08','married','mulungu sama gona','0752354253','sibling',0),
(23,'EVANISI','KABANYORO','0712543233',null,'Makerere','Male','1988-05-08','married','mulungu sama gona','0752354253','sibling',0),
(24,'KALIISA','AISHA','0710987651',null,'WANDEGEYA','Female','1974-05-08','married','mulungu sama gona','0752354253','sibling',0),
(25,'OMYER','SUNDAY','071278865',null,'Kisaasi','Male','1988-05-08','married','mulungu sama gona','0752354253','sibling',0),
(26,'SIGYATRAH','AHMED','0713562232',null,'WANDEGEYA','Male','1990-05-08','married','mulungu sama gona','0752354253','sibling',0),
(27,'BRIDGET','NINSIIMA','0712786242',null,'WANDEGEYA','Female','1978-05-08','married','mulungu sama gona','0752354253','sibling',0),
(28,'AGNES','NAKANWAGI','0736722234',null,'Bwaise','Female','1983-05-08','married','mulungu sama gona','0752354253','sibling',0),
(29,'BRIAN','MWINE','0713675762',null,'WANDEGEYA','Male','1986-05-08','married','mulungu sama gona','0752354253','sibling',0),
(30,'PAUL','KABIGUMIRA','0712356291',null,'Kisaasi','Male','1978-05-08','married','mulungu sama gona','0752354253','sibling',0),
(31,'FAITH','NANYONJO','0712478391',null,'Nankulabye','Male','2018-05-08','married','mulungu sama gona','0752354253','sibling',0),
(32,'RAHIM','KALIISA','0712937262',null,'Makerere','Male','1991-05-08','married','mulungu sama gona','0752354253','sibling',0),
(33,'PAMELA','ANYING','0712765675',null,'Makerere','Female','1986-05-08','married','mulungu sama gona','0752354253','sibling',0),
(34,'KUKUNDAKWE','MORIS','0782627828',null,'WANDEGEYA','Male','1991-05-08','married','mulungu sama gona','0752354253','sibling',0);


INSERT INTO rgc_victor.visits (patient_Id, admition_executer_Id, visitStatus, visitStartTimeStamp, visitEndTimeStamp, visitCount, lastVisit) VALUES
(25, 1001, 'terminated', 1526939612,1527171414,4, 1527171354),
(30, 1001, 'terminated', 1526940061, 1527165955, 4, 1527165955),
(3, 1001, 'terminated', 1526940070, 1527165961, 2, 1527165961),
(16, 1001, 'terminated', 1526940090, 1527165962, 2, 1527165962),
(8, 1001, 'terminated', 1525867707, 1525867710, 1, 1525867710),
(19, 1001, 'terminated', 1526967484, 1526967489, 2, 1526967489),
(1, 1001, 'terminated', 1525867703, 1525867708, 1, 1525867708),
(34, 1001, 'terminated', 1526939598, 1526939605, 1, 1526939605),
(26, 1001, 'terminated', 1526939612, 1526939616, 1, 1526939616),
(2, 1001, 'terminated', 1526949793, 1526949795, 1, 1526949795),
(4, 1001, 'terminated', 1526949770, 1526949773, 1, 1526949773),
(31, 1001, 'terminated', 1526949766, 1526949776, 1, 1526949776),
(20, 1001, 'terminated', 1526949754, 1526949777, 1, 1526949777),
(5, 1001, 'terminated', 1526949751, 1526949782, 1, 1526949782),
(6, 1001, 'terminated', 1526949656, 1526949693, 1, 1526949693),
(7, 1001, 'terminated', 1526949661, 1526949694, 1, 1526949694),
(9, 1001, 'terminated', 1526949664, 1526949695, 1, 1526949695),
(11, 1001, 'terminated', 1526949669, 1526949697, 1, 1526949697),
(10, 1001, 'terminated', 1527165980, 1527171373, 2, 1527171373),
(13, 1001, 'terminated', 1526949679, 1526949700, 1, 1526949700),
(12, 1001, 'terminated', 1526949683, 1526949701, 1, 1526949701),
(14, 1001, 'terminated', 1526949690, 1526949705, 1, 1526949705),
(15, 1001, 'terminated', 1526949715, 1526949734, 1, 1526949734),
(17, 1001, 'terminated', 1526949718, 1526949731, 1, 1526949731),
(18, 1001, 'terminated', 1526949722, 1526949729, 1, 1526949729),
(21, 1001, 'terminated', 1526949722, 1526949729, 1, 1526949729),
(22, 1001, 'terminated', 1526949722, 1526949729, 1, 1526949729),
(23, 1001, 'terminated', 1526963496, 1526963527, 1, 1526963527),
(24, 1001, 'terminated', 1526963490, 1526963528, 1, 1526963528),
(27, 1001, 'terminated', 1526963487, 1526963529, 1, 1526963529),
(28, 1001, 'terminated', 1526963483, 1526963530, 1, 1526963530),
(32, 1001, 'terminated', 1526963473, 1526963520, 1, 1526963520),
(33, 1001, 'terminated', 1526963470, 1526963518, 1, 1526963518),
(29, 1001, 'terminated', 1526963467, 1526963517, 1, 1526963517);

INSERT INTO rgc_victor.laboratory_test (test_Id, test_name, test_cost) VALUES
(1, 'Complete Blood Count/ Full haemogram (CBC)', '15000'),
(2, 'White blood cells film report', '10000'),
(3, 'Reticulocyte count', '20000'),
(5, 'Activated Partial Thromboplastin Time (aPTT)', '20000'),
(6, 'Prothrombin time (PT)', '15000'),
(7, 'International Normalized Ratio (INR)', '15000'),
(8, 'Bleeding and Clotting time', '20000'),
(9, 'Sickle cells test', '20000'),
(10, 'Haemoglobin (Hb) Electrophoresis', '50000'),
(11, 'ABO & Rhesus Blood grouping', '10000'),
(12, 'Coombs Test-Direct', '10000'),
(13, 'Coombs Test-Indirect', '10000'),
(14, 'Enzyme Coombs Test', '20000'),
(15, 'Malaria Parasites- Blood Slide (B/S) Thick Smear','7000'),
(16, 'Malaria Parasites -Thin Smear', '7000'),
(19, 'Trypanasome Parasites', '10000'),
(20, 'Vitamin B12', '85000'),
(21, 'Folate', '60000'),
(22, 'Ferritin', '60000'),
(23, 'Typhoid Widal titres', '7000'),
(27, 'Brucella titres', '10000'),
(29, 'Rheumatoid Factor (RF)', '15000'),
(30, 'ASOT (Anti-Streptolysin O titre)', '15000'),
(31, 'Anti DNA (SLE) Latex Test (Serum)', '20000'),
(35, 'Viral Load (HIV RNA Polymerase Chain Reaction (PCR) ', '200000'),
(36, 'CD4+/ CD8+ Count', '60000'),
(37, 'RPR (VDRL) Screening', '10000'),
(44, 'Streptococcal Group A Antigen Rapid Test', '25000'),
(45, 'TB Antigen MPT64 Rapid Test', '25000'),
(46, 'Cryptococcus Antigen (CRAG)', '20000'),
(47, 'Hepatitis B surface Antigen (HBsAg) Device Rapid Test', '15000'),
(48, 'Hepatitis B surface Antigen (HBsAg) ElIZA', '40000'),
(49, 'Anti-HBs Device Rapid test', '20000'),
(50, 'Anti-Hepatitis B core Antibody IgM ELIZA', '40000'),
(51, 'Anti-Hepatitis B core Antibody (IgM&IGg) ELIZA', '80000'),
(52, 'Hepatitis B Viral Load', '400000'),
(53, 'Hepatitis C Virus (HCV) Device Rapid Test', '25000'),
(54, 'Hepatitis C or A or E Virus ELIZA', '40000'),
(55, 'Hepatitis B e Antigen (active infection) Rapid test', '30000'),
(56, 'Hepatitis A Virus (HAV IgG IgM) Device Rapid Test', '30000'),
(57, 'Hepatitis A IgM ElIZA', '40000'),
(58, 'Hepatitis A IgG ELIZA', '40000'),
(59, 'Toxoplasmosis (IgG&IgM) ELIZA', '110000'),
(60, 'Toxoplasmosis (IgG or IgM) ELIZA', '60000'),
(61, 'CMV (IgG&IgM) ELIZA', '110000'),
(62, 'CMV (IgG or IgM) ELIZA', '60000'),
(63, 'Pregnancy Test HCG (Urine/serum) Rapid Test', '5000'),
(64, 'Pregnancy Test HCG (urine/serum) Quantitative-ELISA', '50000'),
(65, 'Chlamydia (Endocervical/Urethral swab) Ag Rapid test', '25000'),
(66, 'CRP (C-Reactive Protein) Agglutination test', '20000'),
(67, 'CRP (C-Reactive Protein) Quantitative', '25000'),
(68, 'Candida Rapid test', '25000'),
(69, 'Gonorrhoea test (Gram&urinalysis)', '25000'),
(70, 'Bilharrzia Antibodies', '100000'),
(71, 'Blood Sugar, Glucose, (Random/Fasting/postprandial)', '5000'),
(72, 'Glucose Tolerance Test (G.T.T)', '50000'),
(73, 'Serum Calcium (Ca++)', '20000'),
(74, 'Serum Uric Acid', '25000'),
(75, 'Serum Inorganic Phosphorous', '20000'),
(76, 'Serum Magnesium (Mg++)', '20000'),
(77, 'Angiotensin Converting Enzyme serum', '50000'),
(78, 'Serum Osmolality ', '40000'),
(79, 'Cardiac Marker- Troponin Quantitative', '60000'),
(80, 'Tocopherol levels', '120000'),
(81, 'Inorganic Phosphate', '20000'),
(82, 'Homocysteine (Fasting ) serum', '70000'),
(83, 'Myoglobin ', '70000'),
(84, 'Alpha 1 antitrypsin (serum)', '50000'),
(85, 'Alkaline Phosphatase', '20000'),
(86, 'Glucose-6-Phosphate Dehydrogenase (G6PD)', '50000'),
(87, 'Glycated Haemoglobin, Haemoglobin A1c (HbA1c)', '60000'),
(88, 'Protein Electrophoresis', '60000'),
(89, 'Serum Lithium', '20000'),
(90, 'Lactic Acid (Lactate)', '50000'),
(91, 'Serum Cortisol (am & pm)', '50000'),
(92, 'Serum Cortisol (single sample)', '30000'),
(93, 'Creatinine Clearance', '20000'),
(94, '24hrs Urinary cortisol', '40000'),
(95, '24hrs Urine Electrolytes', '20000'),
(96, '24hrs Urine Protein', '10000'),
(97, '24hrs Urine Calcium (Ca ++)', '10000'),
(98, '24hrs Urine Magnesium (Mg ++)', '10000'),
(99, '24hrs Urine uric acid', '10000'),
(100, '24hrs Urine Phosphates', '10000'),
(101, 'Microalbumin (Urine)', '20000'),
(102, 'Urine Bence Jones Protein', '20000'),
(103, 'Serum Creatinine', '20000'),
(104, 'Serum Urea', '20000'),
(105, 'Serum Electrolytes, Potassium (K+), Sodium (Na+), Chloride (Cl-))', '15000'),
(106, 'Cardiac Enzymes (GOT, CPK, LDH)', '50000'),
(107, 'Cardiac Marker- Troponin I, Rapid test for Myocardial Infarction', '30000'),
(108, 'Cardiac markers (Troponin, CK-MB, BNP) ELIZA Quantitative ', '100000'),
(109, 'Liver Function Test', '50000'),
(110, 'Bilirubin (Total & Direct)', '10000'),
(111, 'Alkaline Phosphatase (ALP)', '10000'),
(112, 'ALT (GPT)', '10000'),
(113, 'AST (GOT)', '10000'),
(114, 'y-GT (GGT)', '10000'),
(115, 'Serum Protein (Total, Albumin + Globulin)', '25000'),
(116, 'Amylase (serum/urine)', '20000'),
(117, 'Lipase ', '20000'),
(118, 'Total Cholestrol', '20000'),
(119, 'HDL Cholestrol', '10000'),
(120, 'LDL Cholestrol', '10000'),
(121, 'Triglycerides ', '10000'),
(122, 'Free T3', '25000'),
(123, 'Free T4', '25000'),
(124, 'TSH', '25000'),
(125, 'FSH', '40000'),
(126, 'Luteinizing Hormone (LH), Qualitative Rapid Test for detection of Ovulation', '25000'),
(127, 'LH', '40000'),
(128, 'Prolactin', '50000'),
(129, 'Progesterone', '50000'),
(130, 'Estradiol (E2)', '50000'),
(131, 'Testosterone', '50000'),
(132, 'Semen analysis', '50000'),
(133, 'Alpha Feto Protein (AFP), Ag Rapid Test for liver cancer', '30000'),
(134, 'Alpha Feto Protein (AFP), ELIZA Test for liver cancer', '75000'),
(135, 'Carcino Embryonic Antigen (CEA), Ag Rapid Test for GIT tumors', '30000'),
(136, 'Carcino Embryonic Antigen (CEA), ELIZA Test for GIT tumors', '50000'),
(137, 'Prostatic Specific Antigen (PSA), Ag Rapid Test for prostate cancer', '30000'),
(138, 'Prostatic Specific Antigen (PSA) Total ELIZA', '60000'),
(139, 'Free PSA ELIZA, if total is raised', '50000'),
(140, 'CA125 (Ovarian Cancer) ELIZA', '80000'),
(141, 'CA19.9 (Lungs) ELIZA', '80000'),
(142, 'CA 153 (Breast) ELIZA', '80000'),
(143, 'VMA (Vinyl Mandelic Acid) ELIZA for adrenal gland tumor and neuroblastomas', '80000'),
(144, 'Cervical PAP Smear (one slide)', '60000'),
(145, 'Histology/Biopsy tissue(cost per tissue)', '100000'),
(146, 'Cytology for Cavity fluids, sputum', '120000'),
(147, 'Bone Marrow Aspirate', '120000'),
(148, 'Fine needle Aspirate (FNA)', '150000'),
(149, 'Urinalysis + Microscopy in Adults using urine container', '10000'),
(150, 'Urinalysis + Microscopy in infants using urine bag', '15000'),
(151, 'Urine Culture and Sensitivity', '50000'),
(152, 'Urine ZN for AAFB in 24hr sample(3 consecutive early morning samples)', '60000'),
(153, 'Early morning urine for AAFBs', '20000'),
(154, 'Stool Micro- Direct and Conc', '15000'),
(155, 'Stool Culture and Sensitivity', '50000'),
(156, 'Stool Microscopy for Schistosoma', '10000'),
(157, 'Modified ZN for Cryptosporidium in stool', '20000'),
(158, 'Faecal Occult Blood (FOB) Test in stool for lower GIT bleeding, colorectal cancer and large adenomas', '25000'),
(159, 'Sputum ZN stain', '20000'),
(160, 'Sputum Gram stain', '20000'),
(161, 'Sputum ZN Culture & Sensitivity', '50000'),
(162, 'Swabs-Pus, Urethral, HVS microscopy', '15000'),
(163, 'Swabs-Pus, Urethral, HVS etc C&S', '50000'),
(164, 'CSF (routine examination) including Indian Ink Stain', '30000'),
(165, 'CSF culture & sensitivity', '50000'),
(166, 'Blood culture & sensitivity', '50000'),
(167, 'Semen Gram stain, C&S (without fertility tests)', '70000'),
(168, 'Scrappings/cuttings-skin,nails,hair etc Microscopy', '15000'),
(169, 'Scrappings/cuttings-skin,nails, hair etc c & s', '30000'),
(170, 'Marijuana (THC Device)', '40000'),
(171, 'Cocaine (COC Device)', '40000'),
(172, 'Morphine (MOP Device)', '40000'),
(173, 'Methylenedioxymethamphetamine (MDMA, Ecstasy)', '50000'),
(174, '5-Panel Drug of Abuse (DOA) Multi-Device (AMP, MET, THC, MOP, COC)', '150000'),
(175, '6-Panel Drug of Abuse Multi-Device DOA) (AMP, MET, THC, MOP, COC, MDMA)  ', '175000'),
(176, '10-Panel Drug of Abuse (DOA) Multi-Device (AMP, MET, THC, MOP, COC, MDMA, PCP, OPI, BAR, BENZ)  ', '200000'),
(177, 'Steroids of abuse', '160000'),
(178, 'DNA Partenity test per sample', '300000');

COMMIT;