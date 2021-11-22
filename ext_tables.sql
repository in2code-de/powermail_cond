CREATE TABLE tx_powermailcond_domain_model_conditioncontainer (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,

	conditions int(11) DEFAULT '0' NOT NULL,
	note tinyint(4) DEFAULT '0' NOT NULL,

	title tinytext NOT NULL,
	form int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);

CREATE TABLE tx_powermailcond_domain_model_condition (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,

	rules int(11) DEFAULT '0' NOT NULL,
	conditioncontainer int(11) DEFAULT '0' NOT NULL,

	title tinytext NOT NULL,
	target_field tinytext NOT NULL,
	actions tinytext NOT NULL,
	conjunction tinytext NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY conditioncontainer (conditioncontainer),
	KEY target_field (target_field(20))
);

CREATE TABLE tx_powermailcond_domain_model_rule (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,

	conditions int(11) DEFAULT '0' NOT NULL,

	title tinytext NOT NULL,
	start_field int(11) DEFAULT '0' NOT NULL,
	ops int(11) DEFAULT '0' NOT NULL,
	cond_string text NOT NULL,
	equal_field int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY conditions (conditions),
	KEY start_field (start_field),
	KEY equal_field (equal_field)
);
