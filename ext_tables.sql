#
# Table structure for table 'tx_powermailcond_domain_model_condition'
#
CREATE TABLE tx_powermailcond_domain_model_condition (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,

	rules int(11) DEFAULT '0' NOT NULL,

	title tinytext NOT NULL,
	targetField tinytext NOT NULL,
	actions tinytext NOT NULL,
	filterSelectField text NOT NULL,
	conjunction tinytext NOT NULL,
	form int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);


#
# Table structure for table 'tx_powermailcond_domain_model_rule'
#
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
	startField int(11) DEFAULT '0' NOT NULL,
	ops int(11) DEFAULT '0' NOT NULL,
	condstring text NOT NULL,
	equalField int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);