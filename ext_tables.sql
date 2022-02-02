--
-- Table structure for table 'tx_adserver_domain_model_ad'
--
CREATE TABLE tx_adserver_domain_model_ad (
    title varchar(255) NOT NULL DEFAULT '',
    identifier varchar(255) NOT NULL DEFAULT '',

    code int(11) unsigned NULL DEFAULT NULL COMMENT '1:n tx_adserver_domain_model_code.uid',

    INDEX fk_ad_to_code_1 (code),
    FOREIGN KEY (code) REFERENCES tx_adserver_domain_model_code (uid) ON DELETE CASCADE ON UPDATE RESTRICT,
);

--
-- Table structure for table 'tx_adserver_domain_model_campaign'
--
CREATE TABLE tx_adserver_domain_model_campaign (
    title varchar(255) NOT NULL DEFAULT '',
    identifier varchar(255) NOT NULL DEFAULT '',
    limit_to_pages int(11) NOT NULL DEFAULT 0,
);

--
-- Table structure for table 'tx_adserver_domain_model_campaign_to_pages'
--
CREATE TABLE tx_adserver_domain_model_campaign_to_pages (
    uid_local int(11) unsigned DEFAULT 0 NOT NULL,
    uid_foreign int(11) unsigned DEFAULT 0 NOT NULL,
    sorting int(11) unsigned DEFAULT 0 NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT 0 NOT NULL,

    INDEX uid_local (uid_local),
    INDEX uid_foreign (uid_foreign),
) COMMENT 'Note: m:n table is managed by TYPO3, no foreign key constraints possible';

--
-- Table structure for table 'tx_adserver_domain_model_code'
--
CREATE TABLE tx_adserver_domain_model_code (
    type int(11) unsigned NOT NULL DEFAULT 0,
    title varchar(255) NOT NULL DEFAULT '',
    identifier varchar(255) NOT NULL DEFAULT '',
    source mediumtext,
);

--
-- Table structure for table 'tx_adserver_domain_model_place'
--
CREATE TABLE tx_adserver_domain_model_place (
    title varchar(255) NOT NULL DEFAULT '',
    identifier varchar(255) NOT NULL DEFAULT '',
);

--
-- Table structure for table 'tx_adserver_domain_model_placement'
--
CREATE TABLE tx_adserver_domain_model_placement (
    ad int(11) unsigned NOT NULL COMMENT '1:n tx_adserver_domain_model_ad.uid',
    campaign int(11) unsigned NOT NULL COMMENT '1:n tx_adserver_domain_model_campaign.uid',
    place int(11) unsigned NOT NULL COMMENT '1:n tx_adserver_domain_model_place.uid',

    UNIQUE combikey (ad, campaign, place),
    INDEX fk_placement_to_ad_1 (ad),
    INDEX fk_placement_to_campaign_1 (campaign),
    INDEX fk_placement_to_place_1 (place),
    FOREIGN KEY (ad) REFERENCES tx_adserver_domain_model_ad (uid) ON DELETE CASCADE ON UPDATE RESTRICT,
    FOREIGN KEY (campaign) REFERENCES tx_adserver_domain_model_campaign (uid) ON DELETE CASCADE ON UPDATE RESTRICT,
    FOREIGN KEY (place) REFERENCES tx_adserver_domain_model_place (uid) ON DELETE CASCADE ON UPDATE RESTRICT,
);
