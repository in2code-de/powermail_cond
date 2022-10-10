CREATE TABLE tx_powermailcond_domain_model_conditioncontainer
(
	conditions int(11) unsigned DEFAULT '0' NOT NULL,
	note       tinyint(4) unsigned DEFAULT '0' NOT NULL,

	title      tinytext NOT NULL,
	form       int(11) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_powermailcond_domain_model_condition
(
	conditioncontainer int(11) unsigned DEFAULT '0' NOT NULL,
	rules              int(11) unsigned DEFAULT '0' NOT NULL,

	title              tinytext NOT NULL,
	target_field       tinytext NOT NULL,
	actions            tinytext NOT NULL,
	conjunction        tinytext NOT NULL,

	key                conditioncontainer (conditioncontainer),
	key                target_field (target_field(20))
);

CREATE TABLE tx_powermailcond_domain_model_rule
(
	conditions  int(11) unsigned DEFAULT '0' NOT NULL,

	title       tinytext NOT NULL,
	start_field int(11) unsigned DEFAULT '0' NOT NULL,
	ops         int(11) unsigned DEFAULT '0' NOT NULL,
	cond_string text     NOT NULL,
	equal_field int(11) unsigned DEFAULT '0' NOT NULL,

	key         conditions (conditions),
	key         start_field (start_field),
	key         equal_field (equal_field)
);
